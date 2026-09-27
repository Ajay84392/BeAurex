@extends('layouts.customer')

@section('content')
<style>
    /* Hide layout elements for full-screen status view on mobile */
    header.h-16.bg-white { display: none !important; }
    nav.md\:hidden.fixed.bottom-0 { display: none !important; }
    main .w-full.max-w-\[1600px\] { padding: 0 !important; max-width: 100% !important; }
    aside.w-64.bg-white { display: none !important; }
    @media (min-width: 768px) {
        .mobile-container {
            max-width: 400px;
            margin: 2rem auto;
            border-radius: 2rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 4px solid #1e293b;
            overflow: hidden;
        }
    }
</style>

@php
    $config = [
        'qr-invalid' => [
            'back_text' => 'Scan QR',
            'back_url' => '/customer/scan',
            'icon_svg' => '<svg class="w-12 h-12 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" stroke-dasharray="2 2" class="opacity-30"></path></svg>',
            'badge_bg' => 'bg-[#b00000]',
            'badge_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>',
            'title' => 'QR Invalid',
            'message' => 'This QR code can\'t be verified. Please try again.',
            'button_text' => 'Scan Again',
            'button_icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"></path></svg>',
            'button_url' => '/customer/scan',
            'reason' => null
        ],
        'no-internet' => [
            'back_text' => '',
            'back_url' => 'javascript:history.back()',
            'icon_svg' => '<svg class="w-14 h-14 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>',
            'badge_bg' => 'bg-[#b00000]',
            'badge_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>',
            'title' => 'No Internet Connection',
            'message' => 'Please check your internet connection and try again.',
            'button_text' => 'Retry',
            'button_icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>',
            'button_url' => 'javascript:window.location.reload()',
            'reason' => null
        ],
        'already-claimed' => [
            'back_text' => 'Claim Reward',
            'back_url' => '/customer/claim-reward',
            'icon_svg' => '<svg class="w-14 h-14 text-[#b00000]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
            'badge_bg' => 'bg-green-600',
            'badge_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>',
            'title' => 'Reward Already Claimed',
            'message' => 'You have already claimed this reward. You can view it in your rewards section.',
            'button_text' => 'View Rewards',
            'button_icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>',
            'button_url' => '/customer/rewards',
            'reason' => null
        ],
        'rejected' => [
            'back_text' => 'Request Rejected',
            'back_url' => '/customer/rewards',
            'icon_svg' => '<svg class="w-14 h-14 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"></path><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M14 3v5h5M8 13h8M8 17h8M8 9h2"></path></svg>',
            'badge_bg' => 'bg-[#b00000]',
            'badge_svg' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>',
            'title' => 'Request Rejected',
            'message' => 'Merchant couldn\'t verify this reward request.',
            'button_text' => 'Try Again',
            'button_icon' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>',
            'button_url' => 'javascript:history.back()',
            'reason' => 'The reward could not be verified. Please contact the store staff for more details.'
        ]
    ];
    $data = $config[$type];
@endphp

<div class="bg-white min-h-[100dvh] mobile-container flex flex-col font-sans relative">
    
    <!-- Top Bar -->
    <div class="px-6 py-6 flex items-center">
        <a href="{{ $data['back_url'] }}" class="text-[#0f172a] hover:text-black transition p-2 -ml-2 rounded-full hover:bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-lg font-black tracking-wide ml-4">{{ $data['back_text'] }}</h1>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-8 pb-32">
        
        <!-- Illustration Area -->
        <div class="relative w-40 h-40 mb-10 flex items-center justify-center">
            <!-- Decorative background elements -->
            <div class="absolute inset-0 bg-red-50 rounded-full scale-125 opacity-70"></div>
            <div class="absolute top-2 right-2 text-red-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.6H22l-6.2 4.5 2.4 7.6-6.2-4.5-6.2 4.5 2.4-7.6-6.2-4.5h7.6z"/></svg>
            </div>
            <div class="absolute bottom-4 left-0 text-red-100">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l2.4 7.6H22l-6.2 4.5 2.4 7.6-6.2-4.5-6.2 4.5 2.4-7.6-6.2-4.5h7.6z"/></svg>
            </div>
            
            <!-- Main Icon -->
            <div class="z-10 bg-white rounded-2xl p-6 shadow-sm border border-red-50">
                {!! $data['icon_svg'] !!}
            </div>

            <!-- Status Badge (Red X or Green Check) -->
            <div class="absolute bottom-4 right-4 {{ $data['badge_bg'] }} text-white w-10 h-10 rounded-full flex items-center justify-center shadow-lg border-4 border-white z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    {!! $data['badge_svg'] !!}
                </svg>
            </div>
        </div>

        <!-- Typography -->
        <h2 class="text-2xl font-black text-[#0f172a] mb-4 text-center">{{ $data['title'] }}</h2>
        <p class="text-[#475569] text-center text-[15px] leading-relaxed font-medium">
            {{ $data['message'] }}
        </p>

        <!-- Reason Box (only for Request Rejected) -->
        @if($data['reason'])
        <div class="mt-8 bg-red-50/50 border border-red-100 rounded-2xl p-5 w-full text-left">
            <h4 class="text-[#b00000] font-bold text-sm mb-1">Reason</h4>
            <p class="text-[#475569] text-sm font-medium leading-relaxed">{{ $data['reason'] }}</p>
        </div>
        @endif

    </div>

    <!-- Fixed Bottom Button -->
    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-white via-white to-transparent">
        <a href="{{ $data['button_url'] }}" class="w-full bg-[#b00000] hover:bg-[#8a0000] text-white font-bold py-4 rounded-xl shadow-lg shadow-red-900/20 transition flex items-center justify-center text-lg">
            {!! $data['button_icon'] !!}
            <span>{{ $data['button_text'] }}</span>
        </a>
    </div>

</div>
@endsection

