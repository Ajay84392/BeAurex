@extends('layouts.auth')

@section('title', 'Set New Password')
@section('portal', ucfirst(session('reset_role', 'customer')).' Portal')
@section('heading', 'Set New Password')
@section('subtitle', 'Create a new password for your account')

@section('content')
    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf

        <x-auth.password id="resetPassword" label="New Password" placeholder="Create a strong password" strength />
        <x-auth.password name="password_confirmation" id="resetPasswordConfirm" label="Confirm Password" placeholder="Re-enter new password" confirms="resetPassword" />

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Set New Password</button>
    </form>
@endsection

@section('switch')
    Changed your mind?
    <a href="{{ route('password.request', ['role' => session('reset_role', 'customer')]) }}" class="font-bold hover:underline ml-1 text-[#b00000]">Start over</a>
@endsection
