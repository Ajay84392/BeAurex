<?php
$f = 'resources/views/merchant/profile.blade.php';
$c = file_get_contents($f);
$pos = strpos($c, 'type="file"');
echo substr($c, max(0, $pos - 300), 500);
