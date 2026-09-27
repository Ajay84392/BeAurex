@extends('layouts.admin')

@section('title', 'Edit Plan')

@section('content')
    <div class="flex-1 overflow-auto p-6 md:p-10">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Edit Plan</h1>
            <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
                <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="/admin/plans" class="hover:text-[#b00000] transition">Plans</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#0f172a]">Edit Plan</span>
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
            <a href="{{ route('plans.create') }}"
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#475569] hover:text-slate-700 hover:bg-slate-100 rounded-t-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Create Plan</span>
            </a>
            <div
                class="flex items-center space-x-2 px-4 py-2.5 text-sm font-semibold text-[#b00000] border-b-2 border-[#b00000] bg-red-50/50 rounded-t-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                <span>Edit Plan</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('plans.update', $plan->id) }}" method="POST" id="createPlanForm"
            class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] max-w-5xl">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8">

                <!-- Plan Information -->
                <div class="mb-10">
                    <h2 class="text-lg font-bold text-[#0f172a] mb-6">Plan Information</h2>

                    <div class="flex flex-col md:flex-row gap-8 lg:gap-12">
                        <!-- Left Column -->
                        <div class="flex-1 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Name <span
                                        class="text-[#EF4444]">*</span></label>
                                <input type="text" id="input_name" name="name" required
                                    value="{{ old('name', $plan->name) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]"
                                    placeholder="Enter plan name" onkeyup="updatePreview()">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Sale Price (₹) <span
                                        class="text-[#EF4444]">*</span></label>
                                <input type="number" id="input_price" name="price" step="0.01" required
                                    value="{{ old('price', $plan->price) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]"
                                    placeholder="Enter plan price" onkeyup="updatePreview()">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Duration <span
                                        class="text-[#EF4444]">*</span></label>
                                <select id="input_duration" name="billing_cycle" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] appearance-none bg-white"
                                    onchange="updatePreview()"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%208l5%205%205-5%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222%22%20fill%3D%22none%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px center;">
                                    <option value="" disabled selected>Select duration</option>
                                    <option value="Monthly"
                                        {{ old('billing_cycle', $plan->billing_cycle) == 'Monthly' ? 'selected' : '' }}>
                                        Monthly</option>
                                    <option value="Yearly"
                                        {{ old('billing_cycle', $plan->billing_cycle) == 'Yearly' ? 'selected' : '' }}>
                                        Yearly</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Type <span
                                        class="text-[#EF4444]">*</span></label>
                                <select id="input_type" onchange="updatePreview()" name="type" required
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] appearance-none bg-white"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%208l5%205%205-5%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222%22%20fill%3D%22none%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px center;">
                                    <option value="" disabled selected>Select plan type</option>
                                    <option value="Standard" {{ old('type', $plan->type) == 'Standard' ? 'selected' : '' }}>
                                        Standard</option>
                                    <option value="Premium" {{ old('type', $plan->type) == 'Premium' ? 'selected' : '' }}>
                                        Premium</option>
                                    <option value="Enterprise"
                                        {{ old('type', $plan->type) == 'Enterprise' ? 'selected' : '' }}>Enterprise</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Plan Status</label>
                                <select id="input_status" name="is_active"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] appearance-none bg-white"
                                    onchange="updatePreview()"
                                    style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%208l5%205%205-5%22%20stroke%3D%22%2364748b%22%20stroke-width%3D%222%22%20fill%3D%22none%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 12px center;">
                                    <option value="1" {{ old('is_active', $plan->is_active) ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0" {{ !old('is_active', $plan->is_active) ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="flex-1 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Short Description</label>
                                <textarea id="input_short_desc" name="short_description" rows="3"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] resize-none"
                                    placeholder="Enter short description" maxlength="150"
                                    onkeyup="updateCharCount('input_short_desc', 'short_desc_count'); updatePreview()">{{ old('short_description', $plan->short_description) }}</textarea>
                                <div class="text-right text-[10px] font-bold text-slate-400 mt-1"><span
                                        id="short_desc_count">0</span>/150</div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Price</label>
                                <textarea id="input_detailed_desc" id="input_detailed_desc" onkeyup="updatePreview()" name="detailed_description" rows="5"
                                    class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] resize-none"
                                    placeholder="Enter detailed description" maxlength="500"
                                    onkeyup="updateCharCount('input_detailed_desc', 'detailed_desc_count')">{{ old('detailed_description', $plan->detailed_description) }}</textarea>
                                <div class="text-right text-[10px] font-bold text-slate-400 mt-1"><span
                                        id="detailed_desc_count">0</span>/500</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Features & Preview Split -->
                <div class="flex flex-col md:flex-row gap-8 lg:gap-12">

                    <!-- Plan Features -->
                    <div class="flex-1">
                        <h2 class="text-lg font-bold text-[#0f172a] mb-1">Plan Features</h2>
                        <p class="text-sm text-[#475569] mb-6">Add features included in this plan</p>

                        <div id="features-container" class="space-y-3 mb-4">
                            @php
                                $savedFeatures = json_decode($plan->features) ?? [];
                                if (empty($savedFeatures)) {
                                    $savedFeatures = [''];
                                }
                            @endphp
                            @foreach ($savedFeatures as $feature)
                                <div class="flex items-center space-x-2 feature-row">
                                    <input type="text" name="features[]"
                                        class="flex-1 px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] feature-input"
                                        placeholder="Enter feature name" onkeyup="updatePreview()"
                                        value="{{ $feature }}" required>
                                    <button type="button"
                                        class="w-10 h-10 rounded-xl border border-red-200 text-[#EF4444] hover:bg-red-50 flex flex-shrink-0 items-center justify-center transition"
                                        onclick="removeFeature(this)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addFeature()"
                            class="px-4 py-2 rounded-xl border border-red-200 text-[#b00000] text-xs font-bold hover:bg-red-50 transition flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Add Feature</span>
                        </button>
                    </div>

                    <!-- Plan Preview -->
                    <div class="w-full md:w-80 flex-shrink-0">
                        <h2 class="text-sm font-bold text-[#0f172a] mb-6 text-center">Plan Preview</h2>
                        <div id="preview_card_container"></div>
                    </div>

        </div>
        
        <!-- Action Buttons -->
            <div
                class="px-6 md:px-8 py-5 border-t border-slate-100 bg-[#f1f5f9]/50 rounded-b-2xl flex items-center justify-end space-x-3">
                <a href="{{ route('plans.index') }}"
                    class="px-6 py-2.5 bg-white border border-[#e2e8f0] text-slate-700 text-sm font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition shadow-sm">Update
                    Plan</button>
            </div>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        function updateCharCount(inputId, countId) {
            var len = document.getElementById(inputId).value.length;
            document.getElementById(countId).innerText = len;
        }

        function addFeature() {
            var container = document.getElementById('features-container');
            var row = document.createElement('div');
            row.className = 'flex items-center space-x-2 feature-row';
            row.innerHTML = `
            <input type="text" name="features[]" class="flex-1 px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] feature-input" placeholder="Enter feature name" onkeyup="updatePreview()">
            <button type="button" class="w-10 h-10 rounded-xl border border-red-200 text-[#EF4444] hover:bg-red-50 flex flex-shrink-0 items-center justify-center transition" onclick="removeFeature(this)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        `;
            container.appendChild(row);
        }

        function removeFeature(btn) {
            var container = document.getElementById('features-container');
            if (container.children.length > 1) {
                btn.parentElement.remove();
                updatePreview();
            } else {
                alert('At least one feature is required.');
            }
        }

        function updatePreview() {
            var name = document.getElementById('input_name').value || 'Plan Name';
            var price = document.getElementById('input_price').value || '0';
            var duration = document.getElementById('input_duration').value || 'duration';
            var shortDesc = document.getElementById('input_short_desc').value;
            var detailedDesc = document.getElementById('input_detailed_desc') ? document.getElementById('input_detailed_desc').value : '';
            var type = document.getElementById('input_type') ? document.getElementById('input_type').value : '';
            var status = document.getElementById('input_status') ? document.getElementById('input_status').value : '1';
            
            var featureInputs = document.querySelectorAll('.feature-input');
            var featuresList = [];
            featureInputs.forEach(function(input) {
                if (input.value.trim() !== '') {
                    featuresList.push(input.value.trim());
                }
            });
            if (featuresList.length === 0) {
                featuresList = ['Feature will appear here', 'Feature will appear here'];
            }

                                    var html = '';
            var statusHtml = status == "1" ? "Active" : "Inactive";
            
            if (type === 'Professional') {
                html = `
                <div class="bg-white border-2 border-red-600 p-8 rounded-3xl shadow-xl relative flex flex-col justify-between pt-12 text-left w-full h-full transform lg:-translate-y-2">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-red-600 text-white text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider shadow-sm w-max whitespace-nowrap">Most Popular</span>
                    <div>
                        <h3 class="text-xl font-extrabold text-red-600 tracking-tight">${name}</h3>
                        <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                            ${detailedDesc ? `<span class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹${Number(detailedDesc).toLocaleString('en-IN')}</span>` : ''}
                            <div class="text-3xl font-black text-slate-900 tracking-tight">₹${Number(price).toLocaleString('en-IN')} <span class="text-sm font-medium text-slate-500">/ ${duration}</span></div>
                            ${shortDesc ? `<div class="text-red-600 text-xs font-bold mt-2.5 flex items-center bg-red-50 px-2 py-1 rounded w-fit"><span>${shortDesc}</span></div>` : ''}
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                            ${featuresList.map(f => `<li class="flex items-start text-xs"><span class="text-emerald-500 font-bold mr-2.5">✓</span> ${f}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                        <div class="flex-1 bg-emerald-50 text-[#22C55E] text-sm font-bold py-2 rounded-xl text-center mr-2">
                            ${statusHtml}
                        </div>
                        <div class="relative">
                            <button type="button" class="w-9 h-9 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] bg-white opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            } else if (type === 'Legacy') {
                html = `
                <div class="bg-slate-900 border border-slate-800 p-8 rounded-3xl shadow-sm text-white relative flex flex-col justify-between pt-12 text-left w-full h-full">
                    <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-slate-700 text-amber-400 text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider border border-slate-600 w-max whitespace-nowrap">Best Value</span>
                    <div>
                        <h3 class="text-xl font-extrabold text-white tracking-tight">${name}</h3>
                        <div class="mt-6 mb-6 pb-6 border-b border-slate-800">
                            ${detailedDesc ? `<span class="text-sm font-bold text-slate-500 line-through tracking-wide block mb-1">₹${Number(detailedDesc).toLocaleString('en-IN')}</span>` : ''}
                            <div class="text-3xl font-black text-amber-400 tracking-tight">₹${Number(price).toLocaleString('en-IN')}</div>
                            <div class="text-slate-300 text-[10px] font-bold uppercase tracking-widest mt-2.5 flex items-center space-x-1.5 flex-wrap gap-y-1">
                                <span class="bg-slate-800 px-2 py-0.5 rounded text-emerald-400">${duration}</span>
                                ${shortDesc ? `<span class="bg-slate-800 px-2 py-0.5 rounded text-slate-400">${shortDesc}</span>` : ''}
                            </div>
                        </div>
                        <ul class="space-y-3.5 text-slate-300 text-sm mb-8">
                            ${featuresList.map(f => `<li class="flex items-start text-xs"><span class="text-amber-400 font-bold mr-2.5">✓</span> ${f}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="mt-auto flex items-center justify-between border-t border-slate-700 pt-4">
                        <div class="flex-1 bg-emerald-900/50 text-emerald-400 text-sm font-bold py-2 rounded-xl text-center mr-2">
                            ${statusHtml}
                        </div>
                        <div class="relative">
                            <button type="button" class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-700 text-slate-300 opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            } else {
                html = `
                <div class="bg-white border border-slate-200/90 p-8 rounded-3xl shadow-sm relative flex flex-col justify-between pt-10 text-left w-full h-full">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">${name}</h3>
                        <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                            ${detailedDesc ? `<span class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹${Number(detailedDesc).toLocaleString('en-IN')}</span>` : ''}
                            <div class="text-3xl font-black text-slate-900 tracking-tight">₹${Number(price).toLocaleString('en-IN')} <span class="text-sm font-medium text-slate-500">/ ${duration}</span></div>
                            ${shortDesc ? `<div class="text-emerald-600 text-xs font-bold mt-2.5 flex items-center"><span>${shortDesc}</span></div>` : ''}
                        </div>
                        <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                            ${featuresList.map(f => `<li class="flex items-start text-xs"><span class="text-emerald-500 font-bold mr-2.5">✓</span> ${f}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-4">
                        <div class="flex-1 bg-emerald-50 text-[#22C55E] text-sm font-bold py-2 rounded-xl text-center mr-2">
                            ${statusHtml}
                        </div>
                        <div class="relative">
                            <button type="button" class="w-9 h-9 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] bg-white opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm12 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            }
            document.getElementById('preview_card_container').innerHTML = html;
        }
        
        function initForm() {
            if (document.getElementById('input_short_desc')) {
                updateCharCount('input_short_desc', 'short_desc_count');
            }
            if (document.getElementById('input_detailed_desc')) {
                updateCharCount('input_detailed_desc', 'detailed_desc_count');
            }
            updatePreview();
        }
        window.onload = initForm;
    </script>
@endsection
