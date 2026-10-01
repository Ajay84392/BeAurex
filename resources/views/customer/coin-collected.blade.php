@extends('layouts.customer')

@section('title', 'Coins Collected')

@section('content')
<div class="min-h-[100dvh] md:min-h-0 bg-slate-50 md:bg-transparent pb-24 md:pb-0"
    x-data="{ open: true, seconds: 4, startTimer() { const t = setInterval(() => { this.seconds--; if (this.seconds <= 0) { clearInterval(t); window.location.href = '{{ $redirectTo }}'; } }, 1000); } }"
    x-init="startTimer()">

    <!-- Popup -->
    <div x-show="open" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.2)] max-w-sm w-full p-7 text-center border border-slate-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100">

            <div class="relative w-24 h-24 mx-auto mb-5">
                @if($awarded)
                    <div class="absolute inset-0 rounded-full bg-amber-300/50 animate-ping"></div>
                @endif
                <div class="relative w-24 h-24 rounded-full flex items-center justify-center shadow-lg {{ $awarded ? 'bg-gradient-to-br from-amber-300 to-amber-500' : 'bg-slate-200' }}">
                    <svg class="w-12 h-12 {{ $awarded ? 'text-white' : 'text-slate-500' }}" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                </div>
            </div>

            @if($blocked ?? false)
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">No Coin Added</p>
                <h2 class="text-xl font-black text-[#0f172a] mb-2">Account restricted</h2>
                <p class="text-sm font-medium text-[#475569] mb-5">
                    Your account can't collect coins right now. Please contact support.
                </p>
            @elseif($awarded)
                <p class="text-xs font-bold uppercase tracking-widest text-[#b00000] mb-1">Coin Collected!</p>
                <h2 class="text-2xl font-black text-[#0f172a] mb-2">+1 Aurex Coin</h2>
                <p class="text-sm font-medium text-[#475569] mb-5">
                    Thanks for visiting <span class="font-bold text-[#0f172a]">{{ $business->name }}</span>.
                </p>
            @else
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Already Collected</p>
                <h2 class="text-xl font-black text-[#0f172a] mb-2">You just scanned here</h2>
                <p class="text-sm font-medium text-[#475569] mb-5">
                    You already collected a coin at <span class="font-bold text-[#0f172a]">{{ $business->name }}</span>. Come back on your next visit!
                </p>
            @endif

            <div class="bg-red-50 border border-red-100 rounded-2xl py-3 mb-6">
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-500">Your coins here</p>
                <p class="text-3xl font-black text-[#b00000]">{{ $coins }}</p>
            </div>

            <a href="{{ $redirectTo }}" class="block w-full py-3 bg-[#b00000] hover:bg-[#8a0000] text-white font-bold rounded-xl transition">
                View Rewards
            </a>
            <p class="mt-3 text-xs font-medium text-slate-400">
                Redirecting in <span x-text="seconds"></span>s…
            </p>
        </div>
    </div>
</div>
@endsection
