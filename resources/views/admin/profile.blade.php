@extends('layouts.admin')

@section('title', 'Admin Profile')

@section('content')
<div class="flex-1 overflow-auto p-6 md:p-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#0f172a] mb-1">Admin Profile</h1>
        <div class="text-xs text-[#475569] font-medium flex items-center space-x-1">
            <a href="/admin/dashboard" class="hover:text-[#b00000] transition">Home</a>
            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            <span class="text-[#0f172a]">Profile</span>
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

    <form action="{{ url('/admin/profile') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-[#e2e8f0] max-w-5xl">
        @csrf
        
        <div class="p-6 md:p-8 space-y-10">
            
            <!-- Personal Information -->
            <div>
                <h2 class="text-lg font-bold text-[#0f172a] mb-6">Personal Information</h2>
                
                <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-12">
                    
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Full Name <span class="text-[#EF4444]">*</span></label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                            <input type="text" value="Super Admin" disabled class="w-full bg-slate-100 border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-400 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address <span class="text-[#EF4444]">*</span></label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-slate-100 border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-400 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                            <input type="text" value="{{ auth()->user()->username ?? 'superadmin' }}" disabled class="w-full bg-slate-100 border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-slate-400 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number <span class="text-[#EF4444]">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Account Status</label>
                            <div class="h-[46px] flex items-center">
                                <span class="px-3 py-1.5 text-xs font-bold tracking-wider text-emerald-700 bg-emerald-100 rounded-md">Active</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col items-center justify-start pt-4">
                        <div x-data="{ 
                            photoPreview: '{{ auth()->user()->photo ? asset(auth()->user()->photo) : '' }}',
                            updatePreview(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        this.photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        }" class="flex flex-col items-center">
                            <label class="block text-sm font-bold text-slate-700 mb-4 w-full text-center">Profile Picture</label>
                            
                            <div class="w-32 h-32 rounded-full border-4 border-slate-100 shadow-sm bg-[#f1f5f9] overflow-hidden mb-4 relative group flex items-center justify-center shrink-0">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" alt="Profile" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    <svg class="w-16 h-16 text-slate-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </template>
                            </div>
                            
                            <div class="flex flex-col items-center">
                                <input type="file" id="photoInput" name="photo" class="hidden" accept="image/jpeg,image/png,image/gif" @change="updatePreview">
                                <button type="button" onclick="document.getElementById('photoInput').click()" class="px-4 py-2 border border-red-200 text-[#b00000] text-xs font-bold rounded-xl hover:bg-red-50 transition flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    <span>Change Photo</span>
                                </button>
                                <p class="text-[10px] text-slate-400 mt-2">JPG, PNG or GIF. Max size 2MB.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Change Password -->
            <div class="border-t border-slate-100 pt-8">
                <h2 class="text-lg font-bold text-[#0f172a] mb-6">Change Password</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Current Password <span class="text-[#EF4444]">*</span></label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition pr-10">
                            <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-3.5 text-slate-400 hover:text-[#475569]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">New Password <span class="text-[#EF4444]">*</span></label>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="Enter new password" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition pr-10">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3.5 text-slate-400 hover:text-[#475569]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Confirm New Password <span class="text-[#EF4444]">*</span></label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition pr-10">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-3.5 text-slate-400 hover:text-[#475569]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="text-[11px] text-[#475569] mt-2">Password must be at least 8 characters long and include a number, uppercase and lowercase letter.</p>
            </div>

            <!-- Preferences -->
            <div class="border-t border-slate-100 pt-8">
                <h2 class="text-lg font-bold text-[#0f172a] mb-6">Preferences</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Language <span class="text-[#EF4444]">*</span></label>
                        <select name="language" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition appearance-none">
                            <option value="English" {{ old('language', auth()->user()->language) === 'English' ? 'selected' : '' }}>English</option>
                            <option value="Spanish" {{ old('language', auth()->user()->language) === 'Spanish' ? 'selected' : '' }}>Spanish</option>
                            <option value="French" {{ old('language', auth()->user()->language) === 'French' ? 'selected' : '' }}>French</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Timezone <span class="text-[#EF4444]">*</span></label>
                        <select name="timezone" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition appearance-none">
                            <option value="Asia/Kolkata" {{ old('timezone', auth()->user()->timezone) === 'Asia/Kolkata' ? 'selected' : '' }}>(GMT+05:30) Asia/Kolkata</option>
                            <option value="America/New_York" {{ old('timezone', auth()->user()->timezone) === 'America/New_York' ? 'selected' : '' }}>(GMT-05:00) America/New_York</option>
                            <option value="Europe/London" {{ old('timezone', auth()->user()->timezone) === 'Europe/London' ? 'selected' : '' }}>(GMT+00:00) Europe/London</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Date Format <span class="text-[#EF4444]">*</span></label>
                        <select name="date_format" class="w-full bg-[#f1f5f9] border border-[#e2e8f0] rounded-xl px-4 py-3 text-sm font-medium text-[#0f172a] focus:outline-none focus:border-[#b00000] focus:ring-2 focus:ring-red-600/10 transition appearance-none">
                            <option value="d M, Y" {{ old('date_format', auth()->user()->date_format) === 'd M, Y' ? 'selected' : '' }}>24 May, 2025</option>
                            <option value="Y-m-d" {{ old('date_format', auth()->user()->date_format) === 'Y-m-d' ? 'selected' : '' }}>2025-05-24</option>
                            <option value="m/d/Y" {{ old('date_format', auth()->user()->date_format) === 'm/d/Y' ? 'selected' : '' }}>05/24/2025</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="px-6 md:px-8 py-5 border-t border-slate-100 bg-[#f1f5f9]/50 rounded-b-2xl flex items-center justify-end space-x-3">
            <button type="reset" class="px-6 py-2.5 bg-white border border-red-200 text-[#b00000] text-sm font-bold rounded-xl hover:bg-red-50 transition">Cancel</button>
            <button type="submit" class="px-6 py-2.5 bg-[#b00000] text-white text-sm font-bold rounded-xl hover:bg-[#8a0000] transition flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <span>Update Profile</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }
</script>
@endsection






