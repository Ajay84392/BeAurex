<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Set New Password - BeAurex</title>
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
    </style>
</head>
<body class="antialiased text-[#0f172a] flex items-center justify-center min-h-[100dvh]">

    <div class="mobile-container w-full flex flex-col p-8 pt-12">
        
        <!-- Top Nav -->
        <a href="/verify-otp" class="text-[#0f172a] hover:text-[#475569] transition inline-block mb-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>

        <!-- Illustration -->
        <div class="flex justify-center mb-8 relative">
            <div class="absolute w-40 h-40 bg-red-50 rounded-full flex items-center justify-center -z-10 mt-2"></div>
            <!-- Lock with Checkmark SVG -->
            <div class="relative mt-4">
                <svg class="w-24 h-24 text-[#EF4444]" fill="currentColor" viewBox="0 0 24 24"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-1">
                    <svg class="w-10 h-10 text-[#22C55E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                </div>
            </div>
            
        </div>
        
        <div class="text-center mb-10">
            <h1 class="text-2xl font-extrabold text-[#0f172a] mb-3">Set New Password</h1>
            <p class="text-xs text-[#475569] font-medium px-4 leading-relaxed">
                Create a new password for your account.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6 flex-1 flex flex-col">
            @csrf
            
            @if($errors->any())
                <div class="text-[#EF4444] text-sm font-semibold mb-2">{{ $errors->first() }}</div>
            @endif

            <!-- New Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">New Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Enter new password" class="w-full pl-11 pr-12 py-3.5 border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 text-sm font-semibold text-[#0f172a] placeholder-slate-400 transition" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <button type="button" onclick="togglePassword('password')" class="text-slate-400 hover:text-[#475569] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- Password Rules -->
                <div class="mt-4 space-y-2 pl-2">
                    <div class="flex items-center text-xs font-semibold text-[#22C55E]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        At least 6 characters
                    </div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
                    </div>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="w-full pl-11 pr-12 py-3.5 border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 text-sm font-semibold text-[#0f172a] placeholder-slate-400 transition" required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <button type="button" onclick="togglePassword('password_confirmation')" class="text-slate-400 hover:text-[#475569] transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-4 mt-auto">
                <button type="submit" class="w-full bg-[#8a0000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl transition shadow-md shadow-red-200/50">
                    Set New Password
                </button>
            </div>
            
        </form>

    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }
    </script>
</body>
</html>



