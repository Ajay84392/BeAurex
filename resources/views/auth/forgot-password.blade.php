<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Forgot Password - BeAurex</title>
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
        <a href="/customer/login" class="text-[#0f172a] hover:text-[#475569] transition inline-block mb-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>

        <!-- Illustration -->
        <div class="flex justify-center mb-8 relative">
            <div class="absolute w-40 h-40 bg-red-50 rounded-full flex items-center justify-center -z-10 mt-4"></div>
            <!-- Envelope SVG -->
            <svg class="w-28 h-28 text-[#b00000]" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/>
            </svg>
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-10 text-red-400">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            </div>
            <div class="absolute bottom-4 left-6 text-red-300">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L9 9H2l5.5 4.5L5.5 21 12 16.5 18.5 21l-2-7.5L22 9h-7z"/></svg>
            </div>
        </div>
        
        <div class="text-center mb-10">
            <h1 class="text-2xl font-extrabold text-[#0f172a] mb-3">Enter your email</h1>
            <p class="text-xs text-[#475569] font-medium px-4 leading-relaxed">
                We will send you a 6-digit OTP to reset your password.
            </p>
        </div>

        <form action="{{ route('password.email') }}" method="POST" class="space-y-6 flex-1 flex flex-col">
            @csrf
            
            @if(session('status'))
                <div class="text-[#22C55E] text-sm font-semibold mb-2">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="text-[#EF4444] text-sm font-semibold mb-2">{{ $errors->first() }}</div>
            @endif
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path></svg>
                </div>
                <input type="email" name="email" placeholder="Enter your email address" class="w-full pl-11 pr-4 py-3.5 border border-[#e2e8f0] rounded-xl focus:outline-none focus:ring-2 focus:ring-red-100 focus:border-red-500 text-sm font-semibold text-[#0f172a] placeholder-slate-400 transition" required>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-[#8a0000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl transition shadow-md shadow-red-200/50">
                    Send OTP
                </button>
            </div>

            <div class="text-center pt-2">
                <a href="/customer/login" class="text-xs font-bold text-[#b00000] hover:text-[#8a0000] transition">Back to Login</a>
            </div>
            
        </form>

    </div>

</body>
</html>



