@extends('layouts.customer')

@section('title', 'Home')

@section('content')
@php
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
    $star = '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>';
@endphp
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0">

    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl">
        <div class="flex justify-between items-start mb-6">
            <div class="min-w-0">
                <p class="text-[13px] font-semibold text-red-200 mb-0.5">{{ $greeting }},</p>
                <h1 class="text-2xl font-black mb-1 truncate">{{ auth()->user()->name }}</h1>
                <div class="text-[11px] font-semibold text-red-200 flex items-center" x-data="{ copied: false }">
                    Customer ID: <span class="ml-1">{{ $customerCode }}</span>
                    <button type="button" class="ml-1.5 hover:text-white transition" title="Copy ID" aria-label="Copy customer ID"
                        @click="navigator.clipboard?.writeText('{{ $customerCode }}'); copied = true; setTimeout(() => copied = false, 1500)">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                    <span x-show="copied" x-cloak class="ml-1.5 text-white">Copied</span>
                </div>
            </div>

            <!-- Profile Avatar (Visible on Mobile) -->
            <a href="/customer/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0" aria-label="Profile">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            </a>
        </div>

        <!-- Membership Card -->
        <div class="bg-[#700000] rounded-2xl p-4 flex items-center border border-red-900/50 shadow-inner">
            <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center mr-3">
                <span class="text-3xl filter drop-shadow-md">{{ $tier['icon'] }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="text-base font-bold text-amber-400 mb-0.5">{{ $tier['name'] }}</h3>
                <p class="text-[10px] text-red-200 font-semibold">
                    Member since &bull; {{ $memberSince?->format('M Y') }}
                    @if($tier['next'])
                        &bull; {{ max(0, $tier['next']['at'] - $totalEarned) }} coins to {{ $tier['next']['name'] }}
                    @endif
                </p>
            </div>
            <div class="text-right shrink-0">
                <div class="text-xl font-black leading-none">{{ number_format($totalBalance) }}</div>
                <div class="text-[9px] font-bold uppercase tracking-wide text-red-200 mt-1">Coins</div>
            </div>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-white rounded-t-3xl md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] w-full mx-auto md:border md:border-slate-100 pb-10">

        <!-- Stats -->
        <div class="grid grid-cols-2 gap-4 mb-8 -translate-y-6 md:mt-12 md:-translate-y-0 pt-6">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Active<br>Loyalty Cards</span>
                    <div class="text-2xl font-black text-slate-900 leading-none">{{ $cards->count() }}</div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 text-red-500 flex items-center justify-center shrink-0 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Rewards<br>Redeemed</span>
                    <div class="text-2xl font-black text-slate-900 leading-none">{{ number_format($rewardsRedeemed) }}</div>
                </div>
            </div>
        </div>

        @if($readyCount > 0)
            <a href="{{ route('customer.claim-reward') }}" class="mb-6 -mt-2 flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3 hover:bg-emerald-100 transition">
                <span class="text-sm font-bold text-emerald-700">🎉 {{ $readyCount }} {{ \Illuminate\Support\Str::plural('reward', $readyCount) }} ready to claim</span>
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            </a>
        @endif

        <div class="flex justify-between items-end mb-4 px-1">
            <h2 class="text-[17px] font-black text-slate-900 tracking-tight">Continue Collecting</h2>
            @if($cards->isNotEmpty())
                <a href="{{ route('customer.claim-reward') }}" class="text-[13px] font-bold text-[#b00000] hover:underline transition">View All</a>
            @endif
        </div>

        @if($cards->isEmpty())
            <div class="text-center py-12 px-6 border border-dashed border-slate-200 rounded-2xl">
                <div class="text-5xl mb-4">📱</div>
                <h3 class="text-base font-black text-slate-900 mb-1">No coins yet</h3>
                <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">Scan the BeAurex QR code at a shop's counter to collect your first Aurex coin.</p>
                <a href="{{ route('customer.scan') }}" class="inline-flex items-center justify-center bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3 px-6 rounded-xl text-sm transition">Scan QR Code</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($cards as $card)
                    @php
                        $business = $card['business'];
                        $next = $card['nextOffer'];
                        $ready = $card['readyOffers'];
                        $goal = $next?->orex_coins ?? ($ready->last()?->orex_coins ?? max(5, $card['balance']));
                        $dots = min(max($goal, 1), 10);
                        $filled = $goal > 0 ? (int) floor(min($card['balance'], $goal) / $goal * $dots) : 0;
                    @endphp
                    <a href="{{ route('customer.claim-reward') }}#business-{{ $business->id }}" class="block bg-white rounded-2xl shadow-sm border border-slate-100 p-5 transition transform hover:-translate-y-1 hover:shadow-md group">
                        <div class="flex justify-between items-start mb-5 gap-3">
                            <div class="flex items-center space-x-3 min-w-0">
                                <div class="w-14 h-14 bg-slate-900 rounded-2xl flex items-center justify-center text-white shadow-sm overflow-hidden shrink-0 group-hover:scale-105 transition">
                                    @if($business->logo)
                                        <img src="{{ \App\Support\Media::url($business->logo) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl font-black">{{ strtoupper(mb_substr($business->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-black text-slate-900 text-[17px] leading-tight mb-0.5 truncate">{{ $business->name }}</h3>
                                    <p class="text-[11px] font-semibold text-slate-500 truncate">{{ $business->category ?: 'Local business' }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                @if($ready->isNotEmpty())
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1.5 rounded-lg border border-emerald-200 leading-tight inline-block">Ready to<br>claim</span>
                                @elseif($next)
                                    <span class="text-[10px] font-bold text-[#b00000] bg-red-50 px-2 py-1.5 rounded-lg border border-red-100 leading-tight inline-block">{{ $next->orex_coins - $card['balance'] }} more<br>{{ \Illuminate\Support\Str::plural('coin', $next->orex_coins - $card['balance']) }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Coin progress -->
                        <div class="flex items-center flex-wrap gap-2 mb-2" aria-hidden="true">
                            @for($i = 0; $i < $dots; $i++)
                                @if($i < $filled)
                                    <div class="w-7 h-7 bg-[#b00000] rounded-full flex items-center justify-center text-white">{!! $star !!}</div>
                                @else
                                    <div class="w-7 h-7 bg-white border border-[#b00000] rounded-full"></div>
                                @endif
                            @endfor
                        </div>
                        <p class="text-[11px] font-semibold text-slate-400 mb-5">
                            {{ $card['balance'] }} {{ \Illuminate\Support\Str::plural('coin', $card['balance']) }} available
                            @if($next) &bull; {{ $card['balance'] }} of {{ $next->orex_coins }} for the next reward @endif
                        </p>

                        <!-- Next / ready reward -->
                        @php $featured = $ready->last() ?? $next; @endphp
                        @if($featured)
                            <div class="bg-red-50/50 rounded-xl p-3 flex justify-between items-center gap-3">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-8 h-8 bg-[#b00000] text-white rounded-full flex items-center justify-center shadow-sm shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[13px] font-black text-[#b00000] leading-tight mb-0.5 truncate">{{ $featured->title }}</p>
                                        <p class="text-[10px] font-semibold text-slate-500">
                                            @if($featured->orex_coins <= $card['balance'])
                                                Ready to claim &bull; {{ $featured->orex_coins }} {{ \Illuminate\Support\Str::plural('coin', $featured->orex_coins) }}
                                            @else
                                                Collect {{ $featured->orex_coins - $card['balance'] }} more to unlock
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-[#b00000] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        @else
                            <p class="text-[11px] font-semibold text-slate-400 bg-slate-50 rounded-xl p-3">This shop hasn't added rewards yet. Keep collecting!</p>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

    </div>

</div>
@endsection
