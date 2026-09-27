@extends('layouts.merchant')

@section('title', 'Merchant Profile')

@section('content')
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
    <div class="bg-white md:bg-white rounded-t-[30px] md:rounded-3xl -mt-8 md:-mt-12 relative z-20 px-5 md:px-8 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] md:shadow-lg min-h-[500px] max-w-4xl mx-auto md:border md:border-slate-100 pb-10 pt-8">
        
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center">
                <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('merchant.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Top: Logo & Business Name -->
            <div class="flex flex-col md:flex-row md:items-center mb-8 gap-5">
                @php
                    $businessName = $business ? $business->name : auth()->user()->name;
                    $nameParts = explode(' ', $businessName ?? 'B');
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                    $phone = $business ? $business->phone : auth()->user()->phone;
                    $email = $business ? $business->email : auth()->user()->email;
                @endphp
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <h3 class="text-[13px] font-black text-slate-900 mb-3 text-left">Business Logo</h3>
                    <div class="relative inline-block group">
                         <!-- Avatar image -->
                         <div class="w-20 h-20 rounded-full border border-slate-200 shadow-sm overflow-hidden bg-slate-50 flex items-center justify-center">
                              @if($business && $business->logo)
                                  <img src="{{ Storage::url($business->logo) }}" class="w-full h-full object-cover group-hover:opacity-80 transition">
                              @else
                                  <div class="w-full h-full bg-red-50 text-[#b00000] flex items-center justify-center text-3xl font-black group-hover:opacity-80 transition">{{ $initials }}</div>
                              @endif
                         </div>
                         <!-- Camera icon -->
                         <label class="absolute bottom-0 right-0 w-7 h-7 bg-[#b00000] text-white rounded-full flex items-center justify-center border-2 border-white shadow-sm cursor-pointer hover:bg-[#8a0000] transition">
                             <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                             <input type="file" name="logo" class="hidden" accept="image/*">
                         </label>
                    </div>
                </div>

                <!-- Business Name -->
                <div class="flex-1 w-full md:mt-7">
                     <label class="text-[13px] font-black text-slate-900 mb-3 block">Business Name</label>
                     <div class="bg-white rounded-xl border border-slate-200 px-4 py-3.5 shadow-sm">
                          <input type="text" name="name" value="{{ old('name', $businessName) }}" class="w-full bg-transparent text-sm font-bold text-slate-900 border-none outline-none p-0 focus:ring-0 placeholder-slate-400" required placeholder="Enter Business Name">
                     </div>
                </div>
            </div>

            <!-- Inputs List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <!-- Business Category -->
                <div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center space-x-4 shadow-sm group hover:border-red-200 transition">
                     <div class="w-12 h-12 bg-red-50 text-[#b00000] rounded-xl flex items-center justify-center shrink-0">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                     </div>
                     <div class="flex-1 min-w-0">
                         <label class="text-[11px] font-bold text-slate-500 mb-0.5 block">Business Category</label>
                         <input type="text" name="category" value="{{ old('category', $business->category ?? '') }}" class="w-full bg-transparent text-[13px] font-bold text-slate-900 border-none outline-none p-0 focus:ring-0 placeholder-slate-300" placeholder="e.g. Café">
                     </div>
                     <div class="text-slate-300 shrink-0 pr-1 group-focus-within:text-[#b00000] transition">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </div>
                </div>

                <!-- Contact Number -->
                <div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center space-x-4 shadow-sm group hover:border-purple-200 transition">
                     <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                     </div>
                     <div class="flex-1 min-w-0">
                         <label class="text-[11px] font-bold text-slate-500 mb-0.5 block">Contact Number</label>
                         <input type="tel" name="phone" value="{{ old('phone', $phone) }}" class="w-full bg-transparent text-[13px] font-bold text-slate-900 border-none outline-none p-0 focus:ring-0 placeholder-slate-300">
                     </div>
                     <div class="text-slate-300 shrink-0 pr-1 group-focus-within:text-purple-600 transition">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </div>
                </div>

                <!-- Email Address -->
                <div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center space-x-4 shadow-sm group hover:border-blue-200 transition">
                     <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center shrink-0">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                     </div>
                     <div class="flex-1 min-w-0">
                         <label class="text-[11px] font-bold text-slate-500 mb-0.5 block">Email Address</label>
                         <input type="email" name="email" value="{{ old('email', $email) }}" class="w-full bg-transparent text-[13px] font-bold text-slate-900 border-none outline-none p-0 focus:ring-0 placeholder-slate-300" required>
                     </div>
                     <div class="text-slate-300 shrink-0 pr-1 group-focus-within:text-blue-500 transition">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </div>
                </div>

                <!-- Business Address -->
                <div class="bg-white rounded-2xl border border-slate-200 p-3 flex items-center space-x-4 shadow-sm group hover:border-red-200 transition md:col-span-2">
                     <div class="w-12 h-12 bg-red-50 text-[#b00000] rounded-xl flex items-center justify-center shrink-0">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                     </div>
                     <div class="flex-1 min-w-0">
                         <label class="text-[11px] font-bold text-slate-500 mb-0.5 block">Business Address</label>
                         <input type="text" name="address" value="{{ old('address', $business->address ?? '') }}" class="w-full bg-transparent text-[13px] font-bold text-slate-900 border-none outline-none p-0 focus:ring-0 placeholder-slate-300">
                     </div>
                     <div class="text-slate-300 shrink-0 pr-1 group-focus-within:text-[#b00000] transition">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                     </div>
                </div>
            </div>
            
            <div class="flex justify-end mb-8">
                <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition shadow-md">
                    Save Changes
                </button>
            </div>
        </form>

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

        <a href="{{ route('merchant.logout') }}" class="w-full bg-red-50/50 border border-red-100 text-[#b00000] font-bold py-3.5 rounded-2xl flex items-center justify-center space-x-2 hover:bg-red-50 transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Logout</span>
        </a>

    </div>
</div>

<script>
    // Logo upload preview logic
    document.querySelector('input[name="logo"]').addEventListener('change', function(e) {
        if(e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.querySelector('.relative.inline-block.group > div');
                const existingImg = container.querySelector('img');
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else {
                    const initialsDiv = container.querySelector('div');
                    if (initialsDiv) {
                        const newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.className = 'w-full h-full object-cover group-hover:opacity-80 transition';
                        container.insertBefore(newImg, initialsDiv);
                        initialsDiv.remove();
                    }
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection
