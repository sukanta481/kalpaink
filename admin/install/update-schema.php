<?php
/**
 * Database Schema Update
 * Updates existing tables to match latest schema
 * Kalpoink Admin CRM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';

$message = '';
$success = false;
$details = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDB();
        
        // Update team_members table - add 'image' column (keep image_pro for backward compatibility)
        try {
            $db->exec("ALTER TABLE team_members ADD COLUMN IF NOT EXISTS image VARCHAR(255) AFTER bio");
            $db->exec("ALTER TABLE team_members ADD COLUMN IF NOT EXISTS instagram VARCHAR(255) AFTER twitter");
            // Copy data from image_pro to image if image is empty
            $db->exec("UPDATE team_members SET image = image_pro WHERE image IS NULL AND image_pro IS NOT NULL");
            $details[] = "✓ Updated team_members table";
        } catch (PDOException $e) {
            $details[] = "ℹ team_members: " . $e->getMessage();
        }
        
        // Update projects table
        try {
            // Add new columns
            $db->exec("ALTER TABLE projects ADD COLUMN IF NOT EXISTS short_description TEXT AFTER slug");
            $db->exec("ALTER TABLE projects ADD COLUMN IF NOT EXISTS full_description LONGTEXT AFTER short_description");
            $db->exec("ALTER TABLE projects ADD COLUMN IF NOT EXISTS project_date DATE AFTER client_name");
            $db->exec("ALTER TABLE projects ADD COLUMN IF NOT EXISTS gallery_images TEXT AFTER featured_image");
            $db->exec("ALTER TABLE projects ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER is_featured");
            // Copy data from old columns
            $db->exec("UPDATE projects SET short_description = description WHERE short_description IS NULL AND description IS NOT NULL");
            $db->exec("UPDATE projects SET full_description = content WHERE full_description IS NULL AND content IS NOT NULL");
            $db->exec("UPDATE projects SET project_date = completed_date WHERE project_date IS NULL AND completed_date IS NOT NULL");
            $db->exec("UPDATE projects SET gallery_images = gallery WHERE gallery_images IS NULL AND gallery IS NOT NULL");
            $db->exec("UPDATE projects SET is_active = CASE WHEN status = 'published' THEN 1 ELSE 0 END WHERE is_active IS NULL");
            $details[] = "✓ Updated projects table";
        } catch (PDOException $e) {
            $details[] = "ℹ projects table: " . $e->getMessage();
        }
        
        // Update services table
        try {
            $db->exec("ALTER TABLE services ADD COLUMN IF NOT EXISTS is_featured TINYINT(1) DEFAULT 0 AFTER sort_order");
            $db->exec("ALTER TABLE services ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 AFTER is_featured");
            // Copy data from status
            $db->exec("UPDATE services SET is_active = CASE WHEN status = 'active' THEN 1 ELSE 0 END WHERE is_active IS NULL");
            $details[] = "✓ Updated services table";
        } catch (PDOException $e) {
            $details[] = "ℹ services table: " . $e->getMessage();
        }
        
        // Create content tables if they don't exist
        $content_tables = [
            "hero_slides" => "CREATE TABLE IF NOT EXISTS hero_slides (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                subtitle TEXT,
                badge_text VARCHAR(100),
                image1 VARCHAR(255),
                image2 VARCHAR(255),
                image3 VARCHAR(255),
                button1_text VARCHAR(100) DEFAULT 'Get Quote',
                button1_link VARCHAR(255) DEFAULT 'contact.php',
                button2_text VARCHAR(100) DEFAULT 'Services',
                button2_link VARCHAR(255) DEFAULT 'services.php',
                sort_order INT DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "page_content" => "CREATE TABLE IF NOT EXISTS page_content (
                id INT AUTO_INCREMENT PRIMARY KEY,
                page_name VARCHAR(50) NOT NULL,
                section_key VARCHAR(100) NOT NULL,
                content_title VARCHAR(255),
                content_subtitle TEXT,
                content_body LONGTEXT,
                content_image VARCHAR(255),
                content_extra JSON,
                is_active TINYINT(1) DEFAULT 1,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_section (page_name, section_key)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "gallery" => "CREATE TABLE IF NOT EXISTS gallery (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category VARCHAR(50) DEFAULT 'portfolio',
                title VARCHAR(255) NOT NULL,
                description TEXT,
                image VARCHAR(255) NOT NULL,
                thumbnail VARCHAR(255),
                sort_order INT DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "testimonials" => "CREATE TABLE IF NOT EXISTS testimonials (
                id INT AUTO_INCREMENT PRIMARY KEY,
                client_name VARCHAR(100) NOT NULL,
                client_position VARCHAR(100),
                client_company VARCHAR(100),
                client_avatar VARCHAR(255),
                testimonial_text TEXT NOT NULL,
                rating TINYINT DEFAULT 5,
                sort_order INT DEFAULT 0,
                is_featured TINYINT(1) DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "faqs" => "CREATE TABLE IF NOT EXISTS faqs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                category VARCHAR(50) DEFAULT 'general',
                question VARCHAR(500) NOT NULL,
                answer TEXT NOT NULL,
                sort_order INT DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            
            "statistics" => "CREATE TABLE IF NOT EXISTS statistics (
                id INT AUTO_INCREMENT PRIMARY KEY,
                stat_key VARCHAR(50) NOT NULL UNIQUE,
                stat_value VARCHAR(50) NOT NULL,
                stat_label VARCHAR(100) NOT NULL,
                stat_icon VARCHAR(100),
                stat_suffix VARCHAR(10) DEFAULT '',
                sort_order INT DEFAULT 0,
                is_active TINYINT(1) DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        ];
        
        foreach ($content_tables as $name => $sql) {
            try {
                $db->exec($sql);
                $details[] = "✓ Created/verified $name table";
            } catch (PDOException $e) {
                $details[] = "✗ Error with $name: " . $e->getMessage();
            }
        }
        
        $success = true;
        $message = 'Database schema updated successfully!';
        
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
    <title>Update Database Schema - Kalpoink Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="fas fa-sync-alt me-2"></i>Update Database Schema
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>">
                            <i class="fas fa-<?php echo $success ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                            <?php echo $message; ?>
                        </div>
                        
                        <?php if (!empty($details)): ?>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Update Details:</h6>
                                <ul class="mb-0 small">
                                    <?php foreach ($details as $detail): ?>
                                    <li><?php echo $detail; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="text-center">
                            <a href="demo-content.php" class="btn btn-primary">
                                <i class="fas fa-download me-2"></i>Import Demo Content
                            </a>
                            <a href="../index.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </div>
                        
                        <?php else: ?>
                        <p>This will update your database schema to match the latest version. This includes:</p>
                        
                        <ul>
                            <li>Adding missing columns to existing tables</li>
                            <li>Creating content management tables (hero_slides, gallery, etc.)</li>
                            <li>Updating table structures for compatibility</li>
                        </ul>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This is safe to run multiple times. Existing data will not be affected.
                        </div>
                        
                        <form method="POST">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-sync-alt me-2"></i>Update Schema
                                </button>
                                <a href="../index.php" class="btn btn-outline-secondary">Cancel</a>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
