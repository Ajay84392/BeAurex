<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Verify OTP - BeAurex</title>
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .mobile-container {
            max-width: 414px;
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        @media (max-width: 414px) {
            .mobile-container { box-shadow: none; }
        }
        /* Hide arrows on number input */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>
<body class="antialiased text-[#0f172a] flex items-center justify-center min-h-[100dvh]">

    <div class="mobile-container w-full flex flex-col p-8 pt-12">
        
        <!-- Top Nav -->
        <a href="/forgot-password" class="text-[#0f172a] hover:text-[#475569] transition inline-block mb-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>

        <!-- Illustration -->
        <div class="flex justify-center mb-8 relative">
            <div class="absolute w-40 h-40 bg-red-50 rounded-full flex items-center justify-center -z-10 mt-2"></div>
            <!-- Logo -->
            <div class="relative">
                <img src="/images/logo.jpg" alt="BeAurex Logo" class="w-24 h-24 rounded-3xl object-cover shadow-2xl">
            </div>
            
        </div>
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-[#0f172a] mb-3">Verify OTP</h1>
            <p class="text-xs text-[#475569] font-medium px-4 leading-relaxed">
                Enter the 4-digit code sent to<br>
                <span class="text-[#0f172a] font-bold">your email address</span>
            </p>
        </div>

        <form id="otpForm" action="{{ route('password.verify.post') }}" method="POST" class="space-y-8 flex-1 flex flex-col">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="otp" id="otpValue" value="">
            
            <div class="flex justify-center gap-4 px-1">
                <input type="text" maxlength="1" class="otp-input w-14 h-16 border border-[#e2e8f0] rounded-xl text-center text-2xl font-black text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition shadow-sm">
                <input type="text" maxlength="1" class="otp-input w-14 h-16 border border-[#e2e8f0] rounded-xl text-center text-2xl font-black text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition shadow-sm">
                <input type="text" maxlength="1" class="otp-input w-14 h-16 border border-[#e2e8f0] rounded-xl text-center text-2xl font-black text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition shadow-sm">
                <input type="text" maxlength="1" class="otp-input w-14 h-16 border border-[#e2e8f0] rounded-xl text-center text-2xl font-black text-[#0f172a] focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 transition shadow-sm">
            </div>

            @if($errors->any())
                <div class="text-[#EF4444] text-sm text-center font-bold">{{ $errors->first() }}</div>
            @endif

            <div class="text-center">
                <p class="text-xs font-semibold text-[#475569]">Resend OTP in <span class="text-[#b00000] font-bold">00:45</span></p>
            </div>

            <div class="pt-2">
                <button type="button" onclick="submitOtp()" class="w-full bg-[#8a0000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl transition shadow-md shadow-red-200/50">
                    Verify OTP
                </button>
            </div>

            <div class="text-center pt-2">
                <a href="/" class="text-xs font-bold text-[#b00000] hover:text-[#8a0000] transition">Change Email</a>
            </div>
            
        </form>

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

    </div>

</body>
</html>



