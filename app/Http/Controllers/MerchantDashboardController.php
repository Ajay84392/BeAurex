<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\UpdatesProfile;
use App\Mail\LoginOtpMail;
use App\Models\Business;
use App\Models\CustomerVisit;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\RewardRequest;
use App\Models\User;
use App\Support\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class MerchantDashboardController extends Controller
{
    use UpdatesProfile;

    public function index()
    {
        // The logged-in merchant's own business (the same one their QR code points to).
        $business = self::ownBusiness();

        $visits = CustomerVisit::where('business_id', $business->id);
        $monthStart = now()->startOfMonth();

        $totalScans = (clone $visits)->count();
        $scansThisMonth = (clone $visits)->where('scanned_at', '>=', $monthStart)->count();
        $totalCustomers = (clone $visits)->distinct()->count('customer_id');
        // Customers whose first visit here was this month.
        $newCustomersThisMonth = CustomerVisit::where('business_id', $business->id)
            ->selectRaw('customer_id, MIN(scanned_at) as first_visit')->groupBy('customer_id')
            ->havingRaw('MIN(scanned_at) >= ?', [$monthStart])->get()->count();

        $approved = RewardRequest::where('business_id', $business->id)->where('status', 'approved');
        $rewardsRedeemed = (clone $approved)->count();
        $redeemedThisMonth = (clone $approved)->where('updated_at', '>=', $monthStart)->count();
        $pendingRewards = RewardRequest::where('business_id', $business->id)->where('status', 'pending')->count();

        // Repeat rate: customers with more than one visit / all customers.
        $repeatCustomers = CustomerVisit::where('business_id', $business->id)
            ->select('customer_id')->groupBy('customer_id')->havingRaw('COUNT(*) > 1')->get()->count();
        $repeatRate = $totalCustomers > 0 ? round($repeatCustomers / $totalCustomers * 100) : 0;

        // Plan shown in the banner comes from the business record (set by admin / payments).
        $plan = Plan::where('name', $business->plan)->first();
        $planValidTill = $business->plan_valid_till ? Carbon::parse($business->plan_valid_till) : null;

        $qrCode = null;
        $qrUrl = self::qrTargetUrl();
        $qrImage = self::qrImageUrl();

        return view('merchant.dashboard', compact(
            'business',
            'totalScans',
            'scansThisMonth',
            'totalCustomers',
            'newCustomersThisMonth',
            'rewardsRedeemed',
            'redeemedThisMonth',
            'pendingRewards',
            'repeatRate',
            'repeatCustomers',
            'plan',
            'planValidTill',
            'qrCode',
            'qrUrl',
            'qrImage'
        ));
    }

    /**
     * Where the logged-in merchant's QR code sends customers: the coin-collect step for their business.
     */
    public static function qrTargetUrl(): string
    {
        return route('customer.collect', self::ownBusiness());
    }

    /**
     * PNG of the merchant's QR code. High error correction (H, ~30%) keeps it readable with the
     * logo drawn over its centre, and the quiet-zone margin lets phone cameras lock on quickly.
     */
    public static function qrImageUrl(int $size = 400): string
    {
        return 'https://api.qrserver.com/v1/create-qr-code/?'.http_build_query([
            'size' => $size.'x'.$size,
            'ecc' => 'H',
            'qzone' => 2,
            'margin' => 0,
            'format' => 'png',
            'data' => self::qrTargetUrl(),
        ]);
    }

    /**
     * The logged-in merchant's business. A merchant who skipped onboarding gets a basic one
     * created from their account, so their QR code always awards coins instead of dead-ending.
     */
    public static function ownBusiness(): Business
    {
        $user = auth()->user();

        return Business::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'email' => $user->email, 'phone' => $user->phone]
        );
    }

    public function profile()
    {
        // Only ever show the logged-in merchant's own business.
        $business = Business::where('user_id', auth()->id())->first();

        return view('merchant.profile', compact('business'));
    }

    public function updateProfile(Request $request)
    {
        $business = Business::where('user_id', auth()->id())->first();
        $user = auth()->user();
        $this->normalizeProfileInput($request);
        if (is_string($request->input('business_name'))) {
            $request->merge(['business_name' => preg_replace('/\s+/', ' ', trim($request->input('business_name')))]);
        }

        $rules = $this->accountRules($request, $user, phoneRequired: true) + [
            'business_name' => ['sometimes', 'required', 'string', 'min:2', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'state' => ['nullable', 'string', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'pincode' => ['nullable', 'digits:6'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];

        $validated = $request->validate($rules, $this->accountMessages() + [
            'business_name.required' => 'Business name is required.',
            'business_name.min' => 'Business name must be at least 2 characters.',
            'city.regex' => 'City can only contain letters.',
            'state.regex' => 'State can only contain letters.',
            'pincode.digits' => 'Pincode must be exactly 6 digits.',
            'logo.image' => 'The logo must be an image (JPG, PNG, GIF or WEBP).',
            'logo.mimes' => 'The logo must be a JPG, PNG, GIF or WEBP file.',
            'logo.max' => 'The logo may not be larger than 2 MB.',
        ]);

        $business ??= new Business(['user_id' => $user->id, 'name' => $user->name]);
        $oldLogo = $business->logo;
        $newLogo = $request->hasFile('logo') ? Media::store($request->file('logo'), 'merchant_logos') : null;

        try {
            DB::transaction(function () use ($request, $user, $business, $validated, $newLogo) {
                $this->saveAccount($request, $user, $validated);

                if (array_key_exists('business_name', $validated)) {
                    $business->name = $validated['business_name'];
                }
                foreach (['category', 'description', 'address', 'city', 'state', 'pincode'] as $field) {
                    if (array_key_exists($field, $validated)) {
                        $business->{$field} = $validated[$field];
                    }
                }
                if ($newLogo) {
                    $business->logo = $newLogo;
                }

                // The business contact details follow the merchant's account.
                $business->email = $user->email;
                $business->phone = $user->phone ?? $business->phone;
                $business->save();
            });
        } catch (\Throwable $e) {
            // Never fail silently: keep the old logo, log the real cause, tell the merchant.
            Media::delete($newLogo);
            report($e);

            return redirect()->back()->withInput($request->except(['password', 'password_confirmation', 'current_password', 'logo']))
                ->with('error', 'Your profile could not be saved. Please try again in a moment — if it keeps happening, contact BeAurex support.');
        }

        if ($newLogo) {
            Media::delete($oldLogo);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateAutoApproval(Request $request)
    {
        $business = Business::where('user_id', auth()->id())->first();
        if ($business) {
            $business->auto_approval = $request->has('auto_approval');
            $business->auto_reward_approval = $request->has('auto_reward_approval');
            $business->save();
        }

        return back()->with('success', 'Auto Approval settings updated.');
    }

    public function rewards()
    {
        // Only this merchant's own claims.
        $business = self::ownBusiness();
        $query = RewardRequest::with('customer')->where('business_id', $business->id);

        $status = request('status', 'pending');
        if (! in_array($status, ['pending', 'approved', 'declined', 'programs'], true)) {
            $status = 'pending';
        }

        // Redirect programs tab to pending (tab removed from UI)
        if ($status === 'programs') {
            $status = 'pending';
        }

        $requests = collect();
        $programs = collect();

        // Pending: newest request first. Approved/Declined: most recently actioned first
        if ($status === 'pending') {
            $requests = $query->where('status', $status)->latest('created_at')->get();
        } else {
            $requests = $query->where('status', $status)->latest('updated_at')->get();
        }

        $counts = [
            'pending' => RewardRequest::where('business_id', $business->id)->where('status', 'pending')->count(),
            'approved' => RewardRequest::where('business_id', $business->id)->where('status', 'approved')->count(),
            'declined' => RewardRequest::where('business_id', $business->id)->where('status', 'declined')->count(),
        ];

        return view('merchant.rewards', compact('requests', 'programs', 'status', 'counts'));
    }

    public function updateRewardStatus(Request $request, $id)
    {
        $request->validate(['status' => ['required', 'in:approved,declined']]);

        // A merchant can only act on claims made at their own business, and only once.
        $rewardRequest = RewardRequest::where('business_id', self::ownBusiness()->id)->findOrFail($id);
        if ($rewardRequest->status !== 'pending') {
            return redirect()->route('merchant.rewards', ['status' => $rewardRequest->status])
                ->with('error', 'This reward was already '.$rewardRequest->status.'.');
        }

        // Declining returns the customer's coins automatically (declined claims don't count as spent).
        $rewardRequest->update(['status' => $request->status]);

        // Redirect to the tab matching the action so the new card appears at the top
        return redirect()->route('merchant.rewards', ['status' => $request->status])
            ->with('success', $request->status === 'approved' ? 'Reward approved.' : 'Reward declined. The customer\'s coins were returned.');
    }

    public function liveOffers()
    {
        $business = self::ownBusiness();
        $offers = Offer::where('business_id', $business->id)->get();

        return view('merchant.live-offers', compact('offers'));
    }

    public function createOffer()
    {
        $business = Business::where('user_id', auth()->id())->first();

        $existingOffers = [];
        if ($business) {
            $existingOffers = Offer::where('business_id', $business->id)->get()->map(function ($offer) {
                $img = Media::url($offer->image);

                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'description' => $offer->description,
                    'visits' => $offer->orex_coins,
                    'expiry' => $offer->expiry ?? '',
                    'image' => $img,
                ];
            })->toArray();
        }

        $offers = [
            ['id' => 1, 'visits' => 10, 'expiry' => '', 'title' => '', 'description' => '', 'image' => null],
        ];

        return view('merchant.create-offer', compact('offers', 'existingOffers'));
    }

    public function storeOffer(Request $request)
    {
        $request->validate([
            'rewards_json' => 'required|string',
        ]);

        $business = self::ownBusiness();

        $rewards = json_decode($request->rewards_json, true);

        if (is_array($rewards)) {
            foreach ($rewards as $reward) {
                // Only save if title or description is provided
                if (! empty($reward['title']) || ! empty($reward['description'])) {

                    $imagePath = null;
                    if (! empty($reward['image']) && str_starts_with($reward['image'], 'data:image')) {
                        // Decode base64 image
                        $imageParts = explode(';base64,', $reward['image']);
                        $imageType = strtolower(explode('image/', $imageParts[0])[1] ?? '');
                        $extensions = ['jpeg' => 'jpg', 'jpg' => 'jpg', 'png' => 'png', 'webp' => 'webp', 'gif' => 'gif'];
                        $bytes = count($imageParts) === 2 ? base64_decode($imageParts[1], true) : false;
                        // Real raster images only, at most 2 MB (no SVG: it can carry scripts).
                        if ($bytes !== false && isset($extensions[$imageType]) && strlen($bytes) <= 2 * 1024 * 1024 && @getimagesizefromstring($bytes)) {
                            $imagePath = Media::put('offers', $extensions[$imageType], $bytes);
                        }
                    } elseif (! empty($reward['image']) && str_starts_with($reward['image'], 'http')
                        && ! str_starts_with($reward['image'], url('/'))) {
                        // An external image link. (Our own image URL coming back means "unchanged".)
                        $imagePath = $reward['image'];
                    }

                    $offerData = [
                        'title' => $reward['title'] ?? '',
                        'description' => $reward['description'] ?? '',
                        'orex_coins' => (int) ($reward['visits'] ?? 1),
                        'expiry' => $reward['expiry'] ?? null,
                    ];

                    if ($imagePath) {
                        $offerData['image'] = $imagePath;
                    }

                    if (! empty($reward['id']) && is_numeric($reward['id']) && $reward['id'] < 1000000000) {
                        // Update existing offer
                        $offer = Offer::where('business_id', $business->id)->find($reward['id']);
                        if ($offer) {
                            $offer->update($offerData);
                        }
                    } else {
                        // Create new offer
                        $offerData['business_id'] = $business->id;
                        Offer::create($offerData);
                    }
                }
            }
        }

        return redirect()->route('merchant.create-offer')->with('success', 'Offer saved successfully!');
    }

    public function destroyOffer($id)
    {
        $business = Business::where('user_id', auth()->id())->first();
        if ($business) {
            $offer = Offer::where('business_id', $business->id)->findOrFail($id);
            $offer->delete();
            Media::delete($offer->image);
        }

        return redirect()->route('merchant.create-offer')->with('success', 'Offer deleted successfully!');
    }
}
