<?php
/**
 * Live Server Migration
 * Run once on Hostinger to sync database changes
 * DELETE THIS FILE AFTER RUNNING!
 */
require_once __DIR__ . '/../config/database.php';
$db = getDB();

echo "<h2>🔄 Kalpanik Live Migration</h2><pre>";

// ── 1. Add 'file' to settings.setting_type enum (if not already) ──
try {
    $col = $db->query("SHOW COLUMNS FROM settings LIKE 'setting_type'")->fetch();
    if (strpos($col['Type'], 'file') === false) {
        $db->exec("ALTER TABLE settings MODIFY COLUMN setting_type ENUM('text','textarea','number','boolean','json','file') NOT NULL DEFAULT 'text'");
        echo "✅ Added 'file' to settings.setting_type enum\n";
    } else {
        echo "⏭ settings.setting_type already has 'file'\n";
    }
} catch (Exception $e) {
    echo "❌ Settings enum: " . $e->getMessage() . "\n";
}

// ── 2. Add site_logo and site_favicon settings rows ──
$settingsToAdd = [
    ['site_logo', '', 'file', 'general'],
    ['site_favicon', '', 'file', 'general'],
];

foreach ($settingsToAdd as $s) {
    try {
        $check = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
        $check->execute([$s[0]]);
        if ($check->fetchColumn() == 0) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, category) VALUES (?, ?, ?, ?)");
            $stmt->execute($s);
            echo "✅ Added setting: {$s[0]}\n";
        } else {
            echo "⏭ Setting {$s[0]} already exists\n";
        }
    } catch (Exception $e) {
        echo "❌ Setting {$s[0]}: " . $e->getMessage() . "\n";
    }
}

