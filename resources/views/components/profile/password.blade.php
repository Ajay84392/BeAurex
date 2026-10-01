@props([
    'name',
    'label',
    'placeholder' => '',
    'autocomplete' => 'new-password',
    'strength' => false,
    'confirms' => null,
    'labelClass' => 'block text-[13px] font-bold text-slate-700 mb-2',
    'inputClass' => 'w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition',
])

@php($id = 'profile_'.$name)

<div>
    <label for="{{ $id }}" class="{{ $labelClass }}">{{ $label }}</label>
    <div class="relative">
        <input type="password" id="{{ $id }}" name="{{ $name }}" placeholder="{{ $placeholder }}" maxlength="64" autocomplete="{{ $autocomplete }}"
            @if($strength) minlength="8" @endif
            @if($confirms) data-pw-confirms="profile_{{ $confirms }}" @endif
            @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
            class="{{ $inputClass }} pr-11 @error($name) !border-red-400 @enderror">
        <button type="button" onclick="profileTogglePwd('{{ $id }}', this)" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-slate-400 hover:text-slate-600" aria-label="Show password" aria-pressed="false" title="Show password">
            <svg data-eye="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            <svg data-eye="hide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
        </button>
    </div>

    @if($strength)
        <x-password-hint :for="$id" />
    @endif

    @if($confirms)
        <p class="hidden mt-1.5 text-[11px] font-semibold text-[#EF4444]" data-pw-match-for="{{ $id }}">Passwords do not match.</p>
    @endif

    <x-auth.error :name="$name" />
</div>

@once
<script>
    // Eye button: show/hide the password and swap the eye / eye-off icon.
    function profileTogglePwd(inputId, btn) {
        const input = document.getElementById(inputId);
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('[data-eye="show"]').classList.toggle('hidden', show);
        btn.querySelector('[data-eye="hide"]').classList.toggle('hidden', !show);
        const label = show ? 'Hide password' : 'Show password';
        btn.setAttribute('aria-label', label);
        btn.setAttribute('title', label);
        btn.setAttribute('aria-pressed', show ? 'true' : 'false');
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Password rules are checked live by the password-hint component; empty = keep current password.

        // Confirm field must match the new password.
        document.querySelectorAll('input[data-pw-confirms]').forEach(input => {
            const source = document.getElementById(input.dataset.pwConfirms);
            const note = document.querySelector('[data-pw-match-for="' + input.id + '"]');
            const check = () => {
                const mismatch = (input.value !== '' || source.value !== '') && input.value !== source.value;
                note.classList.toggle('hidden', !(mismatch && input.value !== ''));
                input.setCustomValidity(mismatch ? 'Passwords do not match.' : '');
            };
            input.addEventListener('input', check);
            source.addEventListener('input', check);
        });

        // Current password is needed only when setting a new password or changing the login email.
        document.querySelectorAll('input[name="current_password"]').forEach(current => {
            const pw = current.form.querySelector('input[name="password"]');
            const email = current.form.querySelector('input[name="email"]');
            const sync = () => {
                const emailChanged = email && !email.readOnly && email.value.trim().toLowerCase() !== (email.dataset.original || '').toLowerCase();
                current.required = (pw && pw.value !== '') || emailChanged;
            };
            [pw, email].forEach(el => el && el.addEventListener('input', sync));
            sync();
        });
    });
</script>
@endonce
