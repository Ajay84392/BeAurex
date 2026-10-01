@extends('layouts.merchant')

@section('title', 'Merchant Profile')

@section('content')
@php
    $user = auth()->user();
    $businessName = $business->name ?? $user->name;
    $nameParts = explode(' ', $businessName ?: 'B');
    $initials = strtoupper(substr($nameParts[0], 0, 1)).(count($nameParts) > 1 ? strtoupper(substr($nameParts[1], 0, 1)) : '');
    $label = 'block text-[13px] font-bold text-slate-700 mb-2';
    $input = 'w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-300 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition';
    $invalid = '!border-red-400';
@endphp
<div class="bg-white min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-10">

    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
        <div class="flex justify-between items-center mb-6 max-w-4xl mx-auto">
            <a href="/merchant" class="text-white hover:text-red-200 transition md:hidden">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl md:text-2xl font-black tracking-tight mx-auto md:mx-0">Profile</h1>
            <div class="w-6 h-6 md:hidden"></div> <!-- Spacer for center alignment -->
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-white rounded-t-[30px] md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] max-w-4xl mx-auto md:border md:border-slate-100 pb-10 pt-8">

        @include('partials.profile-alerts')

        <form action="{{ route('merchant.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Business Details -->
            <section>
                <h2 class="text-[15px] font-black text-slate-900 mb-5">Business Details</h2>

                <div class="flex flex-col md:flex-row gap-6 mb-5">
                    <!-- Logo -->
                    <div class="flex-shrink-0">
                        <span class="{{ $label }}">Business Logo</span>
                        <div class="relative inline-block group">
                            <div id="logoPreview" class="w-20 h-20 rounded-full border border-slate-200 shadow-sm overflow-hidden bg-slate-50 flex items-center justify-center">
                                @if($business && $business->logo)
                                    <img src="{{ \App\Support\Media::url($business->logo) }}" alt="Business logo" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-red-50 text-[#b00000] flex items-center justify-center text-3xl font-black">{{ $initials }}</div>
                                @endif
                            </div>
                            <label class="absolute bottom-0 right-0 w-7 h-7 bg-[#b00000] text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm cursor-pointer hover:bg-[#8a0000] transition" title="Change logo">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <input type="file" name="logo" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp" data-preview="logoPreview">
                            </label>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">JPG, PNG, GIF or WEBP. Max 2 MB.</p>
                        <x-auth.error name="logo" />
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="business_name" class="{{ $label }}">Business Name <span class="text-[#EF4444]">*</span></label>
                            <input type="text" id="business_name" name="business_name" value="{{ old('business_name', $businessName) }}" required minlength="2" maxlength="255" placeholder="e.g. Ka-feen Café" autocomplete="organization"
                                class="{{ $input }} @error('business_name') {{ $invalid }} @enderror">
                            <x-auth.error name="business_name" />
                        </div>
                        <div>
                            <label for="category" class="{{ $label }}">Business Category</label>
                            <input type="text" id="category" name="category" value="{{ old('category', $business->category ?? '') }}" maxlength="100" placeholder="e.g. Café"
                                class="{{ $input }} @error('category') {{ $invalid }} @enderror">
                            <x-auth.error name="category" />
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="{{ $label }}">About the Business</label>
                            <textarea id="description" name="description" rows="2" maxlength="1000" placeholder="A short description customers will see"
                                class="{{ $input }} resize-none @error('description') {{ $invalid }} @enderror">{{ old('description', $business->description ?? '') }}</textarea>
                            <x-auth.error name="description" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="md:col-span-3">
                        <label for="address" class="{{ $label }}">Business Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $business->address ?? '') }}" maxlength="255" placeholder="Shop no., street, area" autocomplete="street-address"
                            class="{{ $input }} @error('address') {{ $invalid }} @enderror">
                        <x-auth.error name="address" />
                    </div>
                    <div>
                        <label for="city" class="{{ $label }}">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $business->city ?? '') }}" maxlength="100" placeholder="e.g. New Delhi" autocomplete="address-level2"
                            class="{{ $input }} @error('city') {{ $invalid }} @enderror">
                        <x-auth.error name="city" />
                    </div>
                    <div>
                        <label for="state" class="{{ $label }}">State</label>
                        <input type="text" id="state" name="state" value="{{ old('state', $business->state ?? '') }}" maxlength="100" placeholder="e.g. Delhi" autocomplete="address-level1"
                            class="{{ $input }} @error('state') {{ $invalid }} @enderror">
                        <x-auth.error name="state" />
                    </div>
                    <div>
                        <label for="pincode" class="{{ $label }}">Pincode</label>
                        <input type="text" id="pincode" name="pincode" value="{{ old('pincode', $business->pincode ?? '') }}" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" title="6-digit pincode" placeholder="110001" autocomplete="postal-code"
                            oninput="this.value=this.value.replace(/\D/g,'').slice(0,6)"
                            class="{{ $input }} @error('pincode') {{ $invalid }} @enderror">
                        <x-auth.error name="pincode" />
                    </div>
                </div>
            </section>

            <!-- Owner & Contact -->
            <section class="border-t border-slate-100 pt-8">
                <h2 class="text-[15px] font-black text-slate-900 mb-5">Owner &amp; Contact</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label for="name" class="{{ $label }}">Owner Name <span class="text-[#EF4444]">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required minlength="2" maxlength="100" autocomplete="name" placeholder="John Doe"
                            class="{{ $input }} @error('name') {{ $invalid }} @enderror">
                        <x-auth.error name="name" />
                    </div>
                    <div>
                        <label for="phone" class="{{ $label }}">Contact Number <span class="text-[#EF4444]">*</span></label>
                        <div class="flex">
                            <span class="bg-slate-50 border border-slate-200 border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', \App\Rules\MobileNumber::normalize($user->phone ?: ($business->phone ?? null))) }}" required inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" title="10-digit mobile number starting with 6-9" placeholder="9876543210" autocomplete="tel-national" data-mobile
                                class="{{ $input }} rounded-l-none @error('phone') {{ $invalid }} @enderror">
                        </div>
                        <x-auth.error name="phone" />
                    </div>
                    <div>
                        <label for="email" class="{{ $label }}">Email Address <span class="text-[#EF4444]">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}" required maxlength="255" autocomplete="email"
                            class="{{ $input }} @error('email') {{ $invalid }} @enderror">
                        <p class="text-[11px] text-slate-400 mt-1.5">Used to log in. Changing it needs your current password.</p>
                        <x-auth.error name="email" />
                    </div>
                </div>
            </section>

            <!-- Preferences -->
            @include('partials.profile-preferences', ['user' => $user, 'label' => $label, 'input' => $input, 'invalid' => $invalid])

            <!-- Change Password -->
            @include('partials.profile-password', ['label' => $label, 'input' => $input])

            <div class="flex flex-col-reverse md:flex-row md:justify-end gap-3">
                <a href="{{ route('merchant.profile') }}" class="w-full md:w-auto px-8 py-3.5 bg-white border border-red-200 text-[#b00000] text-sm font-bold rounded-xl hover:bg-red-50 transition text-center">Cancel</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition shadow-md">Save Changes</button>
            </div>
        </form>

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
            <h3 class="text-[15px] font-black text-slate-900 mt-10 mb-3 border-t border-slate-100 pt-8">Auto Approval Settings</h3>
            <form action="{{ route('merchant.profile.auto-approve') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-5 mb-8 shadow-sm">
                @csrf

                <!-- Aurex Coin Approval -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="font-bold text-[13px] text-slate-900">Aurex Coin Auto Approval</h4>
                        <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Automatically approve aurex coin requests</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_approval" class="sr-only peer" :checked="aurexCoin" @click.prevent="promptConfirm('aurex')">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22c55e]"></div>
                    </label>
                </div>

                <div class="border-t border-slate-100 my-4"></div>

                <!-- Reward Approval -->
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h4 class="font-bold text-[13px] text-slate-900">Reward Auto Approval</h4>
                        <p class="text-[11px] text-slate-500 font-semibold mt-0.5">Automatically approve reward requests from customers</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="auto_reward_approval" class="sr-only peer" :checked="rewardApprove" @click.prevent="promptConfirm('reward')">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#22c55e]"></div>
                    </label>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#b00000] text-white text-[13px] font-bold rounded-xl hover:bg-[#8a0000] transition shadow-sm">
                        Save Settings
                    </button>
                </div>
            </form>

            <!-- Confirmation Modal -->
            <div x-show="confirmModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <div @click.away="cancel()" class="bg-white rounded-3xl p-7 w-[90%] max-w-sm shadow-[0_10px_40px_rgba(0,0,0,0.2)] text-center border border-slate-100"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90">
                    
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-amber-100/50">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-black text-[#0f172a] mb-2">Confirm Action</h3>
                    <p class="text-sm font-semibold text-[#475569] mb-6">Are you sure you want to change this auto-approval setting?</p>
                    
                    <div class="flex space-x-3">
                        <button @click="cancel()" type="button" class="flex-1 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition shadow-sm text-sm">Cancel</button>
                        <button @click="confirm()" type="button" class="flex-1 py-3 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition shadow-sm text-sm">Yes, Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-[13px] font-black text-slate-900 mb-3">Active Plan</h3>
        <div class="bg-[#f0f9f3] rounded-2xl border border-[#dcfce7] p-4 flex items-center justify-between mb-8 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-[#22c55e] text-white rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-[13px] text-slate-900 leading-tight">Pro Plan</h4>
                    <p class="text-[10px] text-slate-500 font-semibold mt-0.5">Valid until {{ now()->addYear()->format('d M Y') }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-[15px] font-black text-slate-900 leading-tight">₹999 <span class="text-[10px] font-semibold text-slate-500 font-sans">/ year</span></div>
            </div>
        </div>

        <a href="#" role="button" @click.prevent="logoutModal = true" class="w-full bg-red-50/50 border border-red-100 text-[#b00000] font-bold py-3.5 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Logout</span>
        </a>

    </div>
</div>

@include('partials.profile-scripts')
@endsection
