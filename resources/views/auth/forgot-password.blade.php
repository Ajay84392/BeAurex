@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('portal', ucfirst($role).' Portal')
@section('heading', 'Forgot Password?')
@section('subtitle', "Enter your email and we'll send you a 4-digit code")

@section('content')
    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        <div>
            <label class="auth-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your account email" required class="auth-input">
        </div>

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Send OTP</button>
    </form>
@endsection

@section('switch')
    Remembered it?
    <a href="{{ $loginUrl }}" class="font-bold hover:underline ml-1 text-[#b00000]">Back to Login</a>
@endsection
