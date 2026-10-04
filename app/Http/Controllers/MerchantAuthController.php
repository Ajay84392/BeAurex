<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Business;
use App\Models\Referral;
use App\Models\User;
use App\Rules\MobileNumber;
use App\Support\IndiaPost;
use App\Support\Media;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MerchantAuthController extends Controller
{
    use SendsOtp;

    public function processLogin(LoginRequest $request)
    {
        $user = $request->authenticate('merchant');

        $this->loginAs($user, $request->boolean('remember'));

        return $this->redirectBasedOnOnboarding($user);
    }

    public function showLogin()
    {
        return view('merchant.auth.login', ['role' => 'merchant']);
    }

    public function showRegister()
    {
        return view('merchant.auth.register');
    }

    public function processRegister(RegisterRequest $request)
    {
        $email = $request->validated('email');

        // The same email may also hold a customer or admin account; only merchant accounts matter here.
        // RegisterRequest has already refused a verified merchant email or phone, so any account
        // found here is an unfinished sign-up being retried.
        $user = User::where('email', $email)->where('role', 'merchant')->first();

        $phone = MobileNumber::format($request->validated('phone'));
        $password = Hash::make($request->validated('password'));

        if ($user) {
            $user->name = $request->validated('name');
            $user->phone = $phone;
            $user->password = $password;
            $user->onboarding_step = 'email_verification';
            $user->save();
        } else {
            try {
                $user = User::create([
                    'name' => $request->validated('name'),
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'role' => 'merchant',
                    'onboarding_step' => 'email_verification',
                ]);
            } catch (UniqueConstraintViolationException $e) {
                return OtpAuthController::emailUsedElsewhere($e);
            }
        }

        $this->recordReferral($user, $request->validated('referral_code'));

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

    public function processVerify(OtpRequest $request)
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'merchant') {
            return redirect()->route('merchant.login');
        }

        if (! $this->consumeOtp($user, $request->validated('otp'))) {
            return back()->withErrors(['otp' => 'Invalid or expired code. Please try again or request a new code.']);
        }

        $user->email_verified_at = now();
        $user->onboarding_step = 'account_created';
        $user->save();

        // A referred sign-up only counts once the new account is real.
        Referral::where('referred_id', $user->id)->where('status', 'Pending')->update(['status' => 'Completed']);

        return redirect()->route('merchant.created');
    }

    /**
     * Note who referred this sign-up. A retried sign-up keeps its first referral,
     * so the code can't be swapped after the fact.
     */
    private function recordReferral(User $user, ?string $code): void
    {
        $referrer = User::byReferralCode($code, 'merchant');

        if (! $referrer || $referrer->id === $user->id || Referral::where('referred_id', $user->id)->exists()) {
            return;
        }

        // referrals.code is unique per row, so each referral gets its own code (as in the admin panel).
        do {
            $rowCode = 'REF-'.strtoupper(Str::random(6));
        } while (Referral::where('code', $rowCode)->exists());

        Referral::create([
            'code' => $rowCode,
            'referrer_id' => $referrer->id,
            'referred_email' => $user->email,
            'referred_id' => $user->id,
            'reward_coins' => 0,
            'status' => 'Pending',
        ]);
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

        return view('merchant.auth.business-info', [
            'business' => Business::where('user_id', Auth::id())->first(),
            'categories' => self::CATEGORIES,
        ]);
    }

    /** Business types offered in sign-up. */
    public const CATEGORIES = [
        'Cafe', 'Restaurant', 'Bakery', 'Sweet Shop', 'Grocery & Supermarket', 'Retail Store', 'Clothing & Fashion',
        'Electronics', 'Salon & Beauty', 'Spa & Wellness', 'Gym & Fitness', 'Pharmacy', 'Hotel', 'Other',
    ];

    public function processBusinessInfo(Request $request)
    {
        $request->merge(['business_name' => preg_replace('/\s+/', ' ', trim((string) $request->input('business_name')))]);
        $request->validate([
            'business_name' => ['required', 'string', 'min:2', 'max:100'],
            'business_category' => ['required', 'string', 'in:'.implode(',', self::CATEGORIES)],
            // Raster images only (SVG can carry scripts), up to 2 MB.
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'business_name.required' => 'Please enter your business name.',
            'business_name.min' => 'Business name must be at least 2 characters.',
            'business_category.required' => 'Please choose a business category.',
            'business_category.in' => 'Please choose a business category from the list.',
            'logo.image' => 'The logo must be an image file.',
            'logo.mimes' => 'The logo must be a JPG, PNG or WEBP image.',
            'logo.max' => 'The logo may not be larger than 2 MB.',
            'logo.uploaded' => 'The logo could not be uploaded. Please choose an image smaller than 2 MB.',
        ]);

        $user = Auth::user();

        $business = Business::firstOrNew(
            ['user_id' => $user->id],
            ['email' => $user->email, 'phone' => $user->phone]
        );

        $business->name = $request->business_name;
        $business->category = $request->business_category;

        if ($request->hasFile('logo')) {
            $old = $business->logo;
            $business->logo = Media::store($request->file('logo'), 'merchant_logos');
            Media::delete($old);
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
        $states = IndiaPost::states();

        return view('merchant.auth.business-address', compact('business', 'states'));
    }

    public function processBusinessAddress(Request $request)
    {
        $states = IndiaPost::states();
        $directory = $states !== [];

        $request->validate([
            'address_line_1' => ['required', 'string', 'min:3', 'max:150'],
            'address_line_2' => ['nullable', 'string', 'max:150'],
            'state' => $directory ? ['required', 'string', Rule::in($states)] : ['required', 'string', 'max:100'],
            'city' => $directory
                ? ['required', 'string', Rule::in(IndiaPost::districts((string) $request->input('state')))]
                : ['required', 'string', 'max:100'],
            'pin_code' => ['required', 'digits:6', function ($attribute, $value, $fail) use ($request, $directory) {
                if ($directory && ! IndiaPost::matches((string) $value, (string) $request->input('state'), (string) $request->input('city'))) {
                    $fail('This PIN code does not belong to the selected district and state.');
                }
            }],
            'area' => ['nullable', 'string', 'max:120'],
        ], [
            'address_line_1.required' => 'Please enter your shop or building address.',
            'state.required' => 'Please choose your state.',
            'state.in' => 'Please choose a state from the list.',
            'city.required' => 'Please choose your district / city.',
            'city.in' => 'Please choose a district from the list for the selected state.',
            'pin_code.required' => 'Please enter your 6-digit PIN code.',
            'pin_code.digits' => 'PIN code must be exactly 6 digits.',
        ]);

        $business = Business::where('user_id', Auth::id())->first();
        if ($business) {
            $parts = array_filter([
                $request->address_line_1,
                $request->address_line_2,
                $request->area,
                $request->city,
                $request->state.' - '.$request->pin_code,
            ]);

            $business->address = implode(', ', $parts);
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
