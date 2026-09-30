@extends('layouts.auth')

@section('title', 'Create Business Account')
@section('portal', 'Merchant Portal')
@section('heading', 'Create Account')
@section('subtitle', "Let's get your business account set up")

@section('content')
    <form action="{{ url('/merchant/register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="auth-label">Owner Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required minlength="2" maxlength="100" autocomplete="name" class="auth-input @error('name') border-red-400 @enderror">
        </div>

        <div>
            <label class="auth-label">Business Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="business@example.com" required maxlength="255" autocomplete="email" class="auth-input @error('email') border-red-400 @enderror">
        </div>

        <div>
            <label class="auth-label">Mobile Number</label>
            <div class="flex">
                <span class="bg-[#f1f5f9] border border-[#e2e8f0] border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="9876543210" required data-mobile inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" title="10-digit mobile number starting with 6-9" autocomplete="tel-national" class="auth-input rounded-l-none @error('phone') border-red-400 @enderror">
            </div>
            <p class="text-[11px] font-medium text-slate-500 mt-1.5">10 digits, e.g. 9876543210</p>
        </div>

        <x-auth.password id="merchantPassword" placeholder="Create a strong password" strength />
        <x-auth.password name="password_confirmation" id="merchantPasswordConfirm" label="Confirm Password" placeholder="Re-enter your password" confirms="merchantPassword" />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Continue to Verify</button>
        <p class="text-[11px] font-medium text-slate-500 text-center">We'll email you a 4-digit code to verify your account.</p>
    </form>
@endsection

@section('switch')
    Already have an account?
    <a href="{{ url('/merchant/login') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Login</a>
@endsection
