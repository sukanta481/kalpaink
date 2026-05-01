<?php
/**
 * Kalpanik - Configuration File
 * Digital Marketing Agency Website
 */

// Include CRM data helper
require_once __DIR__ . '/includes/crm-data.php';

// Get settings from CRM database (with fallbacks)
$crm_settings = getSettings();

// Homepage section visibility (default: all enabled)
function isHomepageSectionEnabled($section_key) {
    global $crm_settings;
    $key = 'homepage_section_' . $section_key;
    // Default to '1' (enabled) if setting doesn't exist
    return ($crm_settings[$key] ?? '1') === '1';
}

// Auto-detect the project base path so URLs work in both subfolder and root installs.
function getBasePath() {
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    $projectRoot = realpath(__DIR__);

    if ($documentRoot && $projectRoot) {
        $normalizedDocumentRoot = str_replace('\\', '/', $documentRoot);
        $normalizedProjectRoot = str_replace('\\', '/', $projectRoot);

        if (strpos($normalizedProjectRoot, $normalizedDocumentRoot) === 0) {
            $relativePath = trim(substr($normalizedProjectRoot, strlen($normalizedDocumentRoot)), '/');
            return $relativePath === '' ? '/' : '/' . $relativePath . '/';
        }
    }

    if (($_SERVER['SERVER_NAME'] ?? '') === 'localhost' || ($_SERVER['SERVER_NAME'] ?? '') === '127.0.0.1') {
        return '/kalpoink/';
    }

    return '/';
}

function getSitePath($path = '') {
    $basePath = getBasePath();

    if ($path === '') {
        return $basePath;
    }

    return ($basePath === '/' ? '' : rtrim($basePath, '/')) . '/' . ltrim($path, '/');
}

function getSiteUrlRoot() {
    $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';

    // On live server, always return the canonical production URL
    if ($host !== 'localhost' && $host !== '127.0.0.1') {
        return 'https://www.kalpanikdigital.com';
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    return rtrim($protocol . $host, '/') . rtrim(getBasePath(), '/');
}

define('SITE_URL', getSiteUrlRoot());

// Site Configuration
// Normalize site name — correct any legacy misspellings stored in DB
$_raw_site_name = $crm_settings['site_name'] ?? 'Kalpanik Digital';
if (stripos($_raw_site_name, 'kalpoink') !== false) {
    $_raw_site_name = str_ireplace('Kalpoink', 'Kalpanik', $_raw_site_name);
}
define('SITE_NAME', $_raw_site_name);
define('SITE_TAGLINE', $crm_settings['site_tagline'] ?? 'Content Marketing Company | Brand Identity & Creative Design');

// Helper to make URLs absolute
function getAbsoluteUrl($path) {
    if (empty($path)) return '';
    if (filter_var($path, FILTER_VALIDATE_URL)) return $path;
    return rtrim(SITE_URL, '/') . '/' . ltrim($path, '/');
}

function getAssetVersion($path) {
    $absolutePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($path, '/'));
    return file_exists($absolutePath) ? filemtime($absolutePath) : time();
}

// Branding - Logo (dynamic from admin settings), Favicon (static from assets/images/favicon.png)
define('SITE_LOGO', getAbsoluteUrl(!empty($crm_settings['site_logo']) ? $crm_settings['site_logo'] : 'assets/images/kalpanik-logo.png'));
define('SITE_FAVICON', getSitePath('assets/images/favicon.png'));
define('SITE_FAVICON_VERSION', getAssetVersion('assets/images/favicon.png'));

// Contact Information
define('CONTACT_ADDRESS', $crm_settings['contact_address'] ?? '225 Bagmari Road, Kolkata - 700054');
define('CONTACT_PHONE', $crm_settings['contact_phone'] ?? '+91 891 082 1105');
define('CONTACT_EMAIL', $crm_settings['contact_email'] ?? 'kalpanik@gmail.com');

// Social Media Links (Update with actual links)
define('SOCIAL_FACEBOOK', $crm_settings['social_facebook'] ?? '#');
define('SOCIAL_INSTAGRAM', $crm_settings['social_instagram'] ?? '#');
define('SOCIAL_LINKEDIN', $crm_settings['social_linkedin'] ?? '#');
define('SOCIAL_TWITTER', $crm_settings['social_twitter'] ?? '#');

