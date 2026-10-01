@php
    $selectedLanguage = old('language', \App\Support\ProfileOptions::language($user->language));
    $selectedTimezone = old('timezone', \App\Support\ProfileOptions::timezone($user->timezone));
    $selectedDateFormat = old('date_format', \App\Support\ProfileOptions::dateFormat($user->date_format));
@endphp
<section class="border-t border-slate-100 pt-8">
    <h2 class="text-[15px] font-black text-slate-900 mb-5">Preferences</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label for="language" class="{{ $label }}">Language</label>
            <select id="language" name="language" class="{{ $input }} cursor-pointer @error('language') {{ $invalid }} @enderror">
                @foreach(\App\Support\ProfileOptions::LANGUAGES as $value => $text)
                    <option value="{{ $value }}" @selected($selectedLanguage === $value)>{{ $text }}</option>
                @endforeach
            </select>
            <x-auth.error name="language" />
        </div>

        <div>
            <label for="timezone" class="{{ $label }}">Timezone</label>
            <select id="timezone" name="timezone" class="{{ $input }} cursor-pointer @error('timezone') {{ $invalid }} @enderror">
                @foreach(\App\Support\ProfileOptions::TIMEZONES as $value => $text)
                    <option value="{{ $value }}" @selected($selectedTimezone === $value)>{{ $text }}</option>
                @endforeach
            </select>
            <x-auth.error name="timezone" />
        </div>

        <div>
            <label for="date_format" class="{{ $label }}">Date Format</label>
            <select id="date_format" name="date_format" class="{{ $input }} cursor-pointer @error('date_format') {{ $invalid }} @enderror">
                @foreach(\App\Support\ProfileOptions::dateFormatOptions() as $value => $example)
                    <option value="{{ $value }}" @selected($selectedDateFormat === $value)>{{ $example }}</option>
                @endforeach
            </select>
            <x-auth.error name="date_format" />
        </div>
    </div>
</section>
