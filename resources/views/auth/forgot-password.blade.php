<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>BeAurex {{ ucfirst(request()->query('role', 'Customer')) }} — Forgot Password</title>
    @include('partials.favicon')
    <style>
        :root {
            --red: #8b0000;
            --red2: #a30000;
            --acc: #c00000;
            --ink: #1a1a1a;
            --mut: #6b7280;
            --line: #e5e7eb;
            --cream: #fdf4e6;
            --pink: #fdeeee;
            box-sizing: border-box;
            padding-top: env(safe-area-inset-top, 0px);
            padding-bottom: env(safe-area-inset-bottom, 0px)
        }

        * {
            box-sizing: border-box;
            margin: 0
        }

        html {
            scroll-padding-top: env(safe-area-inset-top, 0px)
        }

        body {
            background: #eef0f3;
            font-family: Inter, system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px
        }

        .phone {
            width: 100%;
            max-width: 400px;
            min-height: 460px;
            background: var(--red);
            border-radius: 36px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .18);
            display: flex;
            flex-direction: column
        }

        @media(max-width:480px) {
            body {
                padding: 0;
                background: var(--red)
            }

            .phone {
                max-width: none;
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none
            }
        }

        .sheet {
            background: #fff;
            border-radius: 36px;
            margin-top: 0;
            flex: 1;
            padding: 36px 24px 24px;
            display: flex;
            flex-direction: column
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 8px;
            margin-bottom: 24px
        }

        .logo-img {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(139, 0, 0, .25)
        }

        .bn {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -.02em;
            line-height: 1
        }

        .bn span {
            color: var(--acc)
        }

        .bs {
            font-size: 11px;
            color: var(--red);
            font-weight: 800;
            margin-top: 4px;
            background: var(--pink);
            padding: 3px 10px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(139, 0, 0, 0.15);
            display: inline-block
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            text-align: center
        }

        .sub {
            font-size: 13px;
            color: var(--mut);
            margin: 4px 0 24px;
            text-align: center
        }

        label.l {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin: 14px 0 6px
        }

        .f {
            position: relative
        }

        .f svg {
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a8f98
        }

        .f .li {
            left: 14px
        }

        .f .ri {
            position: absolute;
            right: 14px;
            cursor: pointer;
            background: none;
            border: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 0;
            bottom: 0;
            transform: none;
            padding: 0;
            margin: 0
        }

        .f .ri svg {
            position: static;
            transform: none;
            margin: 0
        }

        .f input {
            width: 100%;
            height: 48px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 0 44px;
            font-size: 14px;
            color: var(--ink);
            background: #fff
        }

        .f input:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(139, 0, 0, .1)
        }

        .f.err input {
            border-color: var(--acc)
        }

        .em {
            color: var(--acc);
            font-size: 12px;
            margin-top: 5px;
            display: none
        }

        .f.err+.em {
            display: block
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 14px 0 18px;
            font-size: 13px
        }

        .row label {
            display: flex;
            gap: 8px;
            align-items: center;
            color: var(--mut);
            font-weight: 500
        }

        .row input {
            accent-color: var(--red);
            width: 16px;
            height: 16px
        }

        .row a {
            color: var(--acc);
            font-weight: 600;
            text-decoration: none
        }

        .pb {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: 12px;
            background: var(--red2);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
        }

        .pb:hover {
            background: var(--red)
        }

        .or {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0;
            font-size: 11px;
            color: #9ca3af;
            font-weight: 600
        }

        .or:before,
        .or:after {
            content: "";
            flex: 1;
            border-top: 1px solid var(--line)
        }

        .gb {
            width: 100%;
            height: 48px;
            border: 1px solid #f0b4b4;
            background: #fff;
            color: var(--acc);
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer
        }

        .ft {
            text-align: center;
            font-size: 13px;
            color: var(--mut);
            margin-top: 20px;
            margin-bottom: 8px
        }

        .ft a {
            color: var(--acc);
            font-weight: 700;
            text-decoration: none
        }
    </style>
    @include('partials.auth-responsive')
</head>

<body>
    <div class="phone">
        <div class="sheet">
            <div class="ah">
                <a href="{{ $loginUrl }}" class="back"
                    aria-label="Back to login"><svg fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg></a>
                <h1>Forgot Password</h1>
            </div>
            <p class="sub">Enter your email to receive a reset code</p>

            <form id="fm" method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf
                <input type="hidden" name="role" value="{{ request()->query('role', 'customer') }}">
                <label class="l" for="em">Email Address</label>
                <div class="f @error('email') err @enderror" id="fe"><svg class="li" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg><input id="em" name="email" type="email" placeholder="Enter your email"
                        autocomplete="email" value="{{ old('email') }}" required></div>
                <div class="em" id="em-err">
                    @error('email')
                        {{ $message }}
                    @else
                        Enter a valid email address
                    @enderror
                </div>
                <button class="pb" type="submit">Send Reset Code</button>
            </form>
            <div class="ft"><a href="{{ $loginUrl }}">Back to
                    Login</a></div>

        </div>
    </div>
    <script>
        var em = document.getElementById('em');
        document.getElementById('fm').onsubmit = function(e) {
            var a = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value);
            if (!a) {
                e.preventDefault();
                document.getElementById('fe').classList.toggle('err', !a);
                document.getElementById('em-err').innerText = 'Enter a valid email address';
            }
        };
        if (em) em.addEventListener('input', function() {
            document.getElementById('fe').classList.remove('err');
        });
    </script>
</body>

</html>
