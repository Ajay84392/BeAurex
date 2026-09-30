<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>@yield('title') - BeAurex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body { font-family: "Inter", sans-serif; }
        input:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
        .btn-primary { background: #b00000; }
        .btn-primary:hover { background: #8a0000; }
        .auth-input { width: 100%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.625rem 0.875rem; font-size: 0.875rem; font-weight: 500; color: #0f172a; transition: border-color .15s; }
        .auth-input::placeholder { color: #94a3b8; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
    @stack('head')
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4">

            <div class="flex flex-col items-center pt-6 pb-4 px-8 bg-[#f8fafc] border-b border-[#f1f5f9]">
                <h2 class="text-xl font-black text-[#0f172a] mb-1 text-center">@yield('heading', 'Welcome Back!')</h2>
                <p class="text-xs font-medium text-[#475569] text-center">@yield('subtitle')</p>
            </div>

            <div class="p-8 pt-6">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-black text-[#0f172a] mb-1.5">BeAurex</h2>
                    <p class="text-[#475569] text-sm font-medium leading-relaxed">@yield('portal_name')</p>
                </div>

                @if(session('status'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-[#22C55E] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('status') }}</p>
                </div>
                @endif
                @if(session('mail_error'))
                <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-3.5">
                    <p class="text-sm text-amber-800 font-medium">{{ session('mail_error') }}</p>
                </div>
                @endif
                @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-[#EF4444] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-[#8a0000] font-medium">{{ $errors->first() }}</p>
                </div>
                @endif

                @yield('content')
            </div>
        </div>

        <div class="text-center">
            @yield('footer')
            <p class="text-xs font-medium text-slate-500">By continuing, you agree to our <a href="#" class="text-[#b00000] hover:underline font-bold">Terms &amp; Conditions</a> and <a href="#" class="text-[#b00000] hover:underline font-bold">Privacy Policy</a>.</p>
        </div>
    </div>

    <script>
        function togglePwd(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
    @stack('scripts')
</body>
</html>
