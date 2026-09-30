<?php
$files = [
    'resources/views/customer/profile.blade.php',
    'resources/views/admin/profile.blade.php',
    'resources/views/merchant/profile.blade.php'
];

foreach ($files as $f) {
    echo "--- $f ---\n";
    $c = file_get_contents($f);
    preg_match_all('/<input[^>]*type="file"[^>]*>/i', $c, $matches);
    print_r($matches[0]);
}
