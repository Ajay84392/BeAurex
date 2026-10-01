<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>@yield('title', 'Customer') - BeAurex</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @yield('styles')
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] antialiased h-[100dvh] overflow-hidden flex relative" x-data="{ sidebarOpen: false, logoutModal: false }">

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 md:hidden" style="display:none;" @click="sidebarOpen = false"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 w-[240px] max-w-[85vw] bg-[#b00000] text-white flex flex-col flex-shrink-0 h-full overflow-y-auto shadow-2xl z-50 transform md:translate-x-0 transition-transform duration-200 ease-in-out">
        <!-- Logo -->
        <div class="h-[72px] flex items-center px-6">
            <div class="flex items-center space-x-3">
                <img src="/images/logo.jpg" alt="BeAurex Logo" class="w-10 h-10 rounded-xl object-cover bg-white">
                <div>
                    <div class="text-xl font-black leading-tight tracking-tight">BeAurex</div>
                    <div class="text-[11px] font-medium text-red-200">Customer Panel</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="/customer"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('customer') || request()->is('customer/dashboard') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('customer') || request()->is('customer/dashboard') ? 'text-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span>Home</span>
            </a>

            <a href="/customer/scan"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('customer/scan*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('customer/scan*') ? 'text-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"></path></svg>
                <span>Scan QR</span>
            </a>





            <a href="/customer/claim-reward"
                class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-semibold transition {{ request()->is('customer/claim-reward*') ? 'bg-[#8a0000] text-white' : 'text-red-100 hover:bg-[#8a0000] hover:text-white' }}">
                <svg class="w-5 h-5 {{ request()->is('customer/claim-reward*') ? 'text-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Rewards</span>
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
    <main class="flex-1 min-w-0 flex flex-col h-full bg-[#f1f5f9] overflow-hidden w-full relative">
        
        <!-- Topbar -->
        <header class="h-[72px] bg-white border-b border-[#e2e8f0] hidden md:flex items-center justify-between px-6 flex-shrink-0 w-full">
            
            <div class="flex items-center space-x-3 md:hidden">
                <img src="/images/logo.jpg" alt="BeAurex Logo" class="w-8 h-8 rounded-xl object-cover bg-white">
                <div class="text-lg font-black leading-tight tracking-tight text-[#b00000]">BeAurex</div>
            </div>

            <!-- Right side: Profile Link -->
            <div class="flex items-center ml-auto">
                <a href="/customer/profile" class="flex items-center space-x-3 select-none bg-[#f1f5f9] hover:bg-slate-100 border border-[#e2e8f0] px-3 py-1.5 rounded-full transition">
                    @php
                        $nameParts = explode(' ', auth()->user()->name ?? 'Customer');
                        $initials = strtoupper(substr($nameParts[0], 0, 1));
                        if (count($nameParts) > 1) {
                            $initials .= strtoupper(substr($nameParts[1], 0, 1));
                        }
                    @endphp
                    <div class="w-8 h-8 rounded-full bg-red-50 text-[#b00000] flex items-center justify-center text-xs font-bold shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="hidden md:block text-left mr-2">
                        <div class="text-sm font-bold text-[#0f172a] leading-tight">{{ auth()->user()->name ?? 'Customer' }}</div>
                        <div class="text-[11px] font-semibold text-[#475569]">Member</div>
                    </div>
                </a>
            </div>
        </header>

        <!-- View Content -->
        <div class="w-full max-w-7xl mx-auto md:p-8 overflow-auto flex-1">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Bottom Navigation (Visible only on mobile) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-[#e2e8f0] z-50 px-8 py-2 shadow-[0_-4px_20px_-5px_rgba(0,0,0,0.05)]">
        <div class="flex justify-between items-center relative">
            
            <a href="/customer" class="flex flex-col items-center p-2 {{ request()->is('customer') || request()->is('customer/dashboard') ? 'text-[#b00000]' : 'text-slate-400' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-bold tracking-wide">Home</span>
            </a>

            <!-- Scan QR Center Button -->
            <div class="relative -top-6">
                <a href="/customer/scan" class="w-16 h-16 {{ request()->is('customer/scan') ? 'bg-[#8a0000] shadow-md transform scale-95' : 'bg-[#b00000] shadow-[0_8px_20px_-4px_rgba(144,0,0,0.5)] active:scale-95' }} rounded-full flex items-center justify-center text-white border-[5px] border-white transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z"></path></svg>
                </a>
            </div>

            <a href="/customer/claim-reward" class="flex flex-col items-center p-2 {{ request()->is('customer/claim-reward*') ? 'text-[#b00000]' : 'text-slate-400' }}">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[10px] font-bold tracking-wide">Rewards</span>
            </a>



        </div>
    </nav>

    <!-- Logout Confirmation Modal Overlay -->
    <div x-show="logoutModal" style="display: none;" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.15)] max-w-sm w-full p-6 text-center border border-slate-100 relative" @click.away="logoutModal = false">
            <div class="w-12 h-12 rounded-full bg-red-50 text-[#b00000] mx-auto flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-[#0f172a] mb-2">Log Out?</h3>
            <p class="text-sm text-[#475569] mb-6 leading-relaxed">
                Are you sure you want to log out from your account?
            </p>
            <div class="flex space-x-3">
                <button @click="logoutModal = false" class="flex-1 py-2.5 bg-white border border-[#8a0000] text-[#8a0000] font-bold rounded-xl hover:bg-red-50 transition">Cancel</button>
                <form method="POST" action="{{ route('customer.logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-[#b00000] text-white font-bold rounded-xl hover:bg-[#8a0000] transition">Log Out</button>
                </form>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>








