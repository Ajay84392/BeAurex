{{--
    A profile section that is read-only until its pencil button is pressed.
    Used inside a form with x-data="profileEditor()" (see partials/profile-scripts).
    Locked fields sit in a disabled <fieldset>, so they are not submitted and keep their saved value.
    A section with a validation error opens already in edit mode.
--}}
@props([
    'key',
    'title',
    'subtitle' => null,
    'fields' => [],
    'titleClass' => 'text-[15px] font-black text-slate-900',
])

@php($open = $errors->hasAny($fields))

<section {{ $attributes }} data-section="{{ $key }}" x-init="edit['{{ $key }}'] = {{ $open ? 'true' : 'false' }}">
    <div class="flex items-start justify-between gap-3 mb-5">
        <div class="min-w-0">
            <h2 class="{{ $titleClass }}">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-[12px] text-slate-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        <button type="button" @click="toggle('{{ $key }}')"
            :aria-label="edit['{{ $key }}'] ? 'Cancel editing {{ $title }}' : 'Edit {{ $title }}'"
            :title="edit['{{ $key }}'] ? 'Cancel' : 'Edit {{ $title }}'"
            aria-label="Edit {{ $title }}" title="Edit {{ $title }}"
            class="shrink-0 inline-flex items-center justify-center h-9 rounded-full border transition shadow-sm"
            :class="edit['{{ $key }}'] ? 'px-3.5 bg-white border-slate-200 text-slate-600 hover:bg-slate-50' : 'w-9 bg-red-50 border-red-100 text-[#b00000] hover:bg-red-100'">
            <svg x-show="!edit['{{ $key }}']" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            <span x-show="edit['{{ $key }}']" style="display: none" class="flex items-center gap-1.5 text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                Cancel
            </span>
        </button>
    </div>

    <fieldset data-section-fields="{{ $key }}" :disabled="!edit['{{ $key }}']" @disabled(! $open) class="profile-fields min-w-0 border-0 p-0 m-0">
        {{ $slot }}
    </fieldset>
</section>
