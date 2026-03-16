
<?php require_once 'config.php';

// Per-page SEO configuration
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$seo_config = [
    'index' => [
        'title' => SITE_NAME . ' - Content Marketing Company in Kolkata | Brand Identity & Creative Design',
        'description' => 'Kalpanik is a content marketing company in Kolkata specializing in brand identity, creative design, social media management, and brand building. We craft content strategies that grow businesses.',
    ],
    'about' => [
        'title' => 'About ' . SITE_NAME . ' | Content Marketing & Brand Identity Experts in Kolkata',
        'description' => 'Meet Kalpanik — a content marketing company and creative design house from Kolkata. We build brands through strategic content, compelling design, and social media management.',
    ],
    'services' => [
        'title' => 'Services - ' . SITE_NAME . ' | Content Marketing, Brand Identity & Creative Design',
        'description' => 'Content marketing, brand identity, creative design, social media management, web development, and SEO services. Kalpanik delivers end-to-end brand building solutions from Kolkata.',
    ],
    'contact' => [
        'title' => 'Contact ' . SITE_NAME . ' | Content Marketing Company in Kolkata - Free Quote',
        'description' => 'Get in touch with Kalpanik for content marketing, brand identity, and creative design services. Free consultation for businesses looking to build their brand. Based in Kolkata.',
    ],
    'blog' => [
        'title' => 'Blog - ' . SITE_NAME . ' | Content Marketing, Branding & Design Insights',
        'description' => 'Expert insights on content marketing strategy, brand identity, creative design trends, and social media management from Kalpanik — a content marketing company in Kolkata.',
    ],
    'case-studies' => [
        'title' => 'Case Studies - ' . SITE_NAME . ' | Content Marketing & Brand Building Results',
        'description' => 'See how Kalpanik delivers results through content marketing, brand identity design, and social media campaigns. Real case studies from a creative design house in Kolkata.',
    ]
];

