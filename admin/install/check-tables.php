<?php
/**
 * Check Database Tables
 * Quick check script
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $db = new PDO('mysql:host=localhost;dbname=kalpoink_crm;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "<h3>Database connected successfully!</h3>";
    
    // Check if tables exist
    $tables = ['users', 'leads', 'blogs', 'hero_slides', 'services', 'projects', 'team_members', 'faqs', 'statistics', 'testimonials', 'page_content', 'gallery', 'settings', 'activity_log'];
    
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Table</th><th>Status</th><th>Rows</th></tr>";
    
    foreach ($tables as $table) {
        try {
            $stmt = $db->query("SELECT COUNT(*) as cnt FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['cnt'];
            echo "<tr><td>$table</td><td style='color:green'>✓ Exists</td><td>$count rows</td></tr>";
        } catch (PDOException $e) {
            echo "<tr><td>$table</td><td style='color:red'>✗ Missing</td><td>-</td></tr>";
        }
    }
    
    echo "</table>";
    
    echo "<br><br>";
    echo "<a href='update-schema.php' style='padding:10px 20px; background:#ffc107; color:#000; text-decoration:none; margin-right:10px;'>Update Schema</a>";
    echo "<a href='demo-content.php' style='padding:10px 20px; background:#0d6efd; color:#fff; text-decoration:none;'>Import Demo Content</a>";
    
} catch (PDOException $e) {
    echo "<h3 style='color:red'>Database Error: " . $e->getMessage() . "</h3>";
    echo "<p>Please run <a href='index.php'>setup.sql</a> first to create the database.</p>";
}
?>
