@extends('layouts.customer')

@section('title', 'My Profile')

@section('content')
@php
    $user = auth()->user();
    $nameParts = explode(' ', $user->name ?? 'User');
    $initials = strtoupper(substr($nameParts[0], 0, 1)).(count($nameParts) > 1 ? strtoupper(substr($nameParts[1], 0, 1)) : '');
    $label = 'block text-[13px] font-bold text-slate-700 mb-2';
    $input = 'w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-300 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition';
    $invalid = '!border-red-400';
@endphp
<div class="bg-white min-h-[100dvh] md:min-h-0 relative pb-24 md:pb-10">

    <!-- Red Header Section -->
    <div class="bg-[#8a0000] px-6 pt-10 pb-20 md:pb-24 text-white relative md:rounded-3xl md:shadow-md">
        <div class="flex justify-between items-center mb-6 max-w-4xl mx-auto">
            <a href="/customer" class="text-white hover:text-red-200 transition md:hidden">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-xl md:text-2xl font-black tracking-tight mx-auto md:mx-0">Profile</h1>
            <div class="w-6 h-6 md:hidden"></div> <!-- Spacer for center alignment -->
        </div>
    </div>

    <!-- Main Content Wrapper (Overlapping the red header) -->
    <div class="bg-white rounded-t-[30px] md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] max-w-4xl mx-auto md:border md:border-slate-100 pb-10 pt-8">

        @include('partials.profile-alerts')

        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Personal Information -->
            <section>
                <h2 class="text-[15px] font-black text-slate-900 mb-5">Personal Information</h2>

                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Photo -->
                    <div class="flex-shrink-0">
                        <span class="{{ $label }}">Profile Photo</span>
                        <div class="relative inline-block group">
                            <div id="photoPreview" class="w-20 h-20 rounded-full border border-slate-200 shadow-sm overflow-hidden bg-slate-50 flex items-center justify-center">
                                @if($user->photo)
                                    <img src="{{ \App\Support\Media::url($user->photo) }}" alt="Profile photo" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-red-50 text-[#b00000] flex items-center justify-center text-3xl font-black">{{ $initials }}</div>
                                @endif
                            </div>
                            <label class="absolute bottom-0 right-0 w-7 h-7 bg-[#b00000] text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm cursor-pointer hover:bg-[#8a0000] transition" title="Change photo">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <input type="file" name="photo" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp" data-preview="photoPreview">
                            </label>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2">JPG, PNG, GIF or WEBP. Max 2 MB.</p>
                        <x-auth.error name="photo" />
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="name" class="{{ $label }}">Full Name <span class="text-[#EF4444]">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required minlength="2" maxlength="100" autocomplete="name" placeholder="Enter full name"
                                class="{{ $input }} @error('name') {{ $invalid }} @enderror">
                            <x-auth.error name="name" />
                        </div>

                        <div>
                            <label for="phone" class="{{ $label }}">Phone Number</label>
                            <div class="flex">
                                <span class="bg-slate-50 border border-slate-200 border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', \App\Rules\MobileNumber::normalize($user->phone)) }}" inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" title="10-digit mobile number starting with 6-9" placeholder="9876543210" autocomplete="tel-national" data-mobile
                                    class="{{ $input }} rounded-l-none @error('phone') {{ $invalid }} @enderror">
                            </div>
                            <x-auth.error name="phone" />
                        </div>

                        <div>
                            <label for="email" class="{{ $label }}">Email Address <span class="text-[#EF4444]">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}" required maxlength="255" autocomplete="email"
                                class="{{ $input }} @error('email') {{ $invalid }} @enderror">
                            <p class="text-[11px] text-slate-400 mt-1.5">Changing your email needs your current password.</p>
                            <x-auth.error name="email" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Preferences -->
            @include('partials.profile-preferences', ['user' => $user, 'label' => $label, 'input' => $input, 'invalid' => $invalid])

            <!-- Change Password -->
            @include('partials.profile-password', ['label' => $label, 'input' => $input])

            <div class="flex flex-col-reverse md:flex-row md:justify-end gap-3">
                <a href="{{ route('customer.profile') }}" class="w-full md:w-auto px-8 py-3.5 bg-white border border-red-200 text-[#b00000] text-sm font-bold rounded-xl hover:bg-red-50 transition text-center">Cancel</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition shadow-md">Save Changes</button>
            </div>
        </form>

        <a href="#" role="button" @click.prevent="logoutModal = true" class="mt-8 w-full bg-red-50/50 border border-red-100 text-[#b00000] font-bold py-3.5 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Logout</span>
        </a>

    </div>
</div>

@include('partials.profile-scripts')
@endsection
