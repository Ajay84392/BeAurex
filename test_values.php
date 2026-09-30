<?php
$c = file_get_contents('resources/views/customer/profile.blade.php');
preg_match_all('/<input[^>]*name=["\']([^"\']+)["\'][^>]*>/i', $c, $names);
preg_match_all('/<input[^>]*value=["\']([^"\']+)["\'][^>]*>/i', $c, $values);
print_r($names[1]);
print_r($values[1]);
