@extends('layouts.merchant')

@section('title', 'Merchant Dashboard')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0">
    
    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative">
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center space-x-4">
                <!-- Store Logo -->
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-[#b00000]" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tight">Ka-feen Café</h2>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="bg-white text-green-600 text-[9px] font-black uppercase px-2 py-0.5 rounded-full shadow-sm tracking-wide">Active Plan</span>
                        <span class="bg-[#710000] text-white border border-red-500 text-[9px] font-black uppercase px-2 py-0.5 rounded-full tracking-wide">Pro Plan</span>
                    </div>
                </div>
            </div>
            
            <!-- Profile Avatar (Visible on Mobile) -->
            <a href="/merchant/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            </a>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-slate-50 md:bg-transparent rounded-t-3xl -mt-8 relative z-20 px-4 md:px-8">
        
        <div class="pt-6 max-w-[1600px] mx-auto">
            
            <div class="flex justify-between items-center mb-5 px-2">
                <h2 class="text-lg font-black text-slate-900 tracking-tight">Overview</h2>
                <div class="flex items-center text-[11px] font-bold text-slate-500 cursor-pointer">
                    This Month
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- 4 Stats Cards Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Scans -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col items-center text-center justify-center py-6 transition">
                    <div class="flex justify-center items-center w-full space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-[#dc2626] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-[#64748b]">Total Scans</span>
                    </div>
                    <div class="text-2xl font-black text-[#0f172a] leading-none mb-1.5">{{ number_format($totalScans) }}</div>
                    <div class="text-[10px] font-bold text-[#16a34a] flex items-center justify-center gap-0.5">
                        +18.5% 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path></svg>
                    </div>
                </div>

                <!-- Total Customers -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col items-center text-center justify-center py-6 transition">
                    <div class="flex justify-center items-center w-full space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-[#9333ea] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-[#64748b]">Total Customers</span>
                    </div>
                    <div class="text-2xl font-black text-[#0f172a] leading-none mb-1.5">{{ number_format($totalCustomers) }}</div>
                    <div class="text-[10px] font-bold text-[#16a34a] flex items-center justify-center gap-0.5">
                        +12.3% 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path></svg>
                    </div>
                </div>

                <!-- Rewards Redeemed -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col items-center text-center justify-center py-6 transition">
                    <div class="flex justify-center items-center w-full space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#16a34a] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-[#64748b]">Rewards Redeemed</span>
                    </div>
                    <div class="text-2xl font-black text-[#0f172a] leading-none mb-1.5">{{ number_format($rewardsRedeemed) }}</div>
                    <div class="text-[10px] font-bold text-[#16a34a] flex items-center justify-center gap-0.5">
                        +15.7% 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path></svg>
                    </div>
                </div>

                <!-- Repeat Rate -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col items-center text-center justify-center py-6 transition">
                    <div class="flex justify-center items-center w-full space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <span class="text-[11px] font-semibold text-[#64748b]">Repeat Rate</span>
                    </div>
                    <div class="text-2xl font-black text-[#0f172a] leading-none mb-1.5">{{ round($repeatRate) }}%</div>
                    <div class="text-[10px] font-bold text-[#16a34a] flex items-center justify-center gap-0.5">
                        +8.2% 
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path></svg>
                    </div>
                </div>
            </div>

            <!-- One Column Layout for QR -->
            <div class="grid grid-cols-1 gap-4 max-w-md mx-auto md:mx-0">
                
                <!-- QR Code Card -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#e2e8f0]">
                    <h3 class="text-base font-black text-slate-900 mb-0.5">Your QR Code</h3>
                    <p class="text-xs font-semibold text-[#64748b] mb-6">Let customers scan to collect aurex coins</p>
                    
                    <div class="flex items-center space-x-5">
                        <!-- QR Box -->
                        <div class="w-[110px] h-[110px] bg-slate-50 rounded-xl flex items-center justify-center relative overflow-hidden border border-slate-200 shrink-0">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(url('/customer/claim-reward')) }}" alt="QR Code" class="w-full h-full object-contain mix-blend-multiply opacity-80" />
                            <!-- Inner Red Icon (Gift) -->
                            <div class="absolute inset-0 m-auto w-8 h-8 bg-[#b00000] rounded-md flex items-center justify-center shadow-md border-2 border-white">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                            </div>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex flex-col space-y-3 flex-1">
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(url('/customer/claim-reward')) }}" download="qrcode.png" target="_blank" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-2.5 rounded-xl transition flex justify-center items-center space-x-2 text-xs shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span>Download QR</span>
                            </a>
                            <button onclick="printQR()" class="w-full bg-white hover:bg-slate-50 border border-[#fca5a5] text-[#b00000] font-bold py-2.5 rounded-xl transition flex justify-center items-center space-x-2 text-xs shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Print QR</span>
                            </button>
                        </div>
                    </div>
                </div>

                <script>
                function printQR() {
                    var printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                        <html>
                            <head>
                                <title>Print QR Code</title>
                                <style>
                                    body { display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif; }
                                    img { width: 300px; height: 300px; }
                                    h2 { margin-top: 20px; font-size: 24px; font-weight: bold; }
                                    p { margin-top: 10px; font-size: 16px; color: #555; }
                                </style>
                            </head>
                            <body>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(url('/customer/claim-reward')) }}" />
                                <h2>Scan to Claim Reward</h2>
                                <p>Collect Aurex Coins!</p>
                                <script>
                                    window.onload = function() { window.print(); window.close(); }
                                <\/script>
                            </body>
                        </html>
                    `);
                    printWindow.document.close();
                }
                </script>
                
                <!-- Pro Plan Banner -->
                <div class="bg-[#fff8f6] rounded-2xl p-4 shadow-sm border border-[#ffedd5] flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="text-2xl drop-shadow-sm">👑</div>
                        <div>
                            <div class="text-[13px] font-black text-slate-900 leading-tight">You're on Pro Plan</div>
                            <div class="text-[9px] font-semibold text-slate-500 mt-0.5">Plan valid until 20 Aug 2026</div>
                        </div>
                    </div>
                    <div class="bg-white border border-[#fca5a5] text-[#b00000] text-[10px] font-bold px-2.5 py-1.5 rounded-lg flex items-center space-x-1 shadow-sm hover:bg-red-50 transition cursor-pointer">
                        <span>₹999 / year</span>
                        <svg class="w-3 h-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
