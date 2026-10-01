@extends('layouts.merchant')

@section('title', 'Live Offers')

@section('content')
<div class="flex-1 overflow-auto bg-[#f1f5f9] p-4 md:p-6" x-data="{ claimModal: false, selectedOffer: null }">

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="w-full max-w-5xl mx-auto space-y-6 pb-24">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-black text-[#0f172a] tracking-tight">Live Offers & Rewards</h2>
                <p class="text-sm font-semibold text-slate-500 mt-1">Customers can view and claim eligible rewards here.</p>
            </div>
        </div>

        @if($offers && count($offers) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($offers as $offer)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col overflow-hidden hover:shadow-md transition">
                        @if($offer->image)
                            <div class="h-48 w-full bg-slate-100 relative">
                                <img src="{{ \App\Support\Media::url($offer->image) }}" class="w-full h-full object-cover" alt="Offer Image">
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-xl shadow-sm border border-white/20 flex items-center space-x-1.5">
                                    <span class="text-sm font-black text-[#b00000]">{{ $offer->orex_coins }}</span>
                                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">Aurex Coins</span>
                                </div>
                            </div>
                        @else
                            <div class="h-48 w-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center relative">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-xl shadow-sm border border-white/20 flex items-center space-x-1.5">
                                    <span class="text-sm font-black text-[#b00000]">{{ $offer->orex_coins }}</span>
                                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wide">Aurex Coins</span>
                                </div>
                            </div>
                        @endif

                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-lg font-black text-[#0f172a] mb-2 leading-tight">{{ $offer->title }}</h3>
                            <p class="text-sm text-slate-500 font-medium mb-4 flex-1 line-clamp-3">{{ $offer->description }}</p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                                <div class="text-xs font-semibold text-slate-400">
                                    Valid for {{ $offer->expiry ?? 30 }} Days
                                </div>
                                <button @click="selectedOffer = '{{ addslashes($offer->title) }}'; claimModal = true" class="bg-[#b00000] hover:bg-[#8a0000] text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm transition">
                                    Claim Reward
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-xl font-black text-[#0f172a] mb-2">No Live Offers</h3>
                <p class="text-slate-500 font-medium mb-6 max-w-md mx-auto">You haven't created any reward programs yet. Go to Create Offer to set up your first reward.</p>
                <a href="/merchant/create-offer" class="bg-[#b00000] text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:bg-[#8a0000] transition inline-flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Create Offer</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Claim Modal -->
    <div x-show="claimModal" style="display: none;" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" x-transition.opacity>
        <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-md w-full p-6 relative" @click.away="claimModal = false">
            <button @click="claimModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            </div>
            
            <h3 class="text-xl font-black text-center text-[#0f172a] mb-2">Claim Reward</h3>
            <p class="text-sm text-center text-[#475569] mb-6 font-medium">
                Customer is claiming: <br> <span class="font-bold text-[#b00000] text-base" x-text="selectedOffer"></span>
            </p>
            
            <form method="POST" action="{{ route('merchant.live-offers.claim') }}">
                @csrf
                <input type="hidden" name="offer_title" :value="selectedOffer">
                <div class="mb-4">
                    <label class="block text-xs font-bold text-[#475569] uppercase tracking-wider mb-2">Customer Phone / ID</label>
                    <input type="text" name="customer_identifier" required class="block w-full px-4 py-3 border border-[#e2e8f0] rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#b00000]/20 focus:border-[#b00000] text-sm font-semibold transition" placeholder="Enter customer phone number">
                </div>
                
                <div class="flex space-x-3 mt-6">
                    <button type="button" @click="claimModal = false" class="flex-1 py-3 border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition">Confirm Claim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