// ── 3. Seed page_content sections ──
$sections = [
    ['about', 'hero', 'We craft brands', 'that resonate.', 
     'A young, fearless crew of creatives from Kolkata — blending strategy, design, and technology to make businesses unforgettable.', 
     null, '{"eyebrow": "Our Story", "hero_image": "assets/images/about-hero-dark.png", "badge_text": "Since 2018"}', 1],
    
    ['about', 'about_card', 'Who We Really Are', 'Behind The Chisel', 
     '<p>At Kalpanik, creativity isn\'t just our passion – it\'s our heartbeat. We\'re a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p><p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>', 
     null, null, 1],
    
    ['about', 'who_we_are', 'Who we are', null, 
     '<p>We\'re your digital dream team – young, inventive, and fearless. <a href="#" class="highlight-link">Kalpanik</a> blends strategy, creativity, and technology to create unique digital experiences that resonate.</p><p>Our diverse squad includes strategists who live for analytics, creative designers who turn pixels into perfection, and social media wizards who keep trends on a constant watch. When you partner with us, expect authenticity, creative flair, and results-driven creativity.</p><p>Founded by Suman Kundu and Souvik Das, we bring together years of experience in graphic design, branding, and digital marketing to help businesses stand out in the crowded digital landscape.</p>', 
     'assets/images/digital-desk-bento.png', null, 1],
    
    ['about', 'join_us', 'Why Work With Us?', null, 
     'Ready to elevate your brand? If you\'re creative, passionate, and driven by innovation, Kalpanik is your perfect partner. Let\'s disrupt the digital world together!', 
     null, '{"button_text": "Get In Touch", "button_link": "contact.php"}', 1],
    
    ['about', 'team_section', 'Meet The Creators', 'The Minds Behind The Magic', 
     'Two dreamers who turned their passion into your brand\'s success story', 
     null, null, 1],
    
    ['about', 'testimonials_section', 'What Our Clients Say', 'Client Love', 
     'Real feedback from real partners who trusted us with their brands', 
     null, null, 1],

    ['home', 'hero', 'Creative Digital Solutions', 'Kalpanik', 
     'We create stunning digital experiences that drive results for your business.', 
     null, '{"button_text": "Get Started", "button_link": "contact.php"}', 1],

    ['home', 'services_section', 'Our Services', 'What We Do', 
     'Comprehensive digital solutions to help your business grow and thrive in the digital landscape.', 
     null, null, 1],
    
    ['home', 'about_preview', 'About Us', 'Who We Are', 
     'A passionate team of creative professionals dedicated to transforming brands through innovative design and digital marketing strategies.', 
     null, '{"button_text": "Learn More", "button_link": "about.php"}', 1],
    
    ['home', 'cta', 'Ready to Transform Your Brand?', null,
     'Let\'s create something extraordinary together. Get in touch with us today and start your digital journey.',
     null, '{"button_text": "Get Enquiry Now", "button_link": "contact.php"}', 1],

    ['home', 'about', 'About Kalpanik', 'Who We Are',
     '<p>A passionate team of creative professionals dedicated to transforming brands through innovative design and digital marketing strategies.</p>',
     null, null, 1],

    ['home', 'welcome', 'We Sculpt Brands.', 'Who We Are',
     '<p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p><p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we\'re the creative studio that brings visions to life.</p>',
     'assets/images/about-fusion.png', null, 1],

    ['home', 'team', 'Meet The Creators', 'Our Team',
     'Two dreamers who turned their passion into your brand\'s success story',
     null, null, 1],

    ['home', 'case_studies', 'Case Studies', 'Our Work',
     'Some of our recent creative work',
     null, null, 1],

    ['home', 'vlogs', 'VLOG &amp; REEL', 'Video Content',
     null, null, null, 1],

    ['home', 'testimonials', 'What Our Clients Say', 'Client Love',
     'Real feedback from real partners who trusted us with their brands',
     null, null, 1],

    ['home', 'trust_bar', 'Trusted by 250+ business worldwide', 'Brands',
     null, null, null, 1],

    ['home', 'faq', 'Got Questions?', 'FAQ',
     'Find answers to commonly asked questions about our services, process, and more.',
     null, null, 1],

    ['services', 'hero', 'Our Services', 'What We Offer', 
     'Comprehensive digital solutions tailored to elevate your brand and drive measurable results.', 
     null, null, 1],

    ['services', 'process', 'Our Process', 'How We Work', 
     'A streamlined approach to deliver exceptional results for every project.', 
     null, null, 1],
    
    ['services', 'cta', 'Ready to Get Started?', null, 
     'Let\'s discuss how our services can help grow your business.', 
     null, '{"button_text": "Contact Us", "button_link": "contact.php"}', 1],

    ['contact', 'hero', 'Got a project?', 'Contact Us', 
     'Let\'s make it real.', 
     null, '{"eyebrow": "Let\'s Talk", "subtitle": "Whether it\'s a bold rebrand, a digital product, or a campaign that breaks the mold — we\'re ready. Drop us a line and let\'s start something great."}', 1],

    ['contact', 'form_intro', 'Send Us a Message', null, 
     'Fill out the form below and we\'ll get back to you within 24 hours.', 
     null, null, 1],

    ['blog', 'hero', 'Our Blog', 'Insights & Updates', 
     'Stay updated with the latest trends, tips, and insights from our team of digital marketing experts.', 
     null, null, 1],

    ['case_studies', 'hero', 'Proof of', 'Our Work', 
     'Every project we take on is a collision of bold thinking and meaningful intent. Campaigns that didn\'t just perform—they redefined what\'s possible.', 
     null, '{"accent_text": "Impact", "eyebrow": "Our Portfolio"}', 1],
];

$inserted = 0;
$skipped = 0;

