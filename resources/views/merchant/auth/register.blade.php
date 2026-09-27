<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Create Account - BeAurex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body { font-family: "Inter", sans-serif; }
        input:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-8 mb-4">
            
            <!-- Heading -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-[#0f172a] mb-1.5">Create Account</h2>
                <p class="text-[#475569] text-sm font-medium leading-relaxed">Let's get your business account set up</p>
            </div>

            <form action="/merchant/register" method="POST" class="space-y-5">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Owner Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-12 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] font-semibold text-[#0f172a] text-sm transition">
                    </div>
                    @error('name')
                        <p class="text-[#EF4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Business Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="email" name="email" placeholder="business@example.com" value="{{ old('email') }}" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-12 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] font-semibold text-[#0f172a] text-sm transition">
                    </div>
                    @error('email')
                        <p class="text-[#EF4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Contact Number</label>
                    <div class="flex">
                        <div class="bg-[#f1f5f9] border border-[#e2e8f0] border-r-0 rounded-l-xl px-4 py-3.5 flex items-center justify-center font-bold text-slate-700 text-sm">
                            +91
                        </div>
                        <input type="tel" name="phone" placeholder="98765 43210" value="{{ old('phone') }}" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-r-xl px-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] font-semibold text-[#0f172a] text-sm transition">
                    </div>
                    @error('phone')
                        <p class="text-[#EF4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" placeholder="Create a password" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-12 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] font-semibold text-[#0f172a] text-sm transition">
                    </div>
                    @error('password')
                        <p class="text-[#EF4444] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl shadow-md transition text-center text-sm">
                        Continue to Verify
                    </button>
                </div>
            </form>
        </div>

        <!-- Links -->
        <div class="text-center">
            <span class="text-sm font-semibold text-[#475569]">Already have an account? </span>
            <a href="/merchant/login" class="text-sm font-black text-[#0f172a] hover:underline">Login instead</a>
        </div>
    </div>
</body>
</html>