$page_seo = $page_seo ?? $seo_config[$current_page] ?? [
    'title' => (isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - Content Marketing Company in Kolkata'),
    'description' => 'Kalpanik is a content marketing company in Kolkata specializing in brand identity, creative design, social media management, and brand building.',
];

$canonical_url = $canonical_url ?? SITE_URL . '/' . ($current_page === 'index' ? '' : $current_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta name="author" content="Kalpanik">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <title><?php echo htmlspecialchars($page_seo['title']); ?></title>

    <!-- Favicon -->
    <?php if (defined('SITE_FAVICON') && SITE_FAVICON): 
        $favExt = strtolower(pathinfo(parse_url(SITE_FAVICON, PHP_URL_PATH), PATHINFO_EXTENSION));
        $favType = ($favExt === 'ico') ? 'image/x-icon' : (($favExt === 'svg') ? 'image/svg+xml' : 'image/png');
        
        $favVer = '1';
        if (defined('SITE_FAVICON_VERSION')) {
            $favVer = SITE_FAVICON_VERSION;
        } else {
            // Use filemtime for stable cache busting. time() changes every second and breaks browser caching/loading.
            $favFilePath = $_SERVER['DOCUMENT_ROOT'] . parse_url(SITE_FAVICON, PHP_URL_PATH);
            if (file_exists($favFilePath)) {
                $favVer = filemtime($favFilePath);
            }
        }
    ?>
    <link rel="icon" type="<?php echo $favType; ?>" sizes="48x48" href="<?php echo SITE_FAVICON; ?>">
    <link rel="icon" type="<?php echo $favType; ?>" sizes="32x32" href="<?php echo SITE_FAVICON; ?>">
    <link rel="icon" type="<?php echo $favType; ?>" sizes="16x16" href="<?php echo SITE_FAVICON; ?>">
    <link rel="shortcut icon" href="<?php echo SITE_FAVICON; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITE_FAVICON; ?>">
    <?php else: ?>
    <link rel="icon" type="image/png" sizes="48x48" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="shortcut icon" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <?php endif; ?>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo ($current_page === 'blog') ? 'blog' : 'website'; ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_seo['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta property="og:image" content="<?php echo SITE_LOGO; ?>">
    <meta property="og:image:width" content="512">
    <meta property="og:image:height" content="512">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_seo['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta name="twitter:image" content="<?php echo SITE_LOGO; ?>"><?php // JSON-LD Structured Data — Organization + LocalBusiness on all pages, page-specific schema added per page
$socials_arr = [];
if (SOCIAL_FACEBOOK && SOCIAL_FACEBOOK !== '#') $socials_arr[] = SOCIAL_FACEBOOK;
if (SOCIAL_INSTAGRAM && SOCIAL_INSTAGRAM !== '#') $socials_arr[] = SOCIAL_INSTAGRAM;
if (SOCIAL_LINKEDIN && SOCIAL_LINKEDIN !== '#') $socials_arr[] = SOCIAL_LINKEDIN;
?>

    <!-- JSON-LD: Organization + LocalBusiness (all pages) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?php echo SITE_URL; ?>/#organization",
                "name": "Kalpanik Digital",
                "alternateName": ["Kalpanik", "Kalpanik Content Marketing"],
                "url": "<?php echo SITE_URL; ?>",
                "logo": "<?php echo SITE_LOGO; ?>",
                "description": "Kalpanik is a content marketing company in Kolkata specializing in brand identity, creative design, social media management, and brand building.",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "225 Bagmari Road",
                    "addressLocality": "Kolkata",
                    "postalCode": "700054",
                    "addressRegion": "West Bengal",
                    "addressCountry": "IN"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "telephone": "<?php echo CONTACT_PHONE; ?>",
                    "contactType": "customer service",
                    "email": "<?php echo CONTACT_EMAIL; ?>",
                    "areaServed": ["IN", "US", "GB"],
                    "availableLanguage": ["English", "Hindi", "Bengali"]
                },
                "sameAs": <?php echo json_encode($socials_arr); ?>,
                "knowsAbout": ["Content Marketing", "Content Strategy", "Brand Identity", "Creative Design", "Social Media Management", "Brand Building", "Graphics Design", "Web Development", "SEO"]
            },
            {
                "@type": "LocalBusiness",
                "@id": "<?php echo SITE_URL; ?>/#localbusiness",
                "name": "<?php echo SITE_NAME; ?>",
                "image": "<?php echo SITE_LOGO; ?>",
                "url": "<?php echo SITE_URL; ?>",
                "telephone": "<?php echo CONTACT_PHONE; ?>",
                "priceRange": "$$",
                "description": "Content marketing company and creative design house in Kolkata offering brand identity, social media management, and brand building services.",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "225 Bagmari Road",
                    "addressLocality": "Kolkata",
                    "postalCode": "700054",
                    "addressRegion": "West Bengal",
                    "addressCountry": "IN"
                },
                "geo": {
                    "@type": "GeoCoordinates",
                    "latitude": "22.5726",
                    "longitude": "88.3639"
                },
                "openingHoursSpecification": {
                    "@type": "OpeningHoursSpecification",
                    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    "opens": "10:00",
                    "closes": "19:00"
                }
            },
            {
                "@type": "WebSite",
                "@id": "<?php echo SITE_URL; ?>/#website",
                "url": "<?php echo SITE_URL; ?>",
                "name": "Kalpanik Digital",
                "alternateName": ["Kalpanik", "<?php echo SITE_NAME; ?>"],
                "description": "Content marketing company in Kolkata specializing in brand identity, creative design, social media management, and brand building.",
                "publisher": {"@id": "<?php echo SITE_URL; ?>/#organization"},
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "<?php echo SITE_URL; ?>/blog?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "SiteNavigationElement",
                "name": ["Home", "About Us", "Services", "Case Studies", "Blog", "Contact Us"],
                "url": [
                    "<?php echo SITE_URL; ?>/",
                    "<?php echo SITE_URL; ?>/about",
                    "<?php echo SITE_URL; ?>/services",
                    "<?php echo SITE_URL; ?>/case-studies",
                    "<?php echo SITE_URL; ?>/blog",
                    "<?php echo SITE_URL; ?>/contact"
                ]
            }<?php if ($current_page === 'about'): ?>,
            {
                "@type": "AboutPage",
                "mainEntity": {"@id": "<?php echo SITE_URL; ?>/#organization"},
                "url": "<?php echo SITE_URL; ?>/about",
                "name": "About <?php echo SITE_NAME; ?>"
            }<?php endif; ?><?php if ($current_page === 'contact'): ?>,
            {
                "@type": "ContactPage",
                "url": "<?php echo SITE_URL; ?>/contact",
                "name": "Contact <?php echo SITE_NAME; ?>"
            }<?php endif; ?><?php if ($current_page === 'blog'): ?>,
            {
                "@type": "Blog",
                "url": "<?php echo SITE_URL; ?>/blog",
                "name": "<?php echo SITE_NAME; ?> Blog",
                "description": "Expert insights on content marketing strategy, brand identity, creative design trends, and social media management.",
                "publisher": {"@id": "<?php echo SITE_URL; ?>/#organization"}
            }<?php endif; ?><?php if ($current_page === 'services'): ?>,
            {
                "@type": "CollectionPage",
                "url": "<?php echo SITE_URL; ?>/services",
                "name": "Services - <?php echo SITE_NAME; ?>",
                "description": "Content marketing, brand identity, creative design, social media management, and web development services.",
                "provider": {"@id": "<?php echo SITE_URL; ?>/#organization"}
            }<?php endif; ?><?php if ($current_page === 'case-studies'): ?>,
            {
                "@type": "CollectionPage",
                "url": "<?php echo SITE_URL; ?>/case-studies",
                "name": "Case Studies - <?php echo SITE_NAME; ?>",
                "description": "Content marketing and brand building case studies showcasing real results.",
                "provider": {"@id": "<?php echo SITE_URL; ?>/#organization"}
            }<?php endif; ?>

        ]
    }
    </script>
