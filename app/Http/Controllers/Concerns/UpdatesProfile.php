<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use App\Rules\MobileNumber;
use App\Support\Media;
use App\Support\ProfileOptions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Shared validation and saving for the customer, merchant and admin profile pages.
 *
 * Only fields present in the request are touched, so a field the form doesn't send keeps
 * its value, while a field sent empty is cleared (or rejected when it is required).
 */
trait UpdatesProfile
{
    protected function accountRules(Request $request, User $user, bool $phoneRequired): array
    {
        $emailChanged = $request->filled('email') && strtolower(trim($request->input('email'))) !== $user->email;
        $needsCurrentPassword = $request->filled('password') || $emailChanged;

        return [
            'name' => ['sometimes', 'required', 'string', 'min:2', 'max:100', 'regex:/^[\pL][\pL\s.\'-]*$/u'],
            'email' => ['sometimes', 'required', 'string', 'email:rfc', 'max:255',
                Rule::unique('users', 'email')->where('role', $user->role)->ignore($user->id)],
            'phone' => $phoneRequired
                ? ['sometimes', 'required', 'string', new MobileNumber]
                : ['nullable', 'string', new MobileNumber],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'language' => ['sometimes', 'required', Rule::in(array_keys(ProfileOptions::LANGUAGES))],
            'timezone' => ['sometimes', 'required', Rule::in(array_keys(ProfileOptions::TIMEZONES))],
            'date_format' => ['sometimes', 'required', Rule::in(ProfileOptions::DATE_FORMATS)],
            // Asked for only when changing the password or the login email, so browser autofill can't block a normal save.
            'current_password' => $needsCurrentPassword ? ['required', 'string', 'current_password'] : ['exclude'],
            'password' => ['nullable', 'string', 'max:64', Password::defaults(), 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    protected function accountMessages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.regex' => 'Name can only contain letters, spaces, dots, apostrophes and hyphens.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already used by another account.',
            'phone.required' => 'Mobile number is required.',
            'language.in' => 'Please choose a language from the list.',
            'timezone.in' => 'Please choose a timezone from the list.',
            'date_format.in' => 'Please choose a date format from the list.',
            'photo.image' => 'The photo must be an image (JPG, PNG, GIF or WEBP).',
            'photo.mimes' => 'The photo must be a JPG, PNG, GIF or WEBP file.',
            'photo.max' => 'The photo may not be larger than 2 MB.',
            'current_password.required' => 'Enter your current password to change your password or email.',
            'current_password.current_password' => 'Current password is incorrect.',
            'password.max' => 'Password may not be longer than 64 characters.',
            'password.confirmed' => 'New passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed' => 'Password must contain both an uppercase and a lowercase letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol.',
        ];
    }

    /**
     * Lowercase/trim the email and squash spaces in the name before validating.
     */
    protected function normalizeProfileInput(Request $request): void
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => strtolower(trim($request->input('email')))]);
        }
        if (is_string($request->input('name'))) {
            $request->merge(['name' => preg_replace('/\s+/', ' ', trim($request->input('name')))]);
        }
    }

    /**
     * Copy the validated account fields onto the user and save it.
     */
    protected function saveAccount(Request $request, User $user, array $validated): void
    {
        foreach (['name', 'email', 'language', 'timezone', 'date_format'] as $field) {
            if (array_key_exists($field, $validated)) {
                $user->{$field} = $validated[$field];
            }
        }

        if (array_key_exists('phone', $validated)) {
            $user->phone = MobileNumber::format($validated['phone']);
        }

        $oldPhoto = null;
        if ($request->hasFile('photo')) {
            $oldPhoto = $user->photo;
            $user->photo = Media::store($request->file('photo'), 'profiles');
        }

        $passwordChanged = ! empty($validated['password']);
        if ($passwordChanged) {
            $user->password = $validated['password']; // hashed by the model cast
        }

        $user->save();

        // Only remove the old photo once the new one is saved.
        Media::delete($oldPhoto);

        if ($passwordChanged) {
            $request->session()->regenerate();
        }
    }
}
