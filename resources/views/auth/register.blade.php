@php($googleRole = 'customer')
@php($hasTermsCheckbox = true)

@extends('layouts.auth')

@section('title', 'Create Customer Account')
@section('portal', 'Customer Portal')
@section('heading', 'Create Account')
@section('subtitle', 'Join BeAurex and start earning rewards')

@section('content')
    <form action="{{ url('/customer/register') }}" method="POST" class="space-y-4">
        @csrf

        <x-auth.input name="name" label="Full Name" placeholder="Enter your full name" autocomplete="name" required minlength="2" maxlength="100" />
        <x-auth.input name="email" type="email" label="Email Address" placeholder="Enter your email" autocomplete="email" required maxlength="255" />

        <x-auth.password id="registerPassword" placeholder="Create a strong password" strength />
        <x-auth.password name="password_confirmation" id="registerPasswordConfirm" label="Confirm Password" placeholder="Re-enter your password" confirms="registerPassword" />

        <x-auth.terms />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Create Account</button>
        <p class="text-[11px] font-medium text-slate-500 text-center">We'll email you a 4-digit code to verify your account.</p>
    </form>
@endsection

@section('switch')
    Already have an account?
    <a href="{{ url('/customer/login') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Login</a>
@endsection
