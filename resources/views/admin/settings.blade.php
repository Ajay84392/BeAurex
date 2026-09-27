@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
    <div class="flex-1 overflow-auto p-6 md:p-10">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Settings</h1>
            <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
                <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-[#0f172a]">Settings</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-6 md:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-[#0f172a]">Settings Modules</h2>
                <p class="text-sm text-[#475569]">Manage all platform settings and configurations from here.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                <!-- Platform -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">Platform</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage platform name, logo, contact
                                details and business information.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Status</span> <span
                                    class="px-2 py-0.5 bg-emerald-50 text-[#22C55E] rounded font-bold border border-emerald-100">Completed</span>
                            </div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Last Updated</span> <span
                                    class="font-medium text-slate-700">May 24, 2026 11:20 AM</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/platform"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

                <!-- Contact -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-emerald-50 text-[#22C55E] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">Contact</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage support email, phone number,
                                address and social media links.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Status</span> <span
                                    class="px-2 py-0.5 bg-emerald-50 text-[#22C55E] rounded font-bold border border-emerald-100">Completed</span>
                            </div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Last Updated</span> <span
                                    class="font-medium text-slate-700">May 24, 2026 10:45 AM</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/contact"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

                <!-- Brand -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">Brand</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage platform logo, favicon and
                                primary & secondary brand colors.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Status</span> <span
                                    class="px-2 py-0.5 bg-emerald-50 text-[#22C55E] rounded font-bold border border-emerald-100">Completed</span>
                            </div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Last Updated</span> <span
                                    class="font-medium text-slate-700">May 24, 2026 09:30 AM</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/brand"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

                <!-- FAQ -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">FAQ</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage frequently asked questions
                                displayed to merchants and customers.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Total FAQs</span>
                                <span class="font-medium text-slate-700">48</span></div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Last Updated</span> <span
                                    class="font-medium text-slate-700">May 24, 2026 08:50 AM</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/faq"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

                <!-- Privacy Policy -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-red-50 text-[#EF4444] flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">Privacy Policy</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage the privacy policy content and
                                keep users informed.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Last
                                    Updated</span> <span class="font-medium text-slate-700">May 24, 2026 08:20 AM</span>
                            </div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Version</span> <span
                                    class="font-medium text-slate-700">1.0</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/privacy-policy"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

                <!-- Terms & Conditions -->
                <div class="border border-[#e2e8f0] rounded-xl p-6 flex flex-col hover:shadow-md transition bg-white">
                    <div class="flex items-start space-x-4 mb-6">
                        <div
                            class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#0f172a]">Terms & Conditions</h3>
                            <p class="text-[11px] text-[#475569] mt-1 leading-snug">Manage terms & conditions content and
                                platform usage policies.</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-xs mb-6 text-[#475569]">
                        <div>
                            <div class="mb-2"><span class="font-bold text-slate-400 w-20 inline-block">Last
                                    Updated</span> <span class="font-medium text-slate-700">May 24, 2026 07:45 AM</span>
                            </div>
                            <div><span class="font-bold text-slate-400 w-20 inline-block">Version</span> <span
                                    class="font-medium text-slate-700">1.0</span></div>
                        </div>
                    </div>
                    <a href="/admin/settings/terms"
                        class="mt-auto block w-full py-2 border border-red-200 text-[#b00000] font-bold text-sm text-center rounded-xl hover:bg-red-50 transition">Manage</a>
                </div>

            </div>
        </div>
    </div>
@endsection






