<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerVisit;
use App\Models\RewardRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);

        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('status', 'Active')->count();
        $newToday = Customer::whereDate('created_at', today())->count();

        // Coins collected by all customers, and rewards merchants have approved.
        $totalStamps = CustomerVisit::where('stamp_awarded', true)->count();
        $totalRewards = RewardRequest::where('status', 'approved')->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers', 'activeCustomers', 'newToday', 'totalStamps', 'totalRewards'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate(['status' => ['required', 'in:Active,Inactive,Blocked']]);

        $customer = Customer::findOrFail($id);
        $customer->update(['status' => $validated['status']]);

        return response()->json(['success' => true, 'status' => $customer->status]);
    }

    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function update(Request $request, ?string $id = null)
    {
        return back();
    }

    public function destroy(string $id)
    {
        Customer::findOrFail($id)->delete();

        return back()->with('success', 'Customer deleted');
    }
}
