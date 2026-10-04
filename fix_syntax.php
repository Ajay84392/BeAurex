<?php
$file = 'resources/views/auth/portal-login.blade.php';
$content = file_get_contents($file);
$content = str_replace("'{{ \$role }}'", "\$role", $content);
file_put_contents($file, $content);
echo "Fixed!\n";
