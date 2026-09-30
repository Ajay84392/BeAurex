@extends('layouts.auth')

@section('title', 'Set New Password')
@section('heading', 'Set New Password')
@section('subtitle', 'Create a new password for your account')
@section('portal_name', ucfirst(session('reset_role', 'customer')).' Portal')

@section('content')
    <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">New Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" placeholder="At least 6 characters" required minlength="6" class="auth-input pr-10">
                <button type="button" onclick="togglePwd('password')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Confirm Password</label>
            <div class="relative">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter new password" required minlength="6" class="auth-input pr-10">
                <button type="button" onclick="togglePwd('password_confirmation')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Set New Password</button>
        </div>
    </form>
@endsection
