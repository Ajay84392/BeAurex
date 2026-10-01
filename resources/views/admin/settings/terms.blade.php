@extends('layouts.admin')

@section('title', 'Policy Editor')

@section('content')
<!-- Include Quill Stylesheet -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Policy Editor</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/settings" class="hover:text-[#b00000] transition">Settings</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Policy Editor</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-bold flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-[#8a0000] text-sm font-bold">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="policyForm" action="{{ url('/admin/settings') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0]">
        @csrf
        <input type="hidden" name="group" value="terms">
        <input type="hidden" name="terms_conditions_content" id="policyContent">
        <input type="hidden" name="action" id="formAction" value="publish">
        
        <!-- Tabs -->
        <div class="flex border-b border-[#e2e8f0] px-6 pt-4">
            <a href="/admin/settings/privacy-policy" class="px-6 py-3 border-b-2 border-transparent text-[#475569] hover:text-slate-700 font-bold text-sm transition">Privacy Policy</a>
            <a href="/admin/settings/terms" class="px-6 py-3 border-b-2 border-[#b00000] text-[#b00000] font-bold text-sm">Terms & Conditions</a>
        </div>

        <div class="p-6 md:p-8 flex flex-col lg:flex-row gap-8 lg:gap-12">
            
            <!-- Editor Column -->
            <div class="flex-1 flex flex-col min-w-0">
                <h3 class="text-sm font-bold text-[#0f172a] mb-3">Editor</h3>
                
                <div class="flex-1 flex flex-col border border-[#e2e8f0] rounded-xl overflow-hidden bg-white">
                    <div id="editor-container" class="h-96">{!! $settings['terms_conditions_content'] ?? '' !!}</div>
                </div>

                <div class="flex items-center space-x-4 mt-3 text-[10px] text-[#475569] font-semibold">
                    <span id="wordCount">Words: 0</span>
                    <span id="charCount">Characters: 0</span>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-72 flex-shrink-0 space-y-8">
                
                <!-- Document Info -->
                <div class="bg-[#f1f5f9] rounded-xl border border-slate-100 p-5 space-y-5">
                    <h3 class="text-sm font-bold text-[#0f172a] border-b border-[#e2e8f0] pb-3">Document Info</h3>
                    
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Document Type</div>
                        <div class="text-sm font-semibold text-[#0f172a]">Terms & Conditions</div>
                    </div>
                    
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Status</div>
                        <div>
                            @if(($settings['terms_conditions_status'] ?? '') === 'Published')
                                <span class="px-2 py-1 text-[10px] font-bold text-emerald-700 bg-emerald-100 rounded">Published</span>
                            @elseif(($settings['terms_conditions_status'] ?? '') === 'Draft')
                                <span class="px-2 py-1 text-[10px] font-bold text-amber-700 bg-amber-100 rounded">Draft</span>
                            @else
                                <span class="px-2 py-1 text-[10px] font-bold text-[#475569] bg-slate-200 rounded">Unpublished</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Last Updated</div>
                        <div class="text-xs font-semibold text-[#0f172a]">{{ isset($settings['terms_conditions_last_updated']) ? \Carbon\Carbon::parse($settings['terms_conditions_last_updated'])->format('M d, Y h:i A') : '-' }}</div>
                    </div>
                    
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Version</div>
                        <div class="text-xs font-semibold text-[#0f172a]">{{ $settings['terms_conditions_version'] ?? '1.0' }}</div>
                    </div>
                    
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Published By</div>
                        <div class="text-xs font-semibold text-[#0f172a]">{{ $settings['terms_conditions_published_by'] ?? '-' }}</div>
                    </div>
                    
                    <div>
                        <div class="text-[10px] uppercase font-bold tracking-wider text-[#475569] mb-1">Published On</div>
                        <div class="text-xs font-semibold text-[#0f172a]">{{ isset($settings['terms_conditions_published_on']) ? \Carbon\Carbon::parse($settings['terms_conditions_published_on'])->format('M d, Y h:i A') : '-' }}</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-xl border border-[#e2e8f0] p-5 space-y-4">
                    <h3 class="text-sm font-bold text-[#0f172a] border-b border-slate-100 pb-3">Actions</h3>
                    
                    <button type="button" onclick="openPreview()" class="w-full flex items-center justify-center space-x-2 py-2.5 border border-[#e2e8f0] rounded-xl text-sm font-bold text-slate-700 hover:bg-[#f1f5f9] transition shadow-sm">
                        <svg class="w-4 h-4 text-[#475569]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <span>Preview</span>
                    </button>
                    
                    <button type="button" onclick="submitForm('draft')" class="w-full flex items-center justify-center space-x-2 py-2.5 border border-red-200 rounded-xl text-sm font-bold text-[#b00000] hover:bg-red-50 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span>Save Draft</span>
                    </button>
                    
                    <button type="button" onclick="submitForm('publish')" class="w-full flex items-center justify-center space-x-2 py-2.5 bg-[#b00000] rounded-xl text-sm font-bold text-white hover:bg-[#8a0000] transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span>Publish</span>
                    </button>
                </div>
            </div>

        </div>
        
        <div class="px-6 py-4 bg-amber-50/50 border-t border-amber-100 rounded-b-2xl flex items-start space-x-3 text-amber-800 text-xs font-semibold">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>Please preview the content before publishing. Published content will be visible to all users.</p>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background:rgba(0,0,0,0.6)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e2e8f0]">
            <h2 class="text-lg font-black text-[#0f172a]">Terms & Conditions Preview</h2>
            <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-8 overflow-y-auto flex-1 prose max-w-none" id="previewContent">
            <!-- Content injected via JS -->
        </div>
        <div class="px-6 py-4 border-t border-[#e2e8f0] bg-[#f1f5f9] rounded-b-2xl flex justify-end">
            <button onclick="document.getElementById('previewModal').classList.add('hidden')" class="px-6 py-2.5 bg-white border border-[#e2e8f0] text-slate-700 text-sm font-bold rounded-xl hover:bg-[#f1f5f9] transition">Close Preview</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: 'Write your terms and conditions here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    function updateCounts() {
        var text = quill.getText().trim();
        var words = text.length > 0 ? text.split(/\s+/).length : 0;
        var chars = text.length;
        document.getElementById('wordCount').innerText = 'Words: ' + words;
        document.getElementById('charCount').innerText = 'Characters: ' + chars;
    }

    quill.on('text-change', function() {
        updateCounts();
    });
    
    // Initial count
    updateCounts();

    function openPreview() {
        var content = quill.root.innerHTML;
        if(quill.getText().trim().length === 0) {
            content = '<p class="text-slate-400 italic">No content to preview.</p>';
        }
        document.getElementById('previewContent').innerHTML = content;
        document.getElementById('previewModal').classList.remove('hidden');
    }

    function submitForm(action) {
        var text = quill.getText().trim();
        if (text.length === 0) {
            alert('Content cannot be empty.');
            return;
        }

        document.getElementById('policyContent').value = quill.root.innerHTML;
        document.getElementById('formAction').value = action;
        document.getElementById('policyForm').submit();
    }
</script>
@endsection



