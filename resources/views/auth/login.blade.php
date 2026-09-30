@php
    $portals = [
        'customer' => ['name' => 'Customer', 'subtitle' => 'Login to claim your rewards', 'action' => url('/customer/login'), 'register' => url('/customer/register'), 'registerLabel' => 'Create Customer Account'],
        'merchant' => ['name' => 'Merchant', 'subtitle' => 'Login to manage your loyalty program', 'action' => url('/merchant/login'), 'register' => url('/merchant/register'), 'registerLabel' => 'Create Business Account'],
        'admin' => ['name' => 'Admin', 'subtitle' => 'Login to manage the platform', 'action' => url('/admin'), 'register' => null, 'registerLabel' => null],
    ];
    $portal = $portals[$role];
    $otpMode = session('otp_mode', false);
@endphp

@extends('layouts.auth')

@section('title', $portal['name'].' Login')
@section('subtitle', $portal['subtitle'])
@section('portal_name', $portal['name'].' Portal')

@section('content')
    <!-- Login method tabs -->
    <div class="grid grid-cols-2 gap-1 bg-[#f1f5f9] p-1 rounded-xl mb-5 text-xs font-bold">
        <button type="button" data-tab="password" class="login-tab py-2 rounded-lg transition">Password</button>
        <button type="button" data-tab="otp" class="login-tab py-2 rounded-lg transition">Email OTP</button>
    </div>

    <!-- Password login -->
    <form id="panel-password" action="{{ $portal['action'] }}" method="POST" class="login-panel space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required class="auth-input">
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" id="loginPassword" placeholder="Enter your password" required class="auth-input pr-10">
                <button type="button" onclick="togglePwd('loginPassword')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="w-3.5 h-3.5 rounded border-[#e2e8f0] focus:ring-0" style="accent-color:#b00000">
                <span class="text-xs font-medium text-[#475569]">Remember me</span>
            </label>
            <a href="{{ route('password.request', ['role' => $role]) }}" class="text-xs font-bold text-[#b00000] hover:underline">Forgot Password?</a>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Login</button>
        </div>
    </form>

    <!-- OTP login -->
    <form id="panel-otp" action="{{ route('otp.login', ['role' => $role]) }}" method="POST" class="login-panel space-y-4 hidden">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required class="auth-input">
            <p class="text-[11px] font-medium text-slate-500 mt-1.5">We'll email you a 4-digit code to sign in. No password needed.</p>
        </div>

        <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" name="remember" value="1" class="w-3.5 h-3.5 rounded border-[#e2e8f0] focus:ring-0" style="accent-color:#b00000">
            <span class="text-xs font-medium text-[#475569]">Remember me</span>
        </label>

        <div class="pt-2">
            <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Send OTP</button>
        </div>
    </form>

    @if($role === 'customer')
        <div class="flex items-center my-5">
            <div class="flex-1 border-t border-[#e2e8f0]"></div>
            <span class="px-3 text-xs font-semibold text-slate-400">or</span>
            <div class="flex-1 border-t border-[#e2e8f0]"></div>
        </div>
        <a href="{{ route('google.redirect', ['role' => 'customer']) }}" class="w-full bg-white border border-[#e2e8f0] hover:bg-slate-50 text-[#0f172a] font-bold py-3.5 rounded-xl text-sm transition flex items-center justify-center space-x-2 shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            <span>Continue with Google</span>
        </a>
    @endif

    @if($portal['register'])
        <div class="mt-6 text-center">
            <p class="text-xs font-medium text-[#475569]">
                New to BeAurex?
                <a href="{{ $portal['register'] }}" class="font-bold hover:underline ml-1 text-[#b00000]">{{ $portal['registerLabel'] }}</a>
            </p>
        </div>
    @endif
@endsection

@section('footer')
    @if($role === 'admin')
        <p class="text-sm font-bold text-slate-700 mb-1">Secure Admin Access</p>
    @endif
@endsection

@push('scripts')
<script>
    (function () {
        const tabs = document.querySelectorAll('.login-tab');
        function show(name) {
            tabs.forEach(t => {
                const active = t.dataset.tab === name;
                t.classList.toggle('bg-white', active);
                t.classList.toggle('text-[#b00000]', active);
                t.classList.toggle('shadow-sm', active);
                t.classList.toggle('text-slate-500', !active);
            });
            document.getElementById('panel-password').classList.toggle('hidden', name !== 'password');
            document.getElementById('panel-otp').classList.toggle('hidden', name !== 'otp');
        }
        tabs.forEach(t => t.addEventListener('click', () => show(t.dataset.tab)));
        show(@json($otpMode ? 'otp' : 'password'));
    })();
</script>
@endpush
