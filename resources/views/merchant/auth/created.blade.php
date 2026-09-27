<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Account Created - BeAurex</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4 relative overflow-hidden">
    <!-- Confetti Background Layer -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-1/4 left-1/4 w-3 h-3 bg-red-400 rounded-sm rotate-45 opacity-60"></div>
        <div class="absolute top-1/3 right-1/3 w-4 h-4 bg-emerald-400 rounded-full opacity-60"></div>
        <div class="absolute bottom-1/3 left-1/3 w-3 h-3 bg-blue-400 rounded-full opacity-60"></div>
        <div class="absolute top-1/2 right-1/4 w-2 h-2 bg-yellow-400 rounded-sm rotate-12 opacity-60"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-8 text-center mb-4">
            
            <div class="flex justify-center mb-8">
                <div class="w-24 h-24 bg-[#22C55E] rounded-full flex items-center justify-center text-white shadow-xl border-8 border-emerald-50">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>

            <h2 class="text-3xl font-black text-[#0f172a] mb-3">Account Created!</h2>
            <p class="text-[#475569] font-medium text-base mb-8">Your merchant account has been created and securely verified.</p>

            <div class="bg-[#f1f5f9] rounded-2xl p-6 border border-[#e2e8f0] mb-8 text-center flex flex-col items-center space-y-4">
                <div class="text-slate-700 bg-white p-3 rounded-xl shadow-sm border border-[#e2e8f0]">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"></path></svg>
                </div>
                <p class="text-sm font-bold text-[#0f172a] leading-relaxed">You're all set to grow your business with BeAurex!</p>
            </div>

            <a href="{{ route('merchant.business-info') }}" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl shadow-md transition text-center text-sm flex justify-center items-center space-x-2">
                <span>Continue to Business Info</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            
        </div>
    </div>
</body>
</html>
