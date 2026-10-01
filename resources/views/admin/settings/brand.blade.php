@extends('layouts.admin')

@section('title', 'Brand Management')

@section('content')
<div class="flex-1 min-w-0 overflow-auto p-4 sm:p-6 lg:p-10">
    
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Brand Management</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <a href="/admin/settings" class="hover:text-[#b00000] transition">Settings</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Brand Management</span>
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
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Form -->
        <form id="brandForm" action="{{ url('/admin/settings') }}" method="POST" enctype="multipart/form-data" class="flex-1 bg-white rounded-2xl shadow-sm border border-[#e2e8f0]">
            @csrf
            <input type="hidden" name="group" value="brand">
            
            <div class="p-6 md:p-8">
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-[#0f172a]">Brand Information</h2>
                    <p class="text-sm text-[#475569]">Manage your brand identity, logo, favicon and theme colors.</p>
                </div>
                
                <div class="space-y-6">
                    
                    <!-- Brand Name -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6">
                        <label for="brand_name_input" class="w-full md:w-1/3 text-sm font-bold text-slate-700">Brand Name <span class="text-[#EF4444]">*</span></label>
                        <div class="flex-1">
                            <input type="text" id="brand_name_input" name="brand_name" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a]" value="{{ $settings['brand_name'] ?? 'BeAurex' }}" oninput="updatePreviews()">
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div class="flex flex-col md:flex-row gap-2 md:gap-6">
                        <label class="w-full md:w-1/3 text-sm font-bold text-slate-700 pt-3">Logo <span class="text-[#EF4444]">*</span></label>
                        <div class="flex-1">
                            <div class="border border-[#e2e8f0] rounded-xl p-3 flex items-center justify-between bg-[#f1f5f9]">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white rounded border border-[#e2e8f0] flex items-center justify-center overflow-hidden" id="logo_thumb_container">
                                        @if(isset($settings['logo']))
                                            <img src="{{ \App\Support\Media::url($settings['logo']) }}" alt="Logo" class="w-full h-full object-contain p-1" id="logo_preview_img">
                                        @else
                                            <svg class="w-6 h-6 text-[#b00000]" id="default_logo_svg" fill="currentColor" viewBox="0 0 24 24"><path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm12 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zm-6-6h4v4h-4v-4z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#0f172a] text-xs" id="logo_filename">{{ isset($settings['logo']) ? basename($settings['logo']) : 'No logo uploaded' }}</div>
                                        <div class="text-[10px] text-[#475569] mt-0.5">PNG, JPG or SVG • Max 2MB</div>
                                    </div>
                                </div>
                                <input type="file" id="logo_input" name="logo" accept="image/png,image/jpeg,image/svg+xml" class="hidden" onchange="handleLogoChange(this)">
                                <button type="button" onclick="document.getElementById('logo_input').click()" class="px-4 py-1.5 border border-red-200 text-[#b00000] font-bold text-xs whitespace-nowrap shrink-0 rounded hover:bg-red-50 transition">Change</button>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon Upload -->
                    <div class="flex flex-col md:flex-row gap-2 md:gap-6">
                        <label class="w-full md:w-1/3 text-sm font-bold text-slate-700 pt-3">Favicon <span class="text-[#EF4444]">*</span></label>
                        <div class="flex-1">
                            <div class="border border-[#e2e8f0] rounded-xl p-3 flex items-center justify-between bg-[#f1f5f9]">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded border border-[#e2e8f0] flex items-center justify-center overflow-hidden" style="background-color: {{ $settings['primary_color'] ?? '#b00000' }}" id="favicon_bg_preview">
                                        @if(isset($settings['favicon']))
                                            <img src="{{ \App\Support\Media::url($settings['favicon']) }}" alt="Favicon" class="w-full h-full object-contain p-1" id="favicon_preview_img">
                                        @else
                                            <span class="text-white font-black text-sm" id="favicon_text_preview">{{ strtoupper(substr($settings['brand_name'] ?? 'LQ', 0, 2)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-[#0f172a] text-xs" id="favicon_filename">{{ isset($settings['favicon']) ? basename($settings['favicon']) : 'No favicon uploaded' }}</div>
                                        <div class="text-[10px] text-[#475569] mt-0.5">ICO or PNG • Max 512KB</div>
                                    </div>
                                </div>
                                <input type="file" id="favicon_input" name="favicon" accept="image/x-icon,image/png" class="hidden" onchange="handleFaviconChange(this)">
                                <button type="button" onclick="document.getElementById('favicon_input').click()" class="px-4 py-1.5 border border-red-200 text-[#b00000] font-bold text-xs whitespace-nowrap shrink-0 rounded hover:bg-red-50 transition">Change</button>
                            </div>
                        </div>
                    </div>

                    <!-- Primary Color -->
                    <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-6">
                        <label for="primary_color_text" class="w-full md:w-1/3 text-sm font-bold text-slate-700 pt-2">Primary Color <span class="text-[#EF4444]">*</span></label>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded border border-[#e2e8f0] shadow-inner overflow-hidden flex-shrink-0 relative cursor-pointer">
                                    <input type="color" id="primary_color_picker" class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer" value="{{ $settings['primary_color'] ?? '#b00000' }}" oninput="syncColors('primary_color_picker', 'primary_color_text'); updatePreviews()">
                                </div>
                                <input type="text" id="primary_color_text" name="primary_color" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] uppercase" value="{{ $settings['primary_color'] ?? '#b00000' }}" oninput="syncColors('primary_color_text', 'primary_color_picker'); updatePreviews()" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <p class="text-[10px] text-[#475569] mt-2">This color will be used as primary brand color across the platform.</p>
                        </div>
                    </div>

                    <!-- Secondary Color -->
                    <div class="flex flex-col md:flex-row md:items-start gap-2 md:gap-6">
                        <label for="secondary_color_text" class="w-full md:w-1/3 text-sm font-bold text-slate-700 pt-2">Secondary Color <span class="text-[#EF4444]">*</span></label>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded border border-[#e2e8f0] shadow-inner overflow-hidden flex-shrink-0 relative cursor-pointer">
                                    <input type="color" id="secondary_color_picker" class="absolute -top-2 -left-2 w-16 h-16 cursor-pointer" value="{{ $settings['secondary_color'] ?? '#FFFFFF' }}" oninput="syncColors('secondary_color_picker', 'secondary_color_text'); updatePreviews()">
                                </div>
                                <input type="text" id="secondary_color_text" name="secondary_color" required class="w-full px-4 py-2.5 rounded-xl border border-[#e2e8f0] focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-sm font-semibold text-[#0f172a] uppercase" value="{{ $settings['secondary_color'] ?? '#FFFFFF' }}" oninput="syncColors('secondary_color_text', 'secondary_color_picker'); updatePreviews()" maxlength="7" pattern="^#[0-9A-Fa-f]{6}$">
                            </div>
                            <p class="text-[10px] text-[#475569] mt-2">This color will be used as secondary brand color across the platform.</p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="px-6 md:px-8 py-5 border-t border-slate-100 bg-[#f1f5f9]/50 rounded-b-2xl flex items-center justify-end space-x-3">
                <a href="{{ url('/admin/settings/brand') }}" class="px-6 py-2.5 bg-white border border-[#e2e8f0] text-slate-700 text-sm font-bold rounded-xl hover:bg-[#f1f5f9] transition">Cancel</a>
                <button type="submit" id="saveBtn" class="px-6 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    <span>Save Changes</span>
                </button>
            </div>
            
        </form>
        
        <!-- Preview Sidebar -->
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] p-6 flex flex-col sticky top-6">
                <h3 class="text-sm font-bold text-[#0f172a] mb-1">Brand Preview</h3>
                <p class="text-[11px] text-[#475569] mb-6">This is how your brand will appear across the platform.</p>
                
                <!-- Main Preview Box -->
                <div class="border border-slate-100 rounded-xl p-4 bg-white shadow-sm mb-6 flex flex-col" id="preview_bg">
                    
                    <!-- Fake Top Nav -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 flex items-center justify-center" id="preview_logo_container">
                                @if(isset($settings['logo']))
                                    <img src="{{ \App\Support\Media::url($settings['logo']) }}" alt="Logo" class="w-full h-full object-contain preview-logo-img">
                                @else
                                    <svg class="w-full h-full preview-primary-text" fill="currentColor" viewBox="0 0 24 24" style="color: {{ $settings['primary_color'] ?? '#b00000' }}"><path d="M4 4h4v4H4V4zm6 0h4v4h-4V4zm6 0h4v4h-4V4zM4 10h4v4H4v-4zm12 0h4v4h-4v-4zM4 16h4v4H4v-4zm6 0h4v4h-4v-4zm6 0h4v4h-4v-4zm-6-6h4v4h-4v-4z"/></svg>
                                @endif
                            </div>
                            <span class="font-bold text-xs text-[#0f172a] preview-brand-name">{{ $settings['brand_name'] ?? 'BeAurex' }}</span>
                        </div>
                        <div class="px-3 py-1.5 rounded text-[9px] font-bold text-white preview-primary-bg" style="background-color: {{ $settings['primary_color'] ?? '#b00000' }}">Primary Button</div>
                    </div>
                    
                    <!-- Fake Hero -->
                    <div class="text-center py-6 mb-4">
                        <h4 class="font-black text-xl text-[#0f172a] mb-1 preview-brand-name">{{ $settings['brand_name'] ?? 'BeAurex' }}</h4>
                        <p class="text-[10px] text-[#475569] mb-5">Rewards Made Simple</p>
                        <button type="button" class="px-6 py-2 rounded-xl text-xs font-bold text-white shadow-sm preview-primary-bg inline-block" style="background-color: {{ $settings['primary_color'] ?? '#b00000' }}">Get Started</button>
                    </div>

                </div>
                
                <!-- Sample Card -->
                <div class="text-left bg-[#f1f5f9] p-4 rounded-xl border border-slate-100 preview-secondary-bg" style="background-color: {{ $settings['secondary_color'] ?? '#FFFFFF' }}">
                    <h5 class="text-xs font-bold text-[#0f172a] mb-1">Sample Card</h5>
                    <p class="text-[10px] text-[#475569]">This is a sample card preview with your brand colors.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    /**
     * Sync color between picker and text input.
     */
    function syncColors(sourceId, targetId) {
        var sourceVal = document.getElementById(sourceId).value;
        document.getElementById(targetId).value = sourceVal;
    }

    /**
     * Handle logo file selection — update thumbnail and preview sidebar.
     */
    function handleLogoChange(input) {
        if (!input.files || !input.files[0]) {
            return;
        }

        var file = input.files[0];

        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Logo file must be less than 2MB.');
            input.value = '';
            return;
        }

        // Update filename display
        document.getElementById('logo_filename').innerText = file.name;

        // Preview the image
        var reader = new FileReader();
        reader.onload = function(e) {
            // Update form thumbnail
            var container = document.getElementById('logo_thumb_container');
            container.innerHTML = '<img src="' + e.target.result + '" alt="Logo" class="w-full h-full object-contain p-1" id="logo_preview_img">';

            // Update preview sidebar logo
            var previewContainer = document.getElementById('preview_logo_container');
            previewContainer.innerHTML = '<img src="' + e.target.result + '" alt="Logo" class="w-full h-full object-contain preview-logo-img">';
        };
        reader.readAsDataURL(file);
    }

    /**
     * Handle favicon file selection — update thumbnail.
     */
    function handleFaviconChange(input) {
        if (!input.files || !input.files[0]) {
            return;
        }

        var file = input.files[0];

        // Validate file size (512KB max)
        if (file.size > 512 * 1024) {
            alert('Favicon file must be less than 512KB.');
            input.value = '';
            return;
        }

        // Update filename display
        document.getElementById('favicon_filename').innerText = file.name;

        // Preview the image
        var reader = new FileReader();
        reader.onload = function(e) {
            var bgContainer = document.getElementById('favicon_bg_preview');
            bgContainer.innerHTML = '<img src="' + e.target.result + '" alt="Favicon" class="w-full h-full object-contain p-1" id="favicon_preview_img">';
            bgContainer.style.backgroundColor = 'transparent';
        };
        reader.readAsDataURL(file);
    }

    /**
     * Update all live preview elements when brand name or colors change.
     */
    function updatePreviews() {
        var brandName = document.getElementById('brand_name_input').value;
        var primaryColor = document.getElementById('primary_color_text').value;
        var secondaryColor = document.getElementById('secondary_color_text').value;

        // Ensure color values start with #
        if (primaryColor && primaryColor.length > 0 && primaryColor[0] !== '#') {
            primaryColor = '#' + primaryColor;
            document.getElementById('primary_color_text').value = primaryColor;
        }
        if (secondaryColor && secondaryColor.length > 0 && secondaryColor[0] !== '#') {
            secondaryColor = '#' + secondaryColor;
            document.getElementById('secondary_color_text').value = secondaryColor;
        }

        // Update brand name texts in preview
        document.querySelectorAll('.preview-brand-name').forEach(function(el) {
            el.innerText = brandName || 'BeAurex';
        });

        // Update primary color backgrounds in preview
        document.querySelectorAll('.preview-primary-bg').forEach(function(el) {
            el.style.backgroundColor = primaryColor || '#b00000';
        });

        // Update primary color texts in preview
        document.querySelectorAll('.preview-primary-text').forEach(function(el) {
            el.style.color = primaryColor || '#b00000';
        });
        
        // Update favicon background color (only if no image uploaded)
        var faviconBg = document.getElementById('favicon_bg_preview');
        var faviconImg = document.getElementById('favicon_preview_img');
        if (!faviconImg) {
            faviconBg.style.backgroundColor = primaryColor || '#b00000';
        }

        // Update favicon text (first 2 chars of brand name)
        var faviconText = document.getElementById('favicon_text_preview');
        if (faviconText) {
            faviconText.innerText = (brandName || 'LQ').substring(0, 2).toUpperCase();
        }

        // Update secondary color background in preview
        document.querySelectorAll('.preview-secondary-bg').forEach(function(el) {
            el.style.backgroundColor = secondaryColor || '#FFFFFF';
        });
    }

    // Disable submit button on form submit to prevent double-click
    document.getElementById('brandForm').addEventListener('submit', function() {
        var btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg><span>Saving...</span>';
    });
</script>
@endsection




