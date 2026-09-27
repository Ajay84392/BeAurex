@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
<div class="flex-1 overflow-auto p-6 md:p-10">

            
            <!-- Read Only Banner -->
            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-8 text-center flex flex-col items-center justify-center">
                <div class="flex items-center space-x-2 text-[#b00000] mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span class="font-bold">Viewing as Admin (Read Only)</span>
                </div>
                <p class="text-sm font-medium text-[#b00000]">You are viewing the merchant dashboard in read only mode.</p>
            </div>

            <!-- Merchant Profile Header -->
            <div class="bg-white rounded-2xl border border-[#e2e8f0] p-6 flex flex-col md:flex-row items-center justify-between shadow-sm mb-8">
                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                    <div class="w-24 h-24 bg-[#1e4832] rounded-2xl flex flex-col items-center justify-center text-white p-2 text-2xl font-bold uppercase">
                        {{ substr($merchant->business_name ?? $merchant->name ?? 'M', 0, 1) }}
                    </div>
                    <div>
                        <div class="flex items-center space-x-3 mb-2">
                            <h2 class="text-2xl font-bold text-[#0f172a]">{{ $merchant->business_name ?? $merchant->name ?? 'N/A' }}</h2>
                            <span class="px-3 py-1 bg-emerald-50 text-[#22C55E] text-xs font-bold rounded-full border border-emerald-100">Professional Plan</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-6 text-sm font-medium text-[#475569]">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ $merchant->address ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                <span>{{ $merchant->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <span>{{ $merchant->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 w-12 h-12 rounded-full border-2 border-[#e2e8f0] flex items-center justify-center text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Stat 1 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-[#475569] mb-1">Total Scans</h3>
                    <div class="text-3xl font-black text-[#0f172a] mb-2">1,248</div>
                    <div class="flex items-center text-xs font-bold text-[#22C55E]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        12.5% <span class="text-slate-400 ml-1 font-medium">vs last 30 days</span>
                    </div>
                </div>

                <!-- Stat 2 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#22C55E] flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-[#475569] mb-1">Total Customers</h3>
                    <div class="text-3xl font-black text-[#0f172a] mb-2">846</div>
                    <div class="flex items-center text-xs font-bold text-[#22C55E]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        8.3% <span class="text-slate-400 ml-1 font-medium">vs last 30 days</span>
                    </div>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-[#475569] mb-1">Rewards Redeemed</h3>
                    <div class="text-3xl font-black text-[#0f172a] mb-2">328</div>
                    <div class="flex items-center text-xs font-bold text-[#22C55E]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        15.2% <span class="text-slate-400 ml-1 font-medium">vs last 30 days</span>
                    </div>
                </div>
                
                <!-- Stat 4 -->
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>
                    <h3 class="text-sm font-semibold text-[#475569] mb-1">Customer Repeat Rate</h3>
                    <div class="text-3xl font-black text-[#0f172a] mb-2">32.6%</div>
                    <div class="flex items-center text-xs font-bold text-[#22C55E]">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        6.1% <span class="text-slate-400 ml-1 font-medium">vs last 30 days</span>
                    </div>
                </div>

            </div>
            
            <div class="flex items-center space-x-2 text-xs font-medium text-[#475569] bg-white px-4 py-3 rounded-xl border border-[#e2e8f0]">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span><strong class="text-slate-700">Note:</strong> You are viewing this merchant account in read-only mode. No actions can be performed on this screen.</span>
            </div>

        </div>

@endsection





