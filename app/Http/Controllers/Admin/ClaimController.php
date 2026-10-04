<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index(Request $request)
    {
        $deals = Deal::latest()->latest('id')->get();

        return view('admin.claims', compact('deals'));
    }

    public function store(Request $request)
    {
        Deal::create($this->validated($request));

        return redirect()->back()->with('success', 'Deal added successfully!');
    }

    public function show(string $id)
    {
        return redirect('/admin/claims');
    }

    public function edit(string $id)
    {
        $editDeal = Deal::find($id);
        if (! $editDeal) {
            return redirect()->route('claims.index')->with('error', 'Deal not found');
        }
        $deals = Deal::latest()->latest('id')->get();
        $editId = $editDeal->id;

        return view('admin.claims', compact('deals', 'editDeal', 'editId'));
    }

    public function update(Request $request, string $id)
    {
        $deal = Deal::find($id);
        if (! $deal) {
            return redirect()->route('claims.index')->with('error', 'Deal not found');
        }

        $deal->update($this->validated($request));

        return redirect('/admin/claims')->with('success', 'Deal updated successfully!');
    }

    public function destroy(string $id)
    {
        // Merchants using this deal fall back to "No Deal" (deal_id is nulled on delete).
        Deal::whereKey($id)->delete();

        return redirect()->back()->with('success', 'Deal deleted successfully!');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'plan_name' => 'nullable|string|max:255',
            'plan_type' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'deal_name' => 'required|string|max:255',
            'coupon_code' => ['required', 'string', 'max:255', 'regex:/^[A-Z]+$/'],
            'bonus_amount' => 'nullable|numeric|min:0|max:99999999',
            'discount_amount' => 'nullable|numeric|min:0|max:99999999',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'validity_date' => 'required|date',
            'maximum_usage' => 'nullable|integer|min:0',
        ], [
            'coupon_code.regex' => 'The coupon code can only contain capital letters (A-Z), with no numbers, spaces or symbols.',
        ]);
    }
}
