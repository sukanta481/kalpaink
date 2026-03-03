-- Kalpanik CRM - Content Management Tables
-- Run this in phpMyAdmin or use the update script

USE kalpanik_crm;

-- Page Content Table (for managing text content on all pages)
CREATE TABLE IF NOT EXISTS page_content (
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
);

-- Hero Slides Table
CREATE TABLE IF NOT EXISTS hero_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    badge_text VARCHAR(100),
    button1_text VARCHAR(50),
    button1_link VARCHAR(255),
    button2_text VARCHAR(50),
    button2_link VARCHAR(255),
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Gallery/Portfolio Images Table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    category VARCHAR(50),
    tags VARCHAR(255),
    project_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE SET NULL
);

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_position VARCHAR(100),
    client_company VARCHAR(100),
    client_image VARCHAR(255),
    testimonial_text TEXT NOT NULL,
    rating INT DEFAULT 5,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- FAQ Table
CREATE TABLE IF NOT EXISTS faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Statistics/Counters Table
CREATE TABLE IF NOT EXISTS statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stat_label VARCHAR(100) NOT NULL,
    stat_value VARCHAR(50) NOT NULL,
    stat_icon VARCHAR(50),
    stat_suffix VARCHAR(20),
    page_name VARCHAR(50) DEFAULT 'home',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Hero Slides
INSERT INTO hero_slides (title, subtitle, badge_text, button1_text, button1_link, button2_text, button2_link, sort_order, is_active) VALUES
('Reimagining with Purpose', 'Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing.', 'Creative Design Studio', 'Get Quote', 'contact.php', 'Services', 'services.php', 1, 1),
('Grow Your Digital Presence', 'Strategic digital marketing to boost your brand visibility and drive measurable results.', 'Digital Marketing', 'Get Quote', 'contact.php', 'Services', 'services.php', 2, 1),
('Build Your Unique Brand', 'Create a memorable brand identity from logos to complete brand guidelines.', 'Brand Identity', 'Get Quote', 'contact.php', 'Portfolio', 'case-studies.php', 3, 1);

-- Insert Default Statistics
INSERT INTO statistics (stat_label, stat_value, stat_suffix, stat_icon, sort_order) VALUES
('Happy Clients', '50', '+', 'fa-smile', 1),
('Projects Completed', '100', '+', 'fa-briefcase', 2),
('Years Experience', '5', '+', 'fa-calendar', 3),
('Team Members', '10', '+', 'fa-users', 4);

-- Insert Default FAQs
INSERT INTO faqs (question, answer, sort_order) VALUES
('What services does Kalpanik offer?', 'Kalpanik specializes in graphics design, brand identity, social media marketing, web development, SEO services, and content marketing. Our primary expertise is in all types of graphics work.', 1),
('Where is Kalpanik located?', 'We are based in Kolkata, West Bengal, India. Our office is located at 225 Bagmari Road, Kolkata - 700054.', 2),
('How long does a typical project take?', 'Project timelines vary based on complexity. A logo design might take 1-2 weeks, while a complete brand identity package could take 4-6 weeks. We will provide a detailed timeline after understanding your requirements.', 3),
('Do you work with clients outside Kolkata?', 'Yes! We work with clients across India and internationally. Our digital workflow allows us to collaborate seamlessly regardless of location.', 4),
('What makes Kalpanik different from other agencies?', 'Our focus on creative excellence combined with strategic thinking sets us apart. With our partners combined experience, we deliver work that not only looks great but also drives real business results.', 5);

-- Insert Default Page Content
INSERT INTO page_content (page_name, section_key, content_title, content_subtitle, content_body, is_active) VALUES
-- Homepage
('home', 'welcome', 'Welcome to Kalpanik', 'Who We Are', '<p>We are a creative digital agency specializing in graphics design, branding, and digital marketing solutions.</p>', 1),

-- About Page
('about', 'hero', 'About Us', 'Our Story', '<p>We are a passionate team of creative minds dedicated to transforming brands through innovative design and strategic marketing.</p>', 1),
('about', 'story', 'Our Story', NULL, '<p>Founded in Kolkata, Kalpanik started as a small design studio with big dreams. Today, we have grown into a full-service creative agency, helping businesses across India and beyond establish their digital presence.</p><p>Our journey has been driven by a simple belief: great design can transform businesses. We combine creativity with strategy to deliver results that matter.</p>', 1),

-- Services Page
('services', 'hero', 'Our Services', 'What We Offer', '<p>Comprehensive digital solutions to help your business grow and thrive in the digital landscape.</p>', 1),

-- Contact Page
('contact', 'hero', 'Get In Touch', 'Contact Us', '<p>You are One Step Away from Digital Success. Get in Touch!</p>', 1),

-- Blog Page
('blog', 'hero', 'Our Blog', 'Latest Insights', '<p>Insights, tips, and stories from our team of digital marketing experts.</p>', 1),

-- Case Studies Page
('case_studies', 'hero', 'Our Work', 'Portfolio', '<p>Explore our portfolio of creative projects and see how we have helped brands succeed.</p>', 1);
