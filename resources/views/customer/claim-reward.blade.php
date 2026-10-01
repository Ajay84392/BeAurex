@extends('layouts.customer')

@section('title', 'My Rewards')

@section('content')
<div class="bg-white md:rounded-[2rem] md:shadow-xl overflow-hidden min-h-[100dvh] md:min-h-[700px] border-x border-b border-slate-100 relative pb-24 md:pb-8 flex flex-col md:p-8">

    <!-- Top Bar -->
    <div class="bg-white px-6 pt-10 pb-4 md:p-0 md:pb-4 flex justify-between items-center sticky top-0 z-20">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">My Rewards</h1>
        <a href="/customer/profile" class="w-10 h-10 border border-slate-200 rounded-full flex items-center justify-center text-slate-500 bg-white hover:bg-slate-50 transition shadow-sm md:hidden" aria-label="Profile">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
        </a>
    </div>

    <!-- Tabs -->
    @php
        $readyTotal = $cards->sum(fn ($c) => $c['readyOffers']->count());
        $tabs = [
            'available' => ['Rewards', $readyTotal],
            'claim' => ['To Claim', $claimable->count()],
            'history' => ['History', $history->count()],
        ];
    @endphp
    <div class="bg-white px-6 md:px-0 flex gap-6 sm:gap-10 pt-2 border-b border-slate-100 mb-6 overflow-x-auto">
        @foreach($tabs as $key => [$label, $count])
            <a href="?tab={{ $key }}" class="pb-3 text-sm whitespace-nowrap flex items-center {{ $tab == $key ? 'font-black text-slate-900 border-b-2 border-[#b00000]' : 'font-semibold text-slate-400 border-b-2 border-transparent' }}">
                {{ $label }}
                @if($count > 0)
                    <span class="bg-[#b00000] text-white text-[10px] font-black min-w-[1rem] h-4 px-1 inline-flex items-center justify-center rounded-full ml-1">{{ $count }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="px-6 md:px-0">
        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold" role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold" role="alert">{{ session('error') }}</div>
        @endif
    </div>

    @if($tab === 'available')
        @if($cards->isEmpty())
            <div class="flex-1 flex flex-col items-center justify-center px-8 text-center py-10">
                <div class="text-7xl drop-shadow-md mb-6">🎁</div>
                <h2 class="text-[17px] font-black text-slate-900 mb-2">No Coins Yet</h2>
                <p class="text-[13px] font-medium text-slate-500 mb-8 max-w-[260px] mx-auto leading-relaxed">Scan a shop's BeAurex QR code to collect coins and unlock rewards.</p>
                <a href="{{ route('customer.scan') }}" class="w-[80%] max-w-[280px] mx-auto bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl transition text-center text-[13px] shadow-sm">Scan QR Code</a>
            </div>
        @else
            <div class="space-y-8 px-6 md:px-0">
                @foreach($cards as $card)
                    @php $business = $card['business']; @endphp
                    <section id="business-{{ $business->id }}" class="scroll-mt-24">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div class="min-w-0">
                                <h2 class="text-base font-black text-slate-900 truncate">{{ $business->name }}</h2>
                                <p class="text-[11px] font-semibold text-slate-400">{{ $business->category ?: 'Local business' }}</p>
                            </div>
                            <div class="shrink-0 text-right bg-red-50 border border-red-100 rounded-xl px-3 py-1.5">
                                <div class="text-lg font-black text-[#b00000] leading-none">{{ $card['balance'] }}</div>
                                <div class="text-[9px] font-bold uppercase tracking-wide text-slate-500">{{ \Illuminate\Support\Str::plural('coin', $card['balance']) }}</div>
                            </div>
                        </div>

                        @if($card['offers']->isEmpty())
                            <p class="text-xs font-semibold text-slate-400 bg-slate-50 rounded-xl p-4">This shop hasn't added rewards yet. Keep collecting!</p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($card['offers'] as $offer)
                                    @php
                                        $canClaim = $offer->orex_coins <= $card['balance'];
                                        $pct = $offer->orex_coins > 0 ? min(100, (int) round($card['balance'] / $offer->orex_coins * 100)) : 100;
                                        $image = \App\Support\Media::url($offer->image);
                                    @endphp
                                    <div class="bg-white rounded-2xl p-4 border {{ $canClaim ? 'border-emerald-200' : 'border-slate-100' }} shadow-sm flex flex-col">
                                        <div class="flex items-start gap-3 mb-3">
                                            <div class="w-14 h-14 rounded-xl bg-[#b00000] text-white flex items-center justify-center overflow-hidden shrink-0">
                                                @if($image)
                                                    <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h3 class="text-sm font-black text-slate-900 leading-tight mb-1 break-words">{{ $offer->title }}</h3>
                                                @if($offer->description)
                                                    <p class="text-[11px] text-slate-500 leading-snug line-clamp-2">{{ $offer->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-auto">
                                            <div class="flex items-center justify-between text-[11px] font-bold mb-1.5">
                                                <span class="text-[#b00000]">{{ $offer->orex_coins }} {{ \Illuminate\Support\Str::plural('coin', $offer->orex_coins) }}</span>
                                                <span class="text-slate-400">{{ min($card['balance'], $offer->orex_coins) }}/{{ $offer->orex_coins }}</span>
                                            </div>
                                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden mb-3">
                                                <div class="h-full rounded-full {{ $canClaim ? 'bg-emerald-500' : 'bg-[#b00000]' }}" style="width: {{ $pct }}%"></div>
                                            </div>
                                            @if($canClaim)
                                                <form method="POST" action="{{ route('customer.offers.claim', $offer) }}" x-data="{ busy: false }" @submit="busy = true">
                                                    @csrf
                                                    <button type="submit" :disabled="busy" class="w-full py-2.5 bg-[#b00000] hover:bg-[#8a0000] disabled:opacity-60 text-white text-sm font-bold rounded-xl transition">
                                                        <span x-text="busy ? 'Claiming…' : 'Claim Reward'">Claim Reward</span>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-full py-2.5 bg-slate-50 text-slate-500 text-xs font-bold rounded-xl text-center">
                                                    Collect {{ $offer->orex_coins - $card['balance'] }} more {{ \Illuminate\Support\Str::plural('coin', $offer->orex_coins - $card['balance']) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>
                @endforeach
            </div>
        @endif
    @else
        @php $items = $tab == 'claim' ? $claimable : $history; @endphp

        @if($items->isEmpty())
            <div class="flex-1 flex flex-col items-center justify-center px-8 text-center bg-white py-10">
                <div class="text-7xl drop-shadow-md mb-6">🎁</div>
                <h2 class="text-[17px] font-black text-slate-900 mb-2">{{ $tab == 'claim' ? 'Nothing to claim yet' : 'No history yet' }}</h2>
                <p class="text-[13px] font-medium text-slate-500 mb-8 max-w-[260px] mx-auto leading-relaxed">
                    {{ $tab == 'claim' ? 'Claim a reward from the Rewards tab and its code will appear here.' : 'Rewards approved or declined by shops will show here.' }}
                </p>
                <a href="?tab=available" class="w-[80%] max-w-[280px] mx-auto bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-3.5 rounded-xl transition text-center text-[13px] shadow-sm">See Rewards</a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6 md:px-0">
                @foreach($items as $request)
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition relative overflow-hidden">
                    @if($request->status == 'approved')
                        <div class="absolute top-0 right-0 bg-[#22C55E] text-white text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg tracking-wider">Approved</div>
                    @elseif($request->status == 'declined')
                        <div class="absolute top-0 right-0 bg-[#EF4444] text-white text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg tracking-wider">Declined</div>
                    @else
                        <div class="absolute top-0 right-0 bg-amber-500 text-white text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg tracking-wider">Waiting</div>
                    @endif

                    <div class="flex items-start">
                        <div class="w-16 h-16 bg-[#b00000] rounded-xl flex-shrink-0 flex flex-col justify-center items-center text-white p-2 shadow-sm">
                            <span class="text-[8px] font-bold opacity-80 mb-0.5">{{ $request->coins_spent ?: '' }} {{ $request->coins_spent ? \Illuminate\Support\Str::plural('COIN', $request->coins_spent) : $request->reward_type }}</span>
                            <span class="text-[11px] font-black leading-tight text-center line-clamp-2 break-words">{{ $request->reward_title }}</span>
                        </div>

                        <div class="ml-4 flex-1 min-w-0">
                            <h3 class="text-sm font-black text-slate-900 leading-tight mb-0.5 truncate">{{ $request->business?->name ?? 'Business' }}</h3>
                            <p class="text-[10px] text-slate-400 font-semibold mb-2">Code {{ $request->code }} &bull; Claimed {{ $request->created_at->format('d M Y') }}</p>
                            @if($request->reward_description)
                                <p class="text-[11px] font-bold text-slate-900 mb-2 leading-tight line-clamp-2">{{ $request->reward_description }}</p>
                            @endif
                            @if($request->status == 'declined' && $request->coins_spent)
                                <p class="text-[10px] font-bold text-emerald-600">{{ $request->coins_spent }} {{ \Illuminate\Support\Str::plural('coin', $request->coins_spent) }} returned to you</p>
                            @elseif($request->expires_at)
                                <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest">Expires {{ $request->expires_at->format('d M Y') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($tab == 'claim')
                    <div class="mt-5 pt-4 border-t border-slate-50">
                        <div class="bg-slate-50 px-4 py-2 rounded-lg border border-slate-100 w-full">
                            <span class="text-[10px] font-semibold text-slate-500 block text-center mb-0.5">Show this code at the counter</span>
                            <span class="text-base font-black text-slate-900 tracking-wider text-center block">{{ $request->code }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    @endif

</div>
@endsection
