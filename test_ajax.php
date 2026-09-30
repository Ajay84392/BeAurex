<?php
$files = [
    'resources/views/customer/profile.blade.php',
    'resources/views/admin/profile.blade.php',
    'resources/views/merchant/profile.blade.php'
];
foreach($files as $f) {
    $c = file_get_contents($f);
    if (strpos($c, 'fetch(') !== false || strpos($c, 'XMLHttpRequest') !== false || strpos($c, 'axios') !== false) {
        echo "$f has AJAX\n";
    }
}
