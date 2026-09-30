<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
echo substr($c, 15000, 500);
