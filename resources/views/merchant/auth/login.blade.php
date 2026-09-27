<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Merchant Login - BeAurex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body { font-family: "Inter", sans-serif; }
        input:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4">

            <!-- Pink top section -->
            <div class="flex flex-col items-center pt-6 pb-4 px-8" style="background:#fff5f5">
                <h2 class="text-xl font-black text-[#0f172a] mb-1 text-center">Welcome Back!</h2>
                <p class="text-xs font-medium text-[#475569] text-center">Login to manage your loyalty program</p>
            </div>
            
            <div class="p-8 pt-6">
                <!-- Heading -->
                <div class="text-center mb-7">
                    <h2 class="text-2xl font-black text-[#0f172a] mb-1.5">BeAurex</h2>
                    <p class="text-[#475569] text-sm font-medium leading-relaxed">Merchant Portal</p>
                </div>

                <!-- Alerts -->
                @if(session('status'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-[#22C55E] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-emerald-700 font-medium">{{ session('status') }}</p>
                </div>
                @endif
                @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-3.5 flex items-start space-x-2.5">
                    <svg class="w-4 h-4 text-[#EF4444] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-[#8a0000] font-medium">{{ $errors->first() }}</p>
                </div>
                @endif

                <div class="mb-5">
                    <p class="text-lg font-bold text-center text-[#0f172a]">Sign in to your account</p>
                    <p class="text-xs font-medium text-slate-500 text-center mt-1">Enter your credentials to continue</p>
                </div>

                <!-- Login Form -->
                <form action="{{ url('/merchant/login') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                            class="w-full bg-white border border-[#e2e8f0] rounded-xl px-3.5 py-2.5 text-sm font-medium text-[#0f172a] placeholder-slate-400 transition focus:outline-none focus:border-[#b00000]"
                        >
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-semibold text-[#475569] mb-1">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                name="password"
                                id="merchantPassword"
                                placeholder="Enter your password"
                                required
                                class="w-full bg-white border border-[#e2e8f0] rounded-xl px-3.5 py-2.5 pr-10 text-sm font-medium text-[#0f172a] placeholder-slate-400 transition focus:outline-none focus:border-[#b00000]"
                            >
                            <button type="button" onclick="togglePwd('merchantPassword', 'eyeShow', 'eyeHide')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeShow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg id="eyeHide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-[#e2e8f0] focus:ring-0" style="accent-color:#b00000">
                            <span class="text-xs font-medium text-[#475569]">Remember me</span>
                        </label>
                        <a href="/forgot-password" class="text-xs font-bold text-[#b00000] hover:underline">Forgot Password?</a>
                    </div>

                    <!-- Login Button -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full text-white font-bold py-3.5 rounded-xl text-sm transition"
                            style="background:#b00000"
                            onmouseover="this.style.background='#8a0000'"
                            onmouseout="this.style.background='#b00000'"
                        >
                            Login
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs font-medium text-[#475569]">
                        New to BeAurex?
                        <a href="/merchant/register" class="font-bold hover:underline ml-1" style="color:#b00000">Create Business Account</a>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Footer terms -->
        <div class="text-center">
            <p class="text-xs font-medium text-slate-500">By logging in, you agree to our <a href="#" class="text-[#b00000] hover:underline font-bold">Terms &amp; Conditions</a> and <a href="#" class="text-[#b00000] hover:underline font-bold">Privacy Policy</a>.</p>
        </div>
    </div>
    
    <script>
        function togglePwd(inputId, showId, hideId) {
            const input = document.getElementById(inputId);
            const show  = document.getElementById(showId);
            const hide  = document.getElementById(hideId);
            if (input.type === 'password') {
                input.type = 'text';
                show.classList.add('hidden');
                hide.classList.remove('hidden');
            } else {
                input.type = 'password';
                show.classList.remove('hidden');
                hide.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
