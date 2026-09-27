<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $deals = session()->get('deals', []);
        return view('admin.claims', compact('deals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_name' => 'nullable|string',
            'plan_type' => 'nullable|string',
            'state' => 'nullable|string',
            'deal_name' => 'required|string',
            'coupon_code' => 'required|string',
            'bonus_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'validity_date' => 'required|date',
            'maximum_usage' => 'nullable|integer',
        ]);

        $request->session()->push('deals', $data);

        return redirect()->back()->with('success', 'Deal added successfully!');
    }

    public function show(string $id)
    {
        return view('admin.claims');
    }

    public function edit(string $id)
    {
        $deals = session()->get('deals', []);
        if (!isset($deals[$id])) {
            return redirect()->route('claims.index')->with('error', 'Deal not found');
        }
        $editDeal = $deals[$id];
        $editId = $id;
        
        return view('admin.claims', compact('deals', 'editDeal', 'editId'));
    }

    public function update(Request $request, string $id)
    {
        $deals = session()->get('deals', []);
        if (!isset($deals[$id])) {
            return redirect()->route('claims.index')->with('error', 'Deal not found');
        }

        $data = $request->validate([
            'plan_name' => 'nullable|string',
            'plan_type' => 'nullable|string',
            'state' => 'nullable|string',
            'deal_name' => 'required|string',
            'coupon_code' => 'required|string',
            'bonus_amount' => 'nullable|numeric',
            'discount_amount' => 'nullable|numeric',
            'discount_percentage' => 'nullable|numeric',
            'validity_date' => 'required|date',
            'maximum_usage' => 'nullable|integer',
        ]);

        $deals[$id] = $data;
        session()->put('deals', $deals);

        return redirect('/admin/claims')->with('success', 'Deal updated successfully!');
    }

    public function destroy(string $id)
    {
        $deals = session()->get('deals', []);
        if (isset($deals[$id])) {
            unset($deals[$id]);
            session()->put('deals', $deals);
        }

        return redirect()->back()->with('success', 'Deal deleted successfully!');
    }
}
