@extends('layouts.auth')

@section('title', 'Verify OTP')
@section('heading', 'Verify Your Email')
@section('subtitle', 'Enter the 4-digit code we emailed you')
@section('portal_name', ucfirst($role ?? 'customer').' Portal')

@section('content')
    <div class="text-center mb-5">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center text-[#b00000] mx-auto mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <p class="text-xs font-medium text-[#475569]">Code sent to</p>
        <p class="text-sm font-bold text-[#0f172a] break-all">{{ $email }}</p>
    </div>

    <form id="otpForm" action="{{ $action }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="otp" id="otpValue">

        <div class="flex justify-center gap-3">
            @for($i = 0; $i < 4; $i++)
                <input type="text" inputmode="numeric" maxlength="1" autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}" class="otp-input w-12 h-14 text-center text-xl font-black text-[#0f172a] bg-white border border-[#e2e8f0] rounded-xl transition">
            @endfor
        </div>

        <button type="submit" class="btn-primary w-full text-white font-bold py-3.5 rounded-xl text-sm transition">Verify &amp; Continue</button>
    </form>

    <form action="{{ $resendAction }}" method="POST" class="mt-4 text-center">
        @csrf
        <span class="text-xs font-medium text-[#475569]">Didn't receive the code?</span>
        <button type="submit" id="resendBtn" class="text-xs font-bold text-[#b00000] hover:underline disabled:text-slate-400 disabled:no-underline" disabled>
            Resend OTP <span id="resendTimer">in 00:45</span>
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ $backUrl }}" class="text-xs font-bold text-slate-500 hover:text-[#0f172a]">&larr; Use a different email</a>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const inputs = [...document.querySelectorAll('.otp-input')];
        inputs[0].focus();

        inputs.forEach((input, i) => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(-1);
                if (input.value && i < inputs.length - 1) inputs[i + 1].focus();
            });
            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !input.value && i > 0) inputs[i - 1].focus();
            });
            input.addEventListener('paste', e => {
                const digits = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, inputs.length);
                if (!digits) return;
                e.preventDefault();
                digits.split('').forEach((d, j) => inputs[j].value = d);
                inputs[Math.min(digits.length, inputs.length) - 1].focus();
            });
        });

        document.getElementById('otpForm').addEventListener('submit', () => {
            document.getElementById('otpValue').value = inputs.map(x => x.value).join('');
        });

        const btn = document.getElementById('resendBtn');
        const timer = document.getElementById('resendTimer');
        let left = 45;
        const tick = setInterval(() => {
            left--;
            if (left <= 0) {
                clearInterval(tick);
                btn.disabled = false;
                timer.textContent = '';
                return;
            }
            timer.textContent = 'in 00:' + String(left).padStart(2, '0');
        }, 1000);
    })();
</script>
@endpush
