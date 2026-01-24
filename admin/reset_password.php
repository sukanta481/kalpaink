<?php
/**
 * Reset Admin Password
 * Run this once then delete the file
 */

$host = 'localhost';
$dbname = 'kalpoink_crm';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Generate proper password hash for 'admin123'
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    $exists = $stmt->fetch();
    
    if ($exists) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
        $stmt->execute([$password_hash]);
        echo "<h2>✅ Admin password has been reset!</h2>";
    } else {
        // Create admin user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@kalpoink.com', $password_hash, 'Administrator', 'admin', 1]);
        echo "<h2>✅ Admin user has been created!</h2>";
    }
    
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
    echo "<br><br><p style='color:red;'><strong>⚠️ DELETE THIS FILE NOW for security!</strong></p>";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
