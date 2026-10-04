<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>Business Information - BeAurex</title>
    <style>
        .field { width: 100%; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: .75rem; padding: .625rem 1rem; font-size: .875rem; font-weight: 500; color: #0f172a; transition: border-color .15s, box-shadow .15s; }
        .field:focus { outline: none; border-color: #b00000; box-shadow: 0 0 0 3px rgba(176,0,0,.08); background: #fff; }
        .field.invalid { border-color: #f87171; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased min-h-[100dvh] flex flex-col items-center justify-center p-4">
@php
    $currentLogo = \App\Support\Media::url($business?->logo);
@endphp
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden mb-4 p-6 sm:p-8">
            <div class="flex flex-col items-center mb-6">
                <h1 class="text-2xl font-black text-[#0f172a] mb-1.5 text-center">Business Information</h1>
                <p class="text-[#475569] text-sm font-medium text-center">Tell us about your business</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 text-sm font-semibold text-[#8a0000]" role="alert">Please fix the highlighted fields below.</div>
            @endif

            <form action="{{ route('merchant.business-info') }}" method="POST" enctype="multipart/form-data" class="space-y-5" id="businessInfoForm">
                @csrf

                <!-- Logo -->
                <div>
                    <span class="block text-xs font-bold text-slate-700 mb-2">Business Logo <span class="font-medium text-slate-400">(Optional)</span></span>
                    <input type="file" id="logo" name="logo" class="sr-only" accept="image/jpeg,image/png,image/webp" aria-describedby="logoHelp logo-error">

                    <label for="logo" id="logoDrop" class="flex items-center gap-4 border-2 border-dashed rounded-xl p-4 cursor-pointer transition hover:bg-[#f8fafc] hover:border-red-200 {{ $errors->has('logo') ? 'border-red-300' : 'border-[#e2e8f0]' }}">
                        <div id="logoPreview" class="w-16 h-16 rounded-xl bg-red-50 text-[#b00000] flex items-center justify-center overflow-hidden shrink-0 border border-red-100">
                            @if($currentLogo)
                                <img src="{{ $currentLogo }}" alt="Current logo" class="w-full h-full object-cover">
                            @else
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800" id="logoTitle">{{ $currentLogo ? 'Change logo' : 'Upload logo' }}</p>
                            <p class="text-[11px] text-slate-500 truncate" id="logoName">JPG, PNG or WEBP · up to 2 MB</p>
                            <p class="text-[10px] text-slate-400 mt-0.5" id="logoHelp">Square images look best (e.g. 512 × 512 px)</p>
                        </div>
                    </label>
                    <button type="button" id="logoRemove" class="hidden mt-2 text-xs font-bold text-[#b00000] hover:underline">Remove selected image</button>
                    <p id="logoClientError" class="hidden mt-1.5 text-[11px] font-semibold text-[#EF4444]" role="alert"></p>
                    <x-auth.error name="logo" />
                </div>

                <!-- Name -->
                <div>
                    <label for="business_name" class="block text-xs font-bold text-slate-700 mb-2">Business Name <span class="text-[#EF4444]">*</span></label>
                    <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $business?->name) }}" placeholder="e.g. Ka-feen Café" required minlength="2" maxlength="100" autocomplete="organization"
                        class="field @error('business_name') invalid @enderror">
                    <x-auth.error name="business_name" />
                </div>

                <!-- Category -->
                <div>
                    <label for="business_category" class="block text-xs font-bold text-slate-700 mb-2">Business Category <span class="text-[#EF4444]">*</span></label>
                    @php $selectedCategory = old('business_category', $business?->category); @endphp
                    <select id="business_category" name="business_category" required class="field cursor-pointer @error('business_category') invalid @enderror">
                        <option value="" disabled @selected(! $selectedCategory)>Select business category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    <x-auth.error name="business_category" />
                </div>

                <button type="submit" class="w-full text-white font-bold py-3 rounded-xl text-sm transition shadow-md bg-[#b00000] hover:bg-[#8a0000]">Continue</button>
            </form>
        </div>

        @include('merchant.auth.partials.onboarding-progress', ['step' => 5, 'label' => 'Business Info'])
    </div>

<script>
    (() => {
        const input = document.getElementById('logo');
        const preview = document.getElementById('logoPreview');
        const original = preview.innerHTML;
        const title = document.getElementById('logoTitle');
        const name = document.getElementById('logoName');
        const originalTitle = title.textContent, originalName = name.textContent;
        const remove = document.getElementById('logoRemove');
        const error = document.getElementById('logoClientError');
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];

        function reset(message) {
            input.value = '';
            preview.innerHTML = original;
            title.textContent = originalTitle;
            name.textContent = originalName;
            remove.classList.add('hidden');
            error.textContent = message || '';
            error.classList.toggle('hidden', !message);
        }

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return reset();
            if (!allowed.includes(file.type)) return reset('Please choose a JPG, PNG or WEBP image.');
            if (file.size > 2 * 1024 * 1024) return reset('This image is ' + (file.size / 1048576).toFixed(1) + ' MB. Please choose one up to 2 MB.');

            const reader = new FileReader();
            reader.onload = e => {
                preview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Logo preview';
                img.className = 'w-full h-full object-cover';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
            title.textContent = 'Logo selected';
            name.textContent = file.name + ' · ' + Math.max(1, Math.round(file.size / 1024)) + ' KB';
            remove.classList.remove('hidden');
            error.classList.add('hidden');
        });
        remove.addEventListener('click', () => reset());
    })();
</script>
</body>
</html>
