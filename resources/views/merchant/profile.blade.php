@extends('layouts.merchant')

@section('title', 'Merchant Profile')

@section('content')
    @php
        $user = auth()->user();
        $businessName = $business->name ?? $user->name;
        $nameParts = explode(' ', $businessName ?: 'B');
        $initials =
            strtoupper(substr($nameParts[0], 0, 1)) .
            (count($nameParts) > 1 ? strtoupper(substr($nameParts[1], 0, 1)) : '');
        $label = 'block text-[13px] font-bold text-slate-700 mb-2';
        $input =
            'w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-300 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition';
        $invalid = '!border-red-400';
        // Three columns only once there is room beside the sidebar; two on tablets, one on phones.
        $grid = 'md:grid-cols-2 xl:grid-cols-3';
    @endphp
    <div class="bg-white md:bg-transparent min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-10">

        <!-- Red Header Section -->
        <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
            <div class="flex justify-between items-center mb-6">
                <a href="/merchant" class="text-white hover:text-red-200 transition md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-xl md:text-2xl font-black tracking-tight mx-auto md:mx-0">Profile</h1>
                <div class="w-6 h-6 md:hidden"></div> <!-- Spacer for center alignment -->
            </div>
        </div>

        <!-- Main Content Wrapper (Overlapping the red header) -->
        <div
            class="bg-white rounded-t-[30px] md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] md:border md:border-slate-100 pb-10 pt-8">

            @include('partials.profile-alerts')

            <form action="{{ route('merchant.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8" x-data="profileEditor()">
                @csrf

                <!-- Business Details -->
                <x-profile.section key="business" title="Business Details" :fields="['business_name', 'category', 'description', 'address', 'city', 'state', 'pincode', 'logo']">

                    <div class="flex flex-col md:flex-row gap-6 mb-5">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <span class="{{ $label }}">Business Logo</span>
                            <div class="relative inline-block group">
                                <div id="logoPreview"
                                    class="w-20 h-20 rounded-full border border-slate-200 shadow-sm overflow-hidden bg-slate-50 flex items-center justify-center">
                                    @if ($business && $business->logo)
                                        <img src="{{ \App\Support\Media::url($business->logo) }}" alt="Business logo"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full bg-red-50 text-[#b00000] flex items-center justify-center text-3xl font-black">
                                            {{ $initials }}</div>
                                    @endif
                                </div>
                                <label x-show="edit.business" style="display: none"
                                    class="absolute bottom-0 right-0 w-7 h-7 bg-[#b00000] text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm cursor-pointer hover:bg-[#8a0000] transition"
                                    title="Change logo">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <input type="file" name="logo" class="hidden"
                                        accept="image/jpeg,image/png,image/jpg" data-preview="logoPreview">
                                </label>
                            </div>
                            <p x-show="edit.business" style="display: none" class="text-[10px] text-slate-400 mt-2">
                                JPG,JPEG,PNG. Max 2 MB.</p>
                            <x-auth.error name="logo" />
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="business_name" class="{{ $label }}">Business Name <span
                                        class="text-[#EF4444]">*</span></label>
                                <input type="text" id="business_name" name="business_name"
                                    value="{{ old('business_name', $businessName) }}" required minlength="2"
                                    maxlength="255" placeholder="e.g. Ka-feen Café" autocomplete="organization"
                                    class="{{ $input }} @error('business_name') {{ $invalid }} @enderror">
                                <x-auth.error name="business_name" />
                            </div>
                            <div>
                                <label for="category" class="{{ $label }}">Business Category</label>
                                <input type="text" id="category" name="category"
                                    value="{{ old('category', $business->category ?? '') }}" maxlength="100"
                                    placeholder="e.g. Café"
                                    class="{{ $input }} @error('category') {{ $invalid }} @enderror">
                                <x-auth.error name="category" />
                            </div>
                            <div class="md:col-span-2">
                                <label for="description" class="{{ $label }}">About the Business</label>
                                <textarea id="description" name="description" rows="2" maxlength="1000"
                                    placeholder="A short description customers will see"
                                    class="{{ $input }} resize-none @error('description') {{ $invalid }} @enderror">{{ old('description', $business->description ?? '') }}</textarea>
                                <x-auth.error name="description" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="sm:col-span-3">
                            <label for="address" class="{{ $label }}">Business Address</label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address', $business->address ?? '') }}" maxlength="255"
                                placeholder="Shop no., street, area" autocomplete="street-address"
                                class="{{ $input }} @error('address') {{ $invalid }} @enderror">
                            <x-auth.error name="address" />
                        </div>
                        <div>
                            <label for="city" class="{{ $label }}">City</label>
                            <input type="text" id="city" name="city"
                                value="{{ old('city', $business->city ?? '') }}" maxlength="100"
                                placeholder="e.g. New Delhi" autocomplete="address-level2"
                                class="{{ $input }} @error('city') {{ $invalid }} @enderror">
                            <x-auth.error name="city" />
                        </div>
                        <div>
                            <label for="state" class="{{ $label }}">State</label>
                            <input type="text" id="state" name="state"
                                value="{{ old('state', $business->state ?? '') }}" maxlength="100"
                                placeholder="e.g. Delhi" autocomplete="address-level1"
                                class="{{ $input }} @error('state') {{ $invalid }} @enderror">
                            <x-auth.error name="state" />
                        </div>
                        <div>
                            <label for="pincode" class="{{ $label }}">Pincode</label>
                            <input type="text" id="pincode" name="pincode"
                                value="{{ old('pincode', $business->pincode ?? '') }}" inputmode="numeric"
                                maxlength="6" pattern="[0-9]{6}" title="6-digit pincode" placeholder="110001"
                                autocomplete="postal-code" oninput="this.value=this.value.replace(/\D/g,'').slice(0,6)"
                                class="{{ $input }} @error('pincode') {{ $invalid }} @enderror">
                            <x-auth.error name="pincode" />
                        </div>
                    </div>
                </x-profile.section>

                <!-- Owner & Contact -->
                <x-profile.section key="owner" title="Owner & Contact" :fields="['name', 'phone', 'email']"
                    class="border-t border-slate-100 pt-8">

                    <div class="grid grid-cols-1 {{ $grid }} gap-5">
                        <div>
                            <label for="name" class="{{ $label }}">Owner Name <span
                                    class="text-[#EF4444]">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                required minlength="2" maxlength="100" autocomplete="name" placeholder="John Doe"
                                class="{{ $input }} @error('name') {{ $invalid }} @enderror">
                            <x-auth.error name="name" />
                        </div>
                        <div>
                            <label for="phone" class="{{ $label }}">Contact Number <span
                                    class="text-[#EF4444]">*</span></label>
                            <div class="flex">
                                <span
                                    class="bg-slate-50 border border-slate-200 border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                                <input type="tel" id="phone" name="phone"
                                    value="{{ old('phone', \App\Rules\MobileNumber::normalize($user->phone ?: $business->phone ?? null)) }}"
                                    required inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}"
                                    title="10-digit mobile number starting with 6-9" placeholder="9876543210"
                                    autocomplete="tel-national" data-mobile
                                    class="{{ $input }} rounded-l-none @error('phone') {{ $invalid }} @enderror">
                            </div>
                            <x-auth.error name="phone" />
                        </div>
                        <div class="md:col-span-2 xl:col-span-1">
                            <label for="email" class="{{ $label }}">Email Address <span
                                    class="text-[#EF4444]">*</span></label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}" required
                                maxlength="255" autocomplete="email"
                                class="{{ $input }} @error('email') {{ $invalid }} @enderror">
                            <p class="text-[11px] text-slate-400 mt-1.5">Used to log in. Changing it needs your current
                                password.</p>
                            <x-auth.error name="email" />
                        </div>
                    </div>
                </x-profile.section>

                <!-- Preferences -->
                @include('partials.profile-preferences', [
                    'user' => $user,
                    'label' => $label,
                    'input' => $input,
                    'invalid' => $invalid,
                ])

                <!-- Change Password -->
                @include('partials.profile-password', ['label' => $label, 'input' => $input])

                <div x-show="editing" style="display: none"
                    class="flex flex-col-reverse md:flex-row md:justify-end gap-3">
                    <button type="button" @click="cancelAll()"
                        class="w-full md:w-auto px-8 py-3.5 bg-white border border-red-200 text-[#b00000] text-sm font-bold rounded-xl hover:bg-red-50 transition text-center">Cancel</button>
                    <button type="submit"
                        class="w-full md:w-auto px-8 py-3.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition shadow-md">Save
                        Changes</button>
                </div>
            </form>

            {{-- Refer & Earn + Coupon Codes: copy or share with friends --}}
            @php
                $shareText = "Join me on BeAurex and start rewarding your customers! Sign up with my referral code {$referralCode}: {$referralLink}";
            @endphp
            <div class="mt-10 border-t border-slate-100 pt-8 grid grid-cols-1 xl:grid-cols-2 gap-8 items-start"
                x-data="shareTools()">

                <!-- Refer & Earn -->
                <section aria-labelledby="refer-title">
                    <h3 id="refer-title" class="text-[15px] font-black text-slate-900 mb-3">Refer &amp; Earn</h3>
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

                        <!-- Header -->
                        <div class="px-5 pt-5 sm:px-6 sm:pt-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-[#b00000] to-[#7a0000] text-white flex items-center justify-center shrink-0 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-[15px] text-slate-900 leading-tight">Invite friends to BeAurex</p>
                                        <p class="text-[12px] text-slate-500 mt-0.5">Know a business owner? Share your code with them.</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-100 px-3 py-1 text-[12px] font-bold text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ $referralsJoined }} Joined
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-100 px-3 py-1 text-[12px] font-bold text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>{{ $referralsPending }} Pending
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 sm:p-6 space-y-5">
                            <!-- How it works: connected timeline -->
                            <ol class="relative grid grid-cols-3 text-center">
                                <span class="absolute top-5 left-[16.66%] right-[16.66%] h-0.5 bg-red-100" aria-hidden="true"></span>
                                @foreach ([
                                    ['Share', 'Send your code or link', 'M8.68 13.34a3 3 0 100-2.68m0 2.68l6.63 3.32m-6.63-6l6.63-3.32m0 0a3 3 0 105.37-2.68 3 3 0 00-5.37 2.68zm0 9.32a3 3 0 105.37 2.68 3 3 0 00-5.37-2.68z'],
                                    ['Sign up', 'Friend registers with it', 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                                    ['Done', 'Counted once verified', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ] as $i => [$step, $hint, $icon])
                                    <li class="relative px-1">
                                        <span class="relative mx-auto w-10 h-10 rounded-full bg-white border-2 border-red-200 text-[#b00000] flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"></path></svg>
                                            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-[#b00000] text-white text-[9px] font-black flex items-center justify-center">{{ $i + 1 }}</span>
                                        </span>
                                        <p class="text-[12px] font-bold text-slate-900 mt-2 leading-tight">{{ $step }}</p>
                                        <p class="text-[11px] text-slate-500 leading-snug mt-0.5">{{ $hint }}</p>
                                    </li>
                                @endforeach
                            </ol>

                            <!-- Code -->
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2">Your referral code</p>
                                <div class="flex flex-col gap-2">
                                    <div class="flex-1 min-w-0 flex items-center rounded-xl border-2 border-dashed border-red-200 bg-red-50/50 pl-4 pr-1.5 py-1.5">
                                        <span class="flex-1 min-w-0 font-mono font-black text-xl tracking-[0.25em] text-[#b00000] truncate select-all">{{ $referralCode }}</span>
                                        <button type="button" @click="copy(@js($referralCode), 'code')"
                                            class="shrink-0 flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-[#b00000] text-white text-[12px] font-bold hover:bg-[#8a0000] transition"
                                            aria-label="Copy referral code">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            <span x-text="copied === 'code' ? 'Copied!' : 'Copy'">Copy</span>
                                        </button>
                                    </div>
                                    <button type="button" @click="copy(@js($referralLink), 'link')"
                                        class="shrink-0 flex items-center justify-center gap-1.5 px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-[13px] font-bold hover:border-red-200 hover:text-[#b00000] transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        <span x-text="copied === 'link' ? 'Link copied!' : 'Copy link'">Copy link</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Share -->
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-2.5">Share via</p>
                                <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                                    @php
                                        $shareLinks = [
                                            ['WhatsApp', 'https://wa.me/?text='.rawurlencode($shareText), 'bg-[#25D366]', 'M17.47 14.38c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.79-1.47-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.6-.92-2.2-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.21 3.07.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.7.63.71.22 1.36.19 1.87.12.57-.09 1.75-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35zM12.04 21.5h-.01a9.4 9.4 0 01-4.8-1.31l-.34-.2-3.57.93.95-3.48-.22-.36a9.43 9.43 0 01-1.45-5.03c0-5.2 4.24-9.44 9.45-9.44 2.52 0 4.9.99 6.68 2.77a9.38 9.38 0 012.76 6.68c0 5.2-4.24 9.44-9.45 9.44zm8.04-17.48A11.3 11.3 0 0012.04.7C5.77.7.67 5.8.67 12.06c0 2 .52 3.96 1.52 5.68L.57 23.3l5.7-1.5a11.33 11.33 0 005.76 1.47h.01c6.26 0 11.36-5.1 11.36-11.37 0-3.03-1.18-5.89-3.32-8.03z', true],
                                            ['SMS', 'sms:?&body='.rawurlencode($shareText), 'bg-[#0284c7]', 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', false],
                                            ['Telegram', 'https://t.me/share/url?url='.rawurlencode($referralLink).'&text='.rawurlencode("Join me on BeAurex! Use my referral code {$referralCode}."), 'bg-[#229ED9]', 'M9.04 15.6l-.38 5.3c.54 0 .78-.23 1.06-.51l2.55-2.44 5.28 3.87c.97.53 1.65.25 1.91-.89l3.47-16.24c.31-1.43-.52-1.99-1.46-1.64L1.07 9.83c-1.39.54-1.37 1.32-.24 1.67l5.2 1.62 12.07-7.6c.57-.37 1.09-.17.66.21z', true],
                                            ['Facebook', 'https://www.facebook.com/sharer/sharer.php?u='.rawurlencode($referralLink), 'bg-[#1877F2]', 'M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.9h2.54V9.85c0-2.52 1.49-3.91 3.78-3.91 1.1 0 2.24.2 2.24.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.88h2.78l-.44 2.9h-2.34V22c4.78-.79 8.43-4.94 8.43-9.94z', true],
                                            ['Email', 'mailto:?subject='.rawurlencode('Join me on BeAurex').'&body='.rawurlencode($shareText), 'bg-slate-700', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', false],
                                        ];
                                    @endphp
                                    @foreach ($shareLinks as [$name, $href, $bg, $path, $filled])
                                        <a href="{{ $href }}" @if (str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif
                                            class="group flex flex-col items-center gap-1.5 rounded-xl border border-slate-100 py-2.5 hover:border-slate-200 hover:bg-slate-50 transition">
                                            <span class="w-10 h-10 rounded-full {{ $bg }} text-white flex items-center justify-center shadow-sm group-hover:scale-105 transition">
                                                @if ($filled)
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="{{ $path }}"/></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/></svg>
                                                @endif
                                            </span>
                                            <span class="text-[11px] font-bold text-slate-700">{{ $name }}</span>
                                        </a>
                                    @endforeach
                                    <button type="button" @click="share('Join me on BeAurex', @js($shareText), 'share')"
                                        class="group flex flex-col items-center gap-1.5 rounded-xl border border-slate-100 py-2.5 hover:border-slate-200 hover:bg-slate-50 transition">
                                        <span class="w-10 h-10 rounded-full bg-[#b00000] text-white flex items-center justify-center shadow-sm group-hover:scale-105 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.68 13.34a3 3 0 100-2.68m0 2.68l6.63 3.32m-6.63-6l6.63-3.32m0 0a3 3 0 105.37-2.68 3 3 0 00-5.37 2.68zm0 9.32a3 3 0 105.37 2.68 3 3 0 00-5.37-2.68z"></path></svg>
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-700" x-text="copied === 'share' ? 'Copied!' : 'More'">More</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Coupon Codes -->
                <section aria-labelledby="coupons-title">
                    <h3 id="coupons-title" class="text-[15px] font-black text-slate-900 mb-3">Coupon Codes</h3>
                    @forelse ($coupons as $coupon)
                        @php
                            $value = rtrim(rtrim(number_format((float) $coupon->discount_value, 2, '.', ''), '0'), '.');
                            $off = $coupon->discount_type === 'Percentage' ? "{$value}% OFF" : "₹{$value} OFF";
                            $couponText = "Use coupon code {$coupon->code} on BeAurex to get {$off}" .
                                ($coupon->min_order_value ? ' on orders above ₹' . rtrim(rtrim(number_format((float) $coupon->min_order_value, 2, '.', ''), '0'), '.') : '') .
                                ($coupon->expires_at ? ' (valid till ' . $coupon->expires_at->format('d M Y') . ')' : '') . '.';
                        @endphp
                        <div class="rounded-2xl border border-dashed border-red-200 bg-red-50/40 p-4 mb-3 last:mb-0">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <span class="inline-block px-2 py-0.5 rounded-md bg-[#b00000] text-white text-[11px] font-black tracking-wide">{{ $off }}</span>
                                    <p class="font-mono font-black text-[17px] text-slate-900 tracking-wider mt-2 break-all">{{ $coupon->code }}</p>
                                    @if ($coupon->description)
                                        <p class="text-[12px] text-slate-600 mt-1">{{ $coupon->description }}</p>
                                    @endif
                                    <p class="text-[11px] text-slate-500 font-semibold mt-1">
                                        @if ($coupon->min_order_value)
                                            Min. order ₹{{ rtrim(rtrim(number_format((float) $coupon->min_order_value, 2, '.', ''), '0'), '.') }} ·
                                        @endif
                                        {{ $coupon->expires_at ? 'Valid till ' . $coupon->expires_at->format('d M Y') : 'No expiry' }}
                                    </p>
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" @click="copy(@js($coupon->code), 'coupon-{{ $coupon->id }}')"
                                        class="px-3.5 py-2 rounded-xl bg-white border border-red-200 text-[#b00000] text-[12px] font-bold hover:bg-red-50 transition">
                                        <span x-text="copied === 'coupon-{{ $coupon->id }}' ? 'Copied!' : 'Copy'">Copy</span>
                                    </button>
                                    <button type="button" @click="share('BeAurex coupon', @js($couponText), 'coupon-share-{{ $coupon->id }}')"
                                        class="px-3.5 py-2 rounded-xl bg-[#b00000] text-white text-[12px] font-bold hover:bg-[#8a0000] transition">
                                        <span x-text="copied === 'coupon-share-{{ $coupon->id }}' ? 'Copied!' : 'Share'">Share</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                            <div class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.41.59l7 7a2 2 0 010 2.82l-7 7a2 2 0 01-2.82 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path></svg>
                            </div>
                            <p class="text-[13px] font-bold text-slate-700">No coupon codes right now</p>
                            <p class="text-[12px] text-slate-500 mt-1">Active coupons will appear here for you to share with friends.</p>
                        </div>
                    @endforelse
                </section>
            </div>

            <script>
                // Copy to clipboard (with a fallback for non-HTTPS pages) and the phone's share sheet.
                function shareTools() {
                    return {
                        copied: null,
                        copy(text, key) {
                            const done = () => { this.copied = key; setTimeout(() => { if (this.copied === key) this.copied = null; }, 1800); };
                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(text).then(done, () => this.fallbackCopy(text, done));
                            } else {
                                this.fallbackCopy(text, done);
                            }
                        },
                        fallbackCopy(text, done) {
                            const el = document.createElement('textarea');
                            el.value = text;
                            el.setAttribute('readonly', '');
                            el.style.position = 'fixed';
                            el.style.opacity = '0';
                            document.body.appendChild(el);
                            el.select();
                            try { document.execCommand('copy'); done(); } catch (e) {}
                            el.remove();
                        },
                        share(title, text, key) {
                            if (navigator.share) {
                                navigator.share({ title, text }).catch(() => {});
                            } else {
                                this.copy(text, key);
                            }
                        },
                    };
                }
            </script>

            {{-- Auto approval and plan: stacked on small screens, side by side on wide ones --}}
            <div class="mt-10 border-t border-slate-100 pt-8 grid grid-cols-1 xl:grid-cols-2 gap-x-8 items-start">
            <div x-data="{
                confirmModalOpen: false,
                pendingAction: null,
                aurexCoin: {{ $business && $business->auto_approval ? 'true' : 'false' }},
                rewardApprove: {{ $business && $business->auto_reward_approval ? 'true' : 'false' }},
                promptConfirm(setting) {
                    this.pendingAction = setting;
                    this.confirmModalOpen = true;
                },
                confirm() {
                    if (this.pendingAction === 'aurex') {
                        this.aurexCoin = !this.aurexCoin;
                    } else if (this.pendingAction === 'reward') {
                        this.rewardApprove = !this.rewardApprove;
                    }
                    this.confirmModalOpen = false;
                },
                cancel() {
                    this.confirmModalOpen = false;
                    this.pendingAction = null;
                }
            }">
                <h3 class="text-[15px] font-black text-slate-900 mb-3">Auto Approval
                    Settings</h3>
                <form action="{{ route('merchant.profile.auto-approve') }}" method="POST"
                    class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm">
                    @csrf

                    <!-- Aurex Coin Approval -->
                    <div class="flex items-center justify-between gap-4 mb-4">
                        <div>
                            <h4 class="font-bold text-[13px] text-slate-900">Aurex Coin Auto Approval</h4>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Automatically approve aurex coin
                                requests</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="auto_approval" class="sr-only peer" :checked="aurexCoin"
                                @click.prevent="promptConfirm('aurex')">
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22c55e]">
                            </div>
                        </label>
                    </div>

                    <div class="border-t border-slate-100 my-4"></div>

                    <!-- Reward Approval -->
                    <div class="flex items-center justify-between gap-4 mb-5">
                        <div>
                            <h4 class="font-bold text-[13px] text-slate-900">Reward Auto Approval</h4>
                            <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Automatically approve reward
                                requests from customers</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input type="checkbox" name="auto_reward_approval" class="sr-only peer"
                                :checked="rewardApprove" @click.prevent="promptConfirm('reward')">
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22c55e]">
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2.5 bg-[#b00000] text-white text-[13px] font-bold rounded-xl hover:bg-[#8a0000] transition shadow-sm">
                            Save Settings
                        </button>
                    </div>
                </form>

                <!-- Confirmation Modal -->
                <div x-show="confirmModalOpen" style="display: none;"
                    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div @click.away="cancel()"
                        class="bg-white rounded-3xl p-7 w-[90%] max-w-sm shadow-[0_10px_40px_rgba(0,0,0,0.2)] text-center border border-slate-100"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

                        <div
                            class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-amber-100/50">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-xl font-black text-[#0f172a] mb-2">Confirm Action</h3>
                        <p class="text-sm font-semibold text-[#475569] mb-6">Are you sure you want to change this
                            auto-approval setting?</p>

                        <div class="flex space-x-3">
                            <button @click="cancel()" type="button"
                                class="flex-1 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition shadow-sm text-sm">Cancel</button>
                            <button @click="confirm()" type="button"
                                class="flex-1 py-3 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition shadow-sm text-sm">Yes,
                                Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
            <h3 class="text-[15px] font-black text-slate-900 mb-3">Active Plan</h3>
            <div
                class="bg-[#f0f9f3] rounded-2xl border border-[#dcfce7] p-4 flex items-center justify-between mb-8 shadow-sm">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#22c55e] text-white rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[13px] text-slate-900 leading-tight">Pro Plan</h4>
                        <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Valid until
                            {{ now()->addYear()->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[15px] font-black text-slate-900 leading-tight">₹999 <span
                            class="text-[10px] font-semibold text-slate-500 font-sans">/ year</span></div>
                </div>
            </div>
            </div>
            </div>

            <a href="#" role="button" @click.prevent="logoutModal = true"
                class="w-full md:max-w-xs md:ml-auto bg-red-50/50 border border-red-100 text-[#b00000] font-bold py-3.5 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                <span>Logout</span>
            </a>

        </div>
    </div>

    @include('partials.profile-scripts')
@endsection
