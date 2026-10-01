@extends('layouts.admin')

@section('title', 'Edit Offer')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Edit Offer</h1>
    </div>

    <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm p-6 max-w-2xl">
        <form action="{{ route('offers.update', $offer->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title', $offer->title) }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                @error('title') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">{{ old('description', $offer->description) }}</textarea>
                @error('description') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Aurex Coins Required</label>
                <input type="number" name="aurex_coins" value="{{ old('aurex_coins', $offer->aurex_coins) }}" min="1" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                @error('aurex_coins') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Expiry</label>
                <input type="text" name="expiry" value="{{ old('expiry', $offer->expiry) }}" class="w-full border border-[#e2e8f0] rounded-xl px-4 py-2 focus:ring-red-500 focus:border-red-500">
                @error('expiry') <span class="text-[#EF4444] text-xs">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-[#b00000] text-white px-6 py-2 rounded-xl font-bold hover:bg-[#8a0000] transition">Update Offer</button>
        </form>
    </div>
</div>
@endsection




