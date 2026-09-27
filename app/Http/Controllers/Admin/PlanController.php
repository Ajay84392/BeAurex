<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::latest()->paginate(10);

        return view('admin.plans.index', compact('plans'));
    }

    public function history()
    {
        $plans = Plan::latest()->paginate(10);

        return view('admin.plans.history', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'billing_cycle' => 'required|string',
            'type' => 'required|string',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'short_description' => 'nullable|string|max:150',
            'detailed_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
        ]);

        $validated['features'] = json_encode(array_values(array_filter($request->input('features'))));
        $validated['is_active'] = $request->input('is_active', 1);

        Plan::create($validated);

        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $plan = Plan::findOrFail($id);

        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, string $id)
    {
        $plan = Plan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'billing_cycle' => 'required|string',
            'type' => 'required|string',
            'button_text' => 'required|string|max:255',
            'button_link' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'short_description' => 'nullable|string|max:150',
            'detailed_description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'features' => 'required|array',
            'features.*' => 'required|string|max:255',
        ]);

        $validated['features'] = json_encode(array_values(array_filter($request->input('features'))));
        $validated['is_active'] = $request->input('is_active', 1);

        $plan->update($validated);

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();

        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }
}
