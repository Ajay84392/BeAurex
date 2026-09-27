@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0">
    
    <!-- Red Header Section (Mobile-focused, scalable) -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative">
        <div class="flex justify-between items-start mb-6">
            <div>
                <p class="text-[13px] font-semibold text-red-200 mb-0.5">Good Evening,</p>
                <h1 class="text-2xl font-black mb-1">{{ auth()->user()->name ?? 'Customer' }}</h1>
                <div class="text-[11px] font-semibold text-red-200 flex items-center">
                    Customer ID: LQR-8F4A29 
                    <button class="ml-1.5 hover:text-white transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
            </div>
            
            <!-- Profile Avatar (Visible on Mobile) -->
            <a href="/customer/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            </a>
        </div>

        <!-- Gold Member Card -->
        <div class="bg-[#710000] rounded-2xl p-4 flex items-center border border-red-900/50 shadow-inner">
            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center mr-3">
                <span class="text-3xl filter drop-shadow-md">👑</span>
            </div>
            <div>
                <h3 class="text-base font-bold text-yellow-500 mb-0.5">Gold Member</h3>
                <p class="text-[10px] text-red-200 font-semibold">Member Since &bull; Jul 2026</p>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-white md:bg-white rounded-t-3xl md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] w-full mx-auto md:border md:border-slate-100 pb-10">
        
        <!-- Stats Grid for Customer -->
        <div class="grid grid-cols-2 gap-4 mb-8 -translate-y-6 md:mt-12 md:-translate-y-0 pt-6">
            <!-- Active Cards -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Active<br>Loyalty Cards</span>
                    <div class="text-2xl font-black text-slate-900 leading-none">4</div>
                </div>
            </div>

            <!-- Rewards Redeemed -->
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-blue-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Rewards<br>Redeemed</span>
                    <div class="text-2xl font-black text-slate-900 leading-none">3</div>
                </div>
            </div>
        </div>

        <div class="max-w-[1600px] mx-auto">
            <div class="flex justify-between items-end mb-4 px-1">
                <h2 class="text-[17px] font-black text-slate-900 tracking-tight">Continue Collecting</h2>
                <a href="/customer/rewards" class="text-[13px] font-bold text-[#b00000] hover:underline transition">View All</a>
            </div>

            <!-- Web Grid for Business Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                
                <!-- Business Card -->
                <a href="/customer/after-scan" class="block bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition transform hover:-translate-y-1 hover:shadow-md group">
                    <div class="flex justify-between items-start mb-5">
                        <div class="flex items-center space-x-3">
                            <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 text-[17px] leading-tight mb-0.5">Ka-feen</h3>
                                <p class="text-[11px] font-semibold text-slate-500">Coffee Shop</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-[#b00000] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 leading-tight inline-block">2 more<br>coins</span>
                        </div>
                    </div>

                    <!-- Stamps Progress -->
                    <div class="flex items-center space-x-2.5 mb-2">
                        <div class="w-7 h-7 bg-[#b00000] rounded-full flex items-center justify-center text-white"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-7 h-7 bg-[#b00000] rounded-full flex items-center justify-center text-white"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-7 h-7 bg-[#b00000] rounded-full flex items-center justify-center text-white"><svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-7 h-7 bg-white border border-[#b00000] rounded-full"></div>
                        <div class="w-7 h-7 bg-white border border-[#b00000] rounded-full"></div>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-400 mb-5">3 of 5 Aurex Coins</p>

                    <!-- Next Reward Banner -->
                    <div class="bg-red-50/50 rounded-xl p-3 flex justify-between items-center transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-[#b00000] text-white rounded-full flex items-center justify-center shadow-sm shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-[13px] font-black text-[#b00000] leading-tight mb-0.5">30% off on next purchase</p>
                                <p class="text-[10px] font-semibold text-slate-500">Collect 2 more coins to unlock</p>
                            </div>
                        </div>
                        <div class="text-[#b00000]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                        </div>
                    </div>
                </a>

            </div>
        </div>

    </div>

</div>
@endsection
