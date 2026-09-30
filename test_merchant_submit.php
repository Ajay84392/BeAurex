<?php
$f = 'resources/views/merchant/profile.blade.php';
$c = file_get_contents($f);
$submit = strpos($c, '<button type="submit"');
echo substr($c, max(0, $submit - 100), 300);
