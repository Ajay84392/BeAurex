<style>
    /* Locked (not being edited) profile fields read as plain values, not empty inputs. */
    fieldset.profile-fields:disabled :is(input, select, textarea) { background-color: #f8fafc !important; color: #334155; cursor: default; box-shadow: none; }
    fieldset.profile-fields:disabled select { appearance: none; background-image: none; }
    fieldset.profile-fields:disabled ::placeholder { color: transparent; }
</style>
<script>
    // Pencil-to-edit profile sections (x-profile.section). Cancel puts a section's fields back as they were.
    function profileEditor() {
        const previews = new Map();

        return {
            edit: {},
            get editing() { return Object.values(this.edit).some(Boolean); },

            init() {
                this.$el.querySelectorAll('input[type="file"][data-preview]').forEach(input => {
                    const box = document.getElementById(input.dataset.preview);
                    if (box) previews.set(input, box.innerHTML);
                });

                // Changing the login email needs the current password, so open that section too.
                const email = this.$el.querySelector('input[name="email"]');
                if (email && this.$el.querySelector('[data-section="password"]')) {
                    email.addEventListener('input', () => {
                        if (email.value.trim().toLowerCase() !== (email.dataset.original || '').toLowerCase()) {
                            this.edit.password = true;
                        }
                    });
                }
            },

            open(key) {
                this.edit[key] = true;
                this.$nextTick(() => {
                    const first = this.$el.querySelector('[data-section-fields="' + key + '"] :is(input:not([type=file]):not([type=hidden]), select, textarea):not(:disabled)');
                    if (first) first.focus({ preventScroll: true });
                });
            },

            cancel(key) {
                const fields = this.$el.querySelector('[data-section-fields="' + key + '"]');
                fields && fields.querySelectorAll('input, select, textarea').forEach(el => {
                    if (el.type === 'file') {
                        el.value = '';
                        const box = document.getElementById(el.dataset.preview);
                        if (box && previews.has(el)) box.innerHTML = previews.get(el);
                    } else if (el.type === 'checkbox' || el.type === 'radio') {
                        el.checked = el.defaultChecked;
                    } else if (el.tagName === 'SELECT') {
                        [...el.options].forEach(o => o.selected = o.defaultSelected);
                    } else {
                        el.value = el.defaultValue;
                    }
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                });
                this.edit[key] = false;
            },

            toggle(key) { this.edit[key] ? this.cancel(key) : this.open(key); },
            cancelAll() { Object.keys(this.edit).forEach(key => this.edit[key] && this.cancel(key)); },
        };
    }

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
