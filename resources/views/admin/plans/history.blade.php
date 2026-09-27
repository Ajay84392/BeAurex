@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
    <!-- Page Content -->
    <div class="flex-1 overflow-auto p-6 md:p-10">

        <!-- Page Header & Breadcrumbs -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Plan History</h1>
            <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
                <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#0f172a]">Plans</span>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#0f172a]">Plan History</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-2 mb-6 border-b border-[#e2e8f0]">
            <a href="/admin/plans"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#475569] hover:text-slate-700 hover:bg-slate-100 rounded-t-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span>Active Plans</span>
            </a>
            <a href="{{ route('plans.history') }}"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#b00000] border-b-2 border-[#b00000] bg-red-50/50 rounded-t-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span>Plan History</span>
            </a>
            <a href="/admin/plans/create"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#475569] hover:text-slate-700 hover:bg-slate-100 rounded-t-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Create Plan</span>
            </a>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] overflow-hidden flex flex-col">

            <!-- Controls Row -->
            <div class="p-5 border-b border-[#e2e8f0] flex flex-col xl:flex-row items-center gap-4 justify-between">
                <div class="flex flex-col sm:flex-row items-center gap-4 w-full xl:w-auto">
                    <!-- Search -->
                    <div class="relative w-full sm:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                            class="block w-full pl-9 pr-3 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm transition"
                            placeholder="Search by plan name...">
                    </div>

                    <!-- Filters -->
                    <select
                        class="block w-full sm:w-auto pl-3 pr-8 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white text-slate-700 text-sm font-medium focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 transition">
                        <option>All Actions</option>
                        <option>Created</option>
                        <option>Updated</option>
                        <option>Deactivated</option>
                        <option>Deleted</option>
                    </select>
                    <select
                        class="block w-full sm:w-auto pl-3 pr-8 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white text-slate-700 text-sm font-medium focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 transition">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Deleted</option>
                    </select>

                    <!-- Date Range -->
                    <button
                        class="flex items-center justify-between w-full sm:w-64 border border-[#e2e8f0] bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#f1f5f9] transition">
                        <span>May 01, 2026 - May 24, 2026</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Export Button -->
                <button
                    class="flex items-center justify-center space-x-2 border border-red-200 bg-red-50 text-[#b00000] px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-100 transition w-full xl:w-auto shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Export</span>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <div class="overflow-x-auto w-full"><table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                            <th class="px-5 py-4 w-12 text-center">#</th>
                            <th class="px-5 py-4">Plan Name</th>
                            <th class="px-5 py-4">Action</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Performed By</th>
                            <th class="px-5 py-4">Date & Time</th>
                            <th class="px-5 py-4 text-center">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-[#f1f5f9]/50 transition">
                                <td class="px-5 py-4 text-center text-[#475569]">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4 font-semibold">{{ $plan->name }} -
                                    ${{ $plan->price }}/{{ $plan->billing_cycle }}</td>
                                <td class="px-5 py-4"><span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Created</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($plan->is_active)
                                        <span class="text-[#22C55E] font-bold">Active</span>
                                    @else
                                        <span class="text-slate-400 font-bold">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-[#475569]">Admin</td>
                                <td class="px-5 py-4 text-[#475569] text-xs">
                                    {{ $plan->created_at->format('M d, Y, h:i A') }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('plans.edit', $plan->id) }}"
                                            class="text-slate-400 hover:text-blue-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-[#b00000] transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-[#475569]">No plans found. <a
                                        href="{{ route('plans.create') }}" class="text-[#b00000] hover:underline">Create
                                        one</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table></div>
            </div>

            <!-- Pagination Footer -->
            <div
                class="p-4 border-t border-[#e2e8f0] flex flex-col md:flex-row items-center justify-between gap-4 bg-white text-sm text-[#475569]">
                <div class="font-medium">Showing 1 to 10 of 45 entries</div>
                <div class="flex items-center space-x-1">
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-slate-400 hover:bg-[#f1f5f9] transition"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl bg-[#b00000] text-white font-bold shadow-sm">1</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] font-bold transition">2</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] font-bold transition">3</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] font-bold transition">4</button>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] font-bold transition">5</button>
                    <span class="w-8 h-8 flex items-center justify-center text-slate-400 font-bold">...</span>
                    <button
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

    </div>
@endsection





