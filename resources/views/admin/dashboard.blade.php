@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
@php
    // Indian digit grouping (₹ 24,85,430) without needing the intl extension on the server.
    $inr = function ($n) {
        $n = (string) (int) round($n);
        $last3 = substr($n, -3);
        $rest = substr($n, 0, -3);
        return '₹ '.($rest !== '' ? preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest).',' : '').$last3;
    };
    $change = function ($now, $before, $label) {
        if ($before == 0) {
            return $now > 0 ? ['up', 'New '.$label] : ['flat', 'No change '.$label];
        }
        $pct = round(($now - $before) / $before * 100, 1);
        return [$pct > 0 ? 'up' : ($pct < 0 ? 'down' : 'flat'), abs($pct).'% '.$label];
    };
    $icons = [
        'users' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>',
        'add' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>',
        'card' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
        'rupee' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 4h12M6 9h12M13 20L6 13h3a4 4 0 000-8"></path></svg>',
        'customer' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
        'coin' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>',
        'gift' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>',
        'clock' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    ];
    // [title, value, icon, [trend, text], link]
    $platform = [
        ['Active Merchants', number_format($activeMerchants), 'users', ['info', '+'.number_format($newMerchantsThisMonth).' joined this month'], '/admin/merchants'],
        ["Today's Merchant Onboarding", number_format($todayMerchants), 'add', $change($todayMerchants, $yesterdayMerchants, 'vs yesterday'), '/admin/merchants'],
        ["Today's Revenue", $inr($todayRevenue), 'card', $change($todayRevenue, $yesterdayRevenue, 'vs yesterday'), '/admin/plans/history'],
        ['Total Revenue', $inr($totalRevenue), 'rupee', ['info', $inr($monthRevenue).' this month'], '/admin/plans/history'],
    ];
    $loyalty = [
        ['Total Customers', number_format($totalCustomers), 'customer', ['info', '+'.number_format($newCustomersThisMonth).' joined this month'], '/admin/customers'],
        ['Coins Collected', number_format($coinsCollected), 'coin', ['info', '+'.number_format($coinsToday).' today'], null],
        ['Rewards Redeemed', number_format($rewardsRedeemed), 'gift', ['info', 'Approved by merchants'], null],
        ['Pending Approvals', number_format($pendingRewards), 'clock', ['info', 'Waiting at merchant counters'], null],
    ];
@endphp
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-1">Dashboard</h1>
            <p class="text-sm text-slate-500 font-medium">Welcome back! Here's what's happening with your platform today.</p>
        </div>
        <div class="flex items-center space-x-2 bg-white border border-slate-200 px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold text-slate-700 self-start md:self-auto">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>{{ now()->timezone('Asia/Kolkata')->format('M d, Y') }}</span>
        </div>
    </div>

    @foreach(['Platform' => $platform, 'Loyalty Activity' => $loyalty] as $heading => $cards)
        <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500 mb-4 {{ $loop->first ? '' : 'mt-10' }}">{{ $heading }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
            @foreach($cards as [$title, $value, $icon, [$trend, $note], $link])
                @php $cardClass = 'bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center'.($link ? ' hover:shadow-md hover:border-red-100 transition' : ''); @endphp
                @if($link)<a href="{{ $link }}" class="{{ $cardClass }}">@else<div class="{{ $cardClass }}">@endif
                    <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-4">{!! $icons[$icon] !!}</div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-1">{{ $title }}</h3>
                    <div class="text-3xl font-black text-slate-900 mb-3 break-all">{{ $value }}</div>
                    <div class="flex items-center text-xs font-bold {{ ['up' => 'text-emerald-600', 'down' => 'text-red-600', 'flat' => 'text-slate-500', 'info' => 'text-slate-500'][$trend] }}">
                        @if($trend === 'up')
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                        @elseif($trend === 'down')
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                        @endif
                        {{ $note }}
                    </div>
                @if($link)</a>@else</div>@endif
            @endforeach
        </div>
    @endforeach

</div>
@endsection
