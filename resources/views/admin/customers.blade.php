@extends('layouts.admin')

@section('title', 'Claim Logs')

@section('content')
    <div class="flex-1 overflow-auto p-6 md:p-10">

        <!-- Page Header & Breadcrumbs -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Claim Logs</h1>
            <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
                <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#0f172a]">Claim Logs</span>
            </div>
        </div>

        <!-- Main Container -->
        <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] overflow-hidden flex flex-col">

            <!-- Controls Row 1 -->
            <div class="p-5 border-b border-[#e2e8f0] flex flex-col xl:flex-row items-center gap-4 justify-between">
                <div class="flex flex-col lg:flex-row items-center gap-4 w-full xl:w-auto">
                    <!-- Search -->
                    <div class="relative w-full lg:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                            class="block w-full pl-9 pr-3 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm transition"
                            placeholder="Search by claim ID, customer or merchant...">
                    </div>

                    <!-- Filters -->
                    <select
                        class="block w-full lg:w-auto pl-3 pr-8 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white text-slate-700 text-sm font-medium focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 transition">
                        <option>All Merchants</option>
                        <option>Coffee House</option>
                        <option>Pizza Plaza</option>
                        <option>Burger Point</option>
                    </select>
                    <select
                        class="block w-full lg:w-auto pl-3 pr-8 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white text-slate-700 text-sm font-medium focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 transition">
                        <option>All Status</option>
                        <option>Success</option>
                        <option>Pending</option>
                        <option>Failed</option>
                    </select>
                    <select
                        class="block w-full lg:w-auto pl-3 pr-8 py-2 border border-[#e2e8f0] rounded-xl leading-5 bg-white text-slate-700 text-sm font-medium focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 transition">
                        <option>All Reward Types</option>
                        <option>Free Item</option>
                        <option>Discount</option>
                    </select>
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

            <!-- Controls Row 2: Date Range (from image it seems to be below or next to it) -->
            <div class="px-5 py-4 border-b border-[#e2e8f0] bg-[#f1f5f9] flex items-center">
                <button
                    class="flex items-center justify-between w-64 border border-[#e2e8f0] bg-white text-slate-700 px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#f1f5f9] transition">
                    <span>Date Range</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <div class="overflow-x-auto w-full"><table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-[#f1f5f9] border-b border-[#e2e8f0] text-xs font-bold text-[#475569] uppercase tracking-wider">
                            <th class="px-5 py-4 w-12 text-center">#</th>
                            <th class="px-5 py-4">Claim ID</th>
                            <th class="px-5 py-4">Customer</th>
                            <th class="px-5 py-4">Merchant</th>
                            <th class="px-5 py-4">Reward</th>
                            <th class="px-5 py-4 text-center">Points Used</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Claimed At</th>
                            <th class="px-5 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <!-- Row 1 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">1</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10001</td>
                            <td class="px-5 py-4">Rahul Sharma</td>
                            <td class="px-5 py-4 text-[#475569]">Coffee House</td>
                            <td class="px-5 py-4 text-[#475569]">Free Coffee</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">100</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 24, 2026, 11:20 AM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 2 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">2</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10002</td>
                            <td class="px-5 py-4">Priya Singh</td>
                            <td class="px-5 py-4 text-[#475569]">Pizza Plaza</td>
                            <td class="px-5 py-4 text-[#475569]">20% Discount</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">150</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 24, 2026, 10:45 AM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 3 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">3</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10003</td>
                            <td class="px-5 py-4">Amit Patel</td>
                            <td class="px-5 py-4 text-[#475569]">Burger Point</td>
                            <td class="px-5 py-4 text-[#475569]">Free Burger</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">200</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 24, 2026, 09:30 AM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 4 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">4</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10004</td>
                            <td class="px-5 py-4">Neha Verma</td>
                            <td class="px-5 py-4 text-[#475569]">Fashion Hub</td>
                            <td class="px-5 py-4 text-[#475569]">₹100 Off</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">250</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-orange-100 text-orange-700">Pending</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 24, 2026, 09:15 AM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 5 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">5</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10005</td>
                            <td class="px-5 py-4">Vikas Mehta</td>
                            <td class="px-5 py-4 text-[#475569]">Coffee House</td>
                            <td class="px-5 py-4 text-[#475569]">Free Sandwich</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">120</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 08:50 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 6 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">6</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10006</td>
                            <td class="px-5 py-4">Sneha Reddy</td>
                            <td class="px-5 py-4 text-[#475569]">Pizza Plaza</td>
                            <td class="px-5 py-4 text-[#475569]">Free Drink</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">80</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-[#8a0000]">Failed</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 08:20 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 7 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">7</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10007</td>
                            <td class="px-5 py-4">Karan Singh</td>
                            <td class="px-5 py-4 text-[#475569]">Burger Point</td>
                            <td class="px-5 py-4 text-[#475569]">20% Discount</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">150</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 07:45 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 8 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">8</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10008</td>
                            <td class="px-5 py-4">Ishita Malhotra</td>
                            <td class="px-5 py-4 text-[#475569]">Fashion Hub</td>
                            <td class="px-5 py-4 text-[#475569]">₹200 Off</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">300</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-orange-100 text-orange-700">Pending</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 07:10 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 9 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">9</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10009</td>
                            <td class="px-5 py-4">Rohit Kumar</td>
                            <td class="px-5 py-4 text-[#475569]">Coffee House</td>
                            <td class="px-5 py-4 text-[#475569]">Free Coffee</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">100</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-700">Success</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 06:30 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                        <!-- Row 10 -->
                        <tr class="hover:bg-[#f1f5f9]/50 transition">
                            <td class="px-5 py-4 text-center text-[#475569]">10</td>
                            <td class="px-5 py-4 font-bold text-slate-700">CLM10010</td>
                            <td class="px-5 py-4">Anjali Gupta</td>
                            <td class="px-5 py-4 text-[#475569]">Pizza Plaza</td>
                            <td class="px-5 py-4 text-[#475569]">Free Pizza Slice</td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">180</td>
                            <td class="px-5 py-4"><span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-red-100 text-[#8a0000]">Failed</span>
                            </td>
                            <td class="px-5 py-4 text-[#475569] text-xs">May 23, 2026, 05:50 PM</td>
                            <td class="px-5 py-4 text-center"><button
                                    class="text-slate-400 hover:text-[#b00000] transition"><svg class="w-5 h-5 inline"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg></button></td>
                        </tr>
                    </tbody>
                </table></div>
            </div>

            <!-- Pagination Footer -->
            <div
                class="p-4 border-t border-[#e2e8f0] flex flex-col md:flex-row items-center justify-between gap-4 bg-white text-sm text-[#475569]">
                <div class="font-medium">Showing 1 to 10 of 245 entries</div>
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
                        class="w-8 h-8 flex items-center justify-center rounded-xl border border-[#e2e8f0] text-[#475569] hover:bg-[#f1f5f9] font-bold transition">25</button>
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




