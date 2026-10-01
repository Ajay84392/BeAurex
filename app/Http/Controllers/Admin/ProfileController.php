<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UpdatesProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use UpdatesProfile;

    public function index()
    {
        return view('admin.profile');
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $this->normalizeProfileInput($request);
        if (is_string($request->input('username'))) {
            $request->merge(['username' => strtolower(trim($request->input('username')))]);
        }

        $rules = $this->accountRules($request, $user, phoneRequired: true);
        $rules['username'] = ['sometimes', 'required', 'string', 'min:3', 'max:50', 'regex:/^[a-z0-9._-]+$/',
            Rule::unique('users', 'username')->ignore($user->id)];

        $validated = $request->validate($rules, $this->accountMessages() + [
            'username.required' => 'Username is required.',
            'username.min' => 'Username must be at least 3 characters.',
            'username.regex' => 'Username can only contain lowercase letters, numbers, dots, dashes and underscores.',
            'username.unique' => 'This username is already taken.',
        ]);

        if (array_key_exists('username', $validated)) {
            $user->username = $validated['username'];
        }

        $this->saveAccount($request, $user, $validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
