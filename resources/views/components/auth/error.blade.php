@props(['name'])

@error($name)
    <p id="{{ $name }}-error" class="field-error mt-1.5 text-[11px] font-semibold text-[#EF4444] flex items-start space-x-1" role="alert">
        <svg class="w-3.5 h-3.5 shrink-0 mt-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span>{{ $message }}</span>
    </p>
@enderror
