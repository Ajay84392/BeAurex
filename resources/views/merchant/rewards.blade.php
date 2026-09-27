@extends('layouts.merchant')

@section('title', 'Rewards Requests')

@section('content')
<div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-10">
    
    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
        <div class="flex justify-between items-center mb-6 w-full mx-auto">
            <h1 class="text-xl md:text-2xl font-black tracking-tight md:mx-0">Rewards</h1>
            <!-- Profile Avatar (Visible on Mobile) -->
            <a href="/merchant/profile" class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0 absolute right-6 top-10">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path></svg>
            </a>
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-white md:bg-white rounded-t-3xl md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] w-full mx-auto md:border md:border-slate-100 pb-10">
        
        <div class="pt-6 w-full">
            
            <!-- Tabs -->
            <div class="flex items-center justify-between space-x-2 bg-slate-50/50 md:bg-slate-50 p-1.5 rounded-2xl border border-slate-100 mb-8 max-w-2xl mx-auto shadow-sm">
                <a href="?status=pending" class="flex-1 text-center py-2.5 rounded-xl text-[11px] font-bold transition flex items-center justify-center space-x-1.5 {{ $status == 'pending' ? 'bg-white border border-red-200 text-[#b00000] shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                    <span>Pending</span>
                    @if($counts['pending'] > 0)
                        <span class="bg-[#b00000] text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center leading-none">{{ $counts['pending'] }}</span>
                    @endif
                </a>
                <a href="?status=approved" class="flex-1 text-center py-2.5 rounded-xl text-[11px] font-bold transition {{ $status == 'approved' ? 'bg-[#900000] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                    Approved
                </a>
                <a href="?status=declined" class="flex-1 text-center py-2.5 rounded-xl text-[11px] font-bold transition {{ $status == 'declined' ? 'bg-[#900000] text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' }}">
                    Declined
                </a>
            </div>

            @php
                $items = $requests;
            @endphp

            @if(count($items) == 0)
            <!-- Empty State Content -->
            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h2 class="text-[15px] font-black text-slate-900 mb-1">No {{ ucfirst($status) }} Requests</h2>
                <p class="text-[11px] font-semibold text-slate-500 max-w-[200px] mx-auto">There are no {{ $status }} reward requests to show right now.</p>
            </div>
            @else
            <!-- Title -->
            <h3 class="text-xs font-semibold text-[#64748b] mb-4 uppercase tracking-wider">{{ ucfirst($status) }} Requests</h3>

            <!-- List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($items as $request)
                <div x-data="{ submitting: false }" x-show="!submitting" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] relative overflow-hidden flex flex-col">
                    <div class="flex items-start space-x-4 mb-4">
                        <!-- Reward Icon/Image -->
                        <div class="w-20 h-24 rounded-xl flex-shrink-0 flex flex-col justify-center items-center text-white p-2 shadow-inner border border-black/10 {{ $request->reward_type == 'FREE' ? 'bg-slate-900' : ($request->reward_type == 'DISCOUNT' ? 'bg-gradient-to-br from-green-800 to-green-600' : 'bg-gradient-to-br from-[#b00000] to-red-700') }}">
                            <span class="text-[8px] font-black opacity-80 mb-0.5 tracking-wider">{{ $request->reward_type }}</span>
                            <span class="text-[15px] font-black leading-tight text-center">{!! nl2br(e($request->reward_title)) !!}</span>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1">
                            <h4 class="font-black text-slate-900 text-[13px] leading-tight mb-0.5">{{ $request->customer?->name ?? $request->customer_name ?? 'Customer Name' }}</h4>
                            <p class="text-[9px] font-semibold text-slate-500 mb-2">ID: {{ $request->customer?->customer_id ?? $request->code ?? 'LQR-8F4A29' }}</p>
                            
                            <h5 class="text-[11px] font-bold text-slate-800 leading-tight mb-2">{{ $request->reward_description }}</h5>
                            
                            @if($status == 'pending')
                                <div class="flex items-center space-x-1.5 text-[9px] font-semibold text-slate-500 mb-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ now()->format('d M Y, g:i A') }}</span>
                                </div>
                                <div class="flex items-center space-x-1.5 text-[9px] font-bold text-orange-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Expires: {{ Carbon\Carbon::parse($request->expires_at)->setTimezone('Asia/Kolkata')->format('d M Y') }}</span>
                                </div>
                            @elseif($status == 'approved')
                                <p class="text-[9px] font-semibold text-slate-500 mb-0.5">Approved on</p>
                                <p class="text-[10px] font-bold text-green-700">{{ Carbon\Carbon::parse($request->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y, g:i A') }}</p>
                            @else
                                <p class="text-[9px] font-semibold text-slate-500 mb-0.5">Declined on</p>
                                <p class="text-[10px] font-bold text-red-700">{{ Carbon\Carbon::parse($request->updated_at)->setTimezone('Asia/Kolkata')->format('d M Y, g:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto">
                        @if($status == 'pending')
                            <div class="flex items-center justify-between space-x-3 pt-4 border-t border-slate-50">
                                <form action="/merchant/rewards/{{ $request->id }}/status" method="POST" class="flex-1" @submit="submitting = true">
                                    @csrf
                                    <input type="hidden" name="status" value="declined">
                                    <button type="submit" :disabled="submitting" class="w-full bg-white border border-[#fca5a5] text-[#b00000] font-bold py-2 rounded-xl text-[11px] hover:bg-red-50 transition shadow-sm disabled:opacity-50">Decline</button>
                                </form>
                                <form action="/merchant/rewards/{{ $request->id }}/status" method="POST" class="flex-1" @submit="submitting = true">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" :disabled="submitting" class="w-full bg-[#16a34a] hover:bg-[#15803d] text-white font-bold py-2 rounded-xl text-[11px] transition shadow-sm disabled:opacity-50">Accept</button>
                                </form>
                            </div>
                        @elseif($status == 'approved')
                            <div class="flex justify-end pt-3 border-t border-slate-50">
                                <div class="bg-green-50 text-green-600 border border-green-100 px-3 py-1.5 rounded-lg flex items-center space-x-1.5 shadow-sm">
                                    <span class="text-[10px] font-black uppercase tracking-wider">Approved</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-end pt-3 border-t border-slate-50">
                                <div class="bg-red-50 text-red-600 border border-red-100 px-3 py-1.5 rounded-lg flex items-center space-x-1.5 shadow-sm">
                                    <span class="text-[10px] font-black uppercase tracking-wider">Declined</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
