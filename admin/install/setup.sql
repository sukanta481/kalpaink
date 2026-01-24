-- Kalpoink CRM Database Setup
-- Run this SQL in phpMyAdmin or MySQL CLI

CREATE DATABASE IF NOT EXISTS kalpoink_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kalpoink_crm;

-- Users Table (Admin Users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor', 'viewer') DEFAULT 'editor',
    avatar VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Leads Table (Contact Form Submissions)
CREATE TABLE IF NOT EXISTS leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    country VARCHAR(50) DEFAULT NULL,
    message TEXT NOT NULL,
    source VARCHAR(50) DEFAULT 'contact_form',
    status ENUM('new', 'contacted', 'qualified', 'proposal', 'won', 'lost') DEFAULT 'new',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    assigned_to INT DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Blog Posts Table
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    excerpt TEXT DEFAULT NULL,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    category VARCHAR(50) DEFAULT NULL,
    tags VARCHAR(255) DEFAULT NULL,
    author_id INT DEFAULT NULL,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    views INT DEFAULT 0,
    read_time VARCHAR(20) DEFAULT NULL,
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description TEXT DEFAULT NULL,
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Projects/Case Studies Table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT DEFAULT NULL,
    full_description LONGTEXT DEFAULT NULL,
    client_name VARCHAR(100) DEFAULT NULL,
    project_date DATE DEFAULT NULL,
    category VARCHAR(50) DEFAULT NULL,
    tags JSON DEFAULT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    gallery_images JSON DEFAULT NULL,
    project_url VARCHAR(255) DEFAULT NULL,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT NULL,
    short_description TEXT DEFAULT NULL,
    full_description LONGTEXT DEFAULT NULL,
    features JSON DEFAULT NULL,
    price_range VARCHAR(100) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT DEFAULT NULL,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
    category VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Team Members Table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    image_fun VARCHAR(255) DEFAULT NULL,
    tagline VARCHAR(255) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    linkedin VARCHAR(255) DEFAULT NULL,
    twitter VARCHAR(255) DEFAULT NULL,
    instagram VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Activity Log Table
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) DEFAULT NULL,
    entity_id INT DEFAULT NULL,
    details TEXT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert Default Admin User (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@kalpoink.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value, setting_type, category) VALUES
('site_name', 'Kalpoink', 'text', 'general'),
('site_tagline', 'Creative Digital Solutions', 'text', 'general'),
('site_url', 'http://localhost/kalpoink', 'text', 'general'),
('contact_email', 'kalpoinc@gmail.com', 'text', 'contact'),
('contact_phone', '+91 891 082 1105', 'text', 'contact'),
('contact_address', '225 Bagmari Road, Kolkata - 700054', 'textarea', 'contact'),
('social_facebook', '#', 'text', 'social'),
('social_instagram', '#', 'text', 'social'),
('social_linkedin', '#', 'text', 'social'),
('social_twitter', '#', 'text', 'social');

-- Insert Default Services
INSERT INTO services (title, slug, icon, short_description, sort_order, is_featured, is_active) VALUES
('Graphics Design', 'graphics-design', 'fa-palette', 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.', 1, 1, 1),
('Brand Identity', 'brand-identity', 'fa-bullhorn', 'Build a memorable brand with consistent visual identity across all touchpoints.', 2, 1, 1),
('Social Media Marketing', 'social-media-marketing', 'fa-share-nodes', 'Strategic social media campaigns that engage audiences and drive conversions.', 3, 1, 1),
('Web Development', 'web-development', 'fa-code', 'Modern, responsive websites that deliver exceptional user experiences.', 4, 1, 1),
('SEO Services', 'seo-services', 'fa-magnifying-glass', 'Improve your search rankings and drive organic traffic to your website.', 5, 1, 1),
('Content Marketing', 'content-marketing', 'fa-pen-nib', 'Compelling content that tells your story and connects with your audience.', 6, 1, 1);
