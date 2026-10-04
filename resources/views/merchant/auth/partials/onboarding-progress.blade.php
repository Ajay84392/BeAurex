{{-- 7-step merchant onboarding progress. $step = current step (1-7), $label = its name. --}}
<div class="bg-white border border-[#e2e8f0] rounded-2xl px-4 py-4 shadow-sm" aria-label="Onboarding step {{ $step }} of 7">
    <p class="text-[10px] font-bold text-slate-700 mb-3 text-center">Merchant Onboarding</p>
    <div class="flex items-center justify-between">
        @for($i = 1; $i <= 7; $i++)
            @if($i < $step)
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#22C55E]" aria-hidden="true">&#10003;</div>
            @elseif($i === $step)
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-black text-white bg-[#b00000] ring-2 ring-red-100 ring-offset-1">{{ $i }}</div>
            @else
                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold bg-slate-100 text-slate-400">{{ $i }}</div>
            @endif
            @if($i < 7)
                <div class="flex-1 h-px mx-1 {{ $i < $step ? 'bg-green-200' : 'bg-slate-200' }}"></div>
            @endif
        @endfor
    </div>
    <div class="flex justify-between mt-1.5 px-1">
        <span class="text-[8px] font-semibold text-green-600">Login</span>
        <span class="text-[8px] font-bold text-[#b00000]">{{ $label }}</span>
        <span class="text-[8px] font-medium text-slate-400">Complete</span>
    </div>
</div>
