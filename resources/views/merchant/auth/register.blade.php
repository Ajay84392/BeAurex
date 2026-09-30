@extends('layouts.auth')

@section('title', 'Create Business Account')
@section('heading', 'Create Account')
@section('subtitle', "Let's get your business account set up")
@section('portal_name', 'Merchant Portal')

@section('content')
    <form action="{{ url('/merchant/register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Owner Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required class="auth-input">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Business Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="business@example.com" required class="auth-input">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Contact Number</label>
            <div class="flex">
                <span class="bg-[#f1f5f9] border border-[#e2e8f0] border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="98765 43210" required class="auth-input rounded-l-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Password</label>
            <div class="relative">
                <input type="password" id="merchantPassword" name="password" placeholder="At least 6 characters" required minlength="6" class="auth-input pr-10">
                <button type="button" onclick="togglePwd('merchantPassword')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Continue to Verify</button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-xs font-medium text-[#475569]">
            Already have an account?
            <a href="{{ url('/merchant/login') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Login</a>
        </p>
    </div>
@endsection
