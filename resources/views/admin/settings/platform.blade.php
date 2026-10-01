@extends('layouts.admin')

@section('title', 'Platform Management')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Platform Management</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/settings" class="hover:text-[#b00000] transition">Settings</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Platform Management</span>
        </div>
    </div>

    <form action="{{ url('/admin/settings') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-6 md:p-8 max-w-4xl">
        @csrf
        <input type="hidden" name="group" value="platform">
        
        <div class="mb-8 border-b border-slate-100 pb-4">
            <h2 class="text-lg font-bold text-[#0f172a]">Platform Information</h2>
            <p class="text-sm text-[#475569]">Update your platform details and general information.</p>
        </div>
        
        <div class="space-y-6">
            
            <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-8">
                <label class="w-full md:w-1/4 text-sm font-bold text-slate-700">Platform Name <span class="text-[#EF4444]">*</span></label>
                <div class="flex-1">
                    <input type="text" name="platform_name" class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['platform_name'] ?? 'BeAurex' }}">
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-8">
                <label class="w-full md:w-1/4 text-sm font-bold text-slate-700">Website URL <span class="text-[#EF4444]">*</span></label>
                <div class="flex-1">
                    <input type="url" name="website_url" class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['website_url'] ?? 'https://www.beaurex.com' }}">
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-[#b00000] hover:bg-[#8a0000] transition shadow-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Update Platform Information</span>
                </button>
            </div>
            
        </div>
    </form>
</div>
@endsection




