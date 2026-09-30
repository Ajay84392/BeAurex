@extends('layouts.auth')

@section('title', 'Create Customer Account')
@section('heading', 'Create Account')
@section('subtitle', 'Join BeAurex and start earning rewards')
@section('portal_name', 'Customer Portal')

@section('content')
    <a href="{{ route('google.redirect', ['role' => 'customer']) }}" class="w-full bg-white border border-[#e2e8f0] hover:bg-slate-50 text-[#0f172a] font-bold py-3.5 rounded-xl text-sm transition flex items-center justify-center space-x-2 shadow-sm">
        <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        <span>Continue with Google</span>
    </a>

    <div class="flex items-center my-5">
        <div class="flex-1 border-t border-[#e2e8f0]"></div>
        <span class="px-3 text-xs font-semibold text-slate-400">or</span>
        <div class="flex-1 border-t border-[#e2e8f0]"></div>
    </div>

    <form action="{{ url('/customer/register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Full Name</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required class="auth-input">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required class="auth-input">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Password</label>
            <div class="relative">
                <input type="password" id="registerPassword" name="password" placeholder="At least 6 characters" required minlength="6" class="auth-input pr-10">
                <button type="button" onclick="togglePwd('registerPassword')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Create Account</button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="text-xs font-medium text-[#475569]">
            Already have an account?
            <a href="{{ url('/customer/login') }}" class="font-bold hover:underline ml-1 text-[#b00000]">Login</a>
        </p>
    </div>
@endsection
