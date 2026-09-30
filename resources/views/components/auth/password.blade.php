@props(['name' => 'password', 'id' => null, 'label' => 'Password', 'placeholder' => 'Enter your password', 'strength' => false, 'confirms' => null])

@php($id ??= $name.'Input')

<div>
    <label for="{{ $id }}" class="auth-label">{{ $label }}</label>
    <div class="relative">
        <input type="password" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" required maxlength="64"
            autocomplete="{{ $strength || $confirms ? 'new-password' : 'current-password' }}"
            @if($strength) data-strength minlength="8" @endif
            @if($confirms) data-confirms="{{ $confirms }}" @endif
            class="auth-input pr-10 @error($name) border-red-400 @enderror">
        <button type="button" onclick="togglePwd('{{ $id }}')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
        </button>
    </div>

    @if($strength)
        <ul class="pw-rules grid grid-cols-2 gap-x-3 gap-y-1 mt-2 text-[11px] font-medium text-slate-400" data-for="{{ $id }}">
            <li data-rule="length">&#9675; 8+ characters</li>
            <li data-rule="letter">&#9675; A letter</li>
            <li data-rule="number">&#9675; A number</li>
            <li data-rule="symbol">&#9675; A symbol (!@#$…)</li>
        </ul>
    @endif

    @if($confirms)
        <p class="pw-match hidden mt-1.5 text-[11px] font-medium text-[#EF4444]" data-for="{{ $id }}">Passwords do not match</p>
    @endif
</div>