<?php // Page-specific schema
if ($current_page === 'index' && !empty($GLOBALS['faqs'])): ?>
    <!-- JSON-LD: FAQPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            <?php foreach ($GLOBALS['faqs'] as $fi => $faq_item): ?>
            <?php if ($fi > 0) echo ','; ?>
            {
                "@type": "Question",
                "name": <?php echo json_encode(strip_tags($faq_item['question'])); ?>,
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": <?php echo json_encode(strip_tags($faq_item['answer'])); ?>
                }
            }
            <?php endforeach; ?>
        ]
    }
    </script>
<?php endif; ?>
    
    <!-- DNS Prefetch + Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

    <!-- Google Fonts (swap for no FOIT) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@800&family=DM+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <!-- Custom CSS (with auto cache-busting) -->
    <link rel="stylesheet" href="<?php echo getSitePath('assets/css/style.css'); ?>?v=<?php echo filemtime('assets/css/style.css'); ?>">
    
    <!-- Services Page Specific Styles -->
    <?php if (basename($_SERVER['PHP_SELF']) == 'services.php'): ?>
    <link rel="stylesheet" href="<?php echo getSitePath('assets/css/services-page.css'); ?>?v=<?php echo filemtime('assets/css/services-page.css'); ?>">
    <?php endif; ?>

    <!-- Service Detail Page Styles -->
    <?php if (($current_nav ?? '') === 'services' && basename($_SERVER['PHP_SELF']) !== 'services.php'): ?>
    <link rel="stylesheet" href="<?php echo getSitePath('assets/css/service-detail.css'); ?>?v=<?php echo filemtime('assets/css/service-detail.css'); ?>">
    <?php endif; ?>
</head>
<body>
    <!-- Preloader -->
    <div id="preloader">
        <div class="preloader-inner">
            <img src="<?php echo SITE_LOGO; ?>" alt="Loading <?php echo SITE_NAME; ?>" class="preloader-logo" width="120" height="35">
            <div class="preloader-bar"><div class="preloader-bar-fill"></div></div>
        </div>
    </div>

    <!-- Mobile nav backdrop -->
    <div id="navbarBackdrop"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" data-bs-theme="dark" aria-label="Main navigation">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="<?php echo getSitePath(''); ?>">
                <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?> - Content Marketing Company" class="navbar-logo" width="140" height="40">
            </a>
            
            <!-- Mobile Toggle (CSS transforms into X when open) -->
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
            </button>
            
            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile menu header -->
                <div class="mobile-menu-header d-lg-none">
                    <a class="mobile-menu-logo" href="<?php echo getSitePath(''); ?>">
                        <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" height="30">
                    </a>
                </div>

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="<?php echo getSitePath(''); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-home"></i></span>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('about'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-users"></i></span>
                            About Us
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php' || ($current_nav ?? '') === 'services') ? 'active' : ''; ?>" href="<?php echo getSitePath('services'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-briefcase"></i></span>
                            Our Services
                        </a>
                        <button class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle Services submenu"></button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo getSitePath('services'); ?>">All Services</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (!empty($services)): ?>
                                <?php foreach ($services as $svc_nav): ?>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/' . htmlspecialchars($svc_nav['slug'] ?? strtolower(str_replace(' ', '-', $svc_nav['title'])))); ?>"><?php echo htmlspecialchars($svc_nav['title']); ?></a></li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/graphics-design'); ?>">Graphics Design</a></li>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/brand-identity'); ?>">Brand Identity</a></li>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/social-media-marketing'); ?>">Social Media Marketing</a></li>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/web-development'); ?>">Web Development</a></li>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/seo-services'); ?>">SEO Services</a></li>
                                <li><a class="dropdown-item" href="<?php echo getSitePath('services/content-marketing'); ?>">Content Marketing</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'case-studies.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('case-studies'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-layer-group"></i></span>
                            Case Studies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('blog'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-pen-nib"></i></span>
                            Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('contact'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-envelope"></i></span>
                            Contact Us
                        </a>
                    </li>
                </ul>
                
                <!-- CTA Button -->
                <a href="<?php echo getSitePath('contact'); ?>" class="btn btn-primary cta-btn">
                    <span class="btn-text">Get Enquiry Now</span>
                    <i class="fas fa-arrow-right btn-arrow"></i>
                </a>

                <!-- Mobile menu footer -->
                <div class="mobile-menu-footer d-lg-none">
                    <div class="mobile-menu-socials">
                        <?php if (SOCIAL_INSTAGRAM && SOCIAL_INSTAGRAM !== '#'): ?>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (SOCIAL_LINKEDIN && SOCIAL_LINKEDIN !== '#'): ?>
                        <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (SOCIAL_FACEBOOK && SOCIAL_FACEBOOK !== '#'): ?>
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                    </div>
                    <p class="mobile-menu-tagline">Crafting digital experiences ✨</p>
                </div>
            </div>
        </div>
    </nav>
