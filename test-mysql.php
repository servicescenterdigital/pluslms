<?php

echo "SchoolDream+ MySQL Connection Test\n";
echo "====================================\n\n";

// Load environment
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? 'schooldream_lms';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

echo "Testing connection with:\n";
echo "  Host: {$host}\n";
echo "  Port: {$port}\n";
echo "  Database: {$database}\n";
echo "  Username: {$username}\n\n";

try {
    // Try connecting without specifying database
    $dsn = "mysql:host={$host};port={$port}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    
    echo "✓ Successfully connected to MySQL server\n\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '{$database}'");
    $dbExists = $stmt->fetch();
    
    if ($dbExists) {
        echo "✓ Database '{$database}' exists\n";
        
        // Connect to the database
        $pdo->exec("USE `{$database}`");
        
        // Check tables
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($tables) > 0) {
            echo "✓ Found " . count($tables) . " tables\n";
            echo "\nTables:\n";
            foreach ($tables as $table) {
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM `{$table}`");
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                echo "  - {$table}: {$count} rows\n";
            }
        } else {
            echo "⚠ Database is empty (no tables found)\n";
            echo "  Run: php database/setup.php\n";
        }
    } else {
        echo "⚠ Database '{$database}' does not exist\n";
        echo "  Run: php database/setup.php\n";
    }
    
    echo "\n✓ MySQL connection test passed!\n";
    echo "\nYou can now run the application:\n";
    echo "  php -S localhost:3000 -t public\n";
    
} catch (PDOException $e) {
    echo "✗ MySQL connection failed!\n\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "Troubleshooting:\n";
    echo "  1. Check if MySQL/MariaDB is running:\n";
    echo "     sudo systemctl status mysql\n";
    echo "     (or: sudo systemctl status mariadb)\n\n";
    echo "  2. Start MySQL if it's not running:\n";
    echo "     sudo systemctl start mysql\n\n";
    echo "  3. Check your .env file settings\n\n";
    echo "  4. Make sure you can connect with:\n";
    echo "     mysql -u {$username} -p\n\n";
    exit(1);
}
