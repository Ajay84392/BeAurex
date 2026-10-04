{{-- Responsive layout for the standalone auth pages (merchant login/register, forgot password).
     Include right after the page's own <style> block so these rules win.
     The form stays a centred, rounded card on every screen size: 480px wide on desktop, 90% on phones. --}}
<style>
    :root {
        padding: 0
    }

    html {
        -webkit-text-size-adjust: 100%;
        text-size-adjust: 100%
    }

    body {
        min-height: 100vh;
        min-height: 100dvh;
        background: #eef0f3;
        padding: max(24px, env(safe-area-inset-top)) max(16px, env(safe-area-inset-right)) max(24px, env(safe-area-inset-bottom)) max(16px, env(safe-area-inset-left))
    }

    .phone {
        width: 480px;
        max-width: 100%;
        min-height: 0;
        margin: auto;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .12)
    }

    .sheet {
        border-radius: 20px;
        padding: 40px 56px 32px
    }

    /* Page header: back arrow + title with accent underline + subtitle */
    .ah {
        position: relative;
        text-align: center;
        margin-bottom: 8px
    }

    .ah h1 {
        position: relative;
        display: inline-block;
        font-size: 26px;
        font-weight: 800;
        padding-bottom: 8px
    }

    .ah h1::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 0;
        width: 64px;
        transform: translateX(-50%);
        border-bottom: 3px solid var(--acc);
        border-radius: 2px
    }

    .ah .back {
        position: absolute;
        left: -8px;
        top: 50%;
        transform: translateY(-60%);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: var(--ink);
        text-decoration: none
    }

    .ah .back:hover {
        background: var(--pink);
        color: var(--acc)
    }

    .ah .back svg {
        width: 22px;
        height: 22px
    }

    .ah+.sub {
        margin: 8px 0 20px
    }

    /* Larger tap target for the show/hide password button */
    .f .ri {
        right: 4px;
        width: 40px
    }

    .f input,
    .pb,
    .gb,
    .row a,
    .ft a {
        touch-action: manipulation
    }

    .row {
        flex-wrap: wrap;
        gap: 10px
    }

    .ft {
        line-height: 1.6
    }

    /* Tablets and phones: card takes 90% of the width */
    @media (max-width: 768px) {
        .phone {
            width: 90%
        }

        .sheet {
            padding: 40px 20px 28px
        }

        .ah h1 {
            font-size: 24px
        }
    }

    @media (max-width: 480px) {
        body {
            padding-left: max(0px, env(safe-area-inset-left));
            padding-right: max(0px, env(safe-area-inset-right))
        }

        /* 16px stops iOS Safari from zooming in when an input is focused */
        .f input {
            font-size: 16px
        }
    }

    /* Small phones (iPhone SE, Galaxy Fold, etc.) */
    @media (max-width: 360px) {
        .phone {
            width: 94%
        }

        .sheet {
            padding: 32px 16px 24px
        }

        .ah h1 {
            font-size: 21px
        }

        .ah .back {
            left: -10px
        }

        label.l {
            margin: 12px 0 6px
        }

        .f input {
            height: 46px
        }
    }

    /* Phones in landscape: short screens, so start at the top and tighten spacing */
    @media (max-height: 560px) and (orientation: landscape) {
        body {
            align-items: flex-start;
            padding-top: max(16px, env(safe-area-inset-top));
            padding-bottom: max(16px, env(safe-area-inset-bottom))
        }

        .sheet {
            padding-top: 24px;
            padding-bottom: 20px
        }

        .brand {
            margin-bottom: 12px
        }
    }
</style>
