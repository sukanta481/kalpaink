<?php
/**
 * CLI Demo Content Import
 * Imports all demo content from command line
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $db = new PDO('mysql:host=localhost;dbname=kalpoink_crm;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "=== Demo Content Import ===\n\n";
    
    // ========================================
    // 1. HERO SLIDES
    // ========================================
    echo "Importing Hero Slides...\n";
    $db->exec("TRUNCATE TABLE hero_slides");
    
    $hero_slides = [
        ['Reimagining with Purpose', 'Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing.', 'Creative Design Studio', null, null, null, 'Get Quote', 'contact.php', 'Services', 'services.php', 1, 1],
        ['Grow Your Digital Presence', 'Strategic digital marketing to boost your brand visibility and drive measurable results.', 'Digital Marketing', null, null, null, 'Get Quote', 'contact.php', 'Services', 'services.php', 2, 1],
        ['Build Your Unique Brand', 'Create a memorable brand identity from logos to complete brand guidelines.', 'Brand Identity', null, null, null, 'Get Quote', 'contact.php', 'Portfolio', 'case-studies.php', 3, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO hero_slides (title, subtitle, badge_text, image1, image2, image3, button1_text, button1_link, button2_text, button2_link, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($hero_slides as $slide) {
        $stmt->execute($slide);
    }
    echo "  ✓ " . count($hero_slides) . " hero slides added\n";
    
    // ========================================
    // 2. SERVICES
    // ========================================
    echo "Importing Services...\n";
    $db->exec("TRUNCATE TABLE services");
    
    $services = [
        ['Graphics Design', 'graphics', 'fa-palette', 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.', 'From stunning logos to complete visual identities, our graphics design team creates eye-catching visuals.', json_encode(['Logo Design', 'Business Cards', 'Brochures', 'Social Media Graphics']), 1, 1, 1],
        ['Brand Identity', 'branding', 'fa-bullhorn', 'Build a memorable brand with consistent visual identity across all touchpoints.', 'Build a memorable brand with consistent visual identity.', json_encode(['Brand Strategy', 'Visual Identity', 'Brand Guidelines', 'Rebranding']), 2, 1, 1],
        ['Social Media Marketing', 'smm', 'fa-share-nodes', 'Strategic social media campaigns that engage audiences and drive conversions.', 'Strategic social media campaigns.', json_encode(['Content Strategy', 'Community Management', 'Paid Ads', 'Analytics']), 3, 1, 1],
        ['Web Development', 'web', 'fa-code', 'Modern, responsive websites that deliver exceptional user experiences.', 'Modern, responsive websites.', json_encode(['Custom Websites', 'E-commerce', 'WordPress', 'Web Apps']), 4, 1, 1],
        ['SEO Services', 'seo', 'fa-magnifying-glass', 'Improve your search rankings and drive organic traffic to your website.', 'Improve your search rankings.', json_encode(['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building']), 5, 1, 1],
        ['Content Marketing', 'content', 'fa-pen-nib', 'Compelling content that tells your story and connects with your audience.', 'Compelling content creation.', json_encode(['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing']), 6, 1, 1],
        ['Print Design', 'print', 'fa-print', 'High-quality print materials that make a lasting impression.', 'High-quality print materials.', json_encode(['Brochures', 'Posters', 'Business Stationery', 'Catalogs']), 7, 1, 0],
        ['Video Production', 'video', 'fa-video', 'Engaging video content that captures attention and drives action.', 'Engaging video content.', json_encode(['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Explainers']), 8, 1, 0]
    ];
    
    $stmt = $db->prepare("INSERT INTO services (title, slug, icon, short_description, full_description, features, sort_order, is_active, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($services as $service) {
        $stmt->execute($service);
    }
    echo "  ✓ " . count($services) . " services added\n";
    
    // ========================================
    // 3. PROJECTS
    // ========================================
    echo "Importing Projects...\n";
    $db->exec("TRUNCATE TABLE projects");
    
    $projects = [
        ['Modern Restaurant Branding', 'modern-restaurant-branding', 'Branding', 'The Food Studio', '2024-01-15', 'Complete brand identity for a modern restaurant', '<p>Complete brand identity package.</p>', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=800&fit=crop', json_encode([]), json_encode(['Branding', 'Logo']), 1, 1],
        ['E-Commerce Website Design', 'ecommerce-website-design', 'Web Design', 'ShopNow', '2024-02-20', 'Modern e-commerce platform with seamless UX', '<p>Modern e-commerce website.</p>', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop', json_encode([]), json_encode(['UI/UX', 'Web']), 1, 1],
        ['Social Media Campaign', 'social-media-campaign', 'Marketing', 'TrendSetters', '2024-03-10', 'Viral social media campaign with 500% engagement', '<p>Comprehensive social media campaign.</p>', 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=800&h=400&fit=crop', json_encode([]), json_encode(['SMM', 'Content']), 1, 1],
        ['Corporate Identity Design', 'corporate-identity-design', 'Branding', 'TechCorp', '2024-04-05', 'Complete corporate identity for tech company', '<p>Corporate identity system.</p>', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop', json_encode([]), json_encode(['Branding', 'Print']), 0, 1],
        ['Mobile App UI Design', 'mobile-app-ui-design', 'UI/UX', 'FitLife App', '2024-05-15', 'Modern mobile app interface design', '<p>Mobile app interface for fitness app.</p>', 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=800&fit=crop', json_encode([]), json_encode(['UI/UX', 'Mobile']), 1, 1],
        ['YouTube Channel Branding', 'youtube-channel-branding', 'YouTube', 'TechTalks', '2024-06-01', 'Complete YouTube channel branding package', '<p>YouTube channel branding.</p>', 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?w=600&h=400&fit=crop', json_encode([]), json_encode(['YouTube', 'Branding']), 0, 1],
        ['Startup Brand Strategy', 'startup-brand-strategy', 'Branding', 'LaunchPad Inc', '2024-07-20', 'Comprehensive brand strategy for tech startup', '<p>Brand strategy for startup.</p>', 'https://images.unsplash.com/photo-1553028826-f4804a6dba3b?w=800&h=400&fit=crop', json_encode([]), json_encode(['Strategy', 'Branding']), 0, 1],
        ['Product Photography', 'product-photography', 'Photography', 'AudioMax', '2024-08-10', 'Professional product photography for e-commerce', '<p>Product photography.</p>', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop', json_encode([]), json_encode(['Photography', 'Product']), 0, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO projects (title, slug, category, client_name, project_date, short_description, full_description, featured_image, gallery_images, tags, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($projects as $project) {
        $stmt->execute($project);
    }
    echo "  ✓ " . count($projects) . " projects added\n";
    
    // ========================================
    // 4. TEAM MEMBERS
    // ========================================
    echo "Importing Team Members...\n";
    $db->exec("TRUNCATE TABLE team_members");
    
    $team = [
        ['Suman Kundu', 'Co-Founder & Creative Director', 'Turning caffeine into creativity since 2016.', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop', 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&h=500&fit=crop', 'Turning caffeine into creativity since 2016', 'suman@kalpoink.com', null, '#', null, null, 1, 1],
        ['Souvik Das', 'Co-Founder & Strategy Lead', 'Making brands unforgettable, one pixel at a time.', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=500&fit=crop', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop', 'Making brands unforgettable, one pixel at a time', 'souvik@kalpoink.com', null, '#', null, null, 2, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO team_members (name, position, bio, image, image_fun, tagline, email, phone, linkedin, twitter, instagram, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($team as $member) {
        $stmt->execute($member);
    }
    echo "  ✓ " . count($team) . " team members added\n";
    
    // ========================================
    // 5. FAQS
    // ========================================
    echo "Importing FAQs...\n";
    $db->exec("TRUNCATE TABLE faqs");
    
    $faqs = [
        ['general', 'What services does Kalpoink offer?', '<p>Kalpoink specializes in graphics design, brand identity, social media marketing, web development, SEO services, and content marketing.</p>', 1, 1],
        ['general', 'Where is Kalpoink located?', '<p>We are based in Kolkata, West Bengal, India. Our office is at 225 Bagmari Road, Kolkata - 700054.</p>', 2, 1],
        ['process', 'How long does a typical project take?', '<p>Project timelines vary. Logo design: 1-2 weeks. Complete brand identity: 4-6 weeks.</p>', 3, 1],
        ['general', 'Do you work with clients outside Kolkata?', '<p>Yes! We work with clients across India and internationally.</p>', 4, 1],
        ['general', 'What makes Kalpoink different?', '<p>Our focus on creative excellence combined with strategic thinking sets us apart.</p>', 5, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO faqs (category, question, answer, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
    foreach ($faqs as $faq) {
        $stmt->execute($faq);
    }
    echo "  ✓ " . count($faqs) . " FAQs added\n";
    
    // ========================================
    // 6. STATISTICS
    // ========================================
    echo "Importing Statistics...\n";
    $db->exec("TRUNCATE TABLE statistics");
    
    $stats = [
        ['projects_completed', '200', 'Projects Completed', 'fa-briefcase', '+', 1, 1],
        ['happy_clients', '150', 'Happy Clients', 'fa-smile', '+', 2, 1],
        ['years_experience', '8', 'Years Experience', 'fa-calendar-alt', '+', 3, 1],
        ['team_members', '10', 'Team Members', 'fa-users', '', 4, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO statistics (stat_key, stat_value, stat_label, stat_icon, stat_suffix, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($stats as $stat) {
        $stmt->execute($stat);
    }
    echo "  ✓ " . count($stats) . " statistics added\n";
    
    // ========================================
    // 7. TESTIMONIALS
    // ========================================
    echo "Importing Testimonials...\n";
    $db->exec("TRUNCATE TABLE testimonials");
    
    $testimonials = [
        ['Rajesh Kumar', 'CEO', 'TechStart India', null, 'Kalpoink transformed our brand identity completely. Their attention to detail and creative approach exceeded our expectations.', 5, 1, 1, 1],
        ['Priya Sharma', 'Marketing Head', 'Fashion Hub', null, 'Working with Kalpoink was a game-changer for our social media presence. Highly recommended!', 5, 2, 1, 1],
        ['Amit Patel', 'Founder', 'Foodie Express', null, 'The team delivered an amazing website that perfectly captures our brand essence. Great work!', 5, 3, 0, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO testimonials (client_name, client_position, client_company, client_avatar, testimonial_text, rating, sort_order, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($testimonials as $t) {
        $stmt->execute($t);
    }
    echo "  ✓ " . count($testimonials) . " testimonials added\n";
    
    // ========================================
    // 8. PAGE CONTENT
    // ========================================
    echo "Importing Page Content...\n";
    $db->exec("TRUNCATE TABLE page_content");
    
    $pages = [
        ['home', 'about_section', 'About Kalpoink', 'Who We Are', '<p>Kalpoink is a creative digital agency based in Kolkata, specializing in graphics design, brand identity, and digital marketing.</p>', null, null, 1],
        ['about', 'main', 'About Us', 'Our Story', '<p>Founded by creative professionals, Kalpoink has grown to become a trusted partner for businesses.</p>', null, null, 1],
        ['services', 'intro', 'Our Services', 'What We Offer', '<p>We offer a comprehensive range of creative and digital services to help your business grow.</p>', null, null, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO page_content (page_name, section_key, content_title, content_subtitle, content_body, content_image, content_extra, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($pages as $page) {
        $stmt->execute($page);
    }
    echo "  ✓ " . count($pages) . " page sections added\n";
    
    // ========================================
    // 9. GALLERY
    // ========================================
    echo "Importing Gallery...\n";
    $db->exec("TRUNCATE TABLE gallery");
    
    $gallery = [
        ['portfolio', 'Brand Design Work', 'Collection of brand design projects', 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=600&h=400&fit=crop', null, 1, 1],
        ['portfolio', 'Web Design Projects', 'Modern web design showcase', 'https://images.unsplash.com/photo-1467232004584-a241de8bcf5d?w=600&h=400&fit=crop', null, 2, 1],
        ['portfolio', 'Social Media Graphics', 'Social media design collection', 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=600&h=400&fit=crop', null, 3, 1]
    ];
    
    $stmt = $db->prepare("INSERT INTO gallery (category, title, description, image, thumbnail, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($gallery as $item) {
        $stmt->execute($item);
    }
    echo "  ✓ " . count($gallery) . " gallery items added\n";
    
    echo "\n=== Demo Content Import Complete! ===\n";
    echo "\nVisit: http://localhost/kalpoink/admin/\n";
    echo "Login: admin / admin123\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
