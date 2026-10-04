{{-- Plan text / background colour pickers. Empty value = default card colours. $plan is optional (create form). --}}
<div class="mb-8">
    <h2 class="text-lg font-bold text-[#0f172a] mb-1">Plan Colours</h2>
    <p class="text-sm text-[#475569] mb-6">Choose the text and background colour of this plan's card on the home page. Leave on Default to keep the standard look.</p>

    <div class="flex flex-col md:flex-row gap-6 lg:gap-12">
        @foreach(['text_color' => ['Plan Text Colour', '#0f172a'], 'bg_color' => ['Plan Background Colour', '#ffffff']] as $field => [$label, $fallback])
            @php($value = old($field, $plan->$field ?? ''))
            <div class="flex-1">
                <label for="input_{{ $field }}" class="block text-xs font-bold text-slate-700 mb-1.5">{{ $label }}</label>
                <div class="flex items-center gap-2">
                    <input type="color" id="pick_{{ $field }}" value="{{ $value ?: $fallback }}" aria-label="Pick {{ strtolower($label) }}"
                        oninput="setPlanColor('{{ $field }}', this.value)"
                        class="w-11 h-11 p-1 rounded-xl border border-[#e2e8f0] bg-white cursor-pointer flex-shrink-0">
                    <input type="text" id="input_{{ $field }}" name="{{ $field }}" value="{{ $value }}" placeholder="Default" maxlength="7"
                        pattern="#[0-9a-fA-F]{6}" title="A colour like #b00000"
                        oninput="syncPlanColorPicker('{{ $field }}')"
                        class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] uppercase {{ $errors->has($field) ? 'border-red-400' : 'border-[#e2e8f0]' }}">
                    <button type="button" onclick="setPlanColor('{{ $field }}', '')"
                        class="px-3 py-2.5 rounded-xl border border-[#e2e8f0] text-slate-600 text-xs font-bold hover:bg-[#f1f5f9] transition flex-shrink-0">Default</button>
                </div>
                @error($field)
                    <p class="mt-1.5 text-[11px] font-semibold text-[#EF4444]">{{ $message }}</p>
                @enderror
            </div>
        @endforeach
    </div>
</div>
