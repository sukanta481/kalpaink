<?php
/**
 * Content Tables Installer
 * Run this after initial setup to add content management tables
 * Kalpoink Admin CRM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db = getDB();
        
        // Hero Slides Table
        $db->exec("CREATE TABLE IF NOT EXISTS hero_slides (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Page Content Table
        $db->exec("CREATE TABLE IF NOT EXISTS page_content (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Gallery Table
        $db->exec("CREATE TABLE IF NOT EXISTS gallery (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(50) DEFAULT 'portfolio',
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255) NOT NULL,
            thumbnail VARCHAR(255),
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Testimonials Table
        $db->exec("CREATE TABLE IF NOT EXISTS testimonials (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // FAQs Table
        $db->exec("CREATE TABLE IF NOT EXISTS faqs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(50) DEFAULT 'general',
            question VARCHAR(500) NOT NULL,
            answer TEXT NOT NULL,
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Statistics Table
        $db->exec("CREATE TABLE IF NOT EXISTS statistics (
            id INT AUTO_INCREMENT PRIMARY KEY,
            stat_key VARCHAR(50) NOT NULL UNIQUE,
            stat_value VARCHAR(50) NOT NULL,
            stat_label VARCHAR(100) NOT NULL,
            stat_icon VARCHAR(100),
            stat_suffix VARCHAR(10) DEFAULT '',
            sort_order INT DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // Insert default Hero Slide
        $check = $db->query("SELECT COUNT(*) FROM hero_slides")->fetchColumn();
        if ($check == 0) {
            $db->exec("INSERT INTO hero_slides (title, subtitle, badge_text, button1_text, button1_link, button2_text, button2_link, is_active)
                VALUES ('Reimagining Digital with Purpose', 'We create beautiful, functional digital experiences that help your business grow and connect with customers.', 'Creative Design Studio', 'Get Quote', 'contact.php', 'Our Services', 'services.php', 1)");
        }
        
        // Insert default Statistics
        $check = $db->query("SELECT COUNT(*) FROM statistics")->fetchColumn();
        if ($check == 0) {
            $db->exec("INSERT INTO statistics (stat_key, stat_value, stat_label, stat_icon, stat_suffix, sort_order, is_active) VALUES
                ('happy_clients', '500', 'Happy Clients', 'fas fa-smile', '+', 1, 1),
                ('projects_completed', '850', 'Projects Completed', 'fas fa-project-diagram', '+', 2, 1),
                ('years_experience', '10', 'Years Experience', 'fas fa-calendar-check', '+', 3, 1),
                ('team_members', '25', 'Team Members', 'fas fa-users', '', 4, 1)");
        }
        
        // Insert default FAQs
        $check = $db->query("SELECT COUNT(*) FROM faqs")->fetchColumn();
        if ($check == 0) {
            $db->exec("INSERT INTO faqs (category, question, answer, sort_order, is_active) VALUES
                ('general', 'What services does Kalpoink offer?', '<p>We offer a comprehensive range of digital services including web design, web development, branding, UI/UX design, digital marketing, SEO, and content creation. Our team can handle projects from concept to completion.</p>', 1, 1),
                ('process', 'What is your typical project timeline?', '<p>Project timelines vary based on scope and complexity. A simple website might take 2-4 weeks, while a complex web application could take 2-3 months. We provide detailed timelines during our initial consultation.</p>', 2, 1),
                ('pricing', 'How do you price your projects?', '<p>We offer both fixed-price and hourly billing options depending on the project type. We always provide detailed quotes after understanding your requirements. Contact us for a free consultation and estimate.</p>', 3, 1)");
        }
        
        // Insert default Testimonials
        $check = $db->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
        if ($check == 0) {
            $db->exec("INSERT INTO testimonials (client_name, client_position, client_company, testimonial_text, rating, is_featured, is_active) VALUES
                ('John Smith', 'CEO', 'TechCorp Inc.', 'Kalpoink transformed our online presence completely. Their team was professional, creative, and delivered beyond our expectations. Highly recommended!', 5, 1, 1),
                ('Sarah Johnson', 'Marketing Director', 'Growth Labs', 'Working with Kalpoink was a pleasure. They understood our vision and brought it to life with stunning design and flawless execution.', 5, 0, 1)");
        }
        
        $success = true;
        $message = 'Content management tables installed successfully! You can now manage Hero Slides, Gallery, Testimonials, FAQs, Statistics, and Page Content from the admin panel.';
        
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
    <title>Install Content Tables - Kalpoink Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-database me-2"></i>Install Content Management Tables
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>">
                            <i class="fas fa-<?php echo $success ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                            <?php echo $message; ?>
                        </div>
                        
                        <?php if ($success): ?>
                        <div class="text-center">
                            <a href="../content.php" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-2"></i>Go to Content Manager
                            </a>
                            <a href="../index.php" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-home me-2"></i>Dashboard
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <p>This will create the following database tables for content management:</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    <li class="list-group-item">
                                        <i class="fas fa-images text-primary me-2"></i><strong>hero_slides</strong>
                                        <br><small class="text-muted">Homepage slider content</small>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-file-alt text-primary me-2"></i><strong>page_content</strong>
                                        <br><small class="text-muted">All page sections</small>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-photo-video text-primary me-2"></i><strong>gallery</strong>
                                        <br><small class="text-muted">Portfolio & media images</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group mb-3">
                                    <li class="list-group-item">
                                        <i class="fas fa-quote-right text-primary me-2"></i><strong>testimonials</strong>
                                        <br><small class="text-muted">Client reviews</small>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-question-circle text-primary me-2"></i><strong>faqs</strong>
                                        <br><small class="text-muted">Frequently asked questions</small>
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-chart-bar text-primary me-2"></i><strong>statistics</strong>
                                        <br><small class="text-muted">Counter numbers</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This will also add sample data to help you get started. Existing tables will not be affected.
                        </div>
                        
                        <form method="POST">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-download me-2"></i>Install Content Tables
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
