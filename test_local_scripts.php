<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
preg_match_all('/<script[^>]*>(.*?)<\/script>/is', $c, $matches);
foreach($matches[0] as $s) {
    if (strpos($s, 'tailwindcss') === false && strpos($s, 'alpinejs') === false) {
        echo substr($s, 0, 100) . "...\n";
    }
}
