@extends('layouts.admin')

@section('title', 'Manage Deals')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">

    <!-- Page Header & Breadcrumbs -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Manage Deals & Coupons</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1 mb-6">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-[#0f172a]">Manage Deals</span>
        </div>
    </div>

    <!-- Form Container -->
    <div class="w-full bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-6 lg:p-8">
        <form action="{{ isset($editDeal) ? '/admin/claims/'.$editId : '/admin/claims' }}" method="POST">
            @csrf
            @if(isset($editDeal))
                @method('PUT')
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Plan Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Plan Name <span class="text-red-500">*</span></label>
                    <select name="plan_name" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700 bg-white">
                        <option value="">Select Plan</option>
                        <option value="Basic Plan" {{ (isset($editDeal) && $editDeal['plan_name'] == 'Basic Plan') ? 'selected' : '' }}>Basic Plan</option>
                        <option value="Premium Plan" {{ (isset($editDeal) && $editDeal['plan_name'] == 'Premium Plan') ? 'selected' : '' }}>Premium Plan</option>
                        <option value="Enterprise Plan" {{ (isset($editDeal) && $editDeal['plan_name'] == 'Enterprise Plan') ? 'selected' : '' }}>Enterprise Plan</option>
                    </select>
                </div>

                <!-- State -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">State (Optional)</label>
                    <select name="state" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700 bg-white mb-1">
                        <option value="">All States</option>
                        @foreach(['Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal', 'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu', 'Delhi', 'Jammu and Kashmir', 'Ladakh', 'Lakshadweep', 'Puducherry'] as $s)
                            <option value="{{ $s }}" {{ (isset($editDeal) && $editDeal['state'] == $s) ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-slate-400">If set, auto-applies during payment for customers in this state.</p>
                </div>

                <!-- Deal Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Deal Name <span class="text-red-500">*</span></label>
                    <input type="text" name="deal_name" value="{{ $editDeal['deal_name'] ?? '' }}" placeholder="e.g., New Year Offer" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                </div>

                <!-- Coupon Code -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Coupon Code <span class="text-red-500">*</span></label>
                    <input type="text" name="coupon_code" value="{{ $editDeal['coupon_code'] ?? '' }}" placeholder="E.G., NEWYEAR2024" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                </div>

                <!-- Bonus Amount -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Bonus Amount (For Referrer)</label>
                    <input type="number" name="bonus_amount" value="{{ $editDeal['bonus_amount'] ?? '0' }}" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                </div>

                <!-- Discount Amount -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Discount Amount (For Referred)</label>
                    <input type="number" id="discount_amount" name="discount_amount" value="{{ $editDeal['discount_amount'] ?? '0' }}" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                    <p id="msg_discount_amount" class="text-[10px] text-amber-600 mt-1.5 font-semibold hidden"></p>
                </div>

                <!-- Discount Percentage -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Discount Percentage</label>
                    <input type="number" id="discount_percentage" name="discount_percentage" value="{{ $editDeal['discount_percentage'] ?? '0' }}" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                    <p id="msg_discount_percentage" class="text-[10px] text-amber-600 mt-1.5 font-semibold hidden"></p>
                </div>

                <!-- Validity Date -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Valid Till <span class="text-red-500">*</span></label>
                    <input type="date" name="validity_date" value="{{ $editDeal['validity_date'] ?? '' }}" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                </div>

                <!-- Maximum Usage -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Maximum Usage (0 = Unlimited)</label>
                    <input type="number" name="maximum_usage" value="{{ $editDeal['maximum_usage'] ?? '0' }}" class="w-full px-4 py-2.5 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-medium text-slate-700">
                </div>
            </div>

            <div class="pt-8 flex justify-between items-center">
                @if(isset($editDeal))
                    <a href="/admin/claims" class="px-6 py-2.5 border border-[#e2e8f0] text-slate-600 text-sm font-bold rounded-full hover:bg-slate-50 transition">
                        Cancel Edit
                    </a>
                @else
                    <div></div>
                @endif
                <button type="submit" class="px-10 py-3 bg-[#b00000] text-white text-sm font-bold rounded-full hover:bg-[#8a0000] transition shadow-sm">
                    {{ isset($editDeal) ? 'Update Deal' : 'Add Deal' }}
                </button>
            </div>
        </form>
    </div>

    <!-- Deals List -->
    <div class="w-full mx-auto mt-8 bg-white rounded-xl shadow-sm border border-[#e2e8f0] p-6 lg:p-8">
        <h2 class="text-xl font-bold text-[#0f172a] mb-4">Saved Deals</h2>
        @if(session('success'))
            <div class="mb-4 p-4 text-sm font-bold text-emerald-700 bg-emerald-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        
        @if(isset($deals) && count($deals) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50 border-b border-[#e2e8f0] text-xs uppercase tracking-wider font-bold text-slate-600">
                            <th class="px-4 py-3">Deal Name</th>
                            <th class="px-4 py-3">Coupon Code</th>
                            <th class="px-4 py-3">Plan Name</th>
                            <th class="px-4 py-3">State</th>
                            <th class="px-4 py-3">Bonus</th>
                            <th class="px-4 py-3">Discount</th>
                            <th class="px-4 py-3">Disc %</th>
                            <th class="px-4 py-3">Valid Till</th>
                            <th class="px-4 py-3">Max Usage</th>
                            <th class="px-4 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach(array_reverse($deals) as $index => $deal)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-3 font-semibold">{{ $deal['deal_name'] ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600 bg-slate-50 rounded px-1">{{ $deal['coupon_code'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $deal['plan_name'] ?: 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $deal['state'] ?: 'All' }}</td>
                            <td class="px-4 py-3 text-slate-600">₹{{ $deal['bonus_amount'] ?? '0' }}</td>
                            <td class="px-4 py-3 text-slate-600">₹{{ $deal['discount_amount'] ?? '0' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $deal['discount_percentage'] ?? '0' }}%</td>
                            <td class="px-4 py-3 text-slate-600">{{ $deal['validity_date'] ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $deal['maximum_usage'] ? $deal['maximum_usage'] : 'Unlimited' }}</td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <!-- Edit Button -->
                                    <a href="/admin/claims/{{ $index }}/edit" class="text-slate-500 hover:text-[#b00000] bg-slate-100 hover:bg-red-50 p-1.5 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    
                                    <!-- Pause Toggle Switch -->
                                    <label class="relative inline-flex items-center cursor-pointer" title="Pause / Active">
                                      <input type="checkbox" class="sr-only peer" checked>
                                      <div class="w-9 h-5 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>

                                    <!-- Delete Button -->
                                    <form action="/admin/claims/{{ $index }}" method="POST" class="inline-block m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this deal?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-slate-500">No deals saved yet.</p>
        @endif
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amtInput = document.getElementById('discount_amount');
        const pctInput = document.getElementById('discount_percentage');
        const msgAmt = document.getElementById('msg_discount_amount');
        const msgPct = document.getElementById('msg_discount_percentage');

        function toggleFields() {
            if (amtInput.value && parseFloat(amtInput.value) > 0) {
                pctInput.value = 0;
                pctInput.setAttribute('readonly', true);
                pctInput.classList.add('bg-slate-100', 'text-slate-400');
                
                msgPct.classList.remove('hidden');
                msgPct.innerText = "Disabled because Discount Amount is set.";
            } else {
                pctInput.removeAttribute('readonly');
                pctInput.classList.remove('bg-slate-100', 'text-slate-400');
                msgPct.classList.add('hidden');
            }

            if (pctInput.value && parseFloat(pctInput.value) > 0) {
                amtInput.value = 0;
                amtInput.setAttribute('readonly', true);
                amtInput.classList.add('bg-slate-100', 'text-slate-400');
                
                msgAmt.classList.remove('hidden');
                msgAmt.innerText = "Disabled because Discount Percentage is set.";
            } else {
                amtInput.removeAttribute('readonly');
                amtInput.classList.remove('bg-slate-100', 'text-slate-400');
                msgAmt.classList.add('hidden');
            }
        }

        amtInput.addEventListener('input', toggleFields);
        pctInput.addEventListener('input', toggleFields);
        
        // Initial check on load
        toggleFields();
    });
</script>

@endsection
