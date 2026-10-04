<x-profile.section key="password" title="Change Password" subtitle="Leave these blank to keep your current password. Changing your email also needs your current password."
    :fields="['current_password', 'password', 'password_confirmation']" class="border-t border-slate-100 pt-8">
    <div class="grid grid-cols-1 {{ $grid ?? 'md:grid-cols-3' }} gap-5">
        <x-profile.password name="current_password" label="Current Password" placeholder="Enter current password" autocomplete="current-password" :label-class="$label" :input-class="$input" />
        <x-profile.password name="password" label="New Password" placeholder="Enter new password" strength :label-class="$label" :input-class="$input" />
        <x-profile.password name="password_confirmation" label="Confirm New Password" placeholder="Re-enter new password" confirms="password" :label-class="$label" :input-class="$input" />
    </div>
</x-profile.section>
