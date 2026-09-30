<?php

namespace App\Http\Controllers;

use App\Mail\LoginOtpMail;
use App\Models\Business;
use App\Models\Offer;
use App\Models\RewardRequest;
use App\Models\User;
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
    public function index()
    {
        // Get the first business or a default dummy one
        $business = DB::table('businesses')->first();

        $totalScans = 0;
        $totalCustomers = 0;
        $rewardsRedeemed = 0;
        $repeatRate = 0;
        $qrCode = null;

        if ($business) {
            $totalScans = DB::table('customer_visits')->where('business_id', $business->id)->count();
            $totalCustomers = DB::table('customer_visits')->where('business_id', $business->id)->distinct('customer_id')->count('customer_id');
            // Assuming rewards might be in a customer_rewards table or similar
            // For now, let's just make it count from rewards if redeemed, or just use a dummy if not found
            $rewardsRedeemed = 0; // DB::table('customer_rewards')->where('business_id', $business->id)->where('status', 'redeemed')->count();

            // Repeat rate calculation: customers with > 1 visit / total customers
            $repeatCustomers = DB::table('customer_visits')
                ->select('customer_id')
                ->where('business_id', $business->id)
                ->groupBy('customer_id')
                ->havingRaw('COUNT(*) > 1')
                ->get()
                ->count();

            $repeatRate = $totalCustomers > 0 ? round(($repeatCustomers / $totalCustomers) * 100) : 0;

            $qrCode = DB::table('qr_codes')->where('business_id', $business->id)->first();
        }

        // Dummy data fallback to match the design if DB is empty
        if (! $business) {
            $business = (object) [
                'name' => 'Ka-feen Café',
                'category' => 'Café',
                'contact_number' => '+91 98765 43210',
                'email' => 'kafeencafe@gmail.com',
                'address' => '123, MG Road, Connaught Place, New Delhi - 110001',
            ];
            $totalScans = 2453;
            $totalCustomers = 586;
            $rewardsRedeemed = 128;
            $repeatRate = 42;
        }

        return view('merchant.dashboard', compact(
            'business',
            'totalScans',
            'totalCustomers',
            'rewardsRedeemed',
            'repeatRate',
            'qrCode'
        ));
    }

    public function profile()
    {
        $business = Business::where('user_id', auth()->id())->first() ?? Business::first();

        return view('merchant.profile', compact('business'));
    }

    public function updateProfile(Request $request)
    {
        $business = Business::where('user_id', auth()->id())->first();
        $user = auth()->user();

        $rules = [
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ];

        if ($request->filled('password') && $request->filled('current_password')) {
            $rules['current_password'] = 'required';
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $request->request->remove('password');
            $request->request->remove('current_password');
        }

        $request->validate($rules);

        if ($request->filled('current_password')) {
            if (! \Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.'])->withInput();
            }
        }

        $data = array_filter($request->only(['name', 'category', 'phone', 'email', 'address']), function($value) {
            return !is_null($value) && $value !== '';
        });

        if ($request->hasFile('logo')) {
            if ($business && $business->logo) {
                // optionally delete old logo
            }
            $logoPath = $request->file('logo')->store('merchant_logos', 'public');
            $data['logo'] = $logoPath;
        }

        if (!empty($data)) {
            if ($business) {
                $business->update($data);
            } else {
                $data['user_id'] = auth()->id();
                $business = Business::create($data);
            }
        }

        // Also update user
        if ($user) {
            $userData = [];
            if ($request->email) $userData['email'] = $request->email;
            if ($request->name) $userData['name'] = $request->name;
            if ($request->filled('password')) $userData['password'] = \Hash::make($request->password);
            
            if (!empty($userData)) {
                $user->update($userData);
            }
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
        $business = Business::where('user_id', auth()->id())->first() ?? Business::first();

        $query = RewardRequest::query();
        if ($business) {
            $query->where('business_id', $business->id);
        }

        $status = request('status', 'pending');

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
            'pending' => RewardRequest::where('business_id', $business?->id)->where('status', 'pending')->count(),
            'approved' => RewardRequest::where('business_id', $business?->id)->where('status', 'approved')->count(),
            'declined' => RewardRequest::where('business_id', $business?->id)->where('status', 'declined')->count(),
        ];

        return view('merchant.rewards', compact('requests', 'programs', 'status', 'counts'));
    }

    public function updateRewardStatus(Request $request, $id)
    {
        $rewardRequest = RewardRequest::findOrFail($id);

        if (in_array($request->status, ['approved', 'declined'])) {
            $rewardRequest->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);
        }

        // Redirect to the tab matching the action so the new card appears at the top
        return redirect()->route('merchant.rewards', ['status' => $request->status])
            ->with('success', 'Reward status updated.');
    }

    public function liveOffers()
    {
        $business = Business::first();
        $offers = Offer::where('business_id', $business->id)->get();

        return view('merchant.live-offers', compact('offers'));
    }

    public function createOffer()
    {
        $business = Business::where('user_id', auth()->id())->first();

        $existingOffers = [];
        if ($business) {
            $existingOffers = Offer::where('business_id', $business->id)->get()->map(function ($offer) {
                $img = $offer->image;
                if ($img && ! str_starts_with($img, 'http') && ! str_starts_with($img, 'data:')) {
                    $img = asset('storage/'.$img);
                }

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

        $business = Business::where('user_id', auth()->id())->first();
        if (! $business) {
            // Auto-create basic business profile if missing
            $business = Business::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name ?? 'My Business',
                'phone' => '0000000000',
                'email' => 'business_'.auth()->id().'_'.time().'@druto.com',
            ]);
        }

        $rewards = json_decode($request->rewards_json, true);

        if (is_array($rewards)) {
            foreach ($rewards as $reward) {
                // Only save if title or description is provided
                if (! empty($reward['title']) || ! empty($reward['description'])) {

                    $imagePath = null;
                    if (! empty($reward['image']) && str_starts_with($reward['image'], 'data:image')) {
                        // Decode base64 image
                        $imageParts = explode(';base64,', $reward['image']);
                        if (count($imageParts) == 2) {
                            $imageTypeAux = explode('image/', $imageParts[0]);
                            $imageType = $imageTypeAux[1];
                            $imageBase64 = base64_decode($imageParts[1]);
                            $fileName = 'reward_'.uniqid().'.'.$imageType;
                            Storage::disk('public')->put('offers/'.$fileName, $imageBase64);
                            $imagePath = 'offers/'.$fileName;
                        }
                    } elseif (! empty($reward['image']) && str_starts_with($reward['image'], 'http')) {
                        // Keep placeholder image for testing
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
                        if (! isset($offerData['image']) && ! empty($reward['image']) && str_starts_with($reward['image'], 'http')) {
                            $offerData['image'] = $reward['image'];
                        }
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
        }

        return redirect()->route('merchant.create-offer')->with('success', 'Offer deleted successfully!');
    }
}
