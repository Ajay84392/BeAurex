<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - BeAurex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body { font-family: "Inter", sans-serif; }
        .btn-primary { background: #b00000; }
        .btn-primary:hover { background: #8a0000; }
        .auth-input { width: 100%; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 0.625rem 0.875rem; font-size: 0.875rem; font-weight: 500; color: #0f172a; transition: border-color .15s, box-shadow .15s; }
        .auth-input::placeholder { color: #94a3b8; }
        .auth-input:focus, .otp-input:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
        .auth-label { display: block; font-size: 0.75rem; font-weight: 600; color: #475569; margin-bottom: 0.25rem; }
    </style>
    @stack('head')
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center px-4 py-8">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden">

            {{-- Brand row: identical on every auth page --}}
            <div class="flex items-center justify-between px-6 py-4 bg-[#f8fafc] border-b border-[#e2e8f0]">
                <a href="{{ url('/') }}" class="flex items-center space-x-2.5">
                    <img src="/images/logo.jpg" alt="BeAurex" class="w-9 h-9 rounded-xl object-cover shadow-sm">
                    <span class="text-lg font-black tracking-tight text-[#0f172a]">BeAurex</span>
                </a>
                @hasSection('portal')
                    <span class="text-[11px] font-bold uppercase tracking-wide text-[#b00000] bg-red-50 border border-red-100 rounded-full px-2.5 py-1">@yield('portal')</span>
                @endif
            </div>

            <div class="px-6 sm:px-8 pt-7 pb-8">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-black text-[#0f172a] mb-1.5">@yield('heading')</h1>
                    <p class="text-sm font-medium text-[#475569]">@yield('subtitle')</p>
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
                    @if(count($errors->all()) === 1)
                        <p class="text-sm text-[#8a0000] font-medium">{{ $errors->first() }}</p>
                    @else
                        <ul class="text-sm text-[#8a0000] font-medium list-disc pl-4 space-y-0.5">
                            @foreach(array_unique($errors->all()) as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                @endif

                @yield('content')

                @if($googleRole ?? false)
                    <div class="flex items-center my-5">
                        <div class="flex-1 border-t border-[#e2e8f0]"></div>
                        <span class="px-3 text-xs font-semibold text-slate-400">or</span>
                        <div class="flex-1 border-t border-[#e2e8f0]"></div>
                    </div>
                    <a href="{{ route('google.redirect', ['role' => $googleRole]) }}" class="w-full bg-white border border-[#e2e8f0] hover:bg-slate-50 text-[#0f172a] font-bold py-3 rounded-xl text-sm transition flex items-center justify-center space-x-2 shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <span>Continue with Google</span>
                    </a>
                @endif
            </div>

            {{-- Bottom link row: same position and style on every page --}}
            @hasSection('switch')
                <div class="px-6 py-4 bg-[#f8fafc] border-t border-[#e2e8f0] text-center text-xs font-medium text-[#475569]">
                    @yield('switch')
                </div>
            @endif
        </div>

        <p class="mt-4 text-center text-xs font-medium text-slate-500">By continuing, you agree to our <a href="#" class="text-[#b00000] hover:underline font-bold">Terms &amp; Conditions</a> and <a href="#" class="text-[#b00000] hover:underline font-bold">Privacy Policy</a>.</p>
    </div>

    <script>
        function togglePwd(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        // Live password rules: 8+ characters with a letter, a number and a symbol.
        const pwRules = {
            length: v => v.length >= 8,
            letter: v => /[A-Za-z]/.test(v),
            number: v => /\d/.test(v),
            symbol: v => /[^A-Za-z0-9\s]/.test(v),
        };
        document.querySelectorAll('input[data-strength]').forEach(input => {
            const list = document.querySelector('.pw-rules[data-for="' + input.id + '"]');
            const check = () => {
                let ok = true;
                for (const [rule, test] of Object.entries(pwRules)) {
                    const pass = test(input.value);
                    ok = ok && pass;
                    const li = list.querySelector('[data-rule="' + rule + '"]');
                    li.classList.toggle('text-emerald-600', pass);
                    li.classList.toggle('text-slate-400', !pass);
                    li.innerHTML = (pass ? '&#10003; ' : '&#9675; ') + li.textContent.slice(2);
                }
                input.setCustomValidity(ok ? '' : 'Use 8+ characters with a letter, a number and a symbol.');
            };
            input.addEventListener('input', check);
            check();
        });

        // Confirm-password fields must match their source field.
        document.querySelectorAll('input[data-confirms]').forEach(input => {
            const source = document.getElementById(input.dataset.confirms);
            const note = document.querySelector('.pw-match[data-for="' + input.id + '"]');
            const check = () => {
                const mismatch = input.value !== '' && input.value !== source.value;
                note.classList.toggle('hidden', !mismatch);
                input.setCustomValidity(mismatch ? 'Passwords do not match.' : '');
            };
            input.addEventListener('input', check);
            source.addEventListener('input', check);
        });

        // Mobile numbers: digits only, exactly 10, starting 6-9.
        document.querySelectorAll('input[data-mobile]').forEach(input => {
            input.addEventListener('input', () => {
                input.value = input.value.replace(/\D/g, '').slice(0, 10);
                input.setCustomValidity(/^[6-9]\d{9}$/.test(input.value) || input.value === '' ? '' : 'Enter a valid 10-digit mobile number.');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
