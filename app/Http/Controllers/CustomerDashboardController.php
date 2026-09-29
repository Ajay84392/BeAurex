<?php

namespace App\Http\Controllers;

use App\Models\RewardRequest;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|max:2048',
            'language' => 'required|string',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
        ]);
        $data = $request->only('name', 'phone', 'language', 'timezone', 'date_format');
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
        session()->forget('customer_logged_in');

        return redirect('/');
    }
}