// Get Team Members from CRM (with fallback to static data)
$team_members_db = getTeamMembersFromDB();
$team_members = !empty($team_members_db) ? $team_members_db : [
    [
        'name' => 'Suman Kundu',
        'position' => 'Co-Founder & Creative Director',
        'image_pro' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=500&fit=crop',
        'image_fun' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=400&h=500&fit=crop',
        'tagline' => 'Turning caffeine into creativity since 2016',
        'linkedin' => '#'
    ],
    [
        'name' => 'Souvik Das',
        'position' => 'Co-Founder & Strategy Lead',
        'image_pro' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=500&fit=crop',
        'image_fun' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=500&fit=crop',
        'tagline' => 'Making brands unforgettable, one pixel at a time',
        'linkedin' => '#'
    ]
];

// Get Services from CRM (with fallback to static data)
$services_db = getServicesFromDB(true);
// Image map for service illustrations
$service_images = [
    'Brand Identity'         => 'assets/images/services/brand-identity.png',
    'Communication Design'   => 'assets/images/services/communication-design.png',
    'Content Marketing'      => 'assets/images/services/content-marketing.png',
    'Flyers'                 => 'assets/images/services/print-design.png',
    'Graphics Design'        => 'assets/images/services/graphics-design.png',
    'Poster Design'          => 'assets/images/services/print-design.png',
    'Print Design'           => 'assets/images/services/print-design.png',
    'SEO Services'           => 'assets/images/services/seo-services.png',
    'Social Media Marketing' => 'assets/images/services/social-media-marketing.png',
    'Video Production'       => 'assets/images/services/video-production.png',
    'Web Development'        => 'assets/images/services/web-development.png',
];

$services_fallback = [
    [
        'icon' => 'fa-bullhorn',
        'title' => 'Brand Identity',
        'slug' => 'brand-identity',
        'description' => 'Build a memorable brand with consistent visual identity across all touchpoints.',
        'image' => 'assets/images/services/brand-identity.png'
    ],
    [
        'icon' => 'fa-layer-group',
        'title' => 'Communication Design',
        'slug' => 'communication-design',
        'description' => 'Clear, compelling visual communication that conveys your message with impact.',
        'image' => 'assets/images/services/communication-design.png'
    ],
    [
        'icon' => 'fa-pen-nib',
        'title' => 'Content Marketing',
        'slug' => 'content-marketing',
        'description' => 'Compelling content that tells your story and connects with your audience.',
        'image' => 'assets/images/services/content-marketing.png'
    ],
    [
        'icon' => 'fa-file-image',
        'title' => 'Flyers',
        'slug' => 'flyers',
        'description' => 'Eye-catching flyer designs that get noticed and drive action for events and promotions.',
        'image' => 'assets/images/services/print-design.png'
    ],
    [
        'icon' => 'fa-palette',
        'title' => 'Graphics Design',
        'slug' => 'graphics-design',
        'description' => 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.',
        'image' => 'assets/images/services/graphics-design.png'
    ],
    [
        'icon' => 'fa-image',
        'title' => 'Poster Design',
        'slug' => 'poster-design',
        'description' => 'Bold, striking poster designs that communicate powerfully and leave a lasting impression.',
        'image' => 'assets/images/services/print-design.png'
    ],
    [
        'icon' => 'fa-print',
        'title' => 'Print Design',
        'slug' => 'print-design',
        'description' => 'Tangible brand experiences. From high-quality marketing collaterals and custom merchandise to premium packaging, we bring your visual identity into the physical world.',
        'image' => 'assets/images/services/print-design.png'
    ],
    [
        'icon' => 'fa-magnifying-glass',
        'title' => 'SEO Services',
        'slug' => 'seo-services',
        'description' => 'Improve your search rankings and drive organic traffic to your website.',
        'image' => 'assets/images/services/seo-services.png'
    ],
    [
        'icon' => 'fa-share-nodes',
        'title' => 'Social Media Marketing',
        'slug' => 'social-media-marketing',
        'description' => 'Strategic social media campaigns that engage audiences and drive conversions.',
        'image' => 'assets/images/services/social-media-marketing.png'
    ],
    [
        'icon' => 'fa-video',
        'title' => 'Video Production',
        'slug' => 'video-production',
        'description' => 'Engaging video content that captures attention and drives action.',
        'image' => 'assets/images/services/video-production.png'
    ],
    [
        'icon' => 'fa-code',
        'title' => 'Web Development',
        'slug' => 'web-development',
        'description' => 'Modern, responsive websites that deliver exceptional user experiences.',
        'image' => 'assets/images/services/web-development.png'
    ],
];

