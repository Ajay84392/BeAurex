<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    //
    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'language' => 'nullable|string',
            'timezone' => 'nullable|string',
            'date_format' => 'nullable|string',
        ];

        // Conditional password validation
        if ($request->filled('password') && $request->filled('current_password')) {
            $rules['current_password'] = 'required';
            // Assuming the complexity rules can be mapped to Laravel's Password rule or just regex
            $rules['password'] = ['required', 'min:8', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()];
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

        $data = array_filter($request->only('name', 'phone', 'language', 'timezone', 'date_format'), function($value) {
            return !is_null($value) && $value !== '';
        });

        // Generate a username if empty
        if (empty($user->username) && !empty($request->name)) {
            $data['username'] = strtolower(preg_replace('/\s+/', '', $request->name)).$user->id;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $data['photo'] = '/storage/'.$path;
        }

        if ($request->filled('password')) {
            $data['password'] = \Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully');
    }
}
