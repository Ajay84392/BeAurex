{{-- "Sign In | Sign Up" switch for the customer and merchant portals. $role = customer|merchant, $active = signin|signup --}}
@php
    $links = [
        'signin' => ['label' => 'Sign In', 'url' => url("/$role/login")],
        'signup' => ['label' => 'Sign Up', 'url' => url("/$role/register")],
    ];
@endphp
<nav class="grid grid-cols-2 gap-1 bg-[#f1f5f9] p-1 rounded-xl mb-5 text-xs font-bold" aria-label="Sign in or sign up">
    @foreach($links as $key => $link)
        @if($key === $active)
            <span class="py-2 rounded-lg text-center bg-white text-[#b00000] shadow-sm" aria-current="page">{{ $link['label'] }}</span>
        @else
            <a href="{{ $link['url'] }}" class="py-2 rounded-lg text-center text-slate-500 hover:text-slate-700 hover:bg-white/60 transition">{{ $link['label'] }}</a>
        @endif
    @endforeach
</nav>
