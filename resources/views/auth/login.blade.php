@php
    $portals = [
        'customer' => ['name' => 'Customer', 'subtitle' => 'Login to claim your rewards', 'action' => url('/customer/login'), 'register' => url('/customer/register'), 'registerLabel' => 'Create Customer Account'],
        'merchant' => ['name' => 'Merchant', 'subtitle' => 'Login to manage your loyalty program', 'action' => url('/merchant/login'), 'register' => url('/merchant/register'), 'registerLabel' => 'Create Business Account'],
        'admin' => ['name' => 'Admin', 'subtitle' => 'Login to manage the platform', 'action' => url('/admin'), 'register' => null, 'registerLabel' => null],
    ];
    $portal = $portals[$role];
    $otpMode = session('otp_mode', false);
    $googleRole = $role === 'customer' ? 'customer' : null;
@endphp

@extends('layouts.auth')

@section('title', $portal['name'].' Login')
@section('portal', $portal['name'].' Portal')
@section('heading', 'Welcome Back!')
@section('subtitle', $portal['subtitle'])

@section('content')
    <!-- Login method tabs -->
    <div class="grid grid-cols-2 gap-1 bg-[#f1f5f9] p-1 rounded-xl mb-5 text-xs font-bold">
        <button type="button" data-tab="password" class="login-tab py-2 rounded-lg transition">Password</button>
        <button type="button" data-tab="otp" class="login-tab py-2 rounded-lg transition">Email OTP</button>
    </div>

    <!-- Password login -->
    <form id="panel-password" action="{{ $portal['action'] }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="auth-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required class="auth-input">
        </div>

        <x-auth.password id="loginPassword" />

        <div class="flex items-center justify-between">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="remember" value="1" class="w-3.5 h-3.5 rounded border-[#e2e8f0] focus:ring-0" style="accent-color:#b00000">
                <span class="text-xs font-medium text-[#475569]">Remember me</span>
            </label>
            <a href="{{ route('password.request', ['role' => $role]) }}" class="text-xs font-bold text-[#b00000] hover:underline">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Login</button>
    </form>

    <!-- OTP login -->
    <form id="panel-otp" action="{{ route('otp.login', ['role' => $role]) }}" method="POST" class="space-y-4 hidden">
        @csrf
        <div>
            <label class="auth-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required class="auth-input">
            <p class="text-[11px] font-medium text-slate-500 mt-1.5">We'll email you a 4-digit code to sign in. No password needed.</p>
        </div>

        <label class="flex items-center space-x-2 cursor-pointer">
            <input type="checkbox" name="remember" value="1" class="w-3.5 h-3.5 rounded border-[#e2e8f0] focus:ring-0" style="accent-color:#b00000">
            <span class="text-xs font-medium text-[#475569]">Remember me</span>
        </label>

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Send OTP</button>
    </form>
@endsection

@section('switch')
    @if($portal['register'])
        New to BeAurex?
        <a href="{{ $portal['register'] }}" class="font-bold hover:underline ml-1 text-[#b00000]">{{ $portal['registerLabel'] }}</a>
    @else
        <span class="font-bold text-slate-700">Secure Admin Access</span> &middot; authorised staff only
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
