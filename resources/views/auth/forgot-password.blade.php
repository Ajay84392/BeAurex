@extends('layouts.auth')

@section('title', 'Forgot Password')
@section('portal', ucfirst($role).' Portal')
@section('heading', 'Forgot Password?')
@section('subtitle', "Enter your email and we'll send you a 4-digit code")

@section('content')
    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">

        <x-auth.input name="email" type="email" label="Email Address" placeholder="Enter your account email" autocomplete="email" required maxlength="255" />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Send Reset Code</button>
    </form>
@endsection

@section('switch')
    Remembered it?
    <a href="{{ $loginUrl }}" class="font-bold hover:underline ml-1 text-[#b00000]">Back to Login</a>
@endsection
