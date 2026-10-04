<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>Business Address - BeAurex</title>
    <style>
        .field { width: 100%; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: .75rem; padding: .625rem 1rem; font-size: .875rem; font-weight: 500; color: #0f172a; transition: border-color .15s, box-shadow .15s; }
        .field:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,.08); background: #fff; }
        .field:disabled { opacity: .6; cursor: not-allowed; }
        .field.invalid { border-color: #f87171; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
@php
    $initial = [
        'state' => old('state', $business?->state),
        'district' => old('city', $business?->city),
        'pincode' => old('pin_code', $business?->pincode),
        'area' => old('area'),
    ];
@endphp
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4">
            <div class="flex flex-col items-center pt-8 pb-6 px-5 sm:px-8 bg-red-50">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center text-[#EF4444] shadow-sm mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h1 class="text-xl font-black text-[#0f172a] mb-1 text-center">Business Address</h1>
                <p class="text-xs font-medium text-[#475569] text-center">Add your business location</p>
            </div>

            <div class="px-5 sm:px-7 py-6">
                @if($errors->any())
                    <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-sm font-semibold text-[#8a0000]" role="alert">Please fix the highlighted fields below.</div>
                @endif

                <form action="{{ route('merchant.business-address') }}" method="POST" class="space-y-4" id="addressForm">
                    @csrf

                    <div>
                        <label for="address_line_1" class="block text-xs font-bold text-slate-700 mb-2">Address Line 1 <span class="text-[#EF4444]">*</span></label>
                        <input type="text" id="address_line_1" name="address_line_1" value="{{ old('address_line_1') }}" placeholder="Shop no., building, street" required minlength="3" maxlength="150" autocomplete="address-line1"
                            class="field @error('address_line_1') invalid @enderror">
                        <x-auth.error name="address_line_1" />
                    </div>

                    <div>
                        <label for="address_line_2" class="block text-xs font-bold text-slate-700 mb-2">Address Line 2 <span class="font-medium text-slate-400">(Optional)</span></label>
                        <input type="text" id="address_line_2" name="address_line_2" value="{{ old('address_line_2') }}" placeholder="Landmark, sector, locality" maxlength="150" autocomplete="address-line2"
                            class="field @error('address_line_2') invalid @enderror">
                        <x-auth.error name="address_line_2" />
                    </div>

                    <div>
                        <label for="pinInput" class="block text-xs font-bold text-slate-700 mb-2">PIN Code <span class="text-[#EF4444]">*</span></label>
                        <div class="relative">
                            <input type="text" id="pinInput" name="pin_code" value="{{ $initial['pincode'] }}" list="pinList" placeholder="Type your 6-digit PIN code" required inputmode="numeric" maxlength="6" pattern="[1-9][0-9]{5}" autocomplete="postal-code"
                                class="field pr-10 @error('pin_code') invalid @enderror" aria-describedby="pinStatus">
                            <span id="pinSpinner" class="hidden absolute inset-y-0 right-3 flex items-center"><svg class="w-4 h-4 animate-spin text-slate-400" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" opacity=".25"/><path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg></span>
                        </div>
                        <datalist id="pinList"></datalist>
                        <p id="pinStatus" class="mt-1.5 text-[11px] font-medium text-slate-500" aria-live="polite">Type your PIN and we'll fill in the state and district, or choose them below.</p>
                        <x-auth.error name="pin_code" />
                    </div>

                    <div class="grid grid-cols-1 xs:grid-cols-2 gap-4">
                        <div>
                            <label for="stateSelect" class="block text-xs font-bold text-slate-700 mb-2">State / UT <span class="text-[#EF4444]">*</span></label>
                            <select id="stateSelect" name="state" required class="field cursor-pointer @error('state') invalid @enderror">
                                <option value="" disabled @selected(! $initial['state'])>Select state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}" @selected($initial['state'] === $state)>{{ $state }}</option>
                                @endforeach
                            </select>
                            <x-auth.error name="state" />
                        </div>
                        <div>
                            <label for="districtSelect" class="block text-xs font-bold text-slate-700 mb-2">District / City <span class="text-[#EF4444]">*</span></label>
                            <select id="districtSelect" name="city" required disabled class="field cursor-pointer @error('city') invalid @enderror">
                                <option value="" disabled selected>Select state first</option>
                            </select>
                            <x-auth.error name="city" />
                        </div>
                    </div>

                    <div id="areaWrap" class="hidden">
                        <label for="areaSelect" class="block text-xs font-bold text-slate-700 mb-2">Area / Post Office</label>
                        <select id="areaSelect" name="area" class="field cursor-pointer @error('area') invalid @enderror"></select>
                        <x-auth.error name="area" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full text-white font-bold py-3 rounded-xl text-sm transition bg-[#b00000] hover:bg-[#8a0000]">Continue</button>
                    </div>
                </form>
            </div>
        </div>

        @include('merchant.auth.partials.onboarding-progress', ['step' => 6, 'label' => 'Address'])
    </div>

<script>
    (() => {
        const urls = {
            districts: @json(route('locations.districts', [], false)),
            pincodes: @json(route('locations.pincodes', [], false)),
            pincode: @json(url('/locations/pincode')),
        };
        const initial = @json($initial);
        const stateSel = document.getElementById('stateSelect');
        const districtSel = document.getElementById('districtSelect');
        const pinInput = document.getElementById('pinInput');
        const pinList = document.getElementById('pinList');
        const pinStatus = document.getElementById('pinStatus');
        const spinner = document.getElementById('pinSpinner');
        const areaWrap = document.getElementById('areaWrap');
        const areaSel = document.getElementById('areaSelect');
        const cache = {};

        const getJson = url => cache[url] ??= fetch(url, { headers: { Accept: 'application/json' } })
            .then(r => r.ok ? r.json() : Promise.reject(r.status));

        function status(text, tone) {
            pinStatus.textContent = text;
            pinStatus.className = 'mt-1.5 text-[11px] font-medium ' + (tone === 'ok' ? 'text-emerald-600' : tone === 'bad' ? 'text-[#EF4444]' : 'text-slate-500');
        }

        function fillSelect(select, items, placeholder, chosen) {
            select.innerHTML = '';
            const ph = new Option(placeholder, '', !chosen, !chosen);
            ph.disabled = true;
            select.add(ph);
            items.forEach(v => select.add(new Option(v, v, false, v === chosen)));
            select.disabled = items.length === 0;
        }

        async function loadDistricts(state, chosen) {
            districtSel.disabled = true;
            fillSelect(districtSel, [], 'Loading districts…');
            const districts = await getJson(urls.districts + '?state=' + encodeURIComponent(state)).catch(() => []);
            fillSelect(districtSel, districts, 'Select district', chosen);
        }

        async function loadPinSuggestions() {
            pinList.innerHTML = '';
            if (!stateSel.value || !districtSel.value) return;
            const pins = await getJson(urls.pincodes + '?state=' + encodeURIComponent(stateSel.value) + '&district=' + encodeURIComponent(districtSel.value)).catch(() => []);
            pins.forEach(p => {
                const o = document.createElement('option');
                o.value = p.pincode;
                o.label = p.areas.length > 80 ? p.areas.slice(0, 77) + '…' : p.areas;
                pinList.appendChild(o);
            });
            if (!/^\d{6}$/.test(pinInput.value)) status(pins.length + ' PIN codes in ' + districtSel.value + ' — tap the PIN box to pick one.');
        }

        function showAreas(areas, chosen) {
            areaSel.innerHTML = '';
            areaSel.add(new Option('Select your area (optional)', '', !chosen, !chosen));
            areas.forEach(a => areaSel.add(new Option(a, a, false, a === chosen)));
            areaWrap.classList.toggle('hidden', areas.length === 0);
        }

        async function lookupPin(fromTyping) {
            const pin = pinInput.value;
            if (!/^[1-9]\d{5}$/.test(pin)) {
                areaWrap.classList.add('hidden');
                pinInput.setCustomValidity(pin.length === 6 ? 'PIN codes start with 1-9.' : '');
                if (pin.length && pin.length < 6) status('Keep typing — a PIN code has 6 digits.');
                return;
            }
            spinner.classList.remove('hidden');
            const info = await getJson(urls.pincode + '/' + pin).catch(() => null);
            spinner.classList.add('hidden');

            if (!info) {
                pinInput.setCustomValidity('We could not find this PIN code. Please check it.');
                status('We could not find PIN ' + pin + ' in the India Post directory. Please check it.', 'bad');
                areaWrap.classList.add('hidden');
                return;
            }
            pinInput.setCustomValidity('');

            // Fill in the state and district from the PIN (unless the user already picked a valid match).
            const matchesChoice = info.places.some(p => p.state === stateSel.value && p.district === districtSel.value);
            if (!matchesChoice) {
                stateSel.value = info.state;
                await loadDistricts(info.state, info.district);
                loadPinSuggestions();
            }
            status('✓ ' + districtSel.value + ', ' + stateSel.value + ' · ' + info.areas.length + (info.areas.length === 1 ? ' area' : ' areas'), 'ok');
            showAreas(info.areas, fromTyping ? '' : initial.area);
        }

        stateSel.addEventListener('change', async () => {
            await loadDistricts(stateSel.value);
            pinList.innerHTML = '';
            status('Now choose your district, then your PIN code.');
        });
        districtSel.addEventListener('change', async () => {
            await loadPinSuggestions();
            // A PIN typed earlier that is not in this district no longer fits.
            if (/^\d{6}$/.test(pinInput.value)) {
                const info = await getJson(urls.pincode + '/' + pinInput.value).catch(() => null);
                if (!info || !info.places.some(p => p.state === stateSel.value && p.district === districtSel.value)) {
                    pinInput.value = '';
                    areaWrap.classList.add('hidden');
                    status('Pick a PIN code for ' + districtSel.value + '.');
                }
            }
        });
        pinInput.addEventListener('input', () => {
            pinInput.value = pinInput.value.replace(/\D/g, '').slice(0, 6);
            if (pinInput.value.length === 6) lookupPin(true);
            else { pinInput.setCustomValidity(''); areaWrap.classList.add('hidden'); }
        });

        // Restore what was chosen before (validation error or editing again).
        (async () => {
            if (initial.state) await loadDistricts(initial.state, initial.district);
            if (initial.district) loadPinSuggestions();
            if (/^\d{6}$/.test(initial.pincode || '')) lookupPin(false);
        })();
    })();
</script>
</body>
</html>
