<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesProfile;
use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\Offer;
use App\Models\RewardRequest;
use App\Support\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CustomerDashboardController extends Controller
{
    use UpdatesProfile;

    public function index()
    {
        $user = auth()->user();
        $customer = Customer::forUser($user);
        $cards = Loyalty::cards($customer);
        $totalEarned = (int) $cards->sum('earned');

        return view('customer.home', [
            'customer' => $customer,
            'cards' => $cards,
            'totalEarned' => $totalEarned,
            'totalBalance' => (int) $cards->sum('balance'),
            'rewardsRedeemed' => Loyalty::requestsFor($customer)->where('status', 'approved')->count(),
            'readyCount' => (int) $cards->sum(fn ($c) => $c['readyOffers']->count()),
            'tier' => Loyalty::tier($totalEarned),
            'memberSince' => $user->created_at,
            'customerCode' => 'CU-'.strtoupper(substr(md5($customer->id), 0, 8)),
        ]);
    }

    public function scan()
    {
        return view('customer.scan');
    }

    /** Old demo page: the real "after scan" step is the coin popup, then the rewards page. */
    public function afterScan()
    {
        return redirect()->route('customer.claim-reward');
    }

    /**
     * Target of a merchant's QR code: award a coin for the visit, show the
     * "coins collected" popup, then continue to the claim-reward page.
     */
    public function collect(Business $business)
    {
        $customer = Customer::forUser(auth()->user());
        $blocked = $customer->status && $customer->status !== 'Active';

        // One coin per business per customer every few minutes, so refreshing the page can't farm coins.
        $recent = CustomerVisit::where('customer_id', $customer->id)
            ->where('business_id', $business->id)
            ->where('scanned_at', '>=', now()->subMinutes(self::COLLECT_COOLDOWN_MINUTES))
            ->exists();

        $awarded = ! $recent && ! $blocked;
        if ($awarded) {
            CustomerVisit::create([
                'customer_id' => $customer->id,
                'business_id' => $business->id,
                'scanned_at' => now(),
                'stamp_awarded' => true,
            ]);
        }

        return view('customer.coin-collected', [
            'business' => $business,
            'awarded' => $awarded,
            'blocked' => $blocked,
            'coins' => Loyalty::balance($customer, $business->id),
            // Relative, so the popup continues on whichever host the customer is on (live or localhost).
            'redirectTo' => route('customer.claim-reward', absolute: false),
        ]);
    }

    public const COLLECT_COOLDOWN_MINUTES = 5;

    /** "View All" from the home page: same screen as the rewards tab. */
    public function rewards()
    {
        return redirect()->route('customer.claim-reward', request()->only('tab'));
    }

    /** Spend coins on one of a business's offers. */
    public function claimOffer(Offer $offer)
    {
        try {
            $request = Loyalty::claim(Customer::forUser(auth()->user()), $offer);
        } catch (RuntimeException $e) {
            return redirect()->route('customer.claim-reward')->with('error', $e->getMessage());
        }

        $message = $request->status === 'approved'
            ? 'Reward claimed! Show code '.$request->code.' at the counter.'
            : 'Reward claimed! Show code '.$request->code.' at the counter so the shop can approve it.';

        return redirect()->route('customer.claim-reward', ['tab' => 'claim'])->with('success', $message);
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $oldEmail = $user->email;
        $this->normalizeProfileInput($request);

        $validated = $request->validate(
            $this->accountRules($request, $user, phoneRequired: false),
            $this->accountMessages()
        );

        DB::transaction(function () use ($request, $user, $validated, $oldEmail) {
            $this->saveAccount($request, $user, $validated);

            // Coin history is keyed by email, so carry it over to the new address.
            if ($user->email !== $oldEmail && ! Customer::where('email', $user->email)->exists()) {
                Customer::where('email', $oldEmail)->update(['email' => $user->email]);
            }
            if ($record = Customer::where('email', $user->email)->first()) {
                $record->name = $user->name;
                if ($user->phone) {
                    // Phone is unique across customers; keep the old one if this number is taken.
                    $record->phone = Customer::availablePhone($user->phone, $record->id) ?? $record->phone;
                }
                $record->save();
            }
        });

        return back()->with('success', 'Profile updated successfully.');
    }

    public function showStatus($type)
    {
        $validTypes = ['qr-invalid', 'no-internet', 'already-claimed', 'rejected'];
        if (! in_array($type, $validTypes)) {
            abort(404);
        }

        return view('customer.status', compact('type'));
    }

    /**
     * Rewards screen (where the coin popup lands):
     *  - available: offers the customer can claim now, and progress towards the rest,
     *  - claim: claimed rewards waiting to be shown at the counter,
     *  - history: approved and declined claims.
     */
    public function claimReward()
    {
        $tab = in_array(request('tab'), ['available', 'claim', 'history'], true) ? request('tab') : 'available';
        $customer = Customer::forUser(auth()->user());

        $requests = Loyalty::requestsFor($customer)->with('business')->latest()->get();
        $claimable = $requests->where('status', 'pending')->values();
        $history = $requests->whereIn('status', ['approved', 'declined'])->values();
        $cards = Loyalty::cards($customer);

        return view('customer.claim-reward', compact('tab', 'claimable', 'history', 'cards'));
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
