<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
$langStart = strpos($c, 'name="language"');
echo substr($c, $langStart, 400);
