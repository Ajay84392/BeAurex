@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('heading', 'Forgot Password?')
@section('subtitle', "We'll email you a 4-digit code to reset it")
@section('portal_name', ucfirst($role).' Portal')

@section('content')
    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your account email" required class="auth-input">
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Send OTP</button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ $loginUrl }}" class="text-xs font-bold text-[#b00000] hover:underline">&larr; Back to Login</a>
    </div>
@endsection
