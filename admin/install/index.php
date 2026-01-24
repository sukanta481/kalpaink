<?php
/**
 * Database Installer
 * Kalpoink Admin CRM
 * 
 * Run this file once to set up the database
 */

// Error reporting for installation
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'kalpoink_crm';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
    try {
        // Connect without database
        $pdo = new PDO("mysql:host=$host", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        
        // Read and execute SQL file
        $sqlFile = __DIR__ . '/setup.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Remove comments and split by semicolon
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
            
            // Split by semicolon but not inside quotes
            $statements = preg_split('/;(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !preg_match('/^(USE|CREATE DATABASE)/i', $statement)) {
                    $pdo->exec($statement);
                }
            }
            
            $success = true;
            $message = 'Database installed successfully! You can now <a href="../login.php">login to the admin panel</a>.';
            $message .= '<br><br><strong>Default Credentials:</strong><br>Username: admin<br>Password: admin123';
        } else {
            $message = 'SQL file not found. Please make sure setup.sql exists in the install folder.';
        }
        
    } catch (PDOException $e) {
        $message = 'Database Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install - Kalpoink Admin CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .install-card {
            background: white;
            border-radius: 1rem;
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }
        .install-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        .install-logo h1 {
            color: #4f46e5;
            font-weight: 700;
        }
        .check-item {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .check-item i {
            width: 24px;
            text-align: center;
            margin-right: 0.75rem;
        }
        .check-item.success i { color: #10b981; }
        .check-item.error i { color: #ef4444; }
    </style>
</head>
<body>
    <div class="install-card">
        <div class="install-logo">
            <h1><i class="fas fa-cube me-2"></i>Kalpoink</h1>
            <p class="text-muted">Admin CRM Installation</p>
        </div>
        
        <?php if ($message): ?>
        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!$success): ?>
        <h5 class="mb-3">System Requirements</h5>
        
        <div class="check-item <?php echo version_compare(PHP_VERSION, '7.4.0', '>=') ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo version_compare(PHP_VERSION, '7.4.0', '>=') ? 'check' : 'times'; ?>"></i>
            <span>PHP Version <?php echo PHP_VERSION; ?> (Required: 7.4+)</span>
        </div>
        
        <div class="check-item <?php echo extension_loaded('pdo') ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo extension_loaded('pdo') ? 'check' : 'times'; ?>"></i>
            <span>PDO Extension</span>
        </div>
        
        <div class="check-item <?php echo extension_loaded('pdo_mysql') ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo extension_loaded('pdo_mysql') ? 'check' : 'times'; ?>"></i>
            <span>PDO MySQL Extension</span>
        </div>
        
        <?php
        $uploadDir = dirname(__DIR__) . '/../uploads';
        $isWritable = is_writable($uploadDir) || is_writable(dirname($uploadDir));
        ?>
        <div class="check-item <?php echo $isWritable ? 'success' : 'error'; ?>">
            <i class="fas fa-<?php echo $isWritable ? 'check' : 'times'; ?>"></i>
            <span>Uploads Directory Writable</span>
        </div>
        
        <hr class="my-4">
        
        <h5 class="mb-3">Database Configuration</h5>
        <p class="text-muted small">Make sure MySQL/MariaDB is running. The installer will create the database automatically.</p>
        
        <table class="table table-sm">
            <tr><td>Host</td><td><code><?php echo $host; ?></code></td></tr>
            <tr><td>Database</td><td><code><?php echo $dbname; ?></code></td></tr>
            <tr><td>User</td><td><code><?php echo $user; ?></code></td></tr>
        </table>
        
        <form method="POST" class="mt-4">
            <button type="submit" name="install" class="btn btn-primary w-100 py-2">
                <i class="fas fa-database me-2"></i>Install Database
            </button>
        </form>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                Edit <code>admin/config/database.php</code> to change database settings.
            </small>
        </div>
        <?php else: ?>
        <div class="text-center">
            <a href="../login.php" class="btn btn-primary btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>Go to Login
            </a>
        </div>
        
        <div class="alert alert-warning mt-4 mb-0">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Security Notice:</strong> Delete the <code>/admin/install/</code> folder after installation.
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
