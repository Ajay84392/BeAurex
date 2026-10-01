{{-- Save result for the profile pages: success, a save failure, or what to fix. --}}
@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center" role="status">
        <svg class="w-5 h-5 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold" role="alert">{{ session('error') }}</div>
@endif
@if($errors->any())
    @php
        // Errors not tied to a field on the page (e.g. "Your session expired") must be spelled out.
        $general = collect($errors->getMessages())->only(['form'])->flatten();
    @endphp
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold space-y-1" role="alert">
        @foreach($general as $message)
            <p>{{ $message }}</p>
        @endforeach
        @if($errors->count() > $general->count())
            <p>Please fix the highlighted fields below.</p>
        @endif
    </div>
@endif
