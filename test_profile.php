<?php
$files = [
    'resources/views/customer/profile.blade.php',
    'resources/views/merchant/profile.blade.php',
    'resources/views/admin/profile.blade.php'
];

foreach ($files as $f) {
    echo "--- " . $f . " ---\n";
    if (!file_exists($f)) {
        echo "Missing\n";
        continue;
    }
    $content = file_get_contents($f);
    preg_match_all('/name=["\']([^"\']+)["\']/', $content, $matches);
    $names = array_unique($matches[1]);
    print_r($names);
}
