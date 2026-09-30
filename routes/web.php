<?php

use App\Http\Controllers\Admin\ClaimController as AdminClaimController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MerchantController as AdminMerchantController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\PlanController as AdminPlanController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ReferralController as AdminReferralController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\MerchantAuthController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Middleware\CheckAdminSession;
use App\Http\Middleware\CheckCustomerSession;
use App\Http\Middleware\CheckMerchantSession;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// ─── Homepage ────────────────────────────────────────────────────────────────
Route::get('/', function () {
    $faqs = Faq::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
    $settings = Setting::pluck('value', 'key');
    $plans = App\Models\Plan::where('is_active', true)->get();

    return view('welcome', compact('faqs', 'settings', 'plans'));
});

// ─── Google OAuth ─────────────────────────────────────────────────────────────
use App\Http\Controllers\GoogleAuthController;

Route::get('/auth/google/{role}', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/{role}/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// ─── Customer Auth ────────────────────────────────────────────────────────────
use App\Http\Controllers\CustomerAuthController;

Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/customer/login', [CustomerAuthController::class, 'processLogin'])->middleware('throttle:10,1');
Route::get('/customer/register', function () {
    return view('auth.register');
});
Route::post('/customer/register', [OtpAuthController::class, 'register'])->middleware('throttle:10,1');

// OTP login (customer, merchant, admin) + verify routes
Route::post('/{role}/login/otp', [OtpAuthController::class, 'sendLoginOtp'])
    ->whereIn('role', OtpAuthController::ROLES)
    ->middleware('throttle:10,1')
    ->name('otp.login');
Route::post('/otp/resend', [OtpAuthController::class, 'resend'])->middleware('throttle:5,1')->name('otp.resend');
Route::get('/verify-otp', [OtpAuthController::class, 'showVerify'])->name('otp.verify.get');
Route::post('/verify-otp', [OtpAuthController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('otp.verify.post');
Route::get('/admin/verify-otp', [OtpAuthController::class, 'showVerify']);
Route::post('/admin/verify-otp', [OtpAuthController::class, 'verifyOtp'])->middleware('throttle:10,1');
Route::get('/merchant/verify-otp', [OtpAuthController::class, 'showVerify']);
Route::post('/merchant/verify-otp', [OtpAuthController::class, 'verifyOtp'])->middleware('throttle:10,1');

// ─── Protected Customer Routes ────────────────────────────────────────────────
Route::middleware([CheckCustomerSession::class])->group(function () {
    Route::get('/customer', [CustomerDashboardController::class, 'index'])->name('customer.home');
    Route::get('/customer/scan', [CustomerDashboardController::class, 'scan'])->name('customer.scan');
    Route::get('/customer/after-scan', [CustomerDashboardController::class, 'afterScan'])->name('customer.after-scan');
    Route::get('/customer/rewards', [CustomerDashboardController::class, 'rewards'])->name('customer.rewards');
    Route::get('/customer/claim-reward', [CustomerDashboardController::class, 'claimReward'])->name('customer.claim-reward');
    Route::get('/customer/status/{type}', [CustomerDashboardController::class, 'showStatus'])->name('customer.status');
    Route::get('/customer/profile', [CustomerDashboardController::class, 'profile'])->name('customer.profile');
    Route::post('/customer/profile', [CustomerDashboardController::class, 'updateProfile'])->name('customer.profile.update');
    Route::get('/customer/logout', [CustomerDashboardController::class, 'logout'])->name('customer.logout');
});

// ─── Password Reset ───────────────────────────────────────────────────────────
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->middleware('throttle:10,1')->name('password.email');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('password.verify.post');
Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendOtp'])->middleware('throttle:5,1')->name('password.resend');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// ─── Admin Login ─────────────────────────────────────────────────────────────
Route::get('/admin', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin', [AdminAuthController::class, 'processLogin'])->middleware('throttle:10,1');

// ─── Protected Admin Routes ───────────────────────────────────────────────────
Route::middleware([CheckAdminSession::class])->group(function () {
    Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

    Route::patch('/admin/merchants/{merchant}/status', [AdminMerchantController::class, 'updateStatus'])->name('admin.merchants.status');
    Route::patch('/admin/merchants/{merchant}/extend', [AdminMerchantController::class, 'extendValidity'])->name('admin.merchants.extend');
    Route::resource('/admin/merchants', AdminMerchantController::class);
    Route::redirect('/admin/plan', '/admin/plans');
    Route::get('/admin/plans/history', [AdminPlanController::class, 'history'])->name('plans.history');
    Route::resource('/admin/plans', AdminPlanController::class);
    Route::patch('/admin/customers/{customer}/status', [AdminCustomerController::class, 'updateStatus'])->name('admin.customers.status');
    Route::resource('/admin/customers', AdminCustomerController::class);

    Route::resource('/admin/claims', AdminClaimController::class);
    Route::resource('/admin/offers', AdminOfferController::class);
    Route::resource('/admin/referrals', AdminReferralController::class);
    Route::resource('/admin/coupons', AdminCouponController::class);

    Route::get('/admin/settings', [AdminSettingController::class, 'index']);
    Route::post('/admin/settings', [AdminSettingController::class, 'update']);
    Route::get('/admin/settings/platform', [AdminSettingController::class, 'platform']);
    Route::get('/admin/settings/contact', [AdminSettingController::class, 'contact']);
    Route::get('/admin/settings/brand', [AdminSettingController::class, 'brand']);
    Route::get('/admin/settings/privacy-policy', [AdminSettingController::class, 'privacyPolicy']);
    Route::get('/admin/settings/terms', [AdminSettingController::class, 'termsConditions']);
    Route::get('/admin/settings/faq', [AdminSettingController::class, 'faq'])->name('admin.faq.index');
    Route::post('/admin/settings/faq', [AdminSettingController::class, 'faqStore'])->name('admin.faq.store');
    Route::put('/admin/settings/faq/{faq}', [AdminSettingController::class, 'faqUpdate'])->name('admin.faq.update');
    Route::delete('/admin/settings/faq/{faq}', [AdminSettingController::class, 'faqDestroy'])->name('admin.faq.destroy');

    Route::get('/admin/profile', [AdminProfileController::class, 'index']);
    Route::post('/admin/profile', [AdminProfileController::class, 'update']);
});

// ─── Merchant Auth ────────────────────────────────────────────────────────────

Route::get('/merchant/login', [MerchantAuthController::class, 'showLogin'])->name('merchant.login');
Route::post('/merchant/login', [MerchantAuthController::class, 'processLogin'])->middleware('throttle:10,1');
Route::get('/merchant/register', [MerchantAuthController::class, 'showRegister'])->name('merchant.register');
Route::post('/merchant/register', [MerchantAuthController::class, 'processRegister']);
Route::get('/merchant/verify', [MerchantAuthController::class, 'showVerify'])->name('merchant.verify');
Route::post('/merchant/verify', [MerchantAuthController::class, 'processVerify'])->middleware('throttle:10,1');
Route::post('/merchant/resend-otp', [MerchantAuthController::class, 'resendOtp'])->name('merchant.resend-otp');
Route::get('/merchant/account-created', [MerchantAuthController::class, 'showCreated'])->name('merchant.created');
Route::post('/merchant/proceed-batch2', [MerchantAuthController::class, 'proceedToBatch2'])->name('merchant.proceed-batch2');
Route::get('/merchant/business-info', [MerchantAuthController::class, 'showBusinessInfo'])->name('merchant.business-info');
Route::post('/merchant/business-info', [MerchantAuthController::class, 'processBusinessInfo']);
Route::get('/merchant/business-address', [MerchantAuthController::class, 'showBusinessAddress'])->name('merchant.business-address');
Route::post('/merchant/business-address', [MerchantAuthController::class, 'processBusinessAddress']);
Route::get('/merchant/setup-complete', [MerchantAuthController::class, 'showSetupComplete'])->name('merchant.setup-complete');
Route::get('/merchant/logout', [MerchantAuthController::class, 'logout'])->name('merchant.logout');

// ─── Protected Merchant Routes ────────────────────────────────────────────────
Route::middleware([CheckMerchantSession::class])->group(function () {
    Route::get('/merchant', [MerchantDashboardController::class, 'index'])->name('merchant.dashboard');
    Route::get('/merchant/download-qr', function () {
        $url = 'https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode(url('/customer/claim-reward'));
        $image = @file_get_contents($url);
        if (!$image) {
            return back()->with('error', 'Could not fetch QR code.');
        }
        return response($image)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="aurex-reward-qr.png"');
    })->name('merchant.download-qr');
    Route::get('/merchant/profile', [MerchantDashboardController::class, 'profile'])->name('merchant.profile');
    Route::post('/merchant/profile', [MerchantDashboardController::class, 'updateProfile'])->name('merchant.profile.update');
    Route::post('/merchant/profile/auto-approve', [MerchantDashboardController::class, 'updateAutoApproval'])->name('merchant.profile.auto-approve');
    Route::get('/merchant/rewards', [MerchantDashboardController::class, 'rewards'])->name('merchant.rewards');
    Route::post('/merchant/rewards/{id}/status', [MerchantDashboardController::class, 'updateRewardStatus'])->name('merchant.rewards.status');
    Route::get('/merchant/create-offer', [MerchantDashboardController::class, 'createOffer'])->name('merchant.create-offer');
    Route::post('/merchant/create-offer', [MerchantDashboardController::class, 'storeOffer'])->name('merchant.create-offer.store');
    Route::delete('/merchant/offer/{id}', [MerchantDashboardController::class, 'destroyOffer'])->name('merchant.offer.destroy');
});
