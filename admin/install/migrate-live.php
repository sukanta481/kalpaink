<?php
/**
 * Live Server Migration
 * Run once on Hostinger to sync database changes
 * DELETE THIS FILE AFTER RUNNING!
 */
require_once __DIR__ . '/../config/database.php';
$db = getDB();

echo "<h2>🔄 Kalpoink Live Migration</h2><pre>";

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
     '<p>At Kalpoink, creativity isn\'t just our passion – it\'s our heartbeat. We\'re a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p><p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>', 
     null, null, 1],
    
    ['about', 'who_we_are', 'Who we are', null, 
     '<p>We\'re your digital dream team – young, inventive, and fearless. <a href="#" class="highlight-link">Kalpoink</a> blends strategy, creativity, and technology to create unique digital experiences that resonate.</p><p>Our diverse squad includes strategists who live for analytics, creative designers who turn pixels into perfection, and social media wizards who keep trends on a constant watch. When you partner with us, expect authenticity, creative flair, and results-driven creativity.</p><p>Founded by Suman Kundu and Souvik Das, we bring together years of experience in graphic design, branding, and digital marketing to help businesses stand out in the crowded digital landscape.</p>', 
     'assets/images/digital-desk-bento.png', null, 1],
    
    ['about', 'join_us', 'Why Work With Us?', null, 
     'Ready to elevate your brand? If you\'re creative, passionate, and driven by innovation, Kalpoink is your perfect partner. Let\'s disrupt the digital world together!', 
     null, '{"button_text": "Get In Touch", "button_link": "contact.php"}', 1],
    
    ['about', 'team_section', 'Meet The Creators', 'The Minds Behind The Magic', 
     'Two dreamers who turned their passion into your brand\'s success story', 
     null, null, 1],
    
    ['about', 'testimonials_section', 'What Our Clients Say', 'Client Love', 
     'Real feedback from real partners who trusted us with their brands', 
     null, null, 1],

    ['home', 'hero', 'Creative Digital Solutions', 'Kalpoink', 
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
echo "\n⚠️  DELETE THIS FILE NOW!\n";
echo "</pre>";
