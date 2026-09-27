@extends('layouts.customer')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0">
    
    <!-- Top Bar -->
    <div class="bg-white px-6 pt-10 pb-4 md:pt-6 md:pb-6 md:px-8 flex items-center md:rounded-t-[2rem] sticky top-0 z-20">
        <a href="/customer" class="w-10 h-10 -ml-2 md:ml-0 rounded-full flex items-center justify-center text-[#0f172a] hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="flex items-center space-x-3 ml-2">
            <div class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center text-white shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
            </div>
            <div>
                <h1 class="text-base font-black text-[#0f172a] leading-tight mb-0.5">Ka-feen</h1>
                <p class="text-[11px] font-semibold text-slate-500">Coffee Shop</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-12 md:gap-8 bg-slate-50">

        <!-- Left Column: Success Message & COINS -->
        <div class="lg:col-span-12">
            <div class="px-6 py-8 bg-red-50/50 mb-2 border-b border-white relative">
                <div class="text-center relative z-10">
                    <h2 class="text-[15px] font-black text-slate-900 mb-1">You earned 1 Aurex coin!</h2>
                    <p class="text-xs font-semibold text-slate-500 mb-6">3 of 5 Aurex coins collected</p>
                    
                    <div class="flex items-center justify-center space-x-3">
                        <div class="w-12 h-12 bg-[#b00000] rounded-full flex items-center justify-center text-white shadow-md"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-12 h-12 bg-[#b00000] rounded-full flex items-center justify-center text-white shadow-md"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-12 h-12 bg-[#b00000] rounded-full flex items-center justify-center text-white shadow-md"><svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg></div>
                        <div class="w-12 h-12 bg-white border border-[#b00000] rounded-full"></div>
                        <div class="w-12 h-12 bg-white border border-[#b00000] rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Rewards List -->
        <div class="lg:col-span-12 px-6 pt-6 bg-slate-50">
            <h3 class="text-sm font-black text-slate-900 tracking-tight mb-5">Available Rewards</h3>
            
            <div class="flex flex-col space-y-4">
                
                <!-- Reward 1 (Achieved) -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-start space-x-4">
                    <img src="https://images.unsplash.com/photo-1497935586351-b67a49e012bf?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-xl object-cover" alt="Coffee Table">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-black text-slate-900 text-[13px] leading-tight">30% off on next<br>purchase</h4>
                            <span class="bg-[#22C55E] text-white text-[9px] font-black uppercase px-2 py-1 rounded tracking-wider leading-none mt-0.5">Achieved</span>
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-[#b00000] mb-2">
                            2 COINS <span class="text-slate-500 font-semibold ml-2">Ready to claim! 🎉</span>
                        </div>
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">Expires 7/30/2026</p>
                    </div>
                </div>

                <!-- Reward 2 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-start space-x-4">
                    <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-xl object-cover" alt="Discount">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-black text-slate-900 text-[13px] leading-tight">50% discount</h4>
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-[#b00000] mb-2">
                            5 COINS <span class="text-slate-400 font-medium ml-1.5">&bull; Collect 2 more</span>
                        </div>
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">Expires 7/30/2026</p>
                    </div>
                </div>

                <!-- Reward 3 -->
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-start space-x-4">
                    <img src="https://images.unsplash.com/photo-1498804103079-a6351b050096?auto=format&fit=crop&w=150&q=80" class="w-16 h-16 rounded-xl object-cover" alt="Free Coffee">
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h4 class="font-black text-slate-900 text-[13px] leading-tight">Free Coffee</h4>
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-[#b00000] mb-2">
                            3 COINS <span class="text-slate-400 font-medium ml-1.5">&bull; Collect 1 more</span>
                        </div>
                        <p class="text-[9px] font-bold text-orange-500 uppercase tracking-widest">Expires 7/30/2026</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
