<?php
$files = [
    'resources/views/customer/profile.blade.php',
    'resources/views/merchant/profile.blade.php',
    'resources/views/admin/profile.blade.php'
];

foreach ($files as $f) {
    echo "--- " . $f . " ---\n";
    $c = file_get_contents($f);
    preg_match_all('/<form[^>]*>/', $c, $forms);
    print_r($forms[0]);
}
