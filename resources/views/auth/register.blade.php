@php($googleRole = 'customer')

@extends('layouts.auth')

@section('title', 'Create Customer Account')
@section('portal', 'Customer Portal')
@section('heading', 'Create Account')
@section('subtitle', 'Join BeAurex and start earning rewards')

@section('content')
    <form action="{{ url('/customer/register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="auth-label">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required minlength="2" maxlength="100" autocomplete="name" class="auth-input @error('name') border-red-400 @enderror">
        </div>

        <div>
            <label class="auth-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required maxlength="255" autocomplete="email" class="auth-input @error('email') border-red-400 @enderror">
        </div>

        <x-auth.password id="registerPassword" placeholder="Create a strong password" strength />
        <x-auth.password name="password_confirmation" id="registerPasswordConfirm" label="Confirm Password" placeholder="Re-enter your password" confirms="registerPassword" />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Create Account</button>
        <p class="text-[11px] font-medium text-slate-500 text-center">We'll email you a 4-digit code to verify your account.</p>
    </form>
@endsection

@section('switch')
    Already have an account?
    <a href="{{ url('/customer/login') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Login</a>
@endsection
