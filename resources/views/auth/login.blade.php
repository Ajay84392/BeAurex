<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>BeAurex Admin — Login</title>
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
            min-height: 700px;
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
                <a href="{{ url('/') }}" class="back" aria-label="Back to home"><svg fill="none"
                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg></a>
                <h1>Admin Login</h1>
            </div>
            <p class="sub">Login to manage the platform</p>

            <form id="fm" method="POST" action="{{ url('/admin') }}" novalidate>
                @csrf
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
                <label class="l" for="pw">Password</label>
                <div class="f @error('password') err @enderror" id="fp">
                    <svg class="li" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input id="pw" name="password" type="password" placeholder="Enter your password"
                        autocomplete="current-password" required>
                    <button type="button" class="ri" id="eye" aria-label="Show password">
                        <svg id="eye-open" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path d="M2.5 12C3.7 8 7.5 5 12 5s8.3 3 9.5 7c-1.2 4-5 7-9.5 7S3.700 16 2.500 12z" />
                        </svg>
                        <svg id="eye-closed" style="display:none;" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="em" id="pw-err">
                    @error('password')
                        {{ $message }}
                    @else
                        Password must be at least 6 characters
                    @enderror
                </div>
                <div class="row" style="justify-content:flex-end"><a
                        href="{{ route('password.request', ['role' => 'admin']) }}">Forgot Password?</a></div>
                <button class="pb" type="submit">Login</button>
            </form>

        </div>
    </div>
    <script>
        var em = document.getElementById('em'),
            pw = document.getElementById('pw'),
            eye = document.getElementById('eye'),
            eyeOpen = document.getElementById('eye-open'),
            eyeClosed = document.getElementById('eye-closed');

        if (eye) {
            eye.onclick = function() {
                var show = pw.type === 'password';
                pw.type = show ? 'text' : 'password';
                eyeOpen.style.display = show ? 'none' : 'block';
                eyeClosed.style.display = show ? 'block' : 'none';
            };
        }
        document.getElementById('fm').onsubmit = function(e) {
            var a = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value),
                b = pw.value.length >= 6;
            if (!a || !b) {
                e.preventDefault();
                document.getElementById('fe').classList.toggle('err', !a);
                document.getElementById('em-err').innerText = 'Enter a valid email address';
                document.getElementById('fp').classList.toggle('err', !b);
                document.getElementById('pw-err').innerText = 'Password must be at least 6 characters';
            }
        };
        if (em) em.addEventListener('input', function() {
            document.getElementById('fe').classList.remove('err');
        });
        if (pw) pw.addEventListener('input', function() {
            document.getElementById('fp').classList.remove('err');
        });
    </script>
</body>

</html>
