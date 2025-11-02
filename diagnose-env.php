<?php
/**
 * Environment Diagnostic Script
 * Run this via: php diagnose-env.php
 */

echo "=== ENVIRONMENT DIAGNOSTIC ===\n\n";

// Check if .env file exists
$envPath = __DIR__ . '/.env';
$envExists = file_exists($envPath);

echo "1. ENV File Check:\n";
echo "   Path: {$envPath}\n";
echo "   Exists: " . ($envExists ? "✓ YES" : "✗ NO") . "\n";

if (!$envExists) {
    echo "   ERROR: .env file not found!\n";
    echo "   Action: Rename 'env' to '.env'\n\n";
    exit(1);
}

echo "   Readable: " . (is_readable($envPath) ? "✓ YES" : "✗ NO") . "\n";
echo "   Size: " . filesize($envPath) . " bytes\n\n";

// Load Laravel
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "2. Laravel Bootstrap: ✓ SUCCESS\n\n";

// Check Email Configuration
echo "3. Email Configuration:\n";
echo "   MAIL_MAILER: " . (config('mail.default') ?: 'NOT SET') . "\n";
echo "   MAIL_HOST: " . (config('mail.mailers.smtp.host') ?: 'NOT SET') . "\n";
echo "   MAIL_PORT: " . (config('mail.mailers.smtp.port') ?: 'NOT SET') . "\n";
echo "   MAIL_USERNAME: " . (config('mail.mailers.smtp.username') ?: 'NOT SET') . "\n";
echo "   MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***SET***' : 'NOT SET') . "\n";
echo "   MAIL_ENCRYPTION: " . (config('mail.mailers.smtp.encryption') ?: 'NOT SET') . "\n";
echo "   MAIL_FROM_ADDRESS: " . (config('mail.from.address') ?: 'NOT SET') . "\n";
echo "   MAIL_FROM_NAME: " . (config('mail.from.name') ?: 'NOT SET') . "\n\n";

// Check SMS Configuration
echo "4. SMS Configuration (MNotify):\n";
echo "   API_KEY: " . (config('services.mnotify.api_key') ? '***SET***' : 'NOT SET') . "\n";
echo "   SENDER_ID: " . (config('services.mnotify.sender_id') ?: 'NOT SET') . "\n";
echo "   BASE_URL: " . (config('services.mnotify.base_url') ?: 'NOT SET') . "\n\n";

// Check Queue Configuration
echo "5. Queue Configuration:\n";
echo "   QUEUE_CONNECTION: " . (config('queue.default') ?: 'NOT SET') . "\n\n";

// Check Cache Configuration
echo "6. Cache Configuration:\n";
echo "   CACHE_DRIVER: " . (config('cache.default') ?: 'NOT SET') . "\n\n";

// Check if config is cached
$configCached = file_exists(__DIR__ . '/bootstrap/cache/config.php');
echo "7. Config Cache Status:\n";
echo "   Cached: " . ($configCached ? "✓ YES (may be outdated)" : "✗ NO") . "\n";
if ($configCached) {
    echo "   WARNING: Config is cached! Run: php artisan config:clear\n";
}
echo "\n";

// Check Database Connection
echo "8. Database Connection:\n";
try {
    DB::connection()->getPdo();
    echo "   Status: ✓ CONNECTED\n";
    echo "   Database: " . DB::connection()->getDatabaseName() . "\n";
} catch (\Exception $e) {
    echo "   Status: ✗ FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Check SMS Credits
echo "9. SMS Credits Check:\n";
try {
    $admin = DB::table('users')->where('role', 'admin')->first();
    if ($admin) {
        $credit = DB::table('sms_credits')->where('user_id', $admin->id)->first();
        if ($credit) {
            echo "   Admin User: {$admin->name} (ID: {$admin->id})\n";
            echo "   Credits Available: {$credit->credits}\n";
            echo "   Status: " . ($credit->credits > 0 ? "✓ HAS CREDITS" : "✗ NO CREDITS") . "\n";
        } else {
            echo "   Status: ✗ NO CREDIT RECORD FOUND\n";
            echo "   Action: Create SMS credit record\n";
        }
    } else {
        echo "   Status: ✗ NO ADMIN USER FOUND\n";
    }
} catch (\Exception $e) {
    echo "   Status: ✗ ERROR\n";
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Check Jobs Table (for queued jobs)
echo "10. Queue Jobs Check:\n";
try {
    $pendingJobs = DB::table('jobs')->count();
    echo "   Pending Jobs: {$pendingJobs}\n";
    if ($pendingJobs > 0) {
        echo "   WARNING: Jobs are queued but not processing!\n";
        echo "   Action: Change QUEUE_CONNECTION to 'sync' or run queue worker\n";
    }
} catch (\Exception $e) {
    echo "   Status: ✗ ERROR\n";
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Check Failed Jobs
echo "11. Failed Jobs Check:\n";
try {
    $failedJobs = DB::table('failed_jobs')->count();
    echo "   Failed Jobs: {$failedJobs}\n";
    if ($failedJobs > 0) {
        echo "   WARNING: Some jobs have failed!\n";
        $recentFailed = DB::table('failed_jobs')
            ->orderBy('failed_at', 'desc')
            ->limit(3)
            ->get();
        foreach ($recentFailed as $job) {
            echo "   - " . substr($job->exception, 0, 100) . "...\n";
        }
    }
} catch (\Exception $e) {
    echo "   Status: ✗ ERROR\n";
    echo "   Error: " . $e->getMessage() . "\n";
}
echo "\n";

// Summary
echo "=== DIAGNOSTIC SUMMARY ===\n\n";

$issues = [];

if (!$envExists) {
    $issues[] = "ENV file not found";
}

if ($configCached) {
    $issues[] = "Config is cached (run: php artisan config:clear)";
}

if (!config('mail.mailers.smtp.host')) {
    $issues[] = "Email SMTP not configured";
}

if (!config('services.mnotify.api_key')) {
    $issues[] = "MNotify API key not configured";
}

if (config('queue.default') === 'database') {
    $issues[] = "Queue using database but no worker running";
}

if (empty($issues)) {
    echo "✓ No critical issues found!\n";
    echo "\nIf email/SMS still not working, check:\n";
    echo "1. Network connectivity\n";
    echo "2. Gmail App Password is correct\n";
    echo "3. MNotify API key is valid\n";
    echo "4. Firewall/security settings\n";
} else {
    echo "✗ Issues Found:\n";
    foreach ($issues as $i => $issue) {
        echo "   " . ($i + 1) . ". {$issue}\n";
    }
}

echo "\n=== END DIAGNOSTIC ===\n";
