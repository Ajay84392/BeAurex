<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @include('partials.theme')
    <title>BeAurex - Turn Every Visit Into A Repeat Customer</title>
    <style>
        .modal {
            display: none;
        }

        .modal.active {
            display: flex;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <!-- Top Trust Notification Bar -->
    <div
        class="bg-slate-900 text-white text-center py-2.5 text-xs font-bold tracking-wide px-4 flex items-center justify-center space-x-2">
        <span>🇮🇳 Trusted by Fast-Growing Businesses Across India</span>
        <span class="hidden md:inline bg-red-600 px-2 py-0.5 rounded text-[10px] uppercase animate-pulse">2-Day Free
            Trial Active</span>
    </div>

    <!-- Navigation Bar -->
    <nav class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="/images/logo.jpg" alt="BeAurex Logo" class="w-9 h-9 rounded-lg object-cover bg-white">
                <span class="text-xl font-black tracking-tight text-slate-900">BeAurex</span>
            </div>

            <!-- Portals Dropdown / Action -->
            <div class="flex items-center space-x-4 sm:space-x-6">
                <div>
                    <button onclick="document.getElementById('loginModal').classList.add('active')"
                        class="text-slate-600 hover:text-slate-900 font-bold text-sm sm:text-base cursor-pointer flex items-center space-x-1">
                        <span>Login</span>
                    </button>
                </div>

                <a href="/merchant/register"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2.5 rounded-xl shadow-md transition transform hover:-translate-y-0.5 cursor-pointer text-sm sm:text-base">Start
                    Free Trial</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center mb-14">

            <!-- Left: Text content -->
            <div class="text-left">
                <h1
                    class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight mb-6">
                    Turn Every Customer Visit Into A <span class="text-red-600">Repeat Customer Test</span>
                </h1>

                <p class="text-lg sm:text-xl text-slate-600 mb-8 font-medium leading-relaxed">
                    A powerful system that turns your business into a customer magnet.
                </p>

                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 max-w-md mb-6">
                    <a href="/merchant/register"
                        class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-md shadow-lg shadow-red-600/20 transition transform hover:-translate-y-0.5 cursor-pointer">
                        Claim Your 2-Day Free Trial
                    </a>
                </div>

                <div class="bg-slate-100 border border-slate-200/60 rounded-xl p-3.5 inline-flex items-center">
                    {{-- <span class="text-lg mr-2">🇮🇳</span> --}}
                    <p class="text-xs sm:text-sm text-slate-700 font-medium leading-relaxed">
                        A Proudly Indian Platform Built with love to empower local retailers & businesses across INDIA.
                    </p>
                </div>
            </div>

            <!-- Right: Hero Image -->
            <div class="flex justify-center lg:justify-end">
                <img src="/images/hero_woman.png" alt="Scan to Earn Stamps & Rewards"
                    class="w-full max-w-md lg:max-w-lg xl:max-w-xl h-auto rounded-3xl drop-shadow-xl">
            </div>
        </div>

        <!-- CORE STRENGTHS GRID -->
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Zero Hidden Contracts</h3>
                <p class="text-slate-500 text-xs leading-relaxed">No credit card required to start. No auto-debits, No
                    hidden charges, No forced renewals</p>
            </div>

            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 002-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">Quick & Easy</h3>
                <p class="text-slate-500 text-xs leading-relaxed">Customers scan instantly through their default
                    smartphone browser. No slow app downloads or long account setups.</p>
            </div>

            <div class="bg-white border border-slate-200/80 p-6 rounded-2xl shadow-xs hover:shadow-md transition">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-900 text-base mb-1">100% Guarded Privacy</h3>
                <p class="text-slate-500 text-xs leading-relaxed">We secure and isolate user details. Customers get a
                    safe experience without facing unwanted marketing spam.</p>
            </div>
        </div>
    </header>

    <!-- PAIN POINTS & SOLUTIONS SECTION -->
    <section class="bg-white py-20 border-y border-slate-200/80">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="text-xs font-bold text-red-600 uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full">The
                    Reality Check</span>
                <h2 class="text-3xl font-black text-slate-950 mt-3">Why Traditional Stores Lose Valued Customers</h2>
                <p class="text-slate-600 text-sm mt-3 font-medium leading-relaxed">
                    Without a smart system, customers naturally shift to online shopping apps.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                <!-- Pain Points Box -->
                <div
                    class="bg-rose-50/60 border-2 border-rose-100 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-rose-600 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-sm">
                                ✕</div>
                            <h3 class="text-rose-950 font-black text-xl tracking-tight">The Retention Leak</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="bg-white p-4 rounded-xl border border-rose-100 shadow-2xs">
                                <h4 class="font-bold text-rose-900 text-sm flex items-center"><span
                                        class="mr-2">❌</span> Lost After the Sale</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Most customers buy, pay, and
                                    leave, making them completely unreachable tomorrow.</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-rose-100 shadow-2xs">
                                <h4 class="font-bold text-rose-900 text-sm flex items-center"><span
                                        class="mr-2">❌</span> Profit-Bleeding Discounts</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Displaying flat percentage cuts
                                    on checkout counters permanently burns your daily profit margin without driving
                                    future curiosity.</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-rose-100 shadow-2xs">
                                <h4 class="font-bold text-rose-900 text-sm flex items-center"><span
                                        class="mr-2">❌</span> Aggressive App Competition</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Mega online delivery apps use
                                    highly aggressive marketing systems to capture your daily offline neighborhood
                                    clients away.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solutions Box -->
                <div
                    class="bg-emerald-50/60 border-2 border-emerald-100 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex items-center space-x-3 mb-6">
                            <div
                                class="w-10 h-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-sm">
                                ✓</div>
                            <h3 class="text-emerald-950 font-black text-xl tracking-tight">The BeAurex Solution</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs">
                                <h4 class="font-bold text-emerald-900 text-sm flex items-center"><span
                                        class="mr-2">✓</span> Automated Dynamic Retention</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Customers simply scan the QR
                                    code at your counter. It opens on their phone, where they can see rewards that
                                    encourage them to visit your shop again.</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs">
                                <h4 class="font-bold text-emerald-900 text-sm flex items-center"><span
                                        class="mr-2">✓</span> High-Dopamine Gamification</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">Scratch card curiosity mechanics
                                    convert regular checkout loops into highly interactive custom reward events that
                                    customers genuinely talk about with friends.</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-emerald-100 shadow-2xs">
                                <h4 class="font-bold text-emerald-900 text-sm flex items-center"><span
                                        class="mr-2">✓</span> 100% Privacy & Zero Spam</h4>
                                <p class="text-slate-600 text-xs mt-1 leading-relaxed">We protect user data completely.
                                    No spam messages. Customers feel completely safe and trust your store's digital
                                    ecosystem.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Setup Process -->
    <section class="bg-slate-50 py-20 border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-black text-center text-slate-900 mb-12">Setup in Just 3 Simple Steps
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white border border-slate-200 p-6 rounded-2xl text-center shadow-xs">
                    <div
                        class="w-10 h-10 bg-red-600 text-white font-bold rounded-xl flex items-center justify-center mx-auto mb-4">
                        1</div>
                    <h3 class="font-bold text-lg mb-2 text-slate-900">Register Your Business</h3>
                    <p class="text-slate-600 text-sm">Create your account inside two minutes and activate your live
                        trial block instantly.</p>
                </div>
                <div class="bg-white border border-slate-200 p-6 rounded-2xl text-center shadow-xs">
                    <div
                        class="w-10 h-10 bg-red-600 text-white font-bold rounded-xl flex items-center justify-center mx-auto mb-4">
                        2</div>
                    <h3 class="font-bold text-lg mb-2 text-slate-900">Display Your Shop QR</h3>
                    <p class="text-slate-600 text-sm">Download your custom counter configuration template from the
                        dashboard and print it.</p>
                </div>
                <div class="bg-white border border-slate-200 p-6 rounded-2xl text-center shadow-xs">
                    <div
                        class="w-10 h-10 bg-red-600 text-white font-bold rounded-xl flex items-center justify-center mx-auto mb-4">
                        3</div>
                    <h3 class="font-bold text-lg mb-2 text-slate-900">Watch Customers Return</h3>
                    <p class="text-slate-600 text-sm">Visitors scan to play instant scratch cards, keeping them loyal
                        to your local business brand.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- REDESIGNED PRICING PLANS SECTION (As per image_6173dc.png guidelines) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 bg-slate-50">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span
                class="text-xs font-bold text-red-600 uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full">Fair
                & Simple Pricing</span>
            <h2 class="text-3xl sm:text-4xl font-black text-slate-950 mt-3 mb-2">Invest in Your Business Growth</h2>
            <p class="text-slate-600 text-sm font-medium">Choose a timeline that works best for your expansion
                strategy. No hidden charges.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch max-w-6xl mx-auto">
            @if (isset($plans) && $plans->count() > 0)
                @foreach ($plans as $plan)
                    @php
                        $features = json_decode($plan->features) ?? [];
                    @endphp

                    @if (strtolower($plan->type) === 'professional')
                        <!-- Professional Plan (MOST POPULAR HIGHLIGHTED) -->
                        <div class="bg-white border-2 border-red-600 p-8 rounded-3xl shadow-xl relative flex flex-col justify-between pt-12 transform lg:-translate-y-2"
                            {{ $plan->color ? 'style=border-color:' . $plan->color : '' }}>
                            <span
                                class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-red-600 text-white text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider shadow-sm"
                                {{ $plan->color ? 'style=background-color:' . $plan->color : '' }}>Most Popular</span>
                            <div>
                                <h3 class="text-xl font-extrabold text-red-600 tracking-tight"
                                    {{ $plan->color ? 'style=color:' . $plan->color : '' }}>{{ $plan->name }}</h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                                    @if ($plan->detailed_description)
                                        <span
                                            class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹{{ number_format((float) $plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                                        ₹{{ number_format($plan->price) }} <span
                                            class="text-sm font-medium text-slate-500">/
                                            {{ $plan->billing_cycle }}</span></div>
                                    @if ($plan->short_description)
                                        <div
                                            class="text-red-600 text-xs font-bold mt-2.5 flex items-center bg-red-50 px-2 py-1 rounded w-fit">
                                            <span>{{ $plan->short_description }}</span>
                                        </div>
                                    @endif
                                </div>
                                <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                                    @foreach ($features as $feature)
                                        <li class="flex items-start text-xs"><span
                                                class="text-emerald-500 font-bold mr-2.5">✓</span> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="{{ $plan->button_link }}"
                                class="text-center w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl transition shadow-md shadow-red-600/10 cursor-pointer text-sm tracking-wide"
                                {{ $plan->color ? 'style=background-color:' . $plan->color . ';color:#fff;' : '' }}>{{ $plan->button_text }}</a>
                        </div>
                    @elseif(strtolower($plan->type) === 'legacy')
                        <!-- Legacy Plan (BEST VALUE HIGHLIGHTED) -->
                        <div
                            class="bg-slate-900 border border-slate-800 p-8 rounded-3xl shadow-sm text-white relative flex flex-col justify-between pt-12">
                            <span
                                class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-slate-700 text-amber-400 text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-wider border border-slate-600">Best
                                Value</span>
                            <div>
                                <h3 class="text-xl font-extrabold text-white tracking-tight">{{ $plan->name }}</h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-800">
                                    @if ($plan->detailed_description)
                                        <span
                                            class="text-sm font-bold text-slate-500 line-through tracking-wide block mb-1">₹{{ number_format((float) $plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-amber-400 tracking-tight">
                                        ₹{{ number_format($plan->price) }}</div>
                                    <div
                                        class="text-slate-300 text-[10px] font-bold uppercase tracking-widest mt-2.5 flex items-center space-x-1.5">
                                        <span
                                            class="bg-slate-800 px-2 py-0.5 rounded text-emerald-400">{{ $plan->billing_cycle }}</span>
                                        @if ($plan->short_description)
                                            <span
                                                class="bg-slate-800 px-2 py-0.5 rounded text-slate-400">{{ $plan->short_description }}</span>
                                        @endif
                                    </div>
                                </div>
                                <ul class="space-y-3.5 text-slate-300 text-sm mb-8">
                                    @foreach ($features as $feature)
                                        <li class="flex items-start text-xs"><span
                                                class="text-amber-400 font-bold mr-2.5">✓</span> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="{{ $plan->button_link }}"
                                class="text-center w-full bg-white hover:bg-slate-100 text-slate-900 font-bold py-3.5 rounded-xl transition cursor-pointer text-sm tracking-wide"
                                {{ $plan->color ? 'style=background-color:' . $plan->color . ';color:#fff;' : '' }}>{{ $plan->button_text }}</a>
                        </div>
                    @else
                        <!-- Standard Plan -->
                        <div
                            class="bg-white border border-slate-200/90 p-8 rounded-3xl shadow-sm hover:shadow-md transition flex flex-col justify-between relative pt-10">
                            <div>
                                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $plan->name }}
                                </h3>
                                <div class="mt-6 mb-6 pb-6 border-b border-slate-100">
                                    @if ($plan->detailed_description)
                                        <span
                                            class="text-sm font-bold text-slate-400 line-through tracking-wide block mb-1">₹{{ number_format((float) $plan->detailed_description) }}</span>
                                    @endif
                                    <div class="text-3xl font-black text-slate-900 tracking-tight">
                                        ₹{{ number_format($plan->price) }} <span
                                            class="text-sm font-medium text-slate-500">/
                                            {{ $plan->billing_cycle }}</span></div>
                                    @if ($plan->short_description)
                                        <div class="text-emerald-600 text-xs font-bold mt-2.5 flex items-center">
                                            <span>{{ $plan->short_description }}</span>
                                        </div>
                                    @endif
                                </div>
                                <ul class="space-y-3.5 text-slate-600 text-sm mb-8">
                                    @foreach ($features as $feature)
                                        <li class="flex items-start text-xs"><span
                                                class="text-emerald-500 font-bold mr-2.5">✓</span> {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <a href="{{ $plan->button_link }}"
                                class="text-center w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition cursor-pointer text-sm tracking-wide shadow-sm"
                                {{ $plan->color ? 'style=background-color:' . $plan->color . ';color:#fff;' : '' }}>{{ $plan->button_text }}</a>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <p class="text-center text-sm text-emerald-600 font-bold mt-12">🎉 Enjoy any plan free for 2 days. No payment
            required.</p>
    </section>

    <!-- FAQ Section -->
    <div class="bg-white py-20 border-b border-[#e2e8f0]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-black text-[#0f172a] mb-4">Frequently Asked Questions</h2>
                <p class="text-[#475569] font-medium">Everything you need to know about BeAurex</p>
            </div>
            <div class="space-y-3">
                @if (isset($faqs) && $faqs->count())
                    @foreach ($faqs as $faq)
                        <div class="bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl overflow-hidden cursor-pointer"
                            onclick="toggleFaq('faq-{{ $faq->id }}')">
                            <div class="flex justify-between items-center text-left p-5">
                                <h4 class="font-bold text-[#0f172a] pr-8 text-sm sm:text-base">{{ $faq->question }}
                                </h4>
                                <span id="faq-{{ $faq->id }}-icon"
                                    class="text-2xl font-black select-none flex-shrink-0"
                                    style="color:#b00000">+</span>
                            </div>
                            <div id="faq-{{ $faq->id }}-content"
                                class="hidden px-5 pb-5 text-[#475569] text-sm leading-relaxed border-t border-[#e2e8f0] pt-4">
                                {{ $faq->answer }}
                            </div>
                        </div>
                    @endforeach
                @else
                    @php
                        $defaultFaqs = [
                            [
                                'id' => 'a1',
                                'q' => 'What is BeAurex?',
                                'a' =>
                                    'BeAurex is a QR-based customer loyalty platform that helps local businesses retain customers by giving them stamps and rewards every time they visit.',
                            ],
                            [
                                'id' => 'a2',
                                'q' => 'Do customers need to download an app?',
                                'a' =>
                                    'No. Customers simply scan your QR code from their phone camera and everything opens directly in their browser. No app download needed.',
                            ],
                            [
                                'id' => 'a3',
                                'q' => 'How do I get started?',
                                'a' =>
                                    'Register your business, complete your profile, download your QR code, and display it at your counter. The whole setup takes less than 10 minutes.',
                            ],
                            [
                                'id' => 'a4',
                                'q' => 'Is there a free trial?',
                                'a' =>
                                    'Yes! Every new merchant gets a 2-day free trial with full access. No credit card required to start.',
                            ],
                            [
                                'id' => 'a5',
                                'q' => 'Can I customize the rewards?',
                                'a' =>
                                    'Absolutely. You can create custom offers and set the number of stamps required to unlock each reward from your merchant dashboard.',
                            ],
                            [
                                'id' => 'a6',
                                'q' => 'Is my customers data safe?',
                                'a' =>
                                    'Yes. We take privacy seriously. Customer data is encrypted and isolated. We never send spam messages or share data with third parties.',
                            ],
                        ];
                    @endphp
                    @foreach ($defaultFaqs as $faq)
                        <div class="bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl overflow-hidden cursor-pointer"
                            onclick="toggleFaq('faq-{{ $faq['id'] }}')">
                            <div class="flex justify-between items-center text-left p-5">
                                <h4 class="font-bold text-[#0f172a] pr-8 text-sm sm:text-base">{{ $faq['q'] }}
                                </h4>
                                <span id="faq-{{ $faq['id'] }}-icon"
                                    class="text-2xl font-black select-none flex-shrink-0"
                                    style="color:#b00000">+</span>
                            </div>
                            <div id="faq-{{ $faq['id'] }}-content"
                                class="hidden px-5 pb-5 text-[#475569] text-sm leading-relaxed border-t border-[#e2e8f0] pt-4">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div
            class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-10 shadow-sm grid grid-cols-1 md:grid-cols-5 gap-8">
            <div class="md:col-span-2 space-y-4">
                <h3 class="text-2xl font-black text-slate-900">Contact Us</h3>
                <p class="text-slate-500 text-sm leading-relaxed">Have custom workflow questions? Message our tracking
                    activation support desk.</p>
                <div class="text-xs text-slate-600 space-y-1 pt-2">
                    <p>📧 support@BeAurex.com</p>
                    <p>📍 Delhi NCR, India</p>
                </div>
            </div>
            <form class="md:col-span-3 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" placeholder="Your Name" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-600">
                    <input type="text" placeholder="Phone Number" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-600">
                </div>
                <input type="email" placeholder="Email Address" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-600">
                <textarea rows="3" placeholder="Write your message here..." required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-red-600"></textarea>
                <button type="submit"
                    class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 rounded-xl transition text-sm cursor-pointer">Send
                    Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="flex justify-center space-x-6 font-semibold text-slate-300">
                <a href="#" class="hover:text-white cursor-pointer">Terms & Conditions</a>
                <a href="#" class="hover:text-white cursor-pointer">Privacy Policy</a>
            </div>
            <p>© 2026 BeAurex Inc. Pure Value-Driven Manual Activation System.</p>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal"
        class="modal fixed inset-0 bg-slate-900/50 z-[100] items-center justify-center backdrop-blur-sm"
        onclick="if(event.target === this) this.classList.remove('active')">
        <div class="bg-white rounded-3xl w-full max-w-sm p-8 shadow-2xl relative">
            <button onclick="document.getElementById('loginModal').classList.remove('active')"
                class="absolute top-4 right-5 text-slate-400 hover:text-slate-700 text-3xl font-light">&times;</button>
            <h2 class="text-2xl font-black text-slate-900 text-center mb-6">Welcome Back</h2>
            <div class="flex flex-col space-y-4">
                <a href="/merchant/login"
                    class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-md shadow-md transition transform hover:-translate-y-0.5">
                    Merchant Login
                </a>
                <a href="/customer/login"
                    class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold px-6 py-3.5 rounded-xl text-md shadow-md transition transform hover:-translate-y-0.5">
                    Customer Login
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleFaq(id) {
            const content = document.getElementById(id + '-content');
            const icon = document.getElementById(id + '-icon');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.innerText = '−';
            } else {
                content.classList.add('hidden');
                icon.innerText = '+';
            }
        }
    </script>
</body>

</html>
