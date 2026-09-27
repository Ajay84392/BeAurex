<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Verify Email - BeAurex</title>
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; }
        input:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4 relative overflow-hidden">
    <div class="w-full max-w-md relative z-10">
        <!-- Main card -->
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4 p-8">
            <div class="flex flex-col items-center mb-8">
                <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center text-[#b00000] shadow-sm mb-6 relative">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-[#0f172a] mb-2 text-center">Verify Your Email</h2>
                <p class="text-[#475569] text-sm font-medium text-center">We've sent a 4-digit OTP to</p>
                <p class="text-[#b00000] text-sm font-black text-center">{{ session('register_otp_email') ?? session('otp_pending_email') ?? 'your email address' }}</p>
            </div>

            <form id="otpForm" action="" method="POST" class="space-y-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="otp" id="otpValue" value="">
                
                <div class="flex justify-center mb-6 space-x-3">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-black text-[#0f172a] bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] transition">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-black text-[#0f172a] bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] transition">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-black text-[#0f172a] bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] transition">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 text-center text-xl font-black text-[#0f172a] bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] transition">
                </div>

                @error('otp')
                    <div class="text-[#EF4444] text-sm font-bold mb-4 text-center">{{ $message }}</div>
                @enderror

                <div class="flex flex-col items-center justify-center mb-6 bg-[#f1f5f9] rounded-xl p-4 border border-[#e2e8f0] space-y-2">
                    <div class="flex items-center space-x-2 text-[#475569]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="text-xs font-medium">Didn't receive the code?</span>
                    </div>
                    <button type="button" class="text-xs font-black text-[#0f172a] hover:underline">Resend OTP in <span class="text-[#b00000]">00:45</span></button>
                </div>

                <button type="button" onclick="submitOtp()" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl shadow-md transition text-center text-sm flex justify-center items-center space-x-2">
                    <span>Verify & Continue</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
        
        <div class="text-center">
            <a href="/merchant/register" class="text-sm font-semibold text-slate-400 hover:text-[#0f172a] transition">
                &larr; Back to Registration
            </a>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        inputs.forEach((input, index) => {
            input.addEventListener('keyup', (e) => {
                if (e.key >= 0 && e.key <= 9) {
                    if (index < inputs.length - 1) inputs[index + 1].focus();
                } else if (e.key === 'Backspace') {
                    if (index > 0) inputs[index - 1].focus();
                }
            });
        });

        function submitOtp() {
            let otp = '';
            inputs.forEach(input => otp += input.value);
            document.getElementById('otpValue').value = otp;
            document.getElementById('otpForm').submit();
        }
    </script>
</body>
</html>