$checkStmt = $db->prepare("SELECT COUNT(*) FROM page_content WHERE page_name = ? AND section_key = ?");
$insertStmt = $db->prepare("INSERT INTO page_content (page_name, section_key, content_title, content_subtitle, content_body, content_image, content_extra, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

foreach ($sections as $section) {
    try {
        $checkStmt->execute([$section[0], $section[1]]);
        if ($checkStmt->fetchColumn() > 0) {
            $skipped++;
            echo "⏭ Skipped: {$section[0]}/{$section[1]} (already exists)\n";
            continue;
        }
        
        $insertStmt->execute($section);
        $inserted++;
        echo "✅ Inserted: {$section[0]}/{$section[1]}\n";
    } catch (Exception $e) {
        echo "❌ Error {$section[0]}/{$section[1]}: " . $e->getMessage() . "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Page Content — Inserted: {$inserted}, Skipped: {$skipped}\n";
echo "📊 Total rows: " . $db->query("SELECT COUNT(*) FROM page_content")->fetchColumn() . "\n";
echo "📊 Total settings: " . $db->query("SELECT COUNT(*) FROM settings")->fetchColumn() . "\n";

// ── 4. Create clients table + seed data ──
echo "\n━━ 4. Clients Table ━━━━━━━━━━━━━━\n";
try {
    $db->exec("CREATE TABLE IF NOT EXISTS clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(100) NOT NULL,
        client_logo VARCHAR(255) DEFAULT NULL,
        website_url VARCHAR(255) DEFAULT NULL,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "✅ clients table created (or already exists)\n";
    
    $existing = $db->query("SELECT COUNT(*) FROM clients")->fetchColumn();
    if ($existing == 0) {
        $seed_clients = ['Acme Corp', 'TechFlow', 'Brandify', 'DigitalPro', 'MediaMax', 'StartupXYZ', 'CloudNine', 'Innovate Inc'];
        $stmt = $db->prepare("INSERT INTO clients (client_name, sort_order) VALUES (?, ?)");
        foreach ($seed_clients as $i => $name) {
            $stmt->execute([$name, $i + 1]);
        }
        echo "✅ Seeded " . count($seed_clients) . " default clients\n";
    } else {
        echo "⏭ clients table already has {$existing} rows\n";
    }
} catch (Exception $e) {
    echo "❌ Clients table: " . $e->getMessage() . "\n";
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Total clients: " . $db->query("SELECT COUNT(*) FROM clients")->fetchColumn() . "\n";

// ── 5. Create missing tables (vlogs, testimonials, faqs, statistics, team_members) ──
echo "\n━━ 5. Missing Tables ━━━━━━━━━━━━━━\n";

$create_tables = [
    'vlogs' => "CREATE TABLE IF NOT EXISTS vlogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        video_url VARCHAR(500) DEFAULT NULL,
        thumbnail VARCHAR(255) DEFAULT NULL,
        platform VARCHAR(50) DEFAULT 'youtube',
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'testimonials' => "CREATE TABLE IF NOT EXISTS testimonials (
        id INT AUTO_INCREMENT PRIMARY KEY,
        client_name VARCHAR(100) NOT NULL,
        client_position VARCHAR(100),
        client_company VARCHAR(100),
        client_avatar VARCHAR(255),
        testimonial_text TEXT NOT NULL,
        rating INT DEFAULT 5,
        sort_order INT DEFAULT 0,
        is_featured TINYINT(1) DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'faqs' => "CREATE TABLE IF NOT EXISTS faqs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category VARCHAR(50) DEFAULT 'general',
        question VARCHAR(500) NOT NULL,
        answer TEXT NOT NULL,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'statistics' => "CREATE TABLE IF NOT EXISTS statistics (
        id INT AUTO_INCREMENT PRIMARY KEY,
        stat_number VARCHAR(20) NOT NULL,
        stat_suffix VARCHAR(10) DEFAULT '',
        stat_label VARCHAR(100) NOT NULL,
        icon VARCHAR(50) DEFAULT NULL,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    'team_members' => "CREATE TABLE IF NOT EXISTS team_members (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        position VARCHAR(100),
        bio TEXT,
        image VARCHAR(255),
        image_pro VARCHAR(255),
        image_fun VARCHAR(255),
        tagline VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(50),
        linkedin VARCHAR(255),
        twitter VARCHAR(255),
        instagram VARCHAR(255),
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($create_tables as $table => $sql) {
    try {
        $db->exec($sql);
        echo "✅ Table '{$table}' created (or already exists)\n";
    } catch (Exception $e) {
        echo "❌ Table '{$table}': " . $e->getMessage() . "\n";
    }
}

// ── 6. Seed homepage section settings (all enabled by default) ──
echo "\n━━ 6. Homepage Section Settings ━━━\n";

$homepage_sections = ['hero', 'brands', 'welcome', 'services', 'team', 'case_studies', 'vlogs', 'testimonials', 'faq'];

$checkSetting = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
$insertSetting = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, category) VALUES (?, '1', 'boolean', 'homepage')");

foreach ($homepage_sections as $section) {
    $key = 'homepage_section_' . $section;
    try {
        $checkSetting->execute([$key]);
        if ($checkSetting->fetchColumn() == 0) {
            $insertSetting->execute([$key]);
            echo "✅ Added setting: {$key}\n";
        } else {
            echo "⏭ Setting {$key} already exists\n";
        }
    } catch (Exception $e) {
        echo "❌ Setting {$key}: " . $e->getMessage() . "\n";
    }
}

echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "✅ Migration complete!\n";
echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";
