@include('partials.plan-colors')
<script>
    // Plan colour fields: the text box holds the saved value (empty = default); the swatch is just a picker.
    function setPlanColor(field, value) {
        document.getElementById('input_' + field).value = value;
        if (value) document.getElementById('pick_' + field).value = value;
        updatePreview();
    }

    function syncPlanColorPicker(field) {
        const value = document.getElementById('input_' + field).value.trim();
        if (/^#[0-9a-fA-F]{6}$/.test(value)) document.getElementById('pick_' + field).value = value;
        updatePreview();
    }

    // Give the preview card the same colours the home page will use.
    function applyPlanColorsToPreview() {
        const card = document.getElementById('preview_card_container').firstElementChild;
        if (!card) return;
        const valid = v => /^#[0-9a-fA-F]{6}$/.test(v) ? v : '';
        const text = valid(document.getElementById('input_text_color').value.trim());
        const bg = valid(document.getElementById('input_bg_color').value.trim());
        card.style.backgroundColor = bg;
        if (text) {
            card.setAttribute('data-plan-text', '');
            card.style.setProperty('--plan-text', text);
        } else {
            card.removeAttribute('data-plan-text');
            card.style.removeProperty('--plan-text');
        }
        
        // Also apply the background color to badges/buttons that normally take the primary color
        const badge = card.querySelector('.absolute.-top-3\\.5');
        if (badge) {
            badge.style.backgroundColor = bg;
        }
    }
</script>
