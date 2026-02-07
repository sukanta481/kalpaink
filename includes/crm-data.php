<?php
/**
 * CRM Data Helper
 * Fetches content from the CRM database for the website
 * Kalpoink Website
 */

// Database connection for website
function getCRMDatabase() {
    static $db = null;
    
    if ($db === null) {
        // Auto-detect environment (local vs live server)
        if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
            // Local Development (XAMPP)
            $host = 'localhost';
            $dbname = 'kalpoink_crm';
            $user = 'root';
            $pass = '';
        } else {
            // Live Server (Hostinger)
            $host = 'localhost';
            $dbname = 'u286257250_kalpaink_crm';
            $user = 'u286257250_kalpaink_crm';
            $pass = 'Sukanta@8961';
        }
        
        try {
            $db = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // If database connection fails, return null
            return null;
        }
    }
    
    return $db;
}

/**
 * Get all active hero slides
 */
function getHeroSlides() {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $stmt = $db->query("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get all active services
 */
function getServicesFromDB($featured_only = false) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM services WHERE is_active = 1";
        if ($featured_only) {
            $sql .= " AND is_featured = 1";
        }
        $sql .= " ORDER BY sort_order ASC";
        
        $stmt = $db->query($sql);
        $services = $stmt->fetchAll();
        
        // Decode features JSON
        foreach ($services as &$service) {
            $service['features'] = json_decode($service['features'], true) ?? [];
        }
        
        return $services;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get a single service by slug
 */
function getServiceBySlug($slug) {
    $db = getCRMDatabase();
    if (!$db) return null;
    
    try {
        $stmt = $db->prepare("SELECT * FROM services WHERE slug = ? AND is_active = 1");
        $stmt->execute([$slug]);
        $service = $stmt->fetch();
        
        if ($service) {
            $service['features'] = json_decode($service['features'], true) ?? [];
        }
        
        return $service;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get all active projects/case studies
 */
function getProjectsFromDB($limit = null, $featured_only = false) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM projects WHERE is_active = 1";
        if ($featured_only) {
            $sql .= " AND is_featured = 1";
        }
        $sql .= " ORDER BY project_date DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $db->query($sql);
        $projects = $stmt->fetchAll();
        
        // Decode JSON fields
        foreach ($projects as &$project) {
            $project['tags'] = json_decode($project['tags'], true) ?? [];
            $project['gallery_images'] = json_decode($project['gallery_images'], true) ?? [];
            // Map to old format for compatibility
            $project['image'] = $project['featured_image'];
            $project['title'] = $project['title'];
            $project['category'] = $project['category'];
        }
        
        return $projects;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get a single project by slug
 */
function getProjectBySlug($slug) {
    $db = getCRMDatabase();
    if (!$db) return null;
    
    try {
        $stmt = $db->prepare("SELECT * FROM projects WHERE slug = ? AND is_active = 1");
        $stmt->execute([$slug]);
        $project = $stmt->fetch();
        
        if ($project) {
            $project['tags'] = json_decode($project['tags'], true) ?? [];
            $project['gallery_images'] = json_decode($project['gallery_images'], true) ?? [];
            $project['image'] = $project['featured_image'];
        }
        
        return $project;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get a single blog by slug
 */
function getBlogBySlug($slug) {
    $db = getCRMDatabase();
    if (!$db) return null;
    
    try {
        $stmt = $db->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        $blog = $stmt->fetch();
        
        if ($blog) {
            $blog['tags'] = json_decode($blog['tags'], true) ?? [];
        }
        
        return $blog;
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get all active team members
 */
function getTeamMembersFromDB() {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $stmt = $db->query("SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order ASC");
        $members = $stmt->fetchAll();
        
        // Map to old format for compatibility
        foreach ($members as &$member) {
            // Support both old (image_pro) and new (image) column names
            $member['image_pro'] = $member['image'] ?? $member['image_pro'] ?? null;
            $member['image_fun'] = $member['image_fun'] ?? $member['image_pro'];
            $member['tagline'] = $member['tagline'] ?? $member['bio'];
        }
        
        return $members;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get all active FAQs
 */
function getFAQsFromDB($category = null) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM faqs WHERE is_active = 1";
        if ($category) {
            $sql .= " AND category = :category";
        }
        $sql .= " ORDER BY sort_order ASC";
        
        $stmt = $db->prepare($sql);
        if ($category) {
            $stmt->execute(['category' => $category]);
        } else {
            $stmt->execute();
        }
        
        $faqs = $stmt->fetchAll();
        
        // Map to consistent format for frontend
        foreach ($faqs as &$faq) {
            $faq['question'] = $faq['question'] ?? '';
            $faq['answer'] = $faq['answer'] ?? '';
        }
        
        return $faqs;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get all active statistics
 */
function getStatisticsFromDB() {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $stmt = $db->query("SELECT * FROM statistics WHERE is_active = 1 ORDER BY sort_order ASC");
        $stats = $stmt->fetchAll();
        
        // Map to consistent format (support both column naming conventions)
        foreach ($stats as &$stat) {
            $stat['label'] = $stat['stat_label'] ?? $stat['label'] ?? '';
            $stat['value'] = $stat['stat_value'] ?? $stat['value'] ?? '';
            $stat['suffix'] = $stat['stat_suffix'] ?? $stat['suffix'] ?? '';
            $stat['icon'] = $stat['stat_icon'] ?? $stat['icon'] ?? '';
        }
        
        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get all active testimonials
 */
function getTestimonialsFromDB($featured_only = false) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM testimonials WHERE is_active = 1";
        if ($featured_only) {
            $sql .= " AND is_featured = 1";
        }
        $sql .= " ORDER BY is_featured DESC, sort_order ASC";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get page content by page and section
 */
function getPageContent($page_name, $section_key = null) {
    $db = getCRMDatabase();
    if (!$db) return null;
    
    try {
        if ($section_key) {
            $stmt = $db->prepare("SELECT * FROM page_content WHERE page_name = ? AND section_key = ? AND is_active = 1");
            $stmt->execute([$page_name, $section_key]);
            $content = $stmt->fetch();
            if ($content && $content['content_extra']) {
                $content['extra'] = json_decode($content['content_extra'], true);
            }
            return $content;
        } else {
            $stmt = $db->prepare("SELECT * FROM page_content WHERE page_name = ? AND is_active = 1");
            $stmt->execute([$page_name]);
            $contents = $stmt->fetchAll();
            
            $result = [];
            foreach ($contents as $content) {
                if ($content['content_extra']) {
                    $content['extra'] = json_decode($content['content_extra'], true);
                }
                $result[$content['section_key']] = $content;
            }
            return $result;
        }
    } catch (PDOException $e) {
        return null;
    }
}

/**
 * Get clients for marquee
 */
function getClientsFromDB() {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $stmt = $db->query("SELECT * FROM clients WHERE is_active = 1 ORDER BY sort_order ASC, client_name ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get gallery items
 */
function getGalleryFromDB($category = null, $limit = null) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM gallery WHERE is_active = 1";
        if ($category) {
            $sql .= " AND category = :category";
        }
        $sql .= " ORDER BY sort_order ASC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $db->prepare($sql);
        if ($category) {
            $stmt->execute(['category' => $category]);
        } else {
            $stmt->execute();
        }
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get a setting value
 */
function getSettingValue($key, $default = null) {
    $db = getCRMDatabase();
    if (!$db) return $default;
    
    try {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    } catch (PDOException $e) {
        return $default;
    }
}

/**
 * Get multiple settings
 */
function getSettings($keys = []) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        if (empty($keys)) {
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
        } else {
            $placeholders = implode(',', array_fill(0, count($keys), '?'));
            $stmt = $db->prepare("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ($placeholders)");
            $stmt->execute($keys);
        }
        
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Get blogs from database
 */
function getBlogsFromDB($limit = null, $category = null) {
    $db = getCRMDatabase();
    if (!$db) return [];
    
    try {
        $sql = "SELECT * FROM blogs WHERE status = 'published'";
        if ($category) {
            $sql .= " AND category = :category";
        }
        $sql .= " ORDER BY published_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        
        $stmt = $db->prepare($sql);
        if ($category) {
            $stmt->execute(['category' => $category]);
        } else {
            $stmt->execute();
        }
        
        $blogs = $stmt->fetchAll();
        
        foreach ($blogs as &$blog) {
            $blog['tags'] = json_decode($blog['tags'], true) ?? [];
        }
        
        return $blogs;
    } catch (PDOException $e) {
        return [];
    }
}
