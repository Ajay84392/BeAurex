<div>
    <label class="flex items-start space-x-2.5 cursor-pointer">
        <input type="checkbox" name="terms" value="1" required @checked(old('terms'))
            @error('terms') aria-invalid="true" aria-describedby="terms-error" @enderror
            class="mt-0.5 w-4 h-4 rounded border-[#e2e8f0] focus:ring-0 shrink-0" style="accent-color:#b00000">
        <span class="text-xs font-medium text-[#475569] leading-relaxed">
            I agree to the <a href="#" class="text-[#b00000] hover:underline font-bold">Terms &amp; Conditions</a>
            and <a href="#" class="text-[#b00000] hover:underline font-bold">Privacy Policy</a>.
        </span>
    </label>
    <x-auth.error name="terms" />
</div>
