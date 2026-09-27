@extends('layouts.admin')

@section('title', 'Manage Offers')

@section('content')
<div class="flex-1 overflow-auto p-6 md:p-10">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Offers</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Offers</span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <div class="overflow-x-auto w-full"><table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Merchant</th>
                        <th class="px-6 py-4">Aurex Coins Required</th>
                        <th class="px-6 py-4">Expiry</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($offers as $offer)
                    <tr class="hover:bg-[#f1f5f9]/50 transition">
                        <td class="px-6 py-4 font-semibold text-[#0f172a]">
                            {{ $offer->title }}
                        </td>
                        <td class="px-6 py-4">
                            {{ optional($offer->business)->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $offer->aurex_coins }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $offer->expiry }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('offers.edit', $offer->id) }}" class="text-blue-500 hover:text-blue-700 transition mr-3 inline-block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[#EF4444] hover:text-[#8a0000] transition" onclick="return confirm('Delete this offer?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-[#475569]">
                            No offers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
        <div class="px-6 py-4 border-t border-[#e2e8f0]">
            {{ $offers->links() }}
        </div>
    </div>
</div>
@endsection




