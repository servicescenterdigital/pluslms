<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

echo "SchoolDream+ Database Setup\n";
echo "===========================\n\n";

$host = env('DB_HOST', '127.0.0.1');
$port = env('DB_PORT', '3306');
$database = env('DB_DATABASE', 'schooldream_lms');
$username = env('DB_USERNAME', 'root');
$password = env('DB_PASSWORD', '');

try {
    // Connect to MySQL server (without database)
    $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to MySQL server\n";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database '{$database}' created or already exists\n";
    
    // Use the database
    $pdo->exec("USE `{$database}`");
    
    // Run migrations
    echo "\nRunning migrations...\n";
    $migrationFile = __DIR__ . '/migrations/001_create_tables.sql';
    
    if (file_exists($migrationFile)) {
        $sql = file_get_contents($migrationFile);
        $pdo->exec($sql);
        echo "✓ Tables created successfully\n";
    } else {
        echo "✗ Migration file not found\n";
        exit(1);
    }
    
    // Run seeds
    echo "\nSeeding database...\n";
    $seedFile = __DIR__ . '/seeds/seed.sql';
    
    if (file_exists($seedFile)) {
        $sql = file_get_contents($seedFile);
        
        // Execute each statement separately
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                } catch (Exception $e) {
                    // Ignore duplicate entry errors during seeding
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        throw $e;
                    }
                }
            }
        }
        echo "✓ Database seeded successfully\n";
    } else {
        echo "⚠ Seed file not found (skipping)\n";
    }
    
    echo "\n";
    echo "===========================\n";
    echo "Setup completed successfully!\n\n";
    echo "Default credentials:\n";
    echo "  Admin: admin@schooldream.com / admin123\n";
    echo "  Instructor: jpmunyaneza@schooldream.com / instructor123\n";
    echo "  Student: alice@example.com / student123\n";
    echo "\nYou can now start the application.\n";
    
} catch (PDOException $e) {
    echo "\n✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
