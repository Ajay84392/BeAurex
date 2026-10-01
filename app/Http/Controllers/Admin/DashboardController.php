<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\RewardRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $yesterday = today()->subDay();
        $monthStart = now()->startOfMonth();

        // Merchants that can use the platform (everyone except those switched off by an admin).
        $activeMerchants = Business::where('status', '!=', 'Inactive')->count();
        $newMerchantsThisMonth = Business::where('created_at', '>=', $monthStart)->count();
        $todayMerchants = Business::whereDate('created_at', $today)->count();
        $yesterdayMerchants = Business::whereDate('created_at', $yesterday)->count();

        // Revenue = recorded merchant plan payments.
        $todayRevenue = (float) Business::whereDate('payment_date', $today)->sum('payment_amount');
        $yesterdayRevenue = (float) Business::whereDate('payment_date', $yesterday)->sum('payment_amount');
        $totalRevenue = (float) Business::sum('payment_amount');
        $monthRevenue = (float) Business::where('payment_date', '>=', $monthStart)->sum('payment_amount');

        // Loyalty activity across all merchants.
        $totalCustomers = Customer::count();
        $newCustomersThisMonth = Customer::where('created_at', '>=', $monthStart)->count();
        $coinsCollected = CustomerVisit::where('stamp_awarded', true)->count();
        $coinsToday = CustomerVisit::where('stamp_awarded', true)->whereDate('scanned_at', $today)->count();
        $rewardsRedeemed = RewardRequest::where('status', 'approved')->count();
        $pendingRewards = RewardRequest::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'activeMerchants', 'newMerchantsThisMonth', 'todayMerchants', 'yesterdayMerchants',
            'todayRevenue', 'yesterdayRevenue', 'totalRevenue', 'monthRevenue',
            'totalCustomers', 'newCustomersThisMonth', 'coinsCollected', 'coinsToday', 'rewardsRedeemed', 'pendingRewards'
        ));
    }
}
