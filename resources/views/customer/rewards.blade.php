@extends('layouts.customer')

@section('content')
<div class="bg-white md:rounded-[2rem] md:shadow-xl overflow-hidden min-h-[100dvh] md:min-h-[700px] border-x border-b border-slate-100 relative pb-24 md:pb-0 flex flex-col md:p-8">
    
    <!-- Top Bar -->
    <div class="bg-white px-6 pt-10 pb-4 md:p-0 flex justify-between items-center sticky top-0 z-20">
        <h1 class="text-xl font-black text-slate-900 tracking-tight">My Rewards</h1>
        <a href="/customer/profile" class="w-10 h-10 border border-slate-200 rounded-full flex items-center justify-center text-slate-500 bg-white hover:bg-slate-50 transition shadow-sm md:hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
        </a>
    </div>

    <!-- Tabs -->
    <div class="bg-white px-6 md:px-0 flex space-x-12 pt-2 border-b border-slate-100 mb-8">
        <a href="?tab=claim" class="pb-3 text-sm {{ $tab == 'claim' ? 'font-black text-slate-900 border-b-2 border-[#b00000]' : 'font-semibold text-slate-400 border-b-2 border-transparent' }}">
            To Claim
            @if($claimable->count() > 0)
                <span class="bg-[#b00000] text-white text-[10px] font-black w-4 h-4 inline-flex items-center justify-center rounded-full ml-1">{{ $claimable->count() }}</span>
            @endif
        </a>
        <a href="?tab=history" class="pb-3 text-sm {{ $tab == 'history' ? 'font-black text-slate-900 border-b-2 border-[#b00000]' : 'font-semibold text-slate-400 border-b-2 border-transparent' }} flex items-center">
            <span>History</span>
            @if($history->count() > 0)
                <span class="bg-[#b00000] text-white text-[10px] font-black w-4 h-4 inline-flex items-center justify-center rounded-full ml-1">{{ $history->count() }}</span>
            @endif
        </a>
    </div>

    @php
        $items = $tab == 'claim' ? $claimable : $history;
    @endphp

    @if($items->count() == 0)
    <!-- Empty State Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-8 text-center bg-white py-10 mt-4 md:mt-0">
        <div class="w-24 h-24 bg-red-50 rounded-full flex items-center justify-center mb-6">
            <!-- Gift Icon SVG -->
            <svg class="w-10 h-10 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path></svg>
        </div>
        
        <h2 class="text-[17px] font-black text-slate-900 mb-2">No Rewards Yet</h2>
        <p class="text-[13px] font-medium text-slate-500 mb-8 max-w-[250px] mx-auto leading-relaxed">Collect more Aurex coins from your favourite businesses to earn exciting rewards!</p>
        
        <a href="/customer" class="w-full bg-[#900000] hover:bg-[#700000] text-white font-bold py-3.5 rounded-xl transition text-center text-[13px]">
            Explore Businesses
        </a>
    </div>
    @else
    <!-- List of Rewards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6 md:px-0">
        @foreach($items as $request)
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm transition relative overflow-hidden">
            @if($request->status == 'approved')
                <div class="absolute top-0 right-0 bg-[#22C55E] text-white text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg tracking-wider">Approved</div>
            @elseif($request->status == 'declined')
                <div class="absolute top-0 right-0 bg-[#EF4444] text-white text-[9px] font-black uppercase px-2 py-1 rounded-bl-lg tracking-wider">Declined</div>
            @endif
            
            <div class="flex items-start">
                <div class="w-16 h-16 {{ $request->reward_type == 'FREE' ? 'bg-slate-800' : 'bg-[#b00000]' }} rounded-xl flex-shrink-0 flex flex-col justify-center items-center text-white p-2 shadow-sm">
                    <span class="text-[8px] font-bold opacity-80 mb-0.5">{{ $request->reward_type }}</span>
                    <span class="text-sm font-black leading-tight text-center">{!! nl2br(e($request->reward_title)) !!}</span>
                </div>
                
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-black text-slate-900 leading-tight mb-0.5">{{ $request->business?->name ?? 'Business Name' }}</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mb-2">ID: {{ $request->code }}</p>
                    <p class="text-[11px] font-bold text-slate-900 mb-2 leading-tight">{{ $request->reward_description }}</p>
                    
                    <div class="space-y-1">
                        <div class="flex items-center text-[10px] text-orange-500 font-bold uppercase tracking-widest">
                            <span>Expires: {{ Carbon\Carbon::parse($request->expires_at)->format('m/d/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($tab == 'claim')
            <div class="mt-5 pt-4 border-t border-slate-50 flex justify-center">
                <div class="bg-slate-50 px-4 py-2 rounded-lg border border-slate-100 w-full">
                    <span class="text-[10px] font-semibold text-slate-500 block text-center mb-0.5">Show this code at counter</span>
                    <span class="text-base font-black text-slate-900 tracking-wider text-center block">{{ $request->code }}</span>
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
