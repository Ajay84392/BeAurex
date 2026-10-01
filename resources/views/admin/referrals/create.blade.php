@extends('layouts.admin')

@section('title', 'Create Referral')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Create Referral</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/referrals" class="hover:text-[#b00000] transition">Referrals</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Create</span>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ url('/admin/referrals') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-6 md:p-8 max-w-3xl">
        @csrf

        <div class="space-y-6">
            <!-- Referrer -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <label class="w-full md:w-1/3 text-sm font-bold text-slate-700">Referrer <span class="text-[#EF4444]">*</span></label>
                <div class="flex-1">
                    <select name="referrer_id" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] bg-white">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('referrer_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Referred Email -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <label class="w-full md:w-1/3 text-sm font-bold text-slate-700">Referred Email</label>
                <div class="flex-1">
                    <input type="email" name="referred_email" value="{{ old('referred_email') }}" placeholder="email@example.com" class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]">
                </div>
            </div>

            <!-- Reward Coins -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <label class="w-full md:w-1/3 text-sm font-bold text-slate-700">Reward Coins <span class="text-[#EF4444]">*</span></label>
                <div class="flex-1">
                    <input type="number" name="reward_coins" value="{{ old('reward_coins', 0) }}" min="0" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]">
                </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <label class="w-full md:w-1/3 text-sm font-bold text-slate-700">Status <span class="text-[#EF4444]">*</span></label>
                <div class="flex-1">
                    <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] bg-white">
                        <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
            </div>

            <!-- Expires At -->
            <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                <label class="w-full md:w-1/3 text-sm font-bold text-slate-700">Expires At</label>
                <div class="flex-1">
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]">
                </div>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end space-x-3 pt-6 border-t border-slate-100">
            <a href="{{ url('/admin/referrals') }}" class="px-6 py-2.5 bg-white border border-[#e2e8f0] text-slate-700 text-sm font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition">Create Referral</button>
        </div>
    </form>
</div>
@endsection




