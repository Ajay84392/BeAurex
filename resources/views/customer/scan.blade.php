@extends('layouts.customer')

@section('content')
<div class="bg-[#1e293b] md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0 flex flex-col">
    
    <!-- Top Bar -->
    <div class="px-6 pt-10 pb-4 md:pt-6 md:pb-6 md:px-8 flex items-center justify-between text-white md:bg-white md:text-slate-900 md:rounded-t-[2rem] z-20">
        <a href="/customer" class="w-10 h-10 -ml-2 md:ml-0 rounded-full flex items-center justify-center hover:bg-white/10 md:hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>
        <h1 class="text-base font-black tracking-tight">Scan QR Code</h1>
        <button id="flash-toggle" class="w-10 h-10 -mr-2 md:mr-0 rounded-full flex items-center justify-center hover:bg-white/10 md:hover:bg-slate-100 transition focus:outline-none hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </button>
    </div>

    <!-- Scanner Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-6 relative">
        <p class="text-xs font-semibold text-slate-300 md:text-slate-500 mb-8 text-center max-w-[200px]">Position the QR code within the frame to collect Aurex coins</p>
        
        <!-- Scanner Frame Wrapper -->
        <div class="relative w-64 h-64 md:w-72 md:h-72 mb-12">
            <!-- The QR Scanner Div -->
            <div id="reader" class="w-full h-full rounded-2xl overflow-hidden shadow-2xl relative z-10 bg-black"></div>
            
            <!-- Red Scanner Corners Decoration -->
            <div class="absolute -inset-4 border-2 border-transparent z-20 pointer-events-none">
                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-[#b00000] rounded-tl-xl"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-[#b00000] rounded-tr-xl"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-[#b00000] rounded-bl-xl"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-[#b00000] rounded-br-xl"></div>
            </div>
            

</div>

<style>
    @keyframes scan {
        0%, 100% { top: 0%; }
        50% { top: 100%; }
    }
</style>
@endsection


@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            html5QrCode.stop().then(() => {
                window.location.href = '/customer/after-scan?code=' + encodeURIComponent(decodedText);
            }).catch(err => {
                console.log(err);
            });
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .catch(err => {
                console.log("Error starting scanner:", err);
                html5QrCode.start({ facingMode: "user" }, config, qrCodeSuccessCallback)
                    .catch(e => console.log(e));
            });
    });
</script>
<style>
    #reader { border: none !important; }
    #reader video { object-fit: cover; }
    #reader__dashboard_section_csr span { color: white !important; font-size: 12px; }
    #reader__dashboard_section_swaplink { display: none !important; }
</style>
@endsection

