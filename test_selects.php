<?php
$f = 'resources/views/customer/profile.blade.php';
$c = file_get_contents($f);
$tzStart = strpos($c, 'name="timezone"');
echo "Timezone:\n" . substr($c, $tzStart, 300) . "\n\n";

$dfStart = strpos($c, 'name="date_format"');
echo "Date Format:\n" . substr($c, $dfStart, 300) . "\n\n";
