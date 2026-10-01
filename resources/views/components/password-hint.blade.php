@props(['for'])

{{--
    One-line password rule shown under every "new password" field. It turns green once the
    password meets the server rule (Password::defaults() in AppServiceProvider) and lists what
    is still missing while the user types.
--}}
<p class="mt-1.5 text-[11px] font-medium text-slate-500 flex items-start gap-1" data-pw-hint-for="{{ $for }}" aria-live="polite">
    <span data-pw-hint-icon aria-hidden="true">&#9675;</span>
    <span data-pw-hint-text>8+ characters with uppercase, lowercase, number &amp; symbol (!@#$…)</span>
</p>

@once
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const base = '8+ characters with uppercase, lowercase, number & symbol (!@#$…)';
        const rules = [
            ['8+ characters', v => v.length >= 8],
            ['an uppercase letter', v => /[A-Z]/.test(v)],
            ['a lowercase letter', v => /[a-z]/.test(v)],
            ['a number', v => /\d/.test(v)],
            ['a symbol', v => /[^A-Za-z0-9\s]/.test(v)],
        ];
        document.querySelectorAll('[data-pw-hint-for]').forEach(hint => {
            const input = document.getElementById(hint.dataset.pwHintFor);
            if (!input) return;
            const icon = hint.querySelector('[data-pw-hint-icon]');
            const text = hint.querySelector('[data-pw-hint-text]');
            const check = () => {
                const v = input.value;
                const missing = rules.filter(([, test]) => !test(v)).map(([label]) => label);
                const ok = missing.length === 0;
                const empty = v === '';
                // Inline colour: theme green when met, error red while missing, muted when empty.
                hint.style.color = ok ? '#16a34a' : (empty ? '' : '#EF4444');
                icon.innerHTML = ok ? '&#10003;' : '&#9675;';
                text.textContent = ok ? base : (empty ? base : 'Still needs: ' + missing.join(', '));
                // Empty is left to the field's own `required` check (profile pages allow blank = keep password).
                input.setCustomValidity(ok || empty ? '' : 'Use 8+ characters with uppercase, lowercase, a number and a symbol.');
            };
            input.addEventListener('input', check);
            check();
        });
    });
</script>
@endonce
