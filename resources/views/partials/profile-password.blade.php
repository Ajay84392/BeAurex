<section class="border-t border-slate-100 pt-8">
    <h2 class="text-[15px] font-black text-slate-900 mb-1">Change Password</h2>
    <p class="text-[12px] text-slate-500 mb-5">Leave these blank to keep your current password.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <x-profile.password name="current_password" label="Current Password" placeholder="Enter current password" autocomplete="current-password" :label-class="$label" :input-class="$input" />
        <x-profile.password name="password" label="New Password" placeholder="Enter new password" strength :label-class="$label" :input-class="$input" />
        <x-profile.password name="password_confirmation" label="Confirm New Password" placeholder="Re-enter new password" confirms="password" :label-class="$label" :input-class="$input" />
    </div>
</section>
