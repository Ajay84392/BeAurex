@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('content')
@php
    $user = auth()->user();
    $label = 'block text-sm font-bold text-slate-700 mb-2';
    $input = 'w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] placeholder-slate-400 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition';
    $invalid = '!border-red-400';
@endphp
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Admin Profile</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Profile</span>
        </div>
    </div>

    @include('partials.profile-alerts')

    <form action="{{ url('/admin/profile') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] max-w-5xl">
        @csrf

        <div class="p-6 md:p-8 space-y-10">

            <!-- Personal Information -->
            <div>
                <h2 class="text-lg font-bold text-[#0f172a] mb-6">Personal Information</h2>

                <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-12">

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="{{ $label }}">Full Name <span class="text-[#EF4444]">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required minlength="2" maxlength="100" autocomplete="name"
                                class="{{ $input }} @error('name') {{ $invalid }} @enderror">
                            <x-auth.error name="name" />
                        </div>
                        <div>
                            <label class="{{ $label }}">Role</label>
                            <input type="text" value="Super Admin" disabled class="w-full bg-slate-100 border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-400 cursor-not-allowed">
                        </div>

                        <div>
                            <label for="email" class="{{ $label }}">Email Address <span class="text-[#EF4444]">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" data-original="{{ $user->email }}" required maxlength="255" autocomplete="email"
                                class="{{ $input }} @error('email') {{ $invalid }} @enderror">
                            <p class="text-[11px] text-[#475569] mt-1.5">Used to log in. Changing it needs your current password.</p>
                            <x-auth.error name="email" />
                        </div>
                        <div>
                            <label for="username" class="{{ $label }}">Username <span class="text-[#EF4444]">*</span></label>
                            <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required minlength="3" maxlength="50" pattern="[a-zA-Z0-9._\-]+" title="Letters, numbers, dots, dashes and underscores" placeholder="e.g. superadmin" autocomplete="username"
                                class="{{ $input }} @error('username') {{ $invalid }} @enderror">
                            <x-auth.error name="username" />
                        </div>

                        <div>
                            <label for="phone" class="{{ $label }}">Phone Number <span class="text-[#EF4444]">*</span></label>
                            <div class="flex">
                                <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 rounded-l-xl px-3.5 flex items-center font-bold text-slate-700 text-sm">+91</span>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone', \App\Rules\MobileNumber::normalize($user->phone)) }}" required inputmode="numeric" maxlength="10" pattern="[6-9][0-9]{9}" title="10-digit mobile number starting with 6-9" placeholder="9876543210" autocomplete="tel-national" data-mobile
                                    class="{{ $input }} rounded-l-none @error('phone') {{ $invalid }} @enderror">
                            </div>
                            <x-auth.error name="phone" />
                        </div>
                        <div>
                            <label class="{{ $label }}">Account Status</label>
                            <div class="h-[46px] flex items-center">
                                <span class="px-3 py-1.5 text-xs font-bold tracking-wider text-emerald-700 bg-emerald-100 rounded-md">Active</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0 flex flex-col items-center justify-start pt-4">
                        <label class="{{ $label }} w-full text-center !mb-4">Profile Picture</label>

                        <div id="adminPhotoPreview" class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-sm bg-[#f1f5f9] overflow-hidden mb-4 flex items-center justify-center shrink-0">
                            @if($user->photo)
                                <img src="{{ \App\Support\Media::url($user->photo) }}" alt="Profile" class="w-full h-full object-cover">
                            @else
                                <svg class="w-16 h-16 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            @endif
                        </div>

                        <input type="file" id="photoInput" name="photo" class="hidden" accept="image/jpeg,image/png,image/gif,image/webp" data-preview="adminPhotoPreview">
                        <button type="button" onclick="document.getElementById('photoInput').click()" class="px-4 py-2 border border-red-200 text-[#b00000] text-xs font-bold rounded-xl hover:bg-red-50 transition flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            <span>Change Photo</span>
                        </button>
                        <p class="text-[10px] text-slate-400 mt-2">JPG, PNG, GIF or WEBP. Max size 2MB.</p>
                        <x-auth.error name="photo" />
                    </div>

                </div>
            </div>

            <!-- Change Password -->
            @include('partials.profile-password', ['label' => $label, 'input' => $input])

            <!-- Preferences -->
            @include('partials.profile-preferences', ['user' => $user, 'label' => $label, 'input' => $input, 'invalid' => $invalid])

        </div>

        <div class="px-6 md:px-8 py-5 border-t border-slate-100 bg-[#f1f5f9]/50 rounded-b-2xl flex items-center justify-end space-x-3">
            <a href="{{ url('/admin/profile') }}" class="px-6 py-2.5 bg-white border border-red-200 text-[#b00000] text-sm font-bold rounded-xl hover:bg-red-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span>Update Profile</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    @include('partials.profile-scripts')
@endsection
