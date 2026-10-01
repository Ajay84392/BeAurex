@extends('layouts.admin')

@section('title', 'Customer View (Read Only)')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10 bg-[#f1f5f9]">

    <!-- Read Only Banner -->
    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-8 text-center flex flex-col items-center justify-center">
        <div class="flex items-center space-x-2 text-red-600 mb-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            <span class="font-bold">Viewing as Admin (Read Only)</span>
        </div>
        <p class="text-sm font-medium text-red-600">You are viewing the customer profile in read-only mode.</p>
    </div>

    <!-- Customer Profile Header -->
    <div class="bg-white rounded-2xl border border-[#e2e8f0] p-6 flex flex-col md:flex-row items-center justify-between shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
            <div class="w-24 h-24 bg-slate-800 rounded-2xl flex flex-col items-center justify-center text-white p-2 text-3xl font-bold uppercase">
                {{ substr($customer->name ?? 'C', 0, 1) }}
            </div>
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <h2 class="text-2xl font-bold text-[#0f172a]">{{ $customer->name ?? 'N/A' }}</h2>
                    <span class="px-3 py-1 bg-emerald-50 text-[#22C55E] text-xs font-bold rounded-full border border-emerald-100">Active Customer</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-6 text-sm font-medium text-[#475569]">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ $customer->email }}</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $customer->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>Joined: {{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 md:mt-0 w-12 h-12 rounded-full border-2 border-[#e2e8f0] flex items-center justify-center text-slate-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        
        <!-- Stat 1 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-[#475569] mb-1">Active Loyalty Cards</h3>
            <div class="text-3xl font-black text-[#0f172a] mb-2">45</div>
            <div class="flex items-center text-xs font-bold text-[#22C55E]">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                12 cards <span class="text-slate-400 ml-1 font-medium">this month</span>
            </div>
        </div>

        <!-- Stat 2 -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            </div>
            <h3 class="text-sm font-semibold text-[#475569] mb-1">Rewards Redeemed</h3>
            <div class="text-3xl font-black text-[#0f172a] mb-2">4</div>
            <div class="flex items-center text-xs font-bold text-[#22C55E]">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                1 reward <span class="text-slate-400 ml-1 font-medium">this month</span>
            </div>
        </div>

    </div>
    
    <div class="flex items-center space-x-2 text-xs font-medium text-[#475569] bg-white px-4 py-3 rounded-xl border border-[#e2e8f0]">
        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span><strong class="text-slate-700">Note:</strong> You are viewing this customer account in read-only mode. No actions can be performed on this screen.</span>
    </div>

</div>
@endsection


