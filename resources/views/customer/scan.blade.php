@extends('layouts.customer')

@section('content')
<div class="bg-[#1e293b] md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-0 flex flex-col">

    <!-- Top Bar -->
    <div class="px-6 pt-10 pb-4 md:pt-6 md:pb-6 md:px-8 flex items-center justify-between text-white md:bg-white md:text-slate-900 md:rounded-t-[2rem] z-20">
        <a href="/customer" class="w-10 h-10 -ml-2 md:ml-0 rounded-full flex items-center justify-center hover:bg-white/10 md:hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </a>
        <h1 class="text-base font-black tracking-tight">Scan QR Code</h1>
        <!-- Flashlight toggle: shown only when the phone's camera supports a torch -->
        <button id="flash-toggle" type="button" aria-label="Turn flashlight on" aria-pressed="false" class="w-10 h-10 -mr-2 md:mr-0 rounded-full flex items-center justify-center hover:bg-white/10 md:hover:bg-slate-100 transition focus:outline-none hidden">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </button>
        <span id="flash-spacer" class="w-10 h-10 -mr-2 md:mr-0"></span>
    </div>

    <!-- Scanner Content -->
    <div class="flex-1 flex flex-col items-center justify-center px-6 relative">
        <p class="text-xs font-semibold text-slate-300 md:text-slate-500 mb-8 text-center max-w-[200px]">Position the QR code within the frame to collect Aurex coins</p>

        <!-- Scanner Frame Wrapper -->
        <div class="relative w-64 h-64 md:w-72 md:h-72 mb-8">
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

        <!-- Large flashlight button under the scanner (same control as the top-bar icon) -->
        <button id="flash-toggle-big" type="button" class="hidden mb-4 px-5 py-2.5 rounded-full text-xs font-bold flex items-center space-x-2 transition bg-white/10 text-white border border-white/20 md:bg-slate-100 md:text-slate-800 md:border-slate-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span id="flash-label">Turn on flashlight</span>
        </button>

        <p id="scan-error" class="hidden text-xs font-semibold text-red-300 md:text-red-600 text-center max-w-[260px]"></p>
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const html5QrCode = new Html5Qrcode("reader");
        const flashButtons = [document.getElementById('flash-toggle'), document.getElementById('flash-toggle-big')];
        const flashSpacer = document.getElementById('flash-spacer');
        const flashLabel = document.getElementById('flash-label');
        let torch = null;      // html5-qrcode torch feature, when the camera supports it
        let torchOn = false;

        function renderFlash() {
            flashButtons.forEach(btn => {
                btn.setAttribute('aria-pressed', torchOn ? 'true' : 'false');
                btn.setAttribute('aria-label', torchOn ? 'Turn flashlight off' : 'Turn flashlight on');
                btn.classList.toggle('bg-amber-400', torchOn);
                btn.classList.toggle('!text-slate-900', torchOn);
            });
            flashLabel.textContent = torchOn ? 'Turn off flashlight' : 'Turn on flashlight';
        }

        async function setTorch(on) {
            if (!torch) return;
            try {
                await torch.apply(on);
                torchOn = on;
            } catch (e) {
                console.log('Flashlight error:', e);
            }
            renderFlash();
        }

        // After the camera starts, show the flashlight buttons if this camera has a torch.
        function setupTorch() {
            try {
                const feature = html5QrCode.getRunningTrackCameraCapabilities().torchFeature();
                if (feature && feature.isSupported()) {
                    torch = feature;
                    torchOn = !!feature.value();
                    flashButtons.forEach(btn => btn.classList.remove('hidden'));
                    flashSpacer.classList.add('hidden');
                    renderFlash();
                }
            } catch (e) {
                console.log('Flashlight not available:', e);
            }
        }

        flashButtons.forEach(btn => btn.addEventListener('click', () => setTorch(!torchOn)));

        // Where a scanned code should take the customer. A BeAurex merchant QR is a link to its
        // coin-collect page (/customer/collect/{id}); open that path on *this* site, so a QR made on
        // localhost works on beaurex.in and vice versa. Anything else is not a BeAurex code.
        function scanTarget(text) {
            try {
                const url = new URL(text.trim(), window.location.origin);
                if (/^\/customer\/collect\/\d+\/?$/.test(url.pathname)) return url.pathname;
            } catch (e) {}
            return '/customer/status/qr-invalid';
        }
        window.beaurexScanTarget = scanTarget;

        let handled = false;
        const qrCodeSuccessCallback = (decodedText) => {
            // The scanner reports the same code many times a second; act on the first one only.
            if (handled) return;
            handled = true;
            const target = scanTarget(decodedText);
            const stop = torchOn ? setTorch(false).then(() => html5QrCode.stop()) : html5QrCode.stop();
            // Leave even if stopping the camera fails, so the coin popup always opens.
            stop.catch(err => console.log(err)).finally(() => { window.location.href = target; });
        };

        function showError(message) {
            const el = document.getElementById('scan-error');
            el.textContent = message;
            el.classList.remove('hidden');
        }

        const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
            .then(setupTorch)
            .catch(err => {
                console.log("Error starting scanner:", err);
                html5QrCode.start({ facingMode: "user" }, config, qrCodeSuccessCallback)
                    .then(setupTorch)
                    .catch(e => {
                        console.log(e);
                        showError('Could not open the camera. Please allow camera access and make sure the site is opened over https.');
                    });
            });

        // Switch the light off if the customer leaves the page.
        window.addEventListener('pagehide', () => { if (torchOn) setTorch(false); });
    });
</script>
<style>
    #reader { border: none !important; }
    #reader video { object-fit: cover; }
    #reader__dashboard_section_csr span { color: white !important; font-size: 12px; }
    #reader__dashboard_section_swaplink { display: none !important; }
</style>
@endsection
