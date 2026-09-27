<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>BeAurex Admin - @yield('title', 'Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="/favicon.jpg">
    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @yield('styles')
    </style>
</head>

<body class="bg-[#f1f5f9] font-sans antialiased text-[#0f172a] h-[100dvh] flex overflow-hidden" x-data="{ logoutAdminModal: false, sidebarOpen: false }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 md:hidden" style="display: none;" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 w-[240px] bg-[#b00000] text-white flex flex-col h-full overflow-y-auto shadow-2xl flex-shrink-0 z-50 transform md:translate-x-0 transition-transform duration-200 ease-in-out">
        <!-- Logo -->
        <div class="h-[72px] flex items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="/images/logo.jpg" alt="BeAurex Logo" class="w-10 h-10 rounded-xl object-cover bg-white">
                <div>
                    <div class="text-xl font-black leading-tight tracking-tight">BeAurex</div>
                    <div class="text-[11px] font-medium text-red-200">Admin Panel</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="/admin/dashboard"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/dashboard') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/dashboard') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="/admin/merchants"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/merchant*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/merchant*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
                <span>Merchants</span>
            </a>

            <a href="/admin/plans"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/plan*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/plan*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                    </path>
                </svg>
                <span>Plans</span>
            </a>

            <a href="/admin/customers"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/customer*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/customer*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span>Customer</span>
            </a>

            <a href="/admin/offers"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/offer*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/offer*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                </svg>
                <span>Offers</span>
            </a>

            <a href="/admin/claims"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/claim*') || request()->is('admin/referral*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/claim*') || request()->is('admin/referral*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span>Claims & Referrals</span>
            </a>



            <a href="/admin/coupons"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/coupon*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/coupon*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                    </path>
                </svg>
                <span>Coupons</span>
            </a>

            <a href="/admin/settings"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('admin/setting*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('admin/setting*') ? 'text-white' : '' }}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Settings</span>
            </a>


        </nav>

        <!-- Footer Info -->
        <div class="px-6 py-6 border-t border-[#b00000] mt-auto">
            <p class="text-[11px] text-red-200 leading-tight">
                &copy; 2026 BeAurex.<br>
                All rights reserved.
            </p>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full bg-[#f1f5f9] overflow-hidden">

        <!-- Topbar -->
        <header class="h-[72px] bg-white border-b border-[#e2e8f0] flex items-center justify-between px-6 flex-shrink-0 z-30">
            <!-- Mobile Menu / Hamburger -->
            <button @click="sidebarOpen = true" class="text-[#475569] hover:text-slate-700 focus:outline-none md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Right side: Notifications & Profile -->
            <div class="flex items-center space-x-6 ml-auto">
                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="flex items-center space-x-3 cursor-pointer">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin&background=0D8ABC&color=fff"
                            alt="Super Admin" class="w-9 h-9 rounded-full object-cover">
                        <div class="hidden md:block text-right">
                            <div class="text-sm font-bold text-[#0f172a] leading-tight">Super Admin</div>
                            <div class="text-[11px] font-semibold text-[#475569]">Owner</div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 hidden md:block" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                        <a href="/admin/profile" class="block px-4 py-2 text-sm text-slate-700 hover:bg-[#f1f5f9] hover:text-[#8a0000]">My Profile</a>
                        <a href="/admin/settings" class="block px-4 py-2 text-sm text-slate-700 hover:bg-[#f1f5f9] hover:text-[#8a0000]">Settings</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="javascript:void(0)" @click.prevent="logoutAdminModal = true" class="block px-4 py-2 text-sm text-[#b00000] hover:bg-red-50">Logout</a>
                    </div>
                </div>
            </div>
        </header>

        <!-- View Content -->
        @yield('content')

    </main>

    <!-- Logout Confirmation Modal Overlay -->
    <div x-show="logoutAdminModal" style="display: none;" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-6 text-center border border-slate-100 relative" @click.away="logoutAdminModal = false">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#b00000] mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-[#0f172a] mb-2">Log Out?</h3>
            <p class="text-sm text-[#475569] mb-6 leading-relaxed">
                Are you sure you want to log out from your account?
            </p>
            <div class="flex space-x-3">
                <button @click="logoutAdminModal = false" class="flex-1 py-2.5 bg-white border border-[#8a0000] text-[#8a0000] font-bold rounded-xl hover:bg-red-50 transition">Cancel</button>
                <a href="/admin/logout" class="flex-1 py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition block text-center">Log Out</a>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>

</html>




