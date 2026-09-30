<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
echo strpos($c, '<script') !== false ? "Has script\n" : "No script\n";
