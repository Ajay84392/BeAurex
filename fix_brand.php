<?php

$replacements = [
    'resources/views/auth/portal-login.blade.php' => '{{ ucfirst($role) }}',
    'resources/views/auth/login.blade.php' => 'Admin',
    'resources/views/auth/forgot-password.blade.php' => '{{ ucfirst(request()->query(\'role\', \'Customer\')) }}',
    'resources/views/merchant/auth/register.blade.php' => 'Merchant',
    'resources/views/auth/register.blade.php' => 'Customer',
];

foreach ($replacements as $file => $badgeText) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    $newBrand = <<<HTML
    <div class="brand">
        <img src="/images/logo.jpg" alt="BeAurex Logo" class="logo-img" onerror="this.outerHTML='<div style=\\'width:64px;height:64px;border-radius:16px;background:var(--red);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;\\'>B</div>'">
        <div>
            <div class="bn">Be<span>Aurex</span></div>
            <div class="bs">$badgeText</div>
        </div>
    </div>
HTML;

    // Use regex to find the brand block up to the closing </div> of the brand div.
    $pattern = '/<div class="brand">.*?<\/div>\s*<\/div>\s*<\/div>/s';
    $content = preg_replace($pattern, $newBrand, $content);

    file_put_contents($file, $content);
    echo "Updated $file\n";
}

