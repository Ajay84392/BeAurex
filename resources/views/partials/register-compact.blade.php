{{-- Compact two-column sign-up form (merchant + customer register) so every field fits on one
     screen without scrolling. Include right after partials.auth-responsive.
     Markup: <div class="grid"> of <div class="fld">; add "wide" to a field that should span the
     full width on phones. --}}
<style>
    /* Register: a compact two-column form so every field fits on one screen without scrolling. */
    .phone {
        width: 720px
    }

    .sheet {
        padding: 30px 40px 26px
    }

    .ah h1 {
        font-size: 24px
    }

    .ah+.sub {
        margin: 6px 0 18px
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px 16px
    }

    .fld {
        min-width: 0
    }

    label.l {
        margin: 0 0 5px;
        font-size: 12.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .opt {
        color: var(--mut);
        font-weight: 500
    }

    .f .li {
        left: 13px
    }

    .f input {
        height: 44px;
        padding: 0 42px 0 40px
    }

    .em {
        font-size: 11.5px;
        margin-top: 4px
    }

    .row {
        margin: 14px 0 12px
    }

    #terms-err {
        margin: -6px 0 10px
    }

    .pb {
        height: 48px;
        margin-top: 0
    }

    .ft {
        margin: 14px 0 0
    }

    /* Tablets */
    @media (max-width: 768px) {
        .phone {
            width: 94%
        }

        .sheet {
            padding: 28px 24px 22px
        }
    }

    /* Phones: name and email full width, the other fields in pairs */
    @media (max-width: 600px) {
        .fld.wide {
            grid-column: 1 / -1
        }

        .grid {
            gap: 10px 10px
        }
    }

    @media (max-width: 480px) {
        body {
            padding-top: max(12px, env(safe-area-inset-top));
            padding-bottom: max(12px, env(safe-area-inset-bottom))
        }

        .phone {
            width: calc(100% - 24px)
        }

        .sheet {
            padding: 22px 16px 18px
        }

        .ah h1 {
            font-size: 21px
        }

        .ah+.sub {
            margin: 4px 0 14px
        }

        label.l {
            font-size: 12px;
            margin-bottom: 4px
        }

        .f .li {
            left: 11px;
            width: 16px;
            height: 16px
        }

        .f input {
            height: 42px;
            padding: 0 36px 0 34px
        }

        .f .ri {
            width: 34px;
            right: 2px
        }

        .row {
            margin: 12px 0 10px;
            font-size: 12.5px
        }

        .pb {
            height: 46px
        }

        .ft {
            margin-top: 12px;
            font-size: 12.5px
        }
    }

    /* Fields without an eye button don't need right padding */
    .f input:not(#pw):not(#pwc) {
        padding-right: 12px
    }

    /* Very narrow phones (320px): tighter still, keep the pairs */
    @media (max-width: 340px) {
        .sheet {
            padding: 20px 12px 16px
        }

        .grid {
            gap: 8px 8px
        }

        .f input {
            height: 40px
        }

        .ah h1 {
            font-size: 19px
        }

        /* Smaller hint text only; typed text stays 16px so iOS doesn't zoom */
        .f input::placeholder {
            font-size: 13px
        }
    }

    /* Short laptop screens */
    @media (min-width: 601px) and (max-height: 680px) {
        body {
            padding-top: 12px;
            padding-bottom: 12px
        }

        .sheet {
            padding-top: 22px;
            padding-bottom: 20px
        }
    }
</style>
