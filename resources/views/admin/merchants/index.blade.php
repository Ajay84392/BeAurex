@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
<div class="flex-1 overflow-auto p-6 md:p-10" x-data="{
    confirmModal: false,
    updateType: '', // 'status' or 'complimentary'
    merchantName: '',
    merchantId: '',
    merchantDbId: '',
    pendingValue: '',
    currentValue: '',
    pendingStatus: '',
    
    initiateChange(type, name, mId, dbId, newValue, oldValue) {
        if(newValue == oldValue) return; // use loose equality for 1 and '1'
        this.updateType = type;
        this.merchantName = name;
        this.merchantId = mId;
        this.merchantDbId = dbId;
        this.pendingValue = newValue;
        this.currentValue = oldValue;
        this.pendingStatus = type === 'complimentary' ? (newValue == 1 ? 'Yes' : 'No') : newValue;
        this.confirmModal = true;
    },
    
    confirmChange() {
        let endpoint = '';
        let method = '';
        let bodyData = {};
        
        if (this.updateType === 'status') {
            endpoint = `/admin/merchants/${this.merchantDbId}/status`;
            method = 'PATCH';
            bodyData = { status: this.pendingValue };
        } else {
            endpoint = `/admin/merchants/${this.merchantDbId}`;
            method = 'PUT';
            bodyData = { complimentary: this.pendingValue };
        }
        
        fetch(endpoint, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(bodyData)
        }).then(response => {
            window.location.reload();
        }).catch(err => {
            console.error(err);
            window.location.reload();
        });
        this.confirmModal = false;
    },
    
        cancelChange() {
        this.confirmModal = false;
        window.location.reload();
    }
}">

            
            <!-- Page Header & Breadcrumbs -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900 mb-1">Merchants</h1>
                <div class="text-xs text-slate-500 font-medium flex items-center space-x-1">
                    <a href="/admin/dashboard" class="hover:text-red-600 transition">Home</a>
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
                    <span class="text-slate-800">Merchants</span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                
                <!-- Stat 1 -->
                <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mb-1">1,248</div>
                    <h3 class="text-xs font-semibold text-slate-500">Total Merchants</h3>
                </div>

                <!-- Stat 2 -->
                <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mb-1">1,005</div>
                    <h3 class="text-xs font-semibold text-slate-500">Active Merchants</h3>
                </div>

                <!-- Stat 3 -->
                <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mb-1">143</div>
                    <h3 class="text-xs font-semibold text-slate-500">Trial Merchants</h3>
                </div>

                <!-- Stat 4 -->
                <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mb-1">58</div>
                    <h3 class="text-xs font-semibold text-slate-500">Pending Payment</h3>
                </div>

                <!-- Stat 5 -->
                <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex flex-col items-center justify-center text-center">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mb-1">36</div>
                    <h3 class="text-xs font-semibold text-slate-500">Today's Onboarding</h3>
                </div>

            </div>

            <!-- Main Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                
                <!-- Controls Row -->
                <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row items-center gap-4">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-lg leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm transition" placeholder="Search by Merchant ID, Business Name or Email">
                    </div>
                    
                    <div class="flex items-center space-x-3 w-full sm:w-auto">
                        <!-- Filters -->
                        <button class="flex items-center justify-center space-x-2 border border-slate-200 bg-white text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition w-full sm:w-auto">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            <span>Filters</span>
                        </button>
                        <!-- Reset -->
                        <button class="flex items-center justify-center space-x-2 border border-slate-200 bg-white text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-50 transition w-full sm:w-auto">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <span>Reset</span>
                        </button>
                    </div>
                </div>

                <!-- Table Container with Horizontal Scroll -->
                <div class="table-container overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-max">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] uppercase tracking-wider font-bold text-slate-600">
                                <th class="px-5 py-4 w-12 text-center">#</th>
                                <th class="px-5 py-4">Merchant ID</th>
                                <th class="px-5 py-4">Merchant Email</th>
                                <th class="px-5 py-4">Joined Date</th>
                                <th class="px-5 py-4">Business Name</th>
                                <th class="px-5 py-4">Payment Status</th>
                                <th class="px-5 py-4">Payment Date</th>
                                <th class="px-5 py-4">Payment Amount</th>
                                <th class="px-5 py-4">Plan</th>
                                <th class="px-5 py-4">Plan Valid Till</th>
                                <th class="px-5 py-4">Set a Deal</th>
                                <th class="px-5 py-4">Status</th>
                                <th class="px-5 py-4 text-center">View</th>
                                <th class="px-5 py-4 text-center">Complimentary</th>
                            </tr>
                        </thead>
                                                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-800">
                            @forelse($merchants as $index => $merchant)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">{{ $merchants->firstItem() + $index }}</td>
                                <td class="px-5 py-4">MRC-{{ 1000 + $merchant->id }}</td>
                                <td class="px-5 py-4">{{ $merchant->email }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $merchant->created_at->format('M d, Y') }}</td>
                                <td class="px-5 py-4 font-semibold">{{ $merchant->name }}</td>
                                <td class="px-5 py-4">
                                    @if($merchant->payment_status == 'Paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                    @elseif($merchant->payment_status == 'Trial')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700">Trial</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">{{ $merchant->payment_status }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-600 text-xs">{{ $merchant->payment_date ? \Carbon\Carbon::parse($merchant->payment_date)->format('M d, Y h:i A') : '-' }}</td>
                                <td class="px-5 py-4">{{ $merchant->payment_amount ? '₹ ' . number_format($merchant->payment_amount, 2) : '-' }}</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">{{ $merchant->plan }}</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">{{ $merchant->plan_valid_till ? \Carbon\Carbon::parse($merchant->plan_valid_till)->format('M d, Y') : '-' }}</td>
                                <td class="px-5 py-4 text-center">
                                    <!-- Keep Set Deal as visual dummy or implement if needed -->
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                                                <td class="px-5 py-4" x-data="{ rowStatus: '{{ $merchant->status }}' }">
                                    <select x-model="rowStatus" @change="initiateChange('status', '{{ addslashes($merchant->name) }}', 'MRC-{{ 1000 + $merchant->id }}', {{ $merchant->id }}, rowStatus, '{{ $merchant->status }}')" :class="{'text-[#22C55E]': rowStatus === 'Active', 'text-blue-600': rowStatus === 'Trial', 'text-[#F59E0B]': rowStatus === 'Pending', 'text-[#EF4444]': rowStatus === 'Inactive'}" class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold transition-colors cursor-pointer">
                                        <option value="Active" class="text-[#22C55E]">Active</option>
                                        <option value="Trial" class="text-blue-600 font-bold">Trial</option>
                                        <option value="Pending" class="text-[#F59E0B] font-bold">Pending</option>
                                        <option value="Inactive" class="text-[#EF4444] font-bold">Inactive</option>
                                    </select>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="/admin/merchants/{{ $merchant->id }}" class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </td>
                                                                <td class="px-5 py-4 text-center" x-data="{ rowComp: '{{ $merchant->complimentary ? 1 : 0 }}' }">
                                    <select x-model="rowComp" @change="initiateChange('complimentary', '{{ addslashes($merchant->name) }}', 'MRC-{{ 1000 + $merchant->id }}', {{ $merchant->id }}, rowComp, '{{ $merchant->complimentary ? 1 : 0 }}')" :class="{'text-[#22C55E]': rowComp == '1', 'text-slate-700': rowComp == '0'}" class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold transition-colors cursor-pointer">
                                        <option value="0" class="text-slate-700">No</option>
                                        <option value="1" class="text-[#22C55E] font-bold">Yes</option>
                                    </select>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="px-5 py-8 text-center text-slate-500">No merchants found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-slate-200">
                    {{ $merchants->links() }}
                </div>
            </div>
            
        </div>
        
                        <!-- Confirmation Modal Overlay -->
        <div x-show="confirmModal" style="display: none;" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-6 text-center border border-slate-100 relative" @click.away="confirmModal = false">
                <button @click="confirmModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                
                <h3 class="text-lg font-bold text-[#0f172a] mb-2" x-text="updateType === 'status' ? 'Update Merchant Status' : 'Update Complimentary Status'"></h3>
                
                <p class="text-sm text-[#475569] mb-6 leading-relaxed">
                    Are you sure you want to change the <span x-text="updateType === 'status' ? 'status' : 'complimentary status'"></span> of merchant &quot;<span x-text="merchantName" class="font-bold text-slate-700"></span>&quot; (<span x-text="merchantId"></span>) to <br><span x-text="pendingStatus" class="font-bold text-[#b00000] text-base"></span>?
                </p>
                
                <div class="flex space-x-3">
                    <button @click="cancelChange()" class="flex-1 py-2.5 border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</button>
                    <button @click="confirmChange" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition">Confirm</button>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 leading-tight" x-text="updateType === 'status' ? 'This action will affect the merchant\'s ability to log in and manage features.' : 'This will toggle the complimentary plan access for the merchant.'"></p>
                
                <div class="flex space-x-3">
                    <button @click="cancelChange()" class="flex-1 py-2.5 border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</button>
                    <button @click="confirmChange" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition">Confirm</button>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 leading-tight">This action will affect the customer's ability to log in and claim rewards.</p>
                
                <div class="flex space-x-3">
                    <button @click="cancelChange()" class="flex-1 py-2.5 border border-[#e2e8f0] text-slate-700 font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</button>
                    <button @click="confirmChange" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition">Confirm</button>
                </div>
                <p class="text-[10px] text-slate-400 mt-4 leading-tight">This action will affect the customer's ability to log in and claim rewards.</p>
            </div>
        </div>
@endsection