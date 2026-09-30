<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
$pos = strpos($c, 'user()->photo');
if ($pos !== false) {
    echo substr($c, max(0, $pos - 50), 200);
} else {
    echo "Not found";
}
