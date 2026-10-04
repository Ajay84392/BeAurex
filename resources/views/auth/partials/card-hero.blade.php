{{-- Pink card header shared by every auth page: brand, curved bottom edge and storefront illustration. $label = portal name shown under the brand --}}
<div class="relative px-6 pt-6 pb-0" style="background: linear-gradient(180deg, #fde8e8 0%, #fdf1f1 70%, #fff 100%);">
    <a href="{{ url('/') }}" class="relative z-10 inline-flex items-center space-x-2.5">
        <img src="/images/logo.jpg" alt="BeAurex" class="w-10 h-10 rounded-xl object-cover shadow-sm">
        <span class="leading-tight">
            <span class="block text-xl font-black tracking-tight text-[#0f172a]">Be<span class="text-[#b00000]">Aurex</span></span>
            <span class="block text-sm font-medium text-[#334155] -mt-0.5">{{ $label }}</span>
        </span>
    </a>

    <svg class="absolute left-0 right-0 bottom-0 w-full h-16 text-white" viewBox="0 0 400 60" preserveAspectRatio="none" aria-hidden="true">
        <path fill="currentColor" d="M0 40 C 90 0, 200 70, 400 10 L400 60 L0 60 Z"/>
    </svg>

    {{-- Faded storefront --}}
    <svg class="relative mx-auto mt-2 w-40 h-20 text-[#f4c7c7]" viewBox="0 0 160 80" fill="currentColor" aria-hidden="true">
        <rect x="22" y="30" width="86" height="48" rx="2" opacity=".55"/>
        <path d="M16 18h98l6 14H10z" opacity=".8"/>
        <path d="M16 32h14v6a7 7 0 01-14 0zM30 32h14v6a7 7 0 01-14 0zM44 32h14v6a7 7 0 01-14 0zM58 32h14v6a7 7 0 01-14 0zM72 32h14v6a7 7 0 01-14 0zM86 32h14v6a7 7 0 01-14 0zM100 32h14v6a7 7 0 01-14 0z" opacity=".9"/>
        <rect x="32" y="48" width="26" height="18" rx="1.5" fill="#fff" opacity=".7"/>
        <rect x="68" y="48" width="18" height="30" rx="1.5" fill="#fff" opacity=".7"/>
        <rect x="118" y="44" width="12" height="34" rx="2" opacity=".6"/>
        <circle cx="124" cy="38" r="9" opacity=".5"/>
        <rect x="136" y="54" width="10" height="24" rx="2" opacity=".5"/>
    </svg>
</div>
