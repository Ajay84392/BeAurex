@extends('layouts.admin')

@section('title', 'Contact Management')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Contact Management</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/settings" class="hover:text-[#b00000] transition">Settings</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Contact Management</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 p-4 rounded-xl text-sm font-bold border border-emerald-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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

    <form action="{{ url('/admin/settings') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] max-w-5xl">
        @csrf
        <input type="hidden" name="group" value="contact">

        <div class="p-6 md:p-8">
            <div class="mb-8 border-b border-slate-100 pb-4">
                <h2 class="text-lg font-bold text-[#0f172a] mb-1">Contact Information</h2>
                <p class="text-sm text-[#475569]">Update your support contact details and business address.</p>
            </div>
            
            <div class="space-y-6">
                
                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-36 text-sm font-bold text-[#0f172a] shrink-0">Support Email <span class="text-[#EF4444]">*</span></label>
                        <input type="email" name="support_email" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['support_email'] ?? 'support@loyalqr.com' }}" required>
                    </div>
                    <div class="hidden md:block"></div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-36 text-sm font-bold text-[#0f172a] shrink-0">Support Phone <span class="text-[#EF4444]">*</span></label>
                        <input type="text" name="support_phone" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['support_phone'] ?? '+91 98765 43210' }}" required>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-32 text-sm font-bold text-[#0f172a] shrink-0">State <span class="text-[#EF4444]">*</span></label>
                        <input type="text" name="state" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['state'] ?? 'Karnataka' }}" required>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-36 text-sm font-bold text-[#0f172a] shrink-0">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['alternate_phone'] ?? '+91 91234 56789' }}">
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-32 text-sm font-bold text-[#0f172a] shrink-0">Country <span class="text-[#EF4444]">*</span></label>
                        <select name="country" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] appearance-none bg-white" required style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%208l5%205%205-5%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222%22%20fill%3D%22none%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px center;">
                            <option value="India" {{ ($settings['country'] ?? '') == 'India' ? 'selected' : '' }}>India</option>
                            <option value="United States" {{ ($settings['country'] ?? '') == 'United States' ? 'selected' : '' }}>United States</option>
                            <option value="United Kingdom" {{ ($settings['country'] ?? '') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                            <option value="Australia" {{ ($settings['country'] ?? '') == 'Australia' ? 'selected' : '' }}>Australia</option>
                            <option value="Canada" {{ ($settings['country'] ?? '') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        </select>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-36 text-sm font-bold text-[#0f172a] shrink-0">Address <span class="text-[#EF4444]">*</span></label>
                        <input type="text" name="address" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['address'] ?? '12, Business Park, 3rd Floor' }}" required>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-32 text-sm font-bold text-[#0f172a] shrink-0">Postal Code <span class="text-[#EF4444]">*</span></label>
                        <input type="text" name="postal_code" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['postal_code'] ?? '560038' }}" required>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-4">
                        <label class="w-full md:w-36 text-sm font-bold text-[#0f172a] shrink-0">City <span class="text-[#EF4444]">*</span></label>
                        <input type="text" name="city" class="flex-1 w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['city'] ?? 'Bangalore' }}" required>
                    </div>
                    <div class="hidden md:block"></div>
                </div>
                
            </div>
        </div>
        
        <div class="px-6 md:px-8 py-5 border-t border-slate-100 bg-[#f1f5f9]/50 rounded-b-2xl flex items-center justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#b00000] hover:bg-[#8a0000] transition shadow-sm flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span>Update Contact Information</span>
            </button>
        </div>
    </form>
</div>
@endsection




