<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Models\Business;
use App\Models\User;
use App\Rules\MobileNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MerchantAuthController extends Controller
{
    use SendsOtp;

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc|max:255',
            'password' => 'required|string|max:64',
        ]);

        $user = User::where('email', $request->email)->where('role', 'merchant')->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $this->loginAs($user, $request->boolean('remember'));

        return $this->redirectBasedOnOnboarding($user);
    }

    public function showLogin()
    {
        return view('auth.login', ['role' => 'merchant']);
    }

    public function showRegister()
    {
        return view('merchant.auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate(
            OtpAuthController::registrationRules(['phone' => ['required', new MobileNumber]]),
            OtpAuthController::registrationMessages()
        );

        // The same email may also hold a customer or admin account; only merchant accounts matter here.
        $user = User::where('email', $request->email)->where('role', 'merchant')->first();

        if ($user && $user->email_verified_at) {
            return OtpAuthController::alreadyRegistered('merchant', $request->email);
        }

        $phone = MobileNumber::format($request->phone);

        if ($user) {
            $user->name = $request->name;
            $user->phone = $phone;
            $user->password = Hash::make($request->password);
            $user->onboarding_step = 'email_verification';
            $user->save();
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
                'password' => Hash::make($request->password),
                'role' => 'merchant',
                'onboarding_step' => 'email_verification',
            ]);
        }

        $this->issueOtp($user);

        $this->loginAs($user);

        return redirect()->route('merchant.verify');
    }

    public function showVerify()
    {
        if (! Auth::check() || Auth::user()->role !== 'merchant') {
            return redirect()->route('merchant.login');
        }
        if (Auth::user()->email_verified_at) {
            $user = Auth::user();
            if ($user->onboarding_step === 'email_verification') {
                $user->onboarding_step = 'account_created';
                $user->save();
            }

            return $this->redirectBasedOnOnboarding($user);
        }

        return view('auth.verify-otp', [
            'email' => Auth::user()->email,
            'role' => 'merchant',
            'action' => route('merchant.verify'),
            'resendAction' => route('merchant.resend-otp'),
            'backUrl' => route('merchant.register'),
        ]);
    }

    public function processVerify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $user = Auth::user();

        if (! $user || $user->role !== 'merchant') {
            return redirect()->route('merchant.login');
        }

        if (! $this->consumeOtp($user, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        $user->email_verified_at = now();
        $user->onboarding_step = 'account_created';
        $user->save();

        return redirect()->route('merchant.created');
    }

    public function resendOtp()
    {
        $user = Auth::user();
        if (! $user || $user->email_verified_at) {
            return back();
        }
        $this->issueOtp($user);

        return back()->with('status', 'A new OTP has been sent to '.$user->email.'.');
    }

    public function showCreated()
    {
        if (! Auth::check()) {
            return redirect()->route('merchant.login');
        }

        return view('merchant.auth.created');
    }

    public function proceedToBatch2()
    {
        $user = Auth::user();
        $user->onboarding_step = 'business_information';
        $user->save();

        return redirect()->route('merchant.business-info');
    }

    public function showBusinessInfo()
    {
        if (! Auth::check()) {
            return redirect()->route('merchant.login');
        }

        return view('merchant.auth.business-info');
    }

    public function processBusinessInfo(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_category' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $user = Auth::user();

        $business = Business::firstOrNew(
            ['user_id' => $user->id],
            ['email' => $user->email, 'phone' => $user->phone]
        );

        $business->name = $request->business_name;
        $business->category = $request->business_category;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('merchant_logos', 'public');
            $business->logo = $logoPath;
        }
        $business->save();

        $user->onboarding_step = 'business_address';
        $user->save();

        return redirect()->route('merchant.business-address');
    }

    public function showBusinessAddress()
    {
        if (! Auth::check()) {
            return redirect()->route('merchant.login');
        }
        $business = Business::where('user_id', Auth::id())->first();

        return view('merchant.auth.business-address', compact('business'));
    }

    public function processBusinessAddress(Request $request)
    {
        $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pin_code' => 'required|string|max:20',
        ]);

        $business = Business::where('user_id', Auth::id())->first();
        if ($business) {
            $fullAddress = $request->address_line_1;
            if ($request->filled('address_line_2')) {
                $fullAddress .= ', '.$request->address_line_2;
            }
            $fullAddress .= ', '.$request->city.', '.$request->state.' - '.$request->pin_code;

            $business->address = $fullAddress;
            $business->city = $request->city;
            $business->state = $request->state;
            $business->pincode = $request->pin_code;
            $business->save();
        }

        $user = Auth::user();
        $user->onboarding_step = 'completed';
        $user->onboarding_completed_at = now();
        $user->save();

        return redirect()->route('merchant.setup-complete');
    }

    public function showSetupComplete()
    {
        if (! Auth::check()) {
            return redirect()->route('merchant.login');
        }

        return view('merchant.auth.setup-complete');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }

    public function redirectBasedOnOnboarding($user)
    {
        switch ($user->onboarding_step) {
            case 'account_registration':
                return redirect()->route('merchant.register');
            case 'email_verification':
                return redirect()->route('merchant.verify');
            case 'account_created':
                return redirect()->route('merchant.created');
            case 'business_information':
                return redirect()->route('merchant.business-info');
            case 'business_address':
                return redirect()->route('merchant.business-address');
            case 'completed':
            default:
                return redirect()->route('merchant.dashboard');
        }
    }
}
