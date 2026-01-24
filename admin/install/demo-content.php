<?php
/**
 * Demo Content Installer
 * Populates all CRM tables with the current website demo content
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
        
        // ========================================
        // 1. HERO SLIDES
        // ========================================
        $db->exec("TRUNCATE TABLE hero_slides");
        
        $hero_slides = [
            [
                'title' => 'Reimagining with Purpose',
                'subtitle' => 'Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing.',
                'badge_text' => 'Creative Design Studio',
                'image1' => 'uploads/portfolio_website.png',
                'image2' => 'uploads/portfolio_logo.png',
                'image3' => 'uploads/portfolio_social.png',
                'button1_text' => 'Get Quote',
                'button1_link' => 'contact.php',
                'button2_text' => 'Services',
                'button2_link' => 'services.php',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'title' => 'Grow Your Digital Presence',
                'subtitle' => 'Strategic digital marketing to boost your brand visibility and drive measurable results.',
                'badge_text' => 'Digital Marketing',
                'image1' => 'uploads/portfolio_social.png',
                'image2' => 'uploads/portfolio_website.png',
                'image3' => 'uploads/portfolio_logo.png',
                'button1_text' => 'Get Quote',
                'button1_link' => 'contact.php',
                'button2_text' => 'Services',
                'button2_link' => 'services.php',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'title' => 'Build Your Unique Brand',
                'subtitle' => 'Create a memorable brand identity from logos to complete brand guidelines.',
                'badge_text' => 'Brand Identity',
                'image1' => null,
                'image2' => null,
                'image3' => null,
                'button1_text' => 'Get Quote',
                'button1_link' => 'contact.php',
                'button2_text' => 'Portfolio',
                'button2_link' => 'case-studies.php',
                'sort_order' => 3,
                'is_active' => 1
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO hero_slides (title, subtitle, badge_text, image1, image2, image3, button1_text, button1_link, button2_text, button2_link, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($hero_slides as $slide) {
            $stmt->execute(array_values($slide));
        }
        $details[] = "✓ Hero Slides: " . count($hero_slides) . " slides added";
        
        // ========================================
        // 2. SERVICES
        // ========================================
        $db->exec("TRUNCATE TABLE services");
        
        $services = [
            [
                'title' => 'Graphics Design',
                'slug' => 'graphics',
                'icon' => 'fa-palette',
                'short_description' => 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.',
                'full_description' => 'From stunning logos to complete visual identities, our graphics design team creates eye-catching visuals that capture your brand essence and leave lasting impressions.',
                'features' => json_encode(['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design']),
                'sort_order' => 1,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Brand Identity',
                'slug' => 'branding',
                'icon' => 'fa-bullhorn',
                'short_description' => 'Build a memorable brand with consistent visual identity across all touchpoints.',
                'full_description' => 'Build a memorable brand with consistent visual identity across all touchpoints. We create comprehensive brand guidelines that ensure your brand stands out.',
                'features' => json_encode(['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging']),
                'sort_order' => 2,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Social Media Marketing',
                'slug' => 'smm',
                'icon' => 'fa-share-nodes',
                'short_description' => 'Strategic social media campaigns that engage audiences and drive conversions.',
                'full_description' => 'Strategic social media campaigns that engage audiences and drive conversions. We manage your social presence across all major platforms.',
                'features' => json_encode(['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management']),
                'sort_order' => 3,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Web Development',
                'slug' => 'web',
                'icon' => 'fa-code',
                'short_description' => 'Modern, responsive websites that deliver exceptional user experiences.',
                'full_description' => 'Modern, responsive websites that deliver exceptional user experiences. From landing pages to complex web applications, we build it all.',
                'features' => json_encode(['Custom Website Design', 'E-commerce Solutions', 'WordPress Development', 'Web Applications', 'Mobile Responsive', 'Maintenance & Support']),
                'sort_order' => 4,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'SEO Services',
                'slug' => 'seo',
                'icon' => 'fa-magnifying-glass',
                'short_description' => 'Improve your search rankings and drive organic traffic to your website.',
                'full_description' => 'Improve your search rankings and drive organic traffic to your website. Our SEO experts use proven strategies to boost your online visibility.',
                'features' => json_encode(['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building', 'Local SEO', 'SEO Audits']),
                'sort_order' => 5,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Content Marketing',
                'slug' => 'content',
                'icon' => 'fa-pen-nib',
                'short_description' => 'Compelling content that tells your story and connects with your audience.',
                'full_description' => 'Compelling content that tells your story and connects with your audience. We create content that educates, entertains, and converts.',
                'features' => json_encode(['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing', 'Content Strategy', 'eBooks & Whitepapers']),
                'sort_order' => 6,
                'is_active' => 1,
                'is_featured' => 1
            ],
            [
                'title' => 'Print Design',
                'slug' => 'print',
                'icon' => 'fa-print',
                'short_description' => 'High-quality print materials that make a lasting impression.',
                'full_description' => 'High-quality print materials that make a lasting impression. From business cards to large format displays, we handle all your print needs.',
                'features' => json_encode(['Brochures', 'Posters & Banners', 'Business Stationery', 'Catalogs', 'Magazines', 'Signage']),
                'sort_order' => 7,
                'is_active' => 1,
                'is_featured' => 0
            ],
            [
                'title' => 'Video Production',
                'slug' => 'video',
                'icon' => 'fa-video',
                'short_description' => 'Engaging video content that captures attention and drives action.',
                'full_description' => 'Engaging video content that captures attention and drives action. From promotional videos to animations, we bring your vision to life.',
                'features' => json_encode(['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Social Media Videos', 'Explainer Videos', 'Video Editing']),
                'sort_order' => 8,
                'is_active' => 1,
                'is_featured' => 0
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO services (title, slug, icon, short_description, full_description, features, sort_order, is_active, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($services as $service) {
            $stmt->execute(array_values($service));
        }
        $details[] = "✓ Services: " . count($services) . " services added";
        
        // ========================================
        // 3. PROJECTS (Case Studies)
        // ========================================
        $db->exec("TRUNCATE TABLE projects");
        
        $projects = [
            [
                'title' => 'Modern Restaurant Branding',
                'slug' => 'modern-restaurant-branding',
                'category' => 'Branding',
                'client_name' => 'The Food Studio',
                'project_date' => '2024-01-15',
                'short_description' => 'Complete brand identity for a modern restaurant',
                'full_description' => '<p>Created a complete brand identity package for The Food Studio including logo design, menu design, signage, and marketing materials.</p><p>The project involved developing a cohesive visual language that reflects the restaurant\'s modern approach to traditional cuisine.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=800&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['Branding', 'Logo']),
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'title' => 'E-Commerce Website Design',
                'slug' => 'ecommerce-website-design',
                'category' => 'Web Design',
                'client_name' => 'ShopNow',
                'project_date' => '2024-02-20',
                'short_description' => 'Modern e-commerce platform with seamless UX',
                'full_description' => '<p>Designed and developed a modern e-commerce website with focus on user experience and conversion optimization.</p><p>Features include product filtering, wishlist, quick checkout, and mobile-first design approach.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['UI/UX', 'Web']),
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'title' => 'Social Media Campaign',
                'slug' => 'social-media-campaign',
                'category' => 'Marketing',
                'client_name' => 'TrendSetters',
                'project_date' => '2024-03-10',
                'short_description' => 'Viral social media campaign with 500% engagement increase',
                'full_description' => '<p>Executed a comprehensive social media marketing campaign across Instagram, Facebook, and LinkedIn.</p><p>Achieved 500% increase in engagement and 200% growth in follower count within 3 months.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=800&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['SMM', 'Content']),
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'title' => 'Corporate Identity Design',
                'slug' => 'corporate-identity-design',
                'category' => 'Branding',
                'client_name' => 'TechCorp Solutions',
                'project_date' => '2024-04-05',
                'short_description' => 'Complete corporate identity for tech company',
                'full_description' => '<p>Developed a complete corporate identity system including logo, stationery, and brand guidelines.</p><p>The design reflects the company\'s innovative approach while maintaining professional appeal.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['Branding', 'Print']),
                'is_featured' => 0,
                'is_active' => 1
            ],
            [
                'title' => 'Mobile App UI Design',
                'slug' => 'mobile-app-ui-design',
                'category' => 'UI/UX',
                'client_name' => 'FitLife App',
                'project_date' => '2024-05-15',
                'short_description' => 'Modern mobile app interface design',
                'full_description' => '<p>Designed a complete mobile app interface for a fitness tracking application.</p><p>Features intuitive navigation, gamification elements, and a clean, motivating design aesthetic.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=800&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['UI/UX', 'Mobile']),
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'title' => 'YouTube Channel Branding',
                'slug' => 'youtube-channel-branding',
                'category' => 'YouTube',
                'client_name' => 'TechTalks',
                'project_date' => '2024-06-01',
                'short_description' => 'Complete YouTube channel branding package',
                'full_description' => '<p>Created complete branding for a tech YouTube channel including banner, logo, thumbnails, and end screens.</p><p>Consistent visual identity across all channel elements for professional appearance.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?w=600&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['YouTube', 'Branding']),
                'is_featured' => 0,
                'is_active' => 1
            ],
            [
                'title' => 'Startup Brand Strategy',
                'slug' => 'startup-brand-strategy',
                'category' => 'Branding',
                'client_name' => 'LaunchPad Inc',
                'project_date' => '2024-07-20',
                'short_description' => 'Comprehensive brand strategy for tech startup',
                'full_description' => '<p>Developed comprehensive brand strategy for a new tech startup.</p><p>Included market research, competitor analysis, brand positioning, and complete visual identity system.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1553028826-f4804a6dba3b?w=800&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['Strategy', 'Branding']),
                'is_featured' => 0,
                'is_active' => 1
            ],
            [
                'title' => 'Product Photography',
                'slug' => 'product-photography',
                'category' => 'Photography',
                'client_name' => 'AudioMax',
                'project_date' => '2024-08-10',
                'short_description' => 'Professional product photography for e-commerce',
                'full_description' => '<p>Professional product photography for e-commerce and marketing materials.</p><p>Clean, high-quality images optimized for both web and print use.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop',
                'gallery_images' => json_encode([]),
                'tags' => json_encode(['Photography', 'Product']),
                'is_featured' => 0,
                'is_active' => 1
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO projects (title, slug, category, client_name, project_date, short_description, full_description, featured_image, gallery_images, tags, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($projects as $project) {
            $stmt->execute(array_values($project));
        }
        $details[] = "✓ Projects: " . count($projects) . " case studies added";
        
        // ========================================
        // 4. TEAM MEMBERS
        // ========================================
        $db->exec("TRUNCATE TABLE team_members");
        
        $team = [
            [
                'name' => 'Suman Kundu',
                'position' => 'Co-Founder & Creative Director',
                'bio' => 'Turning caffeine into creativity since 2016. Suman leads our creative team with passion and innovation.',
                'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
                'image_fun' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&h=500&fit=crop',
                'tagline' => 'Turning caffeine into creativity since 2016',
                'email' => 'suman@kalpoink.com',
                'phone' => null,
                'linkedin' => '#',
                'twitter' => null,
                'instagram' => null,
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'name' => 'Souvik Das',
                'position' => 'Co-Founder & Strategy Lead',
                'bio' => 'Making brands unforgettable, one pixel at a time. Souvik brings strategic vision to every project.',
                'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=500&fit=crop',
                'image_fun' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop',
                'tagline' => 'Making brands unforgettable, one pixel at a time',
                'email' => 'souvik@kalpoink.com',
                'phone' => null,
                'linkedin' => '#',
                'twitter' => null,
                'instagram' => null,
                'sort_order' => 2,
                'is_active' => 1
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO team_members (name, position, bio, image, image_fun, tagline, email, phone, linkedin, twitter, instagram, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($team as $member) {
            $stmt->execute(array_values($member));
        }
        $details[] = "✓ Team Members: " . count($team) . " members added";
        
        // ========================================
        // 5. FAQS
        // ========================================
        $db->exec("TRUNCATE TABLE faqs");
        
        $faqs = [
            [
                'category' => 'general',
                'question' => 'What services does Kalpoink offer?',
                'answer' => '<p>Kalpoink specializes in graphics design, brand identity, social media marketing, web development, SEO services, and content marketing. Our primary expertise is in all types of graphics work.</p>',
                'sort_order' => 1,
                'is_active' => 1
            ],
            [
                'category' => 'general',
                'question' => 'Where is Kalpoink located?',
                'answer' => '<p>We are based in Kolkata, West Bengal, India. Our office is located at 225 Bagmari Road, Kolkata - 700054.</p>',
                'sort_order' => 2,
                'is_active' => 1
            ],
            [
                'category' => 'process',
                'question' => 'How long does a typical project take?',
                'answer' => '<p>Project timelines vary based on complexity. A logo design might take 1-2 weeks, while a complete brand identity package could take 4-6 weeks. We\'ll provide a detailed timeline after understanding your requirements.</p>',
                'sort_order' => 3,
                'is_active' => 1
            ],
            [
                'category' => 'general',
                'question' => 'Do you work with clients outside Kolkata?',
                'answer' => '<p>Yes! We work with clients across India and internationally. Our digital workflow allows us to collaborate seamlessly regardless of location.</p>',
                'sort_order' => 4,
                'is_active' => 1
            ],
            [
                'category' => 'general',
                'question' => 'What makes Kalpoink different from other agencies?',
                'answer' => '<p>Our focus on creative excellence combined with strategic thinking sets us apart. With our partners\' combined experience, we deliver work that not only looks great but also drives real business results.</p>',
                'sort_order' => 5,
                'is_active' => 1
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO faqs (category, question, answer, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
        foreach ($faqs as $faq) {
            $stmt->execute(array_values($faq));
        }
        $details[] = "✓ FAQs: " . count($faqs) . " questions added";
        
        // ========================================
        // 6. STATISTICS
        // ========================================
        $db->exec("TRUNCATE TABLE statistics");
        
        $stats = [
            ['brands_sculpted', '150', 'Brands Sculpted', 'fas fa-gem', '+', 1, 1],
            ['years_crafting', '5', 'Years Crafting', 'fas fa-calendar-check', '+', 2, 1],
            ['happy_clients', '98', 'Happy Clients', 'fas fa-smile', '%', 3, 1],
            ['projects_completed', '300', 'Projects Completed', 'fas fa-project-diagram', '+', 4, 1]
        ];
        
        $stmt = $db->prepare("INSERT INTO statistics (stat_key, stat_value, stat_label, stat_icon, stat_suffix, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($stats as $stat) {
            $stmt->execute($stat);
        }
        $details[] = "✓ Statistics: " . count($stats) . " stats added";
        
        // ========================================
        // 7. TESTIMONIALS
        // ========================================
        $db->exec("TRUNCATE TABLE testimonials");
        
        $testimonials = [
            [
                'client_name' => 'Rajesh Kumar',
                'client_position' => 'CEO',
                'client_company' => 'TechStart India',
                'client_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop',
                'testimonial_text' => 'Kalpoink transformed our brand identity completely. Their creative team understood our vision perfectly and delivered beyond expectations. Highly recommended for any business looking for top-notch design work!',
                'rating' => 5,
                'sort_order' => 1,
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'client_name' => 'Priya Sharma',
                'client_position' => 'Marketing Director',
                'client_company' => 'Fashion Forward',
                'client_avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&h=150&fit=crop',
                'testimonial_text' => 'Working with Kalpoink on our social media strategy was a game-changer. Our engagement increased by 300% within just two months. They really know how to connect brands with audiences.',
                'rating' => 5,
                'sort_order' => 2,
                'is_featured' => 1,
                'is_active' => 1
            ],
            [
                'client_name' => 'Amit Patel',
                'client_position' => 'Founder',
                'client_company' => 'GreenEats',
                'client_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop',
                'testimonial_text' => 'The website Kalpoink designed for us is not only beautiful but also highly functional. Our online orders have increased significantly since the launch. Great team to work with!',
                'rating' => 5,
                'sort_order' => 3,
                'is_featured' => 0,
                'is_active' => 1
            ]
        ];
        
        $stmt = $db->prepare("INSERT INTO testimonials (client_name, client_position, client_company, client_avatar, testimonial_text, rating, sort_order, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($testimonials as $testimonial) {
            $stmt->execute(array_values($testimonial));
        }
        $details[] = "✓ Testimonials: " . count($testimonials) . " reviews added";
        
        // ========================================
        // 8. PAGE CONTENT
        // ========================================
        $db->exec("TRUNCATE TABLE page_content");
        
        $page_content = [
            // Home page sections
            ['home', 'welcome_badge', 'Who We Are', null, null, null, null, 1],
            ['home', 'welcome_headline', 'We <span class="text-yellow">Sculpt</span> Brands.', 'Where <strong>Art Meets Algorithm.</strong>', '<p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p><p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we\'re the creative studio that brings visions to life.</p>', 'assets/images/about-fusion.png', null, 1],
            ['home', 'cta', 'Ready to Transform Your Brand?', 'Let\'s create something amazing together. Get in touch with us today!', null, null, '{"button_text": "Get Started", "button_link": "contact.php"}', 1],
            
            // About page sections
            ['about', 'hero_title', 'About Us', null, '<p>At Kalpoink, creativity isn\'t just our passion – it\'s our heartbeat. We\'re a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p><p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>', null, null, 1],
            ['about', 'who_we_are', 'Who we are', null, '<p>We\'re your digital dream team – young, inventive, and fearless. <a href="#" class="highlight-link">Kalpoink</a> blends strategy, creativity, and technology to create unique digital experiences that resonate.</p><p>Our diverse squad includes strategists who live for analytics, creative designers who turn pixels into perfection, and social media wizards who keep trends on a constant watch. When you partner with us, expect authenticity, creative flair, and results-driven creativity.</p><p>Founded by Suman Kundu and Souvik Das, we bring together years of experience in graphic design, branding, and digital marketing to help businesses stand out in the crowded digital landscape.</p>', null, null, 1],
            ['about', 'cta', 'Why Work With Us?', 'Ready to elevate your brand? If you\'re creative, passionate, and driven by innovation, Kalpoink is your perfect partner. Let\'s disrupt the digital world together!', null, null, '{"button_text": "Get In Touch", "button_link": "contact.php"}', 1],
            
            // Services page sections
            ['services', 'hero', 'See What Happens When Creativity Meets Purpose', 'Every project we take on is a collision of bold thinking and meaningful intent.', '<p>These are stories where redefined imagination, strategy, and creativity are transformed into impact.</p>', null, null, 1],
            ['services', 'cta', 'Need a Custom Solution?', 'We understand that every business is unique. Let\'s discuss your specific needs and create a tailored solution for you.', null, null, '{"button_text": "Contact Us", "button_link": "contact.php"}', 1],
            
            // Contact page
            ['contact', 'hero', 'Get in Touch', 'We\'d love to hear from you', '<p>Have a project in mind? Let\'s talk about how we can help bring your vision to life.</p>', null, null, 1]
        ];
        
        $stmt = $db->prepare("INSERT INTO page_content (page_name, section_key, content_title, content_subtitle, content_body, content_image, content_extra, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($page_content as $content) {
            $stmt->execute($content);
        }
        $details[] = "✓ Page Content: " . count($page_content) . " sections added";
        
        // ========================================
        // 9. SETTINGS
        // ========================================
        // Update existing settings
        $settings = [
            ['site_name', 'Kalpoink'],
            ['site_tagline', 'Creative Digital Solutions'],
            ['contact_address', '225 Bagmari Road, Kolkata - 700054'],
            ['contact_phone', '+91 891 082 1105'],
            ['contact_email', 'kalpoinc@gmail.com'],
            ['social_facebook', '#'],
            ['social_instagram', '#'],
            ['social_linkedin', '#'],
            ['social_twitter', '#'],
            ['footer_copyright', '© 2024 Kalpoink. All Rights Reserved.']
        ];
        
        foreach ($settings as $setting) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute($setting);
        }
        $details[] = "✓ Settings: " . count($settings) . " settings updated";
        
        // ========================================
        // 10. GALLERY
        // ========================================
        $db->exec("TRUNCATE TABLE gallery");
        
        $gallery = [
            ['portfolio', 'Website Design Portfolio', 'Modern responsive website designs', 'uploads/portfolio_website.png', null, 1, 1],
            ['portfolio', 'Logo Design Collection', 'Brand identity and logo designs', 'uploads/portfolio_logo.png', null, 2, 1],
            ['portfolio', 'Social Media Graphics', 'Engaging social media content', 'uploads/portfolio_social.png', null, 3, 1]
        ];
        
        $stmt = $db->prepare("INSERT INTO gallery (category, title, description, image, thumbnail, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($gallery as $item) {
            $stmt->execute($item);
        }
        $details[] = "✓ Gallery: " . count($gallery) . " images added";
        
        $success = true;
        $message = 'All demo content has been successfully imported! The website will now fetch content from the CRM database.';
        
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
    <title>Import Demo Content - Kalpoink Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-database me-2"></i>Import Demo Content to CRM
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($message): ?>
                        <div class="alert alert-<?php echo $success ? 'success' : 'danger'; ?>">
                            <i class="fas fa-<?php echo $success ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                            <strong><?php echo $success ? 'Success!' : 'Error!'; ?></strong>
                            <br><?php echo $message; ?>
                        </div>
                        
                        <?php if ($success && !empty($details)): ?>
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Import Summary:</h6>
                                <ul class="mb-0">
                                    <?php foreach ($details as $detail): ?>
                                    <li><?php echo $detail; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <a href="../index.php" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i>Go to Dashboard
                            </a>
                            <a href="../content.php" class="btn btn-outline-primary ms-2">
                                <i class="fas fa-edit me-2"></i>Manage Content
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Warning:</strong> This will replace all existing content in the CRM with demo data. Make sure to backup any important data before proceeding.
                        </div>
                        
                        <p>This will import the following demo content:</p>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-images fa-2x text-primary mb-2"></i>
                                    <strong>3 Hero Slides</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                                    <strong>8 Services</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-briefcase fa-2x text-primary mb-2"></i>
                                    <strong>8 Projects</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                    <strong>2 Team Members</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-question-circle fa-2x text-primary mb-2"></i>
                                    <strong>5 FAQs</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                                    <strong>4 Statistics</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-quote-right fa-2x text-primary mb-2"></i>
                                    <strong>3 Testimonials</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                                    <strong>9 Page Sections</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-3">
                                    <i class="fas fa-sliders-h fa-2x text-primary mb-2"></i>
                                    <strong>10 Settings</strong>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-download me-2"></i>Import Demo Content
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
