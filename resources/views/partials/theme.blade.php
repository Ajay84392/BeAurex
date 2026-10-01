{{--
    The one BeAurex theme. Every page includes this in its <head> so they all share
    the same viewport rules, font, colour palette and base styles.

    Palette
      brand (red)  : primary actions, active nav, links    -> red-* / brand-* / #b00000, hover #8a0000
      slate        : text, borders, surfaces
      emerald      : success      amber : warning      red : errors
    Other Tailwind hues (blue, purple, indigo, gray…) are mapped onto this palette below,
    so stray classes can't introduce a second theme.
--}}
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#b00000">
<link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    (() => {
        const brand = { 50: '#fff5f5', 100: '#ffe4e4', 200: '#fecaca', 300: '#f9a3a3', 400: '#ef6b6b', 500: '#d42a2a', 600: '#b00000', 700: '#8a0000', 800: '#700000', 900: '#520000', 950: '#300000' };
        const slate = { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a', 950: '#020617' };
        const amber = { 50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d', 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309', 800: '#92400e', 900: '#78350f' };
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand, red: brand, rose: brand,
                        blue: brand, sky: brand, indigo: brand, violet: brand, purple: brand, fuchsia: brand, pink: brand, cyan: brand, teal: brand,
                        gray: slate, zinc: slate, neutral: slate, stone: slate,
                        green: { 50: '#ecfdf5', 100: '#d1fae5', 200: '#a7f3d0', 300: '#6ee7b7', 400: '#34d399', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' },
                        yellow: amber, orange: amber, amber,
                    },
                    screens: { xs: '400px' },
                },
            },
        };
    })();
</script>
<style>
    :root { --brand: #b00000; --brand-dark: #8a0000; --ink: #0f172a; --muted: #475569; --line: #e2e8f0; --surface: #f1f5f9; }
    *, *::before, *::after { box-sizing: border-box; }
    html { -webkit-text-size-adjust: 100%; text-size-adjust: 100%; }
    body { font-family: "Inter", ui-sans-serif, system-ui, sans-serif; color: var(--ink); -webkit-font-smoothing: antialiased; overflow-wrap: break-word; }
    img, svg, video, canvas { max-width: 100%; }
    img, video { height: auto; }
    [x-cloak] { display: none !important; }
    ::selection { background: #fecaca; color: var(--ink); }
    a, button, input, select, textarea, label { -webkit-tap-highlight-color: transparent; }
    :focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }
    input:focus-visible, select:focus-visible, textarea:focus-visible { outline: none; }
    /* iOS zooms into inputs smaller than 16px; keep form text readable on phones without the zoom jump. */
    @media (max-width: 767px) { input:not([type=checkbox]):not([type=radio]), select, textarea { font-size: max(16px, 1em) !important; } }
    /* Long words, emails and codes must wrap rather than push the layout sideways. */
    td, th { overflow-wrap: normal; }
    /* Tables that scroll sideways keep each cell on one line instead of breaking IDs and dates. */
    :is(.table-container, .overflow-x-auto) :is(th, td) { white-space: nowrap; }
    .pb-safe { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }
    .table-container, .overflow-x-auto { -webkit-overflow-scrolling: touch; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    .table-container::-webkit-scrollbar, .overflow-x-auto::-webkit-scrollbar { height: 8px; }
    .table-container::-webkit-scrollbar-thumb, .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .table-container::-webkit-scrollbar-thumb:hover, .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    @media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; scroll-behavior: auto !important; } }
</style>
