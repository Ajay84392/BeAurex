{{-- Custom plan colours (Admin > Plans). A card with data-plan-text gets --plan-text on its text; inside it, the badge, button, menus and coloured chips keep their own colours. --}}
<style>
    [data-plan-text] :is(h3, div, span, li, p):not(.absolute, a, [class*="bg-"]):not([data-plan-text] :is(.absolute, a, [class*="bg-"]) *) { color: var(--plan-text) !important; }
</style>
