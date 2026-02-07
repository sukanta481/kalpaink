<?php
/**
 * Database Configuration
 * Kalpoink Admin CRM
 */

// Auto-detect environment (local vs live server)
$serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
if ($serverName === 'localhost' || $serverName === '127.0.0.1') {
    // Local Development (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'kalpoink_crm');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    // Live Server (Hostinger)
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'u286257250_kalpaink_crm');
    define('DB_USER', 'u286257250_kalpaink_crm');
    define('DB_PASS', 'Sukanta@8961');
}

class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // If database doesn't exist, redirect to installer
            if (strpos($e->getMessage(), 'Unknown database') !== false) {
                // Auto-detect base path
                $basePath = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') ? '/kalpoink/' : '/';
                header('Location: ' . $basePath . 'admin/install/');
                exit;
            }
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
    
    // Prevent cloning
    private function __clone() {}
    
    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Helper function to get database connection
function getDB() {
    return Database::getInstance()->getConnection();
}
?>
