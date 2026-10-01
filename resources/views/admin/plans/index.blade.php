@extends('layouts.admin')
@section('title', 'Manage Plans')

@section('content')
    <!-- Page Content -->
    <div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">

        <!-- Page Header & Breadcrumbs -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Manage Plans</h1>
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
                <span class="text-[#0f172a]">Active Plans</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex space-x-2 mb-6 border-b border-[#e2e8f0]">
            <a href="/admin/plans"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#b00000] border-b-2 border-[#b00000] bg-red-50/50 rounded-t-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span>Active Plans</span>
            </a>
            <a href="{{ route('plans.history') }}"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#475569] hover:text-slate-700 hover:bg-slate-100 rounded-t-lg transition">
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

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch mb-8">
                @forelse($plans as $plan)
                    @php
                        $features = json_decode($plan->features) ?? [];
                    @endphp
                    
                    @if(strtolower($plan->type) === 'professional')
                        <!-- Professional Plan -->
                        <div class="bg-white border-2 border-red-600 p-8 rounded-3xl shadow-xl relative flex flex-col justify-between pt-12">
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-red-600 text-white text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider shadow-sm">Most Popular</span>
                            <div>
                                <h3 class="text-xl font-extrabold text-red-600 tracking-tight">{{ $plan->name }}</h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                                    @if($plan->detailed_description)
                                    <span class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹{{ number_format((float)$plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-slate-900 tracking-tight">₹{{ number_format($plan->price) }} <span class="text-sm font-medium text-slate-500">/ {{ $plan->billing_cycle }}</span></div>
                                    @if($plan->short_description)
                                    <div class="text-red-600 text-xs font-bold mt-2.5 flex items-center bg-red-50 px-2 py-1 rounded w-fit">
                                        <span>{{ $plan->short_description }}</span>
                                    </div>
                                    @endif
                                </div>
                                <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                                    @foreach($features as $feature)
                                        <li class="flex items-start text-xs"><span class="text-emerald-500 font-bold mr-2.5">✓</span> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="flex-1 bg-emerald-50 text-[#22C55E] text-sm font-bold py-2 rounded-xl text-center mr-2">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="w-9 h-9 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] transition bg-white">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </button>
                                    <div x-show="open" x-transition x-cloak class="absolute right-0 bottom-full mb-2 w-36 bg-white border border-[#e2e8f0] rounded-xl shadow-lg z-50">
                                        <a href="{{ route('plans.edit', $plan->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-[#f1f5f9] font-medium rounded-t-xl text-left">Edit Plan</a>
                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#b00000] hover:bg-red-50 font-medium rounded-b-xl">Delete Plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(strtolower($plan->type) === 'legacy')
                        <!-- Legacy Plan -->
                        <div class="bg-slate-900 border border-slate-800 p-8 rounded-3xl shadow-sm text-white relative flex flex-col justify-between pt-12">
                            <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-slate-700 text-amber-400 text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider border border-slate-600">Best Value</span>
                            <div>
                                <h3 class="text-xl font-extrabold text-white tracking-tight">{{ $plan->name }}</h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-800">
                                    @if($plan->detailed_description)
                                    <span class="text-sm font-bold text-slate-500 line-through tracking-wide block mb-1">₹{{ number_format((float)$plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-amber-400 tracking-tight">₹{{ number_format($plan->price) }}</div>
                                    <div class="text-slate-300 text-[10px] font-bold uppercase tracking-widest mt-2.5 flex items-center space-x-1.5">
                                        <span class="bg-slate-800 px-2 py-0.5 rounded text-emerald-400">{{ $plan->billing_cycle }}</span>
                                        @if($plan->short_description)
                                        <span class="bg-slate-800 px-2 py-0.5 rounded text-slate-400">{{ $plan->short_description }}</span>
                                        @endif
                                    </div>
                                </div>
                                <ul class="space-y-3.5 text-slate-300 text-sm mb-8">
                                    @foreach($features as $feature)
                                        <li class="flex items-start text-xs"><span class="text-amber-400 font-bold mr-2.5">✓</span> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-slate-700 pt-4">
                                <div class="flex-1 bg-emerald-900/50 text-emerald-400 text-sm font-bold py-2 rounded-xl text-center mr-2">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-700 text-slate-300 hover:bg-slate-800 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </button>
                                    <div x-show="open" x-transition x-cloak class="absolute right-0 bottom-full mb-2 w-36 bg-slate-800 border border-slate-700 rounded-xl shadow-lg z-50">
                                        <a href="{{ route('plans.edit', $plan->id) }}" class="block px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 font-medium rounded-t-xl text-left">Edit Plan</a>
                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-900/30 font-medium rounded-b-xl">Delete Plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Standard Plan -->
                        <div class="bg-white border border-slate-200/90 p-8 rounded-3xl shadow-sm hover:shadow-md transition flex flex-col justify-between relative pt-10">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $plan->name }}</h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                                    @if($plan->detailed_description)
                                    <span class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹{{ number_format((float)$plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-slate-900 tracking-tight">₹{{ number_format($plan->price) }} <span class="text-sm font-medium text-slate-500">/ {{ $plan->billing_cycle }}</span></div>
                                    @if($plan->short_description)
                                    <div class="text-emerald-600 text-xs font-bold mt-2.5 flex items-center">
                                        <span>{{ $plan->short_description }}</span>
                                    </div>
                                    @endif
                                </div>
                                <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                                    @foreach($features as $feature)
                                        <li class="flex items-start text-xs"><span class="text-emerald-500 font-bold mr-2.5">✓</span> {{ $feature }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                                <div class="flex-1 bg-emerald-50 text-[#22C55E] text-sm font-bold py-2 rounded-xl text-center mr-2">
                                    {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                </div>
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open" class="w-9 h-9 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </button>
                                    <div x-show="open" x-transition x-cloak class="absolute right-0 bottom-full mb-2 w-36 bg-white border border-[#e2e8f0] rounded-xl shadow-lg z-50">
                                        <a href="{{ route('plans.edit', $plan->id) }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-[#f1f5f9] font-medium rounded-t-xl text-left">Edit Plan</a>
                                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#b00000] hover:bg-red-50 font-medium rounded-b-xl">Delete Plan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                <div class="col-span-1 md:col-span-3 text-center py-12 bg-white rounded-2xl border border-[#e2e8f0]">
                    <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="text-lg font-bold text-[#0f172a]">No Plans Found</h3>
                    <p class="text-[#475569] mb-4">Create your first subscription plan to get started.</p>
                    <a href="{{ route('plans.create') }}" class="inline-flex items-center space-x-2 bg-[#b00000] hover:bg-[#8a0000] text-white px-4 py-2 rounded-xl font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                        <span>Create Plan</span>
                    </a>
                </div>
                @endforelse
            </div>
            <div class="flex items-start space-x-3 bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-700 text-sm font-medium">
                <svg class="w-5 h-5 shrink-0 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>You can reorder plans by dragging. Changes will be reflected to merchants.</p>
            </div>
        </div>
@endsection
