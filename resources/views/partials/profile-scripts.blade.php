<script>
    // Image upload preview: show the picked file in the element named by data-preview.
    document.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file || !file.type.startsWith('image/')) return;
            if (file.size > 2 * 1024 * 1024) {
                alert('Please choose an image smaller than 2 MB.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => {
                const box = document.getElementById(input.dataset.preview);
                box.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.className = 'w-full h-full object-cover';
                box.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Mobile numbers: digits only, exactly 10, starting 6-9.
    document.querySelectorAll('input[data-mobile]').forEach(input => {
        input.addEventListener('input', () => {
            let v = input.value.replace(/\D/g, '');
            if ((v.startsWith('91') || v.startsWith('0')) && v.length > 10) {
                v = v.replace(/^(91|0)/, '');
            }
            input.value = v.slice(0, 10);
            input.setCustomValidity(/^[6-9]\d{9}$/.test(input.value) || input.value === '' ? '' : 'Enter a valid 10-digit mobile number.');
        });
    });
</script>
