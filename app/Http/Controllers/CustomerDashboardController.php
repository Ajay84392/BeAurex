<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Customer;
use App\Models\RewardRequest;
use App\Rules\MobileNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        return view('customer.home');
    }

    public function scan()
    {
        return view('customer.scan');
    }

    public function afterScan()
    {
        return view('customer.after-scan');
    }

    /**
     * Target of a merchant's QR code: award a coin for the visit, show the
     * "coins collected" popup, then continue to the claim-reward page.
     */
    public function collect(Business $business)
    {
        $user = auth()->user();
        $customer = Customer::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name, 'phone' => $user->phone ?: '00000'.rand(10000, 99999)]
        );

        // One coin per business per customer every few minutes, so refreshing the page can't farm coins.
        $recent = DB::table('customer_visits')
            ->where('customer_id', $customer->id)
            ->where('business_id', $business->id)
            ->where('scanned_at', '>=', now()->subMinutes(self::COLLECT_COOLDOWN_MINUTES))
            ->exists();

        if (! $recent) {
            DB::table('customer_visits')->insert([
                'customer_id' => $customer->id,
                'business_id' => $business->id,
                'scanned_at' => now(),
                'stamp_awarded' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $coins = DB::table('customer_visits')
            ->where('customer_id', $customer->id)
            ->where('business_id', $business->id)
            ->where('stamp_awarded', true)
            ->count();

        return view('customer.coin-collected', [
            'business' => $business,
            'awarded' => ! $recent,
            'coins' => $coins,
            'redirectTo' => route('customer.claim-reward'),
        ]);
    }

    public const COLLECT_COOLDOWN_MINUTES = 5;

    public function rewards()
    {
        $tab = request('tab', 'claim');

        // Find by customer name since dummy data uses names
        $requests = RewardRequest::where('customer_name', auth()->user()->name)
            ->latest()
            ->get();

        $claimable = $requests->where('status', 'pending');
        $history = $requests->whereIn('status', ['approved', 'declined']);

        return view('customer.rewards', compact('tab', 'claimable', 'history'));
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'name' => 'nullable|string|max:255',
            'phone' => ['nullable', new MobileNumber],
            'photo' => 'nullable|image|max:2048',
            'language' => 'nullable|string',
            'timezone' => 'nullable|string',
            'date_format' => 'nullable|string',
        ];

        // Only validate password if they explicitly provide both fields
        if ($request->filled('password') && $request->filled('current_password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } else {
            // Ignore password update if incomplete (e.g. browser autofill)
            $request->request->remove('password');
            $request->request->remove('current_password');
        }

        $request->validate($rules);

        if ($request->filled('current_password')) {
            if (! \Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
            }
        }

        $data = array_filter($request->only('name', 'phone', 'language', 'timezone', 'date_format'), function($value) {
            return !is_null($value) && $value !== '';
        });
        if (isset($data['phone'])) {
            $data['phone'] = MobileNumber::format($data['phone']);
        }
        
        if ($request->hasFile('photo')) {
            $data['photo'] = '/storage/'.$request->file('photo')->store('profiles', 'public');
        }
        
        if ($request->filled('password')) {
            $data['password'] = \Hash::make($request->password);
        }
        $user->update($data);

        return back()->with('success', 'Profile updated successfully');
    }

    public function showStatus($type)
    {
        $validTypes = ['qr-invalid', 'no-internet', 'already-claimed', 'rejected'];
        if (! in_array($type, $validTypes)) {
            abort(404);
        }

        return view('customer.status', compact('type'));
    }

    public function claimReward()
    {
        $tab = request('tab', 'claim');

        // Find by customer name since dummy data uses names
        $requests = RewardRequest::where('customer_name', auth()->user()->name)
            ->latest()
            ->get();

        $claimable = $requests->where('status', 'pending');
        $history = $requests->whereIn('status', ['approved', 'declined']);

        return view('customer.claim-reward', compact('tab', 'claimable', 'history'));
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
