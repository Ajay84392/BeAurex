@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Create Coupon</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="{{ route('coupons.index') }}" class="hover:text-[#b00000] transition">Coupons</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Create</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-6 max-w-3xl">
        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500 uppercase">
                    @error('code') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                    <select name="is_active" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('is_active') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description (Optional)</label>
                <textarea name="description" rows="2" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">{{ old('description') }}</textarea>
                @error('description') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Discount Type</label>
                    <select name="discount_type" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                        <option value="Percentage" {{ old('discount_type') == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="Fixed" {{ old('discount_type') == 'Fixed' ? 'selected' : '' }}>Fixed Amount</option>
                    </select>
                    @error('discount_type') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Discount Value</label>
                    <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('discount_value') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Min Order Value (Optional)</label>
                    <input type="number" step="0.01" name="min_order_value" value="{{ old('min_order_value') }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('min_order_value') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Max Uses (Optional)</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses') }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('max_uses') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Starts At (Optional)</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at') ? \Carbon\Carbon::parse(old('starts_at'))->format('Y-m-d') : '' }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('starts_at') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Expires At (Optional)</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') ? \Carbon\Carbon::parse(old('expires_at'))->format('Y-m-d') : '' }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                    @error('expires_at') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="bg-[#b00000] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#8a0000] transition">Create Coupon</button>
            <a href="{{ route('coupons.index') }}" class="ml-4 text-[#475569] hover:text-slate-700 font-medium">Cancel</a>
        </form>
    </div>
</div>
@endsection




