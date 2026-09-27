@extends('layouts.admin')

@section('title', 'Admin')

@section('content')
<div class="flex-1 overflow-auto p-6 md:p-10">

            
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
                            <!-- Row 1 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">1</td>
                                <td class="px-5 py-4">MRC-1001</td>
                                <td class="px-5 py-4">info@coffeecorner.com</td>
                                <td class="px-5 py-4 text-slate-600">May 24, 2025</td>
                                <td class="px-5 py-4 font-semibold">Coffee Corner</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 24, 2025 10:30 AM</td>
                                <td class="px-5 py-4">₹ 5,900.00</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Premium Plan</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 24, 2026</td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative group cursor-pointer">
                                        <div class="flex items-center space-x-1 text-emerald-600 font-bold text-xs">
                                            <span>Active</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <select class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold text-slate-700">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">2</td>
                                <td class="px-5 py-4">MRC-1002</td>
                                <td class="px-5 py-4">hello@burgerhub.com</td>
                                <td class="px-5 py-4 text-slate-600">May 24, 2025</td>
                                <td class="px-5 py-4 font-semibold">Burger Hub</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-blue-100 text-blue-700">Trial</span>
                                </td>
                                <td class="px-5 py-4 text-slate-400 text-xs">-</td>
                                <td class="px-5 py-4 text-slate-400">-</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Trial Plan</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Jun 07, 2025</td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative group cursor-pointer">
                                        <div class="flex items-center space-x-1 text-blue-600 font-bold text-xs">
                                            <span>Trial</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <select class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold text-slate-700">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </td>
                            </tr>

                            <!-- Row 3 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">3</td>
                                <td class="px-5 py-4">MRC-1003</td>
                                <td class="px-5 py-4">contact@pizzapoint.com</td>
                                <td class="px-5 py-4 text-slate-600">May 23, 2025</td>
                                <td class="px-5 py-4 font-semibold">Pizza Point</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700">Pending</span>
                                </td>
                                <td class="px-5 py-4 text-slate-400 text-xs">-</td>
                                <td class="px-5 py-4 text-slate-400">-</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Professional Plan</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 23, 2026</td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative group cursor-pointer">
                                        <div class="flex items-center space-x-1 text-amber-500 font-bold text-xs">
                                            <span>Pending</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <select class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold text-slate-700">
                                        <option>No</option>
                                        <option selected>Yes</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <!-- Row 4 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">4</td>
                                <td class="px-5 py-4">MRC-1004</td>
                                <td class="px-5 py-4">admin@fashionstore.com</td>
                                <td class="px-5 py-4 text-slate-600">May 23, 2025</td>
                                <td class="px-5 py-4 font-semibold">Fashion Store</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 23, 2025 02:15 PM</td>
                                <td class="px-5 py-4">₹ 5,900.00</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Basic Plan</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 23, 2026</td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative group cursor-pointer">
                                        <div class="flex items-center space-x-1 text-emerald-600 font-bold text-xs">
                                            <span>Active</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <select class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold text-slate-700">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </td>
                            </tr>
                            
                            <!-- Row 5 -->
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 text-center text-slate-500">5</td>
                                <td class="px-5 py-4">MRC-1005</td>
                                <td class="px-5 py-4">support@bookworld.com</td>
                                <td class="px-5 py-4 text-slate-600">May 22, 2025</td>
                                <td class="px-5 py-4 font-semibold">Book World</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700">Paid</span>
                                </td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 22, 2025 11:00 AM</td>
                                <td class="px-5 py-4">₹ 5,900.00</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">Basic Plan</td>
                                <td class="px-5 py-4 text-slate-600 text-xs">May 22, 2026</td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-xs font-semibold bg-red-50 text-red-600 px-3 py-1 rounded-lg border border-red-100 hover:bg-red-100 transition">Set Deal</button>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="relative group cursor-pointer">
                                        <div class="flex items-center space-x-1 text-red-600 font-bold text-xs">
                                            <span>Inactive</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button class="text-slate-400 hover:text-red-600 transition" title="View Details">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <select class="text-xs border border-slate-200 rounded px-2 py-1 bg-white focus:outline-none focus:ring-1 focus:ring-red-500 font-semibold text-slate-700">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div class="p-4 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-4 bg-white text-sm text-slate-500">
                    <div class="font-medium">Showing 1 to 10 of 1,248 entries</div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <span class="font-medium">Rows per page:</span>
                            <select class="border border-slate-200 rounded px-2 py-1 bg-white text-slate-700 font-semibold focus:outline-none focus:ring-1 focus:ring-red-500">
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center space-x-1">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-400 hover:bg-slate-50 transition" disabled>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-600 text-white font-bold shadow-sm">1</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">2</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">3</button>
                            <span class="w-8 h-8 flex items-center justify-center text-slate-400 font-bold">...</span>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">125</button>
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 flex items-center space-x-2 text-xs font-semibold text-amber-600 bg-amber-50 px-4 py-3 rounded-xl border border-amber-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>This table contains many columns. Scroll horizontally to view Plan details, Status, and more options.</span>
            </div>

        </div>

@endsection
