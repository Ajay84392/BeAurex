<?php
$f = 'resources/views/merchant/profile.blade.php';
$c = file_get_contents($f);
$formStart1 = strpos($c, '<form');
$formEnd1 = strpos($c, '</form>');
$submit1 = strpos($c, '<button type="submit"', $formStart1);

$formStart2 = strpos($c, '<form', $formEnd1);
$formEnd2 = strpos($c, '</form>', $formEnd1);
$submit2 = strpos($c, '<button type="submit"', $formStart2);

echo "Form 1: $formStart1 to $formEnd1, Submit: $submit1\n";
echo "Form 2: $formStart2 to $formEnd2, Submit: $submit2\n";
