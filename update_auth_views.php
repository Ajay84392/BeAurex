<?php

$template = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title>BeAurex {role_title} — {page_title}</title>
<style>
:root{--red:#8b0000;--red2:#a30000;--acc:#c00000;--ink:#1a1a1a;--mut:#6b7280;--line:#e5e7eb;--cream:#fdf4e6;--pink:#fdeeee;box-sizing:border-box;padding-top:env(safe-area-inset-top,0px);padding-bottom:env(safe-area-inset-bottom,0px)}
*{box-sizing:border-box;margin:0}
html{scroll-padding-top:env(safe-area-inset-top,0px)}
body{background:#eef0f3;font-family:Inter,system-ui,-apple-system,"Segoe UI",sans-serif;color:var(--ink);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:16px}
.phone{width:100%;max-width:400px;min-height:700px;background:var(--red);border-radius:36px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,.18);display:flex;flex-direction:column}
@media(max-width:480px){body{padding:0;background:var(--red)}.phone{max-width:none;border-radius:0;min-height:100vh;box-shadow:none}}
.sheet{background:#fff;border-radius:36px;margin-top:0;flex:1;padding:36px 24px 24px;display:flex;flex-direction:column}
.brand{display:flex;flex-direction:column;align-items:center;text-align:center;gap:8px;margin-bottom:24px}
.logo-img{width:64px;height:64px;border-radius:16px;object-fit:cover;box-shadow:0 4px 10px rgba(139,0,0,.25)}
.bn{font-size:24px;font-weight:800;letter-spacing:-.02em;line-height:1}.bn span{color:var(--acc)}
.bs{font-size:11px;color:var(--red);font-weight:800;margin-top:4px;background:var(--pink);padding:3px 10px;border-radius:10px;text-transform:uppercase;letter-spacing:0.5px;border:1px solid rgba(139,0,0,0.15);display:inline-block}
h1{font-size:22px;font-weight:700;text-align:center}
.sub{font-size:13px;color:var(--mut);margin:4px 0 24px;text-align:center}
label.l{display:block;font-size:13px;font-weight:600;margin:14px 0 6px}
.f{position:relative}
.f svg{position:absolute;width:18px;height:18px;top:50%;transform:translateY(-50%);color:#8a8f98}
.f .li{left:14px}.f .ri{position:absolute;right:14px;cursor:pointer;background:none;border:0;display:flex;align-items:center;justify-content:center;top:0;bottom:0;transform:none;padding:0;margin:0}
.f .ri svg{position:static;transform:none;margin:0}
.f input{width:100%;height:48px;border:1px solid var(--line);border-radius:12px;padding:0 44px;font-size:14px;color:var(--ink);background:#fff}
.f input:focus{outline:none;border-color:var(--red);box-shadow:0 0 0 3px rgba(139,0,0,.1)}
.f.err input{border-color:var(--acc)}
.em{color:var(--acc);font-size:12px;margin-top:5px;display:none}.f.err+.em{display:block}
.row{display:flex;justify-content:space-between;align-items:center;margin:14px 0 18px;font-size:13px}
.row label{display:flex;gap:8px;align-items:center;color:var(--mut);font-weight:500}
.row input{accent-color:var(--red);width:16px;height:16px}
.row a{color:var(--acc);font-weight:600;text-decoration:none}
.pb{width:100%;height:50px;border:0;border-radius:12px;background:var(--red2);color:#fff;font-size:16px;font-weight:600;cursor:pointer;margin-top:10px;}
.pb:hover{background:var(--red)}
.or{display:flex;align-items:center;gap:12px;margin:18px 0;font-size:11px;color:#9ca3af;font-weight:600}
.or:before,.or:after{content:"";flex:1;border-top:1px solid var(--line)}
.gb{width:100%;height:48px;border:1px solid #f0b4b4;background:#fff;color:var(--acc);border-radius:12px;font-weight:600;font-size:14px;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer}
.ft{text-align:center;font-size:13px;color:var(--mut);margin-top:20px;margin-bottom:8px}
.ft a{color:var(--acc);font-weight:700;text-decoration:none}
</style>
</head>
<body>
<div class="phone">
  <div class="sheet">
    <div class="brand">
        <img src="/images/logo.jpg" alt="BeAurex Logo" class="logo-img" onerror="this.outerHTML='<div style=\\'width:64px;height:64px;border-radius:16px;background:var(--red);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;\\'>B</div>'">
        <div>
            <div class="bn">Be<span>Aurex</span></div>
            <div class="bs">{role_title}</div>
        </div>
    </div>
    
    <h1>{heading}</h1>
    <p class="sub">{subheading}</p>
    
    {form_html}
    
  </div>
</div>
{script_html}
</body>
</html>
HTML;

$email_field = <<<HTML
      <label class="l" for="em">Email Address</label>
      <div class="f @error('email') err @enderror" id="fe"><svg class="li" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.9 5.3a2 2 0 002.2 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg><input id="em" name="email" type="email" placeholder="Enter your email" autocomplete="email" value="{{ old('email') }}" required></div>
      <div class="em" id="em-err">@error('email') {{ \$message }} @else Enter a valid email address @enderror</div>
HTML;

$name_field = <<<HTML
      <label class="l" for="name">Full Name</label>
      <div class="f @error('name') err @enderror" id="fn"><svg class="li" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg><input id="name" name="name" type="text" placeholder="Enter your full name" autocomplete="name" value="{{ old('name') }}" required></div>
      <div class="em" id="nm-err">@error('name') {{ \$message }} @else Name is required @enderror</div>
HTML;

$password_field = <<<HTML
      <label class="l" for="pw">Password</label>
      <div class="f @error('password') err @enderror" id="fp">
        <svg class="li" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <input id="pw" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required>
        <button type="button" class="ri" id="eye" aria-label="Show password">
            <svg id="eye-open" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.5 12C3.7 8 7.5 5 12 5s8.3 3 9.5 7c-1.2 4-5 7-9.5 7S3.700 16 2.500 12z"/></svg>
            <svg id="eye-closed" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
        </button>
      </div>
      <div class="em" id="pw-err">@error('password') {{ \$message }} @else Password must be at least 6 characters @enderror</div>
HTML;

$password_confirm_field = <<<HTML
      <label class="l" for="pwc">Confirm Password</label>
      <div class="f @error('password_confirmation') err @enderror" id="fpc">
        <svg class="li" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <input id="pwc" name="password_confirmation" type="password" placeholder="Confirm your password" required>
        <button type="button" class="ri" id="eyec" aria-label="Show password">
            <svg id="eyec-open" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.5 12C3.7 8 7.5 5 12 5s8.3 3 9.5 7c-1.2 4-5 7-9.5 7S3.700 16 2.500 12z"/></svg>
            <svg id="eyec-closed" style="display:none;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
        </button>
      </div>
      <div class="em" id="pwc-err">@error('password_confirmation') {{ \$message }} @else Passwords must match @enderror</div>
HTML;

$google_btn = <<<HTML
    <div class="or">OR</div>
    <a href="{{ route('google.redirect', ['role' => '{role_lower}']) }}" style="text-decoration:none;">
        <button class="gb" type="button"><svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>Continue with Google</button>
    </a>
HTML;

$script_login = <<<HTML
<script>
var em = document.getElementById('em'),
    pw = document.getElementById('pw'),
    eye = document.getElementById('eye'),
    eyeOpen = document.getElementById('eye-open'),
    eyeClosed = document.getElementById('eye-closed');

if(eye) {
    eye.onclick = function() {
        var show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        eyeOpen.style.display = show ? 'none' : 'block';
        eyeClosed.style.display = show ? 'block' : 'none';
    };
}
document.getElementById('fm').onsubmit = function(e){
  var a = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value), b = pw.value.length >= 6;
  if(!a || !b){
      e.preventDefault();
      document.getElementById('fe').classList.toggle('err', !a);
      document.getElementById('em-err').innerText = 'Enter a valid email address';
      document.getElementById('fp').classList.toggle('err', !b);
      document.getElementById('pw-err').innerText = 'Password must be at least 6 characters';
  }
};
if(em) em.addEventListener('input', function() { document.getElementById('fe').classList.remove('err'); });
if(pw) pw.addEventListener('input', function() { document.getElementById('fp').classList.remove('err'); });
</script>
HTML;

$script_register = <<<HTML
<script>
var nm = document.getElementById('name'),
    em = document.getElementById('em'),
    pw = document.getElementById('pw'),
    pwc = document.getElementById('pwc'),
    eye = document.getElementById('eye'),
    eyec = document.getElementById('eyec');

if(eye) {
    eye.onclick = function() {
        var show = pw.type === 'password';
        pw.type = show ? 'text' : 'password';
        document.getElementById('eye-open').style.display = show ? 'none' : 'block';
        document.getElementById('eye-closed').style.display = show ? 'block' : 'none';
    };
}
if(eyec) {
    eyec.onclick = function() {
        var show = pwc.type === 'password';
        pwc.type = show ? 'text' : 'password';
        document.getElementById('eyec-open').style.display = show ? 'none' : 'block';
        document.getElementById('eyec-closed').style.display = show ? 'block' : 'none';
    };
}
document.getElementById('fm').onsubmit = function(e){
  var n = nm.value.trim().length > 0;
  var a = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value);
  var b = pw.value.length >= 6;
  var c = pwc.value === pw.value;
  if(!n || !a || !b || !c){
      e.preventDefault();
      document.getElementById('fn').classList.toggle('err', !n);
      document.getElementById('fe').classList.toggle('err', !a);
      document.getElementById('fp').classList.toggle('err', !b);
      document.getElementById('fpc').classList.toggle('err', !c);
  }
};
if(nm) nm.addEventListener('input', function() { document.getElementById('fn').classList.remove('err'); });
if(em) em.addEventListener('input', function() { document.getElementById('fe').classList.remove('err'); });
if(pw) pw.addEventListener('input', function() { document.getElementById('fp').classList.remove('err'); });
if(pwc) pwc.addEventListener('input', function() { document.getElementById('fpc').classList.remove('err'); });
</script>
HTML;

$script_forgot = <<<HTML
<script>
var em = document.getElementById('em');
document.getElementById('fm').onsubmit = function(e){
  var a = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value);
  if(!a){
      e.preventDefault();
      document.getElementById('fe').classList.toggle('err', !a);
      document.getElementById('em-err').innerText = 'Enter a valid email address';
  }
};
if(em) em.addEventListener('input', function() { document.getElementById('fe').classList.remove('err'); });
</script>
HTML;

function compile($tpl, $vars) {
    return str_replace(array_keys($vars), array_values($vars), $tpl);
}

// 1. Customer Login -> auth/portal-login.blade.php
$portal_html = compile($template, [
    '{role_title}' => '{{ ucfirst($role) }}',
    '{page_title}' => 'Login',
    '{heading}' => 'Welcome Back!',
    '{subheading}' => 'Login to your {{ $role }} account',
    '{form_html}' => <<<HTML
    <form id="fm" method="POST" action="{{ url('/' . \$role . '/login') }}" novalidate>
      @csrf
      $email_field
      $password_field
      <div class="row"><label><input type="checkbox" name="remember" value="1">Remember me</label><a href="{{ route('password.request', ['role' => \$role]) }}">Forgot Password?</a></div>
      <button class="pb" type="submit">Sign In</button>
    </form>
    @if(\$role !== 'admin')
        <div class="ft">New to BeAurex? <a href="{{ url('/' . \$role . '/register') }}">Create Account</a></div>
        @if(\$role === 'customer')
            $google_btn
        @endif
    @endif
HTML,
    '{script_html}' => $script_login
]);
$portal_html = "@php\n    \$role = \$role ?? 'customer';\n@endphp\n" . str_replace("['role' => '{role_lower}']", "['role' => \$role]", $portal_html);

// 2. Admin Login -> auth/login.blade.php
$admin_html = compile($template, [
    '{role_title}' => 'Admin',
    '{page_title}' => 'Login',
    '{heading}' => 'Admin Portal',
    '{subheading}' => 'Login to manage the platform',
    '{form_html}' => <<<HTML
    <form id="fm" method="POST" action="{{ url('/admin/login') }}" novalidate>
      @csrf
      $email_field
      $password_field
      <div class="row"><label><input type="checkbox" name="remember" value="1">Remember me</label><a href="{{ route('password.request', ['role' => 'admin']) }}">Forgot Password?</a></div>
      <button class="pb" type="submit">Sign In</button>
    </form>
HTML,
    '{script_html}' => $script_login
]);

// 3. Forgot Password -> auth/forgot-password.blade.php
$forgot_html = compile($template, [
    '{role_title}' => "{{ ucfirst(request()->query('role', 'Customer')) }}",
    '{page_title}' => 'Forgot Password',
    '{heading}' => 'Forgot Password',
    '{subheading}' => 'Enter your email to receive a reset code',
    '{form_html}' => <<<HTML
    <form id="fm" method="POST" action="{{ route('password.email') }}" novalidate>
      @csrf
      <input type="hidden" name="role" value="{{ request()->query('role', 'customer') }}">
      $email_field
      <button class="pb" type="submit">Send Reset Code</button>
    </form>
    <div class="ft"><a href="{{ url('/' . request()->query('role', 'customer') . '/login') }}">Back to Login</a></div>
HTML,
    '{script_html}' => $script_forgot
]);

// 4. Merchant Register -> merchant/auth/register.blade.php
$merchant_reg_html = compile($template, [
    '{role_title}' => 'Merchant',
    '{page_title}' => 'Register',
    '{heading}' => 'Create Business Account',
    '{subheading}' => 'Start rewarding your customers',
    '{form_html}' => <<<HTML
    <form id="fm" method="POST" action="{{ url('/merchant/register') }}" novalidate>
      @csrf
      $name_field
      $email_field
      $password_field
      $password_confirm_field
      <div class="row"><label><input type="checkbox" required>I agree to terms</label></div>
      <button class="pb" type="submit">Create Account</button>
    </form>
    <div class="ft">Already have an account? <a href="{{ url('/merchant/login') }}">Sign In</a></div>
    $google_btn
HTML,
    '{script_html}' => $script_register
]);
$merchant_reg_html = str_replace('{role_lower}', 'merchant', $merchant_reg_html);

// 5. Customer Register -> auth/register.blade.php
$customer_reg_html = compile($template, [
    '{role_title}' => 'Customer',
    '{page_title}' => 'Register',
    '{heading}' => 'Create Account',
    '{subheading}' => 'Join to earn and manage rewards',
    '{form_html}' => <<<HTML
    <form id="fm" method="POST" action="{{ url('/customer/register') }}" novalidate>
      @csrf
      $name_field
      $email_field
      $password_field
      $password_confirm_field
      <div class="row"><label><input type="checkbox" required>I agree to terms</label></div>
      <button class="pb" type="submit">Create Account</button>
    </form>
    <div class="ft">Already have an account? <a href="{{ url('/customer/login') }}">Sign In</a></div>
    $google_btn
HTML,
    '{script_html}' => $script_register
]);
$customer_reg_html = str_replace('{role_lower}', 'customer', $customer_reg_html);

function write_file($path, $content) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $content);
    echo "Updated \$path\n";
}

write_file('c:/xampp/htdocs/BeAurex/resources/views/auth/portal-login.blade.php', $portal_html);
write_file('c:/xampp/htdocs/BeAurex/resources/views/auth/login.blade.php', $admin_html);
write_file('c:/xampp/htdocs/BeAurex/resources/views/auth/forgot-password.blade.php', $forgot_html);
write_file('c:/xampp/htdocs/BeAurex/resources/views/merchant/auth/register.blade.php', $merchant_reg_html);
write_file('c:/xampp/htdocs/BeAurex/resources/views/auth/register.blade.php', $customer_reg_html);

echo "All views updated to mobile format!";
