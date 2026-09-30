<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
$formStart = strpos($c, '<form');
$formEnd = strpos($c, '</form>');
$fileInput = strpos($c, '<input type="file"');
echo "Customer: form $formStart to $formEnd, file: $fileInput\n";

$f = 'resources/views/admin/profile.blade.php';
$c = file_get_contents($f);
$formStart = strpos($c, '<form');
$formEnd = strpos($c, '</form>');
$fileInput = strpos($c, '<input type="file"');
echo "Admin: form $formStart to $formEnd, file: $fileInput\n";

$f = 'resources/views/merchant/profile.blade.php';
$c = file_get_contents($f);
$formStart = strpos($c, '<form');
$formEnd = strpos($c, '</form>');
$fileInput = strpos($c, '<input type="file"');
echo "Merchant: form $formStart to $formEnd, file: $fileInput\n";
