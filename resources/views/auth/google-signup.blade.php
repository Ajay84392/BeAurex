@php($hasTermsCheckbox = true)
@php($customerAuthPage = true)

@extends('layouts.auth')

@section('title', 'Create Customer Account')
@section('portal', 'Customer Portal')
@section('heading', 'Create Your Account')
@section('subtitle', 'You signed in with Google. Confirm your details to create your BeAurex account.')

@section('content')
    <form action="{{ url('/customer/register/google') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <span class="auth-label">Google Account</span>
            <div class="auth-input bg-[#f8fafc] text-slate-600 flex items-center gap-2 select-all">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                <span class="truncate">{{ $profile['email'] }}</span>
            </div>
        </div>

        <x-auth.input name="name" label="Full Name" :value="old('name', $profile['name'])" placeholder="Enter your full name" autocomplete="name" required minlength="2" maxlength="100" />

        <x-auth.terms />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Create Account &amp; Continue</button>
        <p class="text-[11px] font-medium text-slate-500 text-center">You'll log in with Google. You can also set a password later with “Forgot password”.</p>
    </form>
@endsection

@section('switch')
    Not you?
    <a href="{{ url('/customer/register') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Sign up with email instead</a>
@endsection
