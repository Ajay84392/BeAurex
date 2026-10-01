<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\CustomerVisit;
use App\Models\Plan;
use App\Models\RewardRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_date')) {
            $query->whereDate('payment_date', $request->payment_date);
        }

        if ($request->filled('payment_amount')) {
            $query->where('payment_amount', $request->payment_amount);
        }

        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        if ($request->filled('plan_valid_till')) {
            $query->whereDate('plan_valid_till', $request->plan_valid_till);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $merchants = $query->latest()->paginate(10)->withQueryString();

        $totalMerchants = Business::count();
        $activeMerchants = Business::where('status', 'Active')->count();
        $trialMerchants = Business::where('status', 'Trial')->count();
        $pendingPayment = Business::whereIn('status', ['Pending', 'Pending Payment'])->count();
        $onboardingToday = Business::whereDate('created_at', today())->count();
        $plans = Plan::where('is_active', true)->get();

        return view('admin.merchants.index', compact('merchants', 'totalMerchants', 'activeMerchants', 'trialMerchants', 'pendingPayment', 'onboardingToday', 'plans'));
    }

    public const STATUSES = ['Active', 'Trial', 'Pending', 'Inactive'];

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate(['status' => ['required', 'in:'.implode(',', self::STATUSES)]]);

        $merchant = Business::findOrFail($id);
        $merchant->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'status' => $merchant->status]);
    }

    public function updateComplimentary(Request $request, string $id)
    {
        $validated = $request->validate(['complimentary' => ['required', 'boolean']]);

        $merchant = Business::findOrFail($id);
        $merchant->update(['complimentary' => (bool) $validated['complimentary']]);

        return response()->json(['success' => true, 'complimentary' => (bool) $merchant->complimentary]);
    }

    public function show(string $id)
    {
        $merchant = Business::findOrFail($id);

        $visits = CustomerVisit::where('business_id', $merchant->id);
        $since = now()->subDays(30);
        $customers = (clone $visits)->distinct()->count('customer_id');
        $repeatCustomers = CustomerVisit::where('business_id', $merchant->id)
            ->select('customer_id')->groupBy('customer_id')->havingRaw('COUNT(*) > 1')->get()->count();

        $stats = [
            'scans' => (clone $visits)->count(),
            'scans30' => (clone $visits)->where('scanned_at', '>=', $since)->count(),
            'customers' => $customers,
            'customers30' => (clone $visits)->where('scanned_at', '>=', $since)->distinct()->count('customer_id'),
            'redeemed' => RewardRequest::where('business_id', $merchant->id)->where('status', 'approved')->count(),
            'pending' => RewardRequest::where('business_id', $merchant->id)->where('status', 'pending')->count(),
            'repeatCustomers' => $repeatCustomers,
            'repeatRate' => $customers > 0 ? round($repeatCustomers / $customers * 100, 1) : 0,
        ];

        return view('admin.merchants.show', compact('merchant', 'stats'));
    }

    public function edit(string $id)
    {
        $merchant = Business::findOrFail($id);

        return view('admin.merchants.edit', compact('merchant'));
    }

    public function update(Request $request, ?string $id = null)
    {
        $merchant = Business::findOrFail($id);
        $merchant->update($request->all());

        return back()->with('success', 'Updated');
    }

    public function extendValidity(Request $request, string $id)
    {
        $request->validate(['extend_days' => 'required|integer|min:1']);

        $merchant = Business::findOrFail($id);

        // If plan_valid_till exists and is in the future, extend from there; otherwise extend from today
        $baseDate = $merchant->plan_valid_till && Carbon::parse($merchant->plan_valid_till)->isFuture()
            ? Carbon::parse($merchant->plan_valid_till)
            : now();

        $newDate = $baseDate->addDays((int) $request->extend_days);

        $merchant->update([
            'plan_valid_till' => $newDate,
            'status' => 'Active',
        ]);

        $days = (int) $request->extend_days;
        $label = match (true) {
            $days >= 36500 => 'Forever',
            $days >= 365 => '1 Year',
            $days >= 180 => '180 Days',
            $days >= 90 => '90 Days',
            $days >= 30 => '30 Days',
            $days >= 15 => '15 Days',
            default => '1 Week',
        };

        return back()->with('success', "Plan validity for {$merchant->business_name} extended by {$label}. New expiry: ".$newDate->format('M d, Y'));
    }

    public function destroy(string $id)
    {
        Business::destroy($id);

        return back()->with('success', 'Deleted');
    }
}
