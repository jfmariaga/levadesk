<?php
chdir(dirname(__DIR__)); // Cambia al directorio superior donde está 'artisan'
exec('/usr/bin/php artisan schedule:run');
?>
