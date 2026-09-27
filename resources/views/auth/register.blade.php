<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Customer Register - LoyalQR</title>
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
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-8">
            
            <div class="text-center mb-7">
                <h2 class="text-2xl font-black text-[#0f172a] mb-1.5">Create Account</h2>
                <p class="text-[#475569] text-sm font-medium leading-relaxed">Join LoyalQR and start<br>earning amazing rewards today.</p>
            </div>

            <!-- Google Button -->
            <a href="/auth/google/customer" class="w-full flex items-center justify-center space-x-3 border border-[#e2e8f0] rounded-xl py-3 px-4 bg-white hover:bg-[#f1f5f9] transition text-sm font-semibold text-slate-700 shadow-sm mb-5">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Continue with Google</span>
            </a>

            <!-- Or divider -->
            <div class="flex items-center mb-5">
                <div class="flex-1 border-t border-[#e2e8f0]"></div>
                <span class="px-3 text-xs font-semibold text-slate-400">or</span>
                <div class="flex-1 border-t border-[#e2e8f0]"></div>
            </div>

            <!-- Registration Form -->
            <form action="/customer/register" method="POST" class="space-y-3">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div>
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Full Name</label>
                    <input type="text" name="name" placeholder="Enter your full name" required class="w-full bg-white border border-[#e2e8f0] rounded-xl px-3.5 py-2.5 text-sm font-medium text-[#0f172a] placeholder-slate-400 transition focus:outline-none focus:border-[#b00000]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required class="w-full bg-white border border-[#e2e8f0] rounded-xl px-3.5 py-2.5 text-sm font-medium text-[#0f172a] placeholder-slate-400 transition focus:outline-none focus:border-[#b00000]">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-[#475569] mb-1">Password</label>
                    <input type="password" name="password" placeholder="Create a password" required class="w-full bg-white border border-[#e2e8f0] rounded-xl px-3.5 py-2.5 text-sm font-medium text-[#0f172a] placeholder-slate-400 transition focus:outline-none focus:border-[#b00000]">
                </div>

                <!-- Next Button -->
                <div class="pt-3">
                    <button type="submit" class="w-full text-white font-bold py-3.5 rounded-xl text-sm transition" style="background:#b00000" onmouseover="this.style.background='#8a0000'" onmouseout="this.style.background='#b00000'">
                        Create Account
                    </button>
                </div>
            </form>

        </div>
        
        <div class="mt-8 text-center">
            <span class="text-sm font-medium text-[#475569]">Already have an account? </span>
            <a href="/customer/login" class="text-sm font-black text-[#b00000] hover:underline">Login</a>
        </div>
    </div>
</body>
</html>
