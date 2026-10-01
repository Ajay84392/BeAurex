<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>Setup Complete - BeAurex</title>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4 relative overflow-hidden">
    <!-- Confetti Background Layer -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute top-1/4 left-1/4 w-3 h-3 bg-red-400 rounded-sm rotate-45 opacity-60"></div>
        <div class="absolute top-1/3 right-1/3 w-4 h-4 bg-emerald-400 rounded-full opacity-60"></div>
        <div class="absolute bottom-1/3 left-1/3 w-3 h-3 bg-red-400 rounded-full opacity-60"></div>
        <div class="absolute top-1/2 right-1/4 w-2 h-2 bg-yellow-400 rounded-sm rotate-12 opacity-60"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Main card -->
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4 p-8">
            <div class="flex flex-col items-center mb-8">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center text-[#22C55E] shadow-sm mb-6 relative">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <div class="absolute bottom-0 right-0 bg-[#22C55E] rounded-full p-1 border-2 border-white text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </div>
                <h2 class="text-3xl font-black text-[#0f172a] mb-2 text-center">Setup Complete!</h2>
                <p class="text-[#475569] text-base font-medium text-center">Your business profile is ready.</p>
            </div>

            <div class="bg-[#f1f5f9] rounded-2xl p-5 border border-slate-100 mb-8">
                <h3 class="text-sm font-bold text-[#0f172a] mb-4 text-center">What's Next?</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 p-2 rounded-xl text-green-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-[#0f172a]">Create Loyalty Card</h4>
                            <p class="text-xs text-[#475569]">Set up your stamp card</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 p-2 rounded-xl text-green-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-[#0f172a]">Add Rewards</h4>
                            <p class="text-xs text-[#475569]">Create exciting rewards for your customers</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 p-2 rounded-xl text-green-600 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-[#0f172a]">Download Business QR</h4>
                            <p class="text-xs text-[#475569]">Let customers scan & collect stamps</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('merchant.dashboard') }}" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl shadow-md transition text-center text-sm flex justify-center items-center space-x-2">
                <span>Go to Dashboard</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- 7-Step Progress indicator -->
        <div class="bg-white border border-[#e2e8f0] rounded-2xl px-4 py-4 shadow-sm">
            <p class="text-[10px] font-bold text-slate-700 mb-3 text-center">Merchant Onboarding</p>
            <div class="flex items-center justify-between">
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]">?</div>
                <div class="flex-1 h-px mx-1 bg-green-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E] ring-2 ring-green-100 ring-offset-1">?</div>
            </div>
            <div class="flex justify-between mt-1.5 px-1">
                <span class="text-[8px] font-semibold text-green-600">Login</span>
                <span class="text-[8px] font-bold text-[#22C55E]">Complete</span>
            </div>
        </div>

    </div>
</body>
</html>
