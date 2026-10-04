@extends('layouts.merchant')

@section('title', 'Merchant Dashboard')

@section('content')
    <div class="bg-slate-50 md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0">

        <!-- Red Header Section -->
        <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center space-x-4">
                    <!-- Store Logo -->
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-7 h-7 text-[#b00000]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black tracking-tight">{{ $business->name ?? auth()->user()->name }}</h2>
                        @php
                            $planActive = $business->complimentary || ($planValidTill && $planValidTill->isFuture()) || $business->status === 'Active';
                        @endphp
                        <div class="flex items-center flex-wrap gap-2 mt-1">
                            <span
                                class="bg-white {{ $planActive ? 'text-green-600' : 'text-amber-600' }} text-[9px] font-black uppercase px-2 py-0.5 rounded-full shadow-sm tracking-wide">{{ $business->status ?: 'Trial' }}</span>
                            <span
                                class="bg-[#700000] text-white border border-red-500 text-[9px] font-black uppercase px-2 py-0.5 rounded-full tracking-wide">{{ $business->complimentary ? 'Complimentary' : ($business->plan ?: 'Trial Plan') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Profile Avatar (Visible on Mobile) -->
                <a href="/merchant/profile"
                    class="w-10 h-10 rounded-full border border-red-400/50 flex items-center justify-center hover:bg-white/10 transition md:hidden shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z">
                        </path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Main Content Wrapper (Overlapping the red header) -->
        <div
            class="bg-white md:bg-white rounded-t-3xl md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg w-full mx-auto md:border md:border-slate-100 pb-10">

            <div class="pt-6 w-full mx-auto">

                <div class="flex justify-between items-center mb-5 px-2">
                    <h2 class="text-lg font-black text-slate-900 tracking-tight">Overview</h2>
                    <div class="flex items-center text-[11px] font-bold text-slate-500">
                        All time
                    </div>
                </div>

                <!-- 4 Stats Cards Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <!-- Total Scans -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col justify-between transition">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-red-50 text-[#b00000] flex items-center justify-center shrink-0 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="text-[11px] font-semibold text-[#64748b] mb-1">Total Scans</div>
                            <div class="text-2xl font-black text-[#0f172a] leading-none mb-3">{{ number_format($totalScans) }}</div>
                        </div>
                        <div class="text-[10px] font-bold {{ $scansGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} flex items-center space-x-1">
                            <span>{{ $scansGrowth > 0 ? '+' : '' }}{{ $scansGrowth }}%</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                @if($scansGrowth >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"></path>
                                @endif
                            </svg>
                        </div>
                    </div>

                    <!-- Total Customers -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col justify-between transition">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="text-[11px] font-semibold text-[#64748b] mb-1">Total Customers</div>
                            <div class="text-2xl font-black text-[#0f172a] leading-none mb-3">{{ number_format($totalCustomers) }}</div>
                        </div>
                        <div class="text-[10px] font-bold {{ $customersGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} flex items-center space-x-1">
                            <span>{{ $customersGrowth > 0 ? '+' : '' }}{{ $customersGrowth }}%</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                @if($customersGrowth >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"></path>
                                @endif
                            </svg>
                        </div>
                    </div>

                    <!-- Rewards Redeemed -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col justify-between transition">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#16a34a] flex items-center justify-center shrink-0 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                </svg>
                            </div>
                            <div class="text-[11px] font-semibold text-[#64748b] mb-1">Rewards Redeemed</div>
                            <div class="text-2xl font-black text-[#0f172a] leading-none mb-3">{{ number_format($rewardsRedeemed) }}</div>
                        </div>
                        <div class="text-[10px] font-bold {{ $rewardsGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} flex items-center space-x-1">
                            <span>{{ $rewardsGrowth > 0 ? '+' : '' }}{{ $rewardsGrowth }}%</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                @if($rewardsGrowth >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"></path>
                                @endif
                            </svg>
                        </div>
                    </div>

                    <!-- Repeat Rate -->
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-[#e2e8f0] flex flex-col justify-between transition">
                        <div>
                            <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center shrink-0 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <div class="text-[11px] font-semibold text-[#64748b] mb-1">Repeat Rate</div>
                            <div class="text-2xl font-black text-[#0f172a] leading-none mb-3">{{ round($repeatRate) }}%</div>
                        </div>
                        <div class="text-[10px] font-bold {{ $repeatGrowth >= 0 ? 'text-emerald-600' : 'text-red-600' }} flex items-center space-x-1">
                            <span>{{ $repeatGrowth > 0 ? '+' : '' }}{{ $repeatGrowth }}%</span>
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                @if($repeatGrowth >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25"></path>
                                @endif
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Responsive Layout for QR and Pro Plan Banner -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">

                    <!-- QR Code Card -->
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-[#e2e8f0]">
                        <h3 class="text-base font-black text-slate-900 mb-0.5">Your QR Code</h3>
                        <p class="text-xs font-semibold text-[#64748b] mb-6">Let customers scan to collect aurex coins</p>

                        <div class="flex items-center space-x-5">
                            <!-- QR Box -->
                            <div id="merchantQr"
                                class="w-[132px] h-[132px] bg-white rounded-xl flex items-center justify-center relative overflow-hidden border border-slate-200 shrink-0">
                                <img src="{{ $qrImage }}" alt="QR code for {{ $business->name }}" width="132" height="132"
                                    class="w-full h-full object-contain" style="image-rendering: pixelated" />
                                <!-- Inner Red Icon (Gift): small enough for the QR's error correction to cover -->
                                <div
                                    class="absolute inset-0 m-auto w-7 h-7 bg-[#b00000] rounded-md flex items-center justify-center shadow-md border-2 border-white">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col space-y-3 flex-1">
                                <a href="{{ route('merchant.download-qr') }}"
                                    class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-2.5 rounded-xl transition flex justify-center items-center space-x-2 text-xs shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span>Download QR</span>
                                </a>
                                <!-- Copy the link the QR points to, so it can be shared and opened without scanning -->
                                <div x-data="{
                                        url: @js($qrUrl),
                                        copied: false,
                                        async copy() {
                                            try {
                                                await navigator.clipboard.writeText(this.url);
                                            } catch (e) {
                                                // Clipboard API needs a secure page; fall back to a hidden textarea.
                                                const t = document.createElement('textarea');
                                                t.value = this.url;
                                                t.setAttribute('readonly', '');
                                                t.style.position = 'fixed';
                                                t.style.opacity = '0';
                                                document.body.appendChild(t);
                                                t.select();
                                                document.execCommand('copy');
                                                t.remove();
                                            }
                                            this.copied = true;
                                            clearTimeout(this.timer);
                                            this.timer = setTimeout(() => this.copied = false, 4000);
                                        }
                                    }" class="relative">
                                    <button type="button" @click="copy()"
                                        class="w-full bg-white hover:bg-slate-50 border border-[#fca5a5] text-[#b00000] font-bold py-2.5 rounded-xl transition flex justify-center items-center space-x-2 text-xs shadow-sm">
                                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        <svg x-show="copied" style="display: none" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="copied ? 'Copied!' : 'Copy URL'">Copy URL</span>
                                    </button>
                                    <a x-show="copied" style="display: none" x-transition.opacity :href="url" target="_blank" rel="noopener"
                                        class="mt-1.5 flex items-center justify-center gap-1 text-[10px] font-bold text-[#b00000] hover:underline">
                                        <span>Open link</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pro Plan Banner -->
                    <div
                        class="bg-red-50 rounded-2xl p-4 shadow-sm border border-red-100 flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="text-2xl drop-shadow-sm">👑</div>
                            <div>
                                <div class="text-[13px] font-black text-slate-900 leading-tight">
                                    You're on {{ $business->complimentary ? 'a Complimentary Plan' : ($business->plan ?: 'the Trial Plan') }}
                                </div>
                                <div class="text-[9px] font-semibold text-slate-500 mt-0.5">
                                    @if($business->complimentary)
                                        Free access granted by BeAurex
                                    @elseif($planValidTill)
                                        {{ $planValidTill->isPast() ? 'Plan expired on' : 'Plan valid until' }} {{ $planValidTill->format('d M Y') }}
                                    @else
                                        No expiry date set yet
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($plan && ! $business->complimentary)
                            <div
                                class="bg-white border border-[#fca5a5] text-[#b00000] text-[10px] font-bold px-2.5 py-1.5 rounded-lg shadow-sm whitespace-nowrap">
                                ₹{{ number_format((float) $plan->price) }}{{ $plan->billing_cycle ? ' / '.strtolower($plan->billing_cycle) : '' }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection
