
<?php require_once 'config.php';

// Per-page SEO configuration
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$seo_config = [
    'index' => [
        'title' => SITE_NAME . ': Top Content Marketing & Creative Agency in Kolkata',
        'description' => 'Kalpanik is a leading content marketing and creative agency in Kolkata. We specialize in content strategy, brand identity, graphics design, social media marketing, web development, and SEO services.',
        'keywords' => 'content marketing agency Kolkata, creative agency Kolkata, brand identity, graphics design, social media marketing, web development, SEO services, digital marketing Kolkata, content strategy, content creation'
    ],
    'about' => [
        'title' => 'About Us - ' . SITE_NAME . ' | Content Marketing Experts in Kolkata',
        'description' => 'Discover Kalpanik — a passionate team of content creators, designers, and digital strategists from Kolkata. We transform brands through compelling content and innovative marketing strategies.',
        'keywords' => 'about Kalpanik, content marketing team Kolkata, creative agency team, digital marketing experts Kolkata'
    ],
    'services' => [
        'title' => 'Our Services - ' . SITE_NAME . ' | Content Marketing, Design & Digital Solutions',
        'description' => 'Explore our services: content marketing, graphics design, brand identity, social media marketing, web development, and SEO. Comprehensive digital solutions tailored to grow your brand.',
        'keywords' => 'content marketing services, graphics design services, branding, social media marketing, web development, SEO services Kolkata'
    ],
    'contact' => [
        'title' => 'Contact Us - ' . SITE_NAME . ' | Get a Free Quote',
        'description' => 'Get in touch with Kalpanik for content marketing, branding, and digital marketing services. Request a free quote today. Based in Kolkata, serving clients worldwide.',
        'keywords' => 'contact Kalpanik, digital marketing quote, content marketing agency contact, Kolkata'
    ],
    'blog' => [
        'title' => 'Blog - ' . SITE_NAME . ' | Content Marketing Insights & Digital Trends',
        'description' => 'Read the latest insights on content marketing, digital marketing trends, branding tips, and creative strategies from Kalpanik\'s team of experts.',
        'keywords' => 'content marketing blog, digital marketing tips, branding insights, Kolkata agency blog'
    ],
    'case-studies' => [
        'title' => 'Case Studies - ' . SITE_NAME . ' | Our Creative Work & Results',
        'description' => 'Explore our portfolio of successful content marketing campaigns, brand identity projects, and digital marketing case studies that delivered real results.',
        'keywords' => 'content marketing case studies, branding portfolio, digital marketing results, creative work Kolkata'
    ]
];

$page_seo = $page_seo ?? $seo_config[$current_page] ?? [
    'title' => (isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE),
    'description' => 'Kalpanik is a leading content marketing and creative agency in Kolkata specializing in content strategy, graphics design, branding, and digital marketing.',
    'keywords' => 'content marketing, creative agency, Kolkata, graphics design, branding, digital marketing'
];

$canonical_url = $canonical_url ?? SITE_URL . '/' . ($current_page === 'index' ? '' : $current_page . '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_seo['keywords']); ?>">
    <meta name="author" content="Kalpanik Digital">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <title><?php echo htmlspecialchars($page_seo['title']); ?></title>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_seo['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta property="og:image" content="<?php echo SITE_LOGO; ?>">
    <meta property="og:site_name" content="<?php echo SITE_NAME; ?>">
    <meta property="og:locale" content="en_IN">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_seo['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_seo['description']); ?>">
    <meta name="twitter:image" content="<?php echo SITE_LOGO; ?>"><?php // JSON-LD Structured Data only on homepage
if ($current_page === 'index'): ?>

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "Organization",
                "@id": "<?php echo SITE_URL; ?>/#organization",
                "name": "<?php echo SITE_NAME; ?>",
                "alternateName": "Kalpanik Digital",
                "url": "<?php echo SITE_URL; ?>",
                "logo": "<?php echo SITE_LOGO; ?>",
                "description": "Leading content marketing and creative agency in Kolkata specializing in content strategy, graphics design, brand identity, social media marketing, web development, and SEO.",
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
                    "areaServed": "IN",
                    "availableLanguage": ["English", "Hindi", "Bengali"]
                },
                "sameAs": [
                    <?php $socials = []; if (SOCIAL_FACEBOOK && SOCIAL_FACEBOOK !== '#') $socials[] = '"' . SOCIAL_FACEBOOK . '"'; if (SOCIAL_INSTAGRAM && SOCIAL_INSTAGRAM !== '#') $socials[] = '"' . SOCIAL_INSTAGRAM . '"'; if (SOCIAL_LINKEDIN && SOCIAL_LINKEDIN !== '#') $socials[] = '"' . SOCIAL_LINKEDIN . '"'; echo implode(",\n                    ", $socials); ?>

                ],
                "knowsAbout": ["Content Marketing", "Content Strategy", "Graphics Design", "Brand Identity", "Social Media Marketing", "Web Development", "SEO", "Digital Marketing"]
            },
            {
                "@type": "LocalBusiness",
                "@id": "<?php echo SITE_URL; ?>/#localbusiness",
                "name": "<?php echo SITE_NAME; ?>",
                "image": "<?php echo SITE_LOGO; ?>",
                "url": "<?php echo SITE_URL; ?>",
                "telephone": "<?php echo CONTACT_PHONE; ?>",
                "priceRange": "$$",
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
                "name": "<?php echo SITE_NAME; ?>",
                "publisher": {"@id": "<?php echo SITE_URL; ?>/#organization"},
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "<?php echo SITE_URL; ?>/blog.php?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "SiteNavigationElement",
                "name": ["Home", "About Us", "Services", "Case Studies", "Blog", "Contact Us"],
                "url": [
                    "<?php echo SITE_URL; ?>/",
                    "<?php echo SITE_URL; ?>/about.php",
                    "<?php echo SITE_URL; ?>/services.php",
                    "<?php echo SITE_URL; ?>/case-studies.php",
                    "<?php echo SITE_URL; ?>/blog.php",
                    "<?php echo SITE_URL; ?>/contact.php"
                ]
            }
        ]
    }
    </script>
<?php endif; ?>
    
    <!-- Favicon -->
    <?php if (defined('SITE_FAVICON') && SITE_FAVICON): 
        $favExt = strtolower(pathinfo(parse_url(SITE_FAVICON, PHP_URL_PATH), PATHINFO_EXTENSION));
        $favType = ($favExt === 'ico') ? 'image/x-icon' : (($favExt === 'svg') ? 'image/svg+xml' : 'image/png');
        $favVer = defined('SITE_FAVICON_VERSION') ? SITE_FAVICON_VERSION : time();
    ?>
    <link rel="icon" type="<?php echo $favType; ?>" sizes="48x48" href="<?php echo SITE_FAVICON; ?>?v=<?php echo $favVer; ?>">
    <link rel="icon" type="<?php echo $favType; ?>" sizes="32x32" href="<?php echo SITE_FAVICON; ?>?v=<?php echo $favVer; ?>">
    <link rel="icon" type="<?php echo $favType; ?>" sizes="16x16" href="<?php echo SITE_FAVICON; ?>?v=<?php echo $favVer; ?>">
    <link rel="shortcut icon" href="<?php echo SITE_FAVICON; ?>?v=<?php echo $favVer; ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITE_FAVICON; ?>?v=<?php echo $favVer; ?>">
    <?php else: ?>
    <link rel="icon" type="image/png" sizes="48x48" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="shortcut icon" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo getSitePath('assets/images/favicon.png'); ?>">
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
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
            <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" class="preloader-logo">
            <div class="preloader-bar"><div class="preloader-bar-fill"></div></div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" data-bs-theme="dark" aria-label="Main navigation">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="<?php echo getSitePath('index.php'); ?>">
                <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" class="navbar-logo">
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
                    <a class="mobile-menu-logo" href="<?php echo getSitePath('index.php'); ?>">
                        <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" height="30">
                    </a>
                </div>

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('index.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-home"></i></span>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('about.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-users"></i></span>
                            About Us
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'services.php' || ($current_nav ?? '') === 'services') ? 'active' : ''; ?>" href="<?php echo getSitePath('services.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-briefcase"></i></span>
                            Our Services
                        </a>
                        <button class="dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle Services submenu"></button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo getSitePath('services.php'); ?>">All Services</a></li>
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
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'case-studies.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('case-studies.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-layer-group"></i></span>
                            Case Studies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('blog.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-pen-nib"></i></span>
                            Blog
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="<?php echo getSitePath('contact.php'); ?>">
                            <span class="nav-icon d-lg-none"><i class="fas fa-envelope"></i></span>
                            Contact Us
                        </a>
                    </li>
                </ul>
                
                <!-- CTA Button -->
                <a href="<?php echo getSitePath('contact.php'); ?>" class="btn btn-primary cta-btn">
                    <span class="btn-text">Get Enquiry Now</span>
                    <i class="fas fa-arrow-right btn-arrow"></i>
                </a>

                <!-- Mobile menu footer -->
                <div class="mobile-menu-footer d-lg-none">
                    <div class="mobile-menu-socials">
                        <?php if (SOCIAL_INSTAGRAM && SOCIAL_INSTAGRAM !== '#'): ?>
                        <a href="<?php echo SOCIAL_INSTAGRAM; ?>" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if (SOCIAL_LINKEDIN && SOCIAL_LINKEDIN !== '#'): ?>
                        <a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (SOCIAL_FACEBOOK && SOCIAL_FACEBOOK !== '#'): ?>
                        <a href="<?php echo SOCIAL_FACEBOOK; ?>" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                    </div>
                    <p class="mobile-menu-tagline">Crafting digital experiences ✨</p>
                </div>
            </div>
        </div>
    </nav>
