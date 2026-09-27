<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>Business Information - BeAurex</title>
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: "Inter", sans-serif; }
        input:focus, select:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,0.08); }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4 relative overflow-hidden">
    <div class="w-full max-w-md relative z-10">
        <!-- Main card -->
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4 p-8">
            <div class="flex flex-col items-center mb-6">
                <h2 class="text-2xl font-black text-[#0f172a] mb-1.5 text-center">Business Information</h2>
                <p class="text-[#475569] text-sm font-medium leading-relaxed text-center">Tell us about your business</p>
            </div>

            <form action="{{ route('merchant.business-info') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Business Logo (Optional)</label>
                    <div class="border-2 border-dashed border-[#e2e8f0] rounded-xl p-4 text-center cursor-pointer hover:bg-[#f1f5f9] transition relative overflow-hidden" onclick="document.getElementById('logo').click()">
                        <input type="file" id="logo" name="logo" class="hidden" accept="image/*">
                        <svg class="w-6 h-6 text-red-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-xs font-bold text-slate-700">Upload Logo</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Recommended size: 512x512 px</p>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Business Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <input type="text" name="business_name" placeholder="Enter your business name" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl pl-10 pr-4 py-2.5 text-sm font-medium text-[#0f172a] transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Business Category</label>
                    <div class="relative">
                        <select name="business_category" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-2.5 text-sm font-medium text-[#0f172a] transition appearance-none">
                            <option value="" disabled selected>Select business category</option>
                            <option value="Cafe">Cafe</option>
                            <option value="Restaurant">Restaurant</option>
                            <option value="Retail">Retail</option>
                            <option value="Salon">Salon</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full text-white font-bold py-3 rounded-xl text-sm transition shadow-md" style="background:#b00000" onmouseover="this.style.background='#8a0000'" onmouseout="this.style.background='#b00000'">
                        Continue
                    </button>
                </div>
            </form>
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
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#b00000] ring-2 ring-red-100 ring-offset-1">5</div>
                <div class="flex-1 h-px mx-1 bg-slate-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold bg-slate-100 text-slate-400">6</div>
                <div class="flex-1 h-px mx-1 bg-slate-200"></div>
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold bg-slate-100 text-slate-400">7</div>
            </div>
            <div class="flex justify-between mt-1.5 px-1">
                <span class="text-[8px] font-semibold text-green-600">Login</span>
                <span class="text-[8px] font-bold text-[#b00000]">Business Info</span>
                <span class="text-[8px] font-medium text-slate-400">Complete</span>
            </div>
        </div>

    </div>
</body>
</html>
