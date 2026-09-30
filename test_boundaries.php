<?php
$f = 'resources/views/admin/profile.blade.php';
$c = file_get_contents($f);
$formStart = strpos($c, '<form');
$formEnd = strpos($c, '</form>');
$submit = strpos($c, '<button type="submit"');
echo "Admin Form starts at: $formStart, ends at: $formEnd, submit at: $submit\n";

$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
$formStart = strpos($c, '<form');
$formEnd = strpos($c, '</form>');
$submit = strpos($c, '<button type="submit"');
echo "Customer Form starts at: $formStart, ends at: $formEnd, submit at: $submit\n";
