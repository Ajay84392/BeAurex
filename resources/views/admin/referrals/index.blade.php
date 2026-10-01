@extends('layouts.admin')

@section('title', 'Referrals')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Referrals</h1>
            <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
                <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                <span class="text-[#0f172a]">Referrals</span>
            </div>
        </div>
        <a href="{{ url('/admin/referrals/create') }}" class="px-4 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            <span>Add Referral</span>
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters -->
    <form method="GET" action="{{ url('/admin/referrals') }}" class="mb-6 flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code, email or referrer..." class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-[#0f172a]">
        </div>
        <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700 bg-white">
            <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Status</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Expired" {{ request('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition">Search</button>
    </form>

    <div class="bg-white rounded-2xl border border-[#e2e8f0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <div class="overflow-x-auto w-full"><table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                        <th class="px-6 py-4">Code</th>
                        <th class="px-6 py-4">Referrer</th>
                        <th class="px-6 py-4">Referred Email</th>
                        <th class="px-6 py-4">Reward Coins</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Expires At</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($referrals as $referral)
                    <tr class="hover:bg-[#f1f5f9]/50 transition">
                        <td class="px-6 py-4 font-semibold text-[#0f172a]">{{ $referral->code }}</td>
                        <td class="px-6 py-4">{{ optional($referral->referrer)->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $referral->referred_email ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $referral->reward_coins }}</td>
                        <td class="px-6 py-4">
                            @if($referral->status === 'Completed')
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Completed</span>
                            @elseif($referral->status === 'Expired')
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-100 text-[#8a0000]">Expired</span>
                            @else
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold bg-yellow-100 text-yellow-700">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $referral->expires_at ? $referral->expires_at->format('d M Y') : '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('referrals.edit', $referral->id) }}" class="text-slate-500 hover:text-[#b00000] transition mr-3 inline-block">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('referrals.destroy', $referral->id) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[#EF4444] hover:text-[#8a0000] transition" onclick="return confirm('Delete this referral?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-[#475569]">
                            No referrals found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table></div>
        </div>
        <div class="px-6 py-4 border-t border-[#e2e8f0]">
            {{ $referrals->links() }}
        </div>
    </div>
</div>
@endsection