if (!empty($services_db)) {
    $services = array_map(function($s) use ($service_images) {
        return [
            'icon' => $s['icon'],
            'title' => $s['title'],
            'slug' => $s['slug'] ?? strtolower(str_replace(' ', '-', $s['title'])),
            'description' => $s['short_description'],
            'image' => !empty($s['image']) ? $s['image'] : ($service_images[$s['title']] ?? 'assets/images/services/graphics-design.png')
        ];
    }, $services_db);
    usort($services, fn($a, $b) => strcasecmp($a['title'], $b['title']));
} else {
    $services = $services_fallback; // already sorted A-Z above
}

// Get Case Studies/Projects from CRM (with fallback to static data)
$case_studies_db = getProjectsFromDB(8);
$case_studies = !empty($case_studies_db) ? $case_studies_db : [
    [
        'title' => 'Modern Restaurant Branding',
        'category' => 'Branding',
        'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=800&fit=crop',
        'tags' => ['Branding', 'Logo']
    ],
    [
        'title' => 'E-Commerce Website Design',
        'category' => 'Web Design',
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
        'tags' => ['UI/UX', 'Web']
    ],
    [
        'title' => 'Social Media Campaign',
        'category' => 'Marketing',
        'image' => 'https://images.unsplash.com/photo-1611162616305-c69b3fa7fbe0?w=800&h=400&fit=crop',
        'tags' => ['SMM', 'Content']
    ],
    [
        'title' => 'Corporate Identity Design',
        'category' => 'Branding',
        'image' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Print']
    ],
    [
        'title' => 'Mobile App UI Design',
        'category' => 'UI/UX',
        'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&h=800&fit=crop',
        'tags' => ['UI/UX', 'Mobile']
    ],
    [
        'title' => 'YouTube Channel Branding',
        'category' => 'YouTube',
        'image' => 'https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?w=600&h=400&fit=crop',
        'tags' => ['YouTube', 'Branding']
    ],
    [
        'title' => 'Startup Brand Strategy',
        'category' => 'Branding',
        'image' => 'https://images.unsplash.com/photo-1553028826-f4804a6dba3b?w=800&h=400&fit=crop',
        'tags' => ['Strategy', 'Branding']
    ],
    [
        'title' => 'Product Photography',
        'category' => 'Photography',
        'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop',
        'tags' => ['Photography', 'Product']
    ]
];

// Get FAQs from CRM (with fallback to static data)
$faqs_db = getFAQsFromDB();
$faqs = !empty($faqs_db) ? $faqs_db : [
    [
        'question' => 'What services does Kalpanik offer?',
        'answer' => 'Kalpanik specializes in graphics design, brand identity, social media marketing, web development, SEO services, and content marketing. Our primary expertise is in all types of graphics work.'
    ],
    [
        'question' => 'Where is Kalpanik located?',
        'answer' => 'We are based in Kolkata, West Bengal, India. Our office is located at 225 Bagmari Road, Kolkata - 700054.'
    ],
    [
        'question' => 'How long does a typical project take?',
        'answer' => 'Project timelines vary based on complexity. A logo design might take 1-2 weeks, while a complete brand identity package could take 4-6 weeks. We\'ll provide a detailed timeline after understanding your requirements.'
    ],
    [
        'question' => 'Do you work with clients outside Kolkata?',
        'answer' => 'Yes! We work with clients across India and internationally. Our digital workflow allows us to collaborate seamlessly regardless of location.'
    ],
    [
        'question' => 'What makes Kalpanik different from other agencies?',
        'answer' => 'Our focus on creative excellence combined with strategic thinking sets us apart. With our partners\' combined experience, we deliver work that not only looks great but also drives real business results.'
    ]
];

// Get Statistics from CRM
$statistics = getStatisticsFromDB();

// Get Testimonials from CRM
$testimonials = getTestimonialsFromDB();

// Get Hero Slides from CRM
$hero_slides = getHeroSlides();
?>
