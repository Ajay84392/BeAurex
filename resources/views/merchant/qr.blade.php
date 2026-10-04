<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>Your QR Code - BeAurex</title>
</head>
<body class="bg-[#8a0000] text-white antialiased min-h-[100dvh] flex flex-col items-center justify-center px-6 py-10">
    <div class="w-full max-w-sm text-center">
        <img src="/images/logo.jpg" alt="BeAurex" class="w-12 h-12 rounded-xl object-cover bg-white mx-auto mb-4">
        <h1 class="text-2xl font-black tracking-tight">{{ $business->name }}</h1>
        <p class="text-sm font-semibold text-red-200 mt-1 mb-8">Scan to collect your aurex coin</p>

        <div class="bg-white rounded-3xl p-5 shadow-2xl">
            <div class="relative w-full aspect-square">
                <img src="{{ $qrImage }}" alt="QR code for {{ $business->name }}"
                    class="w-full h-full object-contain" style="image-rendering: pixelated" />
                <div class="absolute inset-0 m-auto w-12 h-12 bg-[#b00000] rounded-lg flex items-center justify-center shadow-md border-[3px] border-white">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <p class="text-[11px] font-medium text-red-200 mt-6 break-all">{{ $qrUrl }}</p>

        <div class="flex gap-3 mt-8">
            <a href="{{ route('merchant.dashboard') }}"
                class="flex-1 bg-white/10 hover:bg-white/20 border border-white/30 font-bold py-3 rounded-xl text-sm transition">Dashboard</a>
            <a href="{{ route('merchant.download-qr') }}"
                class="flex-1 bg-white text-[#b00000] hover:bg-red-50 font-bold py-3 rounded-xl text-sm transition">Download QR</a>
        </div>
    </div>
</body>
</html>
