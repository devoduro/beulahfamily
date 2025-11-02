<?php
// Clear OPcache
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared!\n";
} else {
    echo "OPcache not enabled\n";
}

// Clear Laravel caches
echo shell_exec('cd .. && php artisan optimize:clear 2>&1');
echo "\nAll caches cleared!\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";
