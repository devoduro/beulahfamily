<?php
// Quick script to add missing columns to events table

$host = '127.0.0.1';
$db = 'beulah_db25';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n\n";
    
    // Check if columns exist
    $stmt = $pdo->query("SHOW COLUMNS FROM events LIKE 'flyer_path'");
    $flyerExists = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW COLUMNS FROM events LIKE 'program_outline_path'");
    $programExists = $stmt->rowCount() > 0;
    
    echo "flyer_path exists: " . ($flyerExists ? "YES" : "NO") . "\n";
    echo "program_outline_path exists: " . ($programExists ? "YES" : "NO") . "\n\n";
    
    if (!$flyerExists) {
        echo "Adding flyer_path column...\n";
        $pdo->exec("ALTER TABLE events ADD COLUMN flyer_path VARCHAR(255) NULL AFTER image_path");
        echo "✓ flyer_path column added successfully!\n";
    } else {
        echo "✓ flyer_path column already exists.\n";
    }
    
    if (!$programExists) {
        echo "Adding program_outline_path column...\n";
        $pdo->exec("ALTER TABLE events ADD COLUMN program_outline_path VARCHAR(255) NULL AFTER flyer_path");
        echo "✓ program_outline_path column added successfully!\n";
    } else {
        echo "✓ program_outline_path column already exists.\n";
    }
    
    echo "\n✅ All columns are now in place!\n";
    echo "\nYou can now upload flyers and program outlines when creating events.\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
