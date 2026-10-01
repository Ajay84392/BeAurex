@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10 bg-[#f1f5f9]" x-data="{
    search: '',
    statusFilter: '',
    confirmStatusModal: false,
    selectedCustomer: '',
    selectedCustomerId: '',
    selectedCustomerDbId: '',
    pendingStatus: '',
    activeStatus: 'Active',
    
    saving: false,
    errorMessage: '',
    revert: null,

    // Called when a row's dropdown changes; revertFn puts that dropdown back if the change is not saved.
    initiateStatusChange(customerName, customerId, customerDbId, newStatus, currentStatus, revertFn) {
        if(newStatus === currentStatus) return;
        this.selectedCustomer = customerName;
        this.selectedCustomerId = customerId;
        this.selectedCustomerDbId = customerDbId;
        this.pendingStatus = newStatus;
        this.activeStatus = currentStatus;
        this.revert = revertFn;
        this.errorMessage = '';
        this.confirmStatusModal = true;
    },

    confirmStatus() {
        this.saving = true;
        this.errorMessage = '';
        fetch(`/admin/customers/${this.selectedCustomerDbId}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ status: this.pendingStatus })
        }).then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok || !data.success) throw new Error(data.message || 'Could not save the change. Please try again.');
            window.location.reload();
        }).catch(err => {
            this.saving = false;
            this.errorMessage = err.message || 'Could not save the change. Please try again.';
        });
    },

    cancelStatus() {
        if (this.saving) return;
        if (this.revert) this.revert();
        this.revert = null;
        this.errorMessage = '';
        this.confirmStatusModal = false;
    }
}" @keydown.escape.window="confirmStatusModal && cancelStatus()">
    <!-- Page Header & Breadcrumbs -->
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#0f172a] tracking-tight mb-2">Customers</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-[#0f172a]">Customers</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div @click="search = ''; statusFilter = ''" class="bg-white p-6 rounded-2xl shadow-sm border border-[#e2e8f0] flex flex-col items-center justify-center text-center hover:shadow-md transition cursor-pointer">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div class="text-2xl font-black text-[#0f172a] leading-tight">{{ number_format($totalCustomers) }}</div>
            <div class="text-[11px] font-bold text-[#475569] mt-1 uppercase tracking-wide">Total Customers</div>
        </div>
        <div @click="statusFilter = 'Active'" class="bg-white p-6 rounded-2xl shadow-sm border border-[#e2e8f0] flex flex-col items-center justify-center text-center hover:shadow-md transition cursor-pointer">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-[#22C55E] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="text-2xl font-black text-[#0f172a] leading-tight">{{ number_format($activeCustomers) }}</div>
            <div class="text-[11px] font-bold text-[#475569] mt-1 uppercase tracking-wide">Active Customers</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#e2e8f0] flex flex-col items-center justify-center text-center hover:shadow-md transition cursor-pointer">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-[#F59E0B] flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div class="text-2xl font-black text-[#0f172a] leading-tight">{{ number_format($newToday) }}</div>
            <div class="text-[11px] font-bold text-[#475569] mt-1 uppercase tracking-wide">New Today</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#e2e8f0] flex flex-col items-center justify-center text-center hover:shadow-md transition">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
            <div class="text-2xl font-black text-[#0f172a] leading-tight">{{ number_format($totalStamps) }}</div>
            <div class="text-[11px] font-bold text-[#475569] mt-1 uppercase tracking-wide">Total Aurex Coins</div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#e2e8f0] flex flex-col items-center justify-center text-center hover:shadow-md transition">
            <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
            </div>
            <div class="text-2xl font-black text-[#0f172a] leading-tight">{{ number_format($totalRewards) }}</div>
            <div class="text-[11px] font-bold text-[#475569] mt-1 uppercase tracking-wide">Total Rewards</div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] overflow-hidden flex flex-col relative">

        <!-- Controls Row -->
        <div class="p-6 border-b border-[#e2e8f0] flex flex-col md:flex-row items-center gap-3 flex-wrap justify-between">
            <div class="flex flex-col md:flex-row items-center gap-3 flex-wrap w-full xl:w-auto">
                <!-- Search -->
                <div class="relative w-full md:w-96">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" x-model="search"
                        class="block w-full pl-9 pr-3 py-2.5 border border-[#e2e8f0] rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-[#b00000] focus:border-[#b00000] text-sm font-semibold text-[#0f172a] transition shadow-sm"
                        placeholder="Search by Customer ID, Name or Email">
                </div>
                <select x-model="statusFilter" :class="{'text-red-600': statusFilter === 'Inactive', 'text-black': statusFilter === 'Blocked', 'text-slate-700': statusFilter === '' || statusFilter === 'Active'}" class="border border-[#e2e8f0] bg-white px-5 py-2.5 rounded-xl text-sm font-bold focus:outline-none focus:ring-1 focus:ring-[#b00000] focus:border-[#b00000] shadow-sm w-full md:w-auto shrink-0 transition-colors">
                    <option value="">All Statuses</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button @click="search = ''; statusFilter = ''" class="flex items-center justify-center space-x-2 border border-[#e2e8f0] bg-white text-slate-700 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-[#f1f5f9] transition shadow-sm w-full md:w-auto shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Reset</span>
                </button>
            </div>
            
            <div class="flex items-center space-x-2 w-full md:w-auto mt-4 md:mt-0">
                <span class="text-sm font-semibold text-[#475569]">Show</span>
                <select class="border border-[#e2e8f0] rounded-xl px-2 py-1 focus:outline-none font-bold text-slate-700">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold text-center w-16">#</th>
                        <th class="px-6 py-4 font-bold">Customer ID</th>
                        <th class="px-6 py-4 font-bold">Phone Number</th>
                        <th class="px-6 py-4 font-bold">Full Name</th>
                        <th class="px-6 py-4 font-bold text-center">Aurex Coins</th>
                        <th class="px-6 py-4 font-bold text-center">Rewards Claimed</th>
                        <th class="px-6 py-4 font-bold">Joined Date</th>
                        <th class="px-6 py-4 font-bold text-center">Status</th>
                        <th class="px-6 py-4 font-bold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    
                    @forelse($customers as $index => $customer)
                    <tr class="hover:bg-[#f1f5f9]/50 transition" x-show="(search === '' || '{{ strtolower(addslashes($customer->name . ' ' . $customer->phone . ' CU-'.strtoupper(substr(md5($customer->id), 0, 8)))) }}'.includes(search.toLowerCase())) && (statusFilter === '' || '{{ $customer->status }}' === statusFilter)">
                        <td class="px-6 py-5 text-center text-slate-400">{{ $customers->firstItem() + $index }}</td>
                        <td class="px-6 py-5 font-bold text-slate-700 font-mono">CU-{{ strtoupper(substr(md5($customer->id), 0, 8)) }}</td>
                        <td class="px-6 py-5 text-[#475569]">{{ $customer->phone ?? 'â€”' }}</td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-slate-700">{{ $customer->name }}</div>
                            <div class="text-xs text-[#475569] mt-0.5">{{ $customer->email }}</div>
                        </td>
                        <td class="px-6 py-5 text-center font-bold text-slate-700">{{ rand(5, 50) }}</td>
                        <td class="px-6 py-5 text-center font-bold text-[#22C55E]">{{ rand(0, 10) }}</td>
                        <td class="px-6 py-5 text-[#475569] text-xs">{{ $customer->created_at->format('M d, Y') }}</td>
                                                <td class="px-6 py-5" x-data="{ rowStatus: '{{ $customer->status }}' }">
                            <div class="relative">
                                <select x-model="rowStatus" @change="initiateStatusChange('{{ addslashes($customer->name) }}', 'CU-{{ strtoupper(substr(md5($customer->id), 0, 8)) }}', '{{ $customer->id }}', rowStatus, '{{ $customer->status }}', () => rowStatus = '{{ $customer->status }}')" aria-label="Customer status" :class="{'text-[#22C55E]': rowStatus === 'Active', 'text-red-600': rowStatus === 'Inactive', 'text-black': rowStatus === 'Blocked'}" class="appearance-none bg-transparent border border-[#e2e8f0] rounded-xl px-3 py-1.5 pr-8 text-xs font-bold focus:outline-none focus:border-[#b00000] focus:ring-1 focus:ring-[#b00000] cursor-pointer transition-colors">
                                    <option value="Active" class="text-[#22C55E]">Active</option>
                                    <option value="Inactive" class="text-red-600 font-bold">Inactive</option>
                                    <option value="Blocked" class="text-black font-bold">Blocked</option>
                                </select>
                                <svg class="w-3 h-3 text-slate-400 absolute right-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center whitespace-nowrap">
                            <a href="/admin/customers/{{ $customer->id }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-[#b00000] bg-red-50 hover:bg-red-100 transition border border-red-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-[#475569]">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span class="text-[#475569] font-medium">No customers found.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="p-4 border-t border-[#e2e8f0] flex flex-col md:flex-row items-center justify-between gap-4 bg-white text-sm text-[#475569]">
            @if($customers->hasPages())
                <div class="w-full">
                    {{ $customers->links() }}
                </div>
            @else
                <div>Showing {{ $customers->count() }} entries</div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <span>Rows per page</span>
                        <select class="border border-[#e2e8f0] rounded-xl px-2 py-1 focus:outline-none font-bold text-slate-700">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>
            @endif
        </div>

        <!-- Horizontal scroll hint -->
        <div class="bg-slate-50 border-t border-slate-200 p-3 flex items-center justify-center space-x-2 text-slate-600 text-xs font-semibold w-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Scroll horizontally to view more details about the customers.</span>
        </div>
        
        <!-- Confirmation Modal Overlay -->
        <div x-show="confirmStatusModal" style="display: none;" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-6 text-center border border-slate-100 relative" @click.outside="cancelStatus()">
                <button type="button" @click="cancelStatus()" aria-label="Close" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-[#0f172a] mb-2">Update Customer Status</h3>
                <p class="text-sm text-[#475569] mb-6 leading-relaxed">
                    Are you sure you want to change the status of customer &quot;<span x-text="selectedCustomer" class="font-bold text-slate-700"></span>&quot; (<span x-text="selectedCustomerId"></span>) to <br><span x-text="pendingStatus" class="font-bold text-[#b00000] text-base"></span>?
                </p>
                <p x-show="errorMessage" x-text="errorMessage" class="mb-4 text-xs font-semibold text-[#EF4444]" role="alert"></p>
                <div class="flex space-x-3">
                    <button type="button" @click="cancelStatus()" :disabled="saving" class="flex-1 py-2.5 border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-[#f1f5f9] transition disabled:opacity-50">Cancel</button>
                    <button type="button" @click="confirmStatus()" :disabled="saving" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition disabled:opacity-75" x-text="saving ? 'Saving…' : 'Confirm'">Confirm</button>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 leading-tight">This action will affect the customer's ability to log in and claim rewards.</p>
            </div>
        </div>
    </div>
</div>
@endsection






