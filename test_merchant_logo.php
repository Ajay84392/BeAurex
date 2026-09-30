<?php
$f = 'resources/views/merchant/profile.blade.php';
$c = file_get_contents($f);
$pos = strpos($c, 'business->logo');
if ($pos !== false) {
    echo substr($c, max(0, $pos - 50), 200);
} else {
    echo "Not found";
}
