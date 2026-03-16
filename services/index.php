<?php
/**
 * Service Detail Page
 * Route: /services/{slug}
 * Renders individual service pages with full content
 */

// Set working directory to project root so all relative paths work
chdir(__DIR__ . '/..');

$page_title = 'Services';
$current_nav = 'services';

// Include config (loads services, DB helpers, etc.)
require_once __DIR__ . '/../config.php';

// Get slug from URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    // No slug — serve the services listing page directly
    // Override PHP_SELF so header.php detects 'services' page for SEO
    $_SERVER['PHP_SELF'] = '/kalpoink/services.php';
    include __DIR__ . '/../services.php';
    exit;
}

// Try database first
$service = getServiceBySlug($slug);

// Fallback: match against hardcoded services by slug
if (!$service) {
    $fallback_services = [
        'graphics-design' => [
            'title' => 'Graphics Design',
            'slug' => 'graphics-design',
            'icon' => 'fa-palette',
            'short_description' => 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.',
            'full_description' => '<p>At Kalpanik, our graphics design team transforms ideas into stunning visual experiences. Whether you need a logo that tells your story, marketing collateral that converts, or social media graphics that stop the scroll — we deliver designs that leave lasting impressions.</p><p>We combine creative flair with strategic thinking to ensure every design element serves a purpose. Our team stays on top of the latest design trends while maintaining timeless quality that represents your brand for years to come.</p>',
            'features' => ['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design', 'Poster & Banner Design', 'Illustration'],
            'image' => 'assets/images/services/graphics-design.png'
        ],
        'brand-identity' => [
            'title' => 'Brand Identity',
            'slug' => 'brand-identity',
            'icon' => 'fa-bullhorn',
            'short_description' => 'Build a memorable brand with consistent visual identity across all touchpoints.',
            'full_description' => '<p>Your brand is more than a logo — it\'s the complete experience your audience has with your business. At Kalpanik, we craft comprehensive brand identities that resonate with your target audience and differentiate you from the competition.</p><p>From initial brand strategy and positioning to visual identity systems and brand guidelines, we ensure every touchpoint tells a cohesive, compelling story. Our branding process digs deep into what makes your business unique and translates that into a visual language that speaks volumes.</p>',
            'features' => ['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging', 'Color Palette & Typography', 'Brand Voice Development'],
            'image' => 'assets/images/services/brand-identity.png'
        ],
        'social-media-marketing' => [
            'title' => 'Social Media Marketing',
            'slug' => 'social-media-marketing',
            'icon' => 'fa-share-nodes',
            'short_description' => 'Strategic social media campaigns that engage audiences and drive conversions.',
            'full_description' => '<p>Social media is where your audience lives, and we make sure your brand is part of the conversation. Our social media marketing strategies are built on data-driven insights, creative content, and consistent engagement that turns followers into loyal customers.</p><p>We manage your presence across all major platforms — Instagram, Facebook, LinkedIn, Twitter, and more — with customized strategies that align with your business goals. From content calendars to paid advertising, we handle every aspect of your social media growth.</p>',
            'features' => ['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management', 'Platform Optimization', 'Trend Monitoring'],
            'image' => 'assets/images/services/social-media-marketing.png'
        ],
        'web-development' => [
            'title' => 'Web Development',
            'slug' => 'web-development',
            'icon' => 'fa-code',
            'short_description' => 'Modern, responsive websites that deliver exceptional user experiences.',
            'full_description' => '<p>Your website is your digital headquarters — it needs to be fast, beautiful, and built to convert. Our web development team creates custom websites that not only look stunning but are engineered for performance, accessibility, and search engine visibility.</p><p>From single-page landing sites to complex e-commerce platforms, we use modern technologies and best practices to build websites that grow with your business. Every site we build is mobile-responsive, SEO-optimized, and designed for maximum user engagement.</p>',
            'features' => ['Custom Website Design', 'E-commerce Solutions', 'WordPress Development', 'Web Applications', 'Mobile Responsive', 'Maintenance & Support', 'Performance Optimization', 'CMS Integration'],
            'image' => 'assets/images/services/web-development.png'
        ],
        'seo-services' => [
            'title' => 'SEO Services',
            'slug' => 'seo-services',
            'icon' => 'fa-magnifying-glass',
            'short_description' => 'Improve your search rankings and drive organic traffic to your website.',
            'full_description' => '<p>Getting found online shouldn\'t be a matter of luck. Our SEO experts use proven, white-hat strategies to improve your search engine rankings and drive qualified organic traffic to your website. We focus on sustainable growth that delivers long-term results.</p><p>From comprehensive site audits to keyword research, on-page optimization, and link building — we cover every aspect of SEO that matters. Our data-driven approach means every decision is backed by analytics, ensuring your investment delivers measurable returns.</p>',
            'features' => ['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building', 'Local SEO', 'SEO Audits', 'Content Optimization', 'Competitor Analysis'],
            'image' => 'assets/images/services/seo-services.png'
        ],
        'content-marketing' => [
            'title' => 'Content Marketing',
            'slug' => 'content-marketing',
            'icon' => 'fa-pen-nib',
            'short_description' => 'Compelling content that tells your story and connects with your audience.',
            'full_description' => '<p>Content is the backbone of every successful digital marketing strategy. At Kalpanik, we create content that educates, entertains, and converts — building trust with your audience while positioning your brand as an authority in your industry.</p><p>Our content marketing approach goes beyond just writing blog posts. We develop comprehensive content strategies that align with your business goals, create high-quality content across multiple formats, and distribute it through the right channels to maximize reach and engagement.</p>',
            'features' => ['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing', 'Content Strategy', 'eBooks & Whitepapers', 'Social Media Content', 'Content Calendar Planning'],
            'image' => 'assets/images/services/content-marketing.png'
        ],
        'print-design' => [
            'title' => 'Print Design',
            'slug' => 'print-design',
            'icon' => 'fa-print',
            'short_description' => 'High-quality print materials that make a lasting impression.',
            'full_description' => '<p>In a digital world, print still carries weight. Well-designed print materials create tangible connections with your audience that digital simply can\'t replicate. Our print design team creates everything from business stationery to large-format displays with meticulous attention to detail.</p><p>We understand the nuances of print production — color management, paper selection, finishing techniques — and ensure every piece we design is print-ready and impactful. Whether it\'s a premium brochure or eye-catching event signage, we deliver print that impresses.</p>',
            'features' => ['Brochures', 'Posters & Banners', 'Business Stationery', 'Catalogs', 'Magazines', 'Signage', 'Packaging Design', 'Event Materials'],
            'image' => 'assets/images/services/print-design.png'
        ],
        'video-production' => [
            'title' => 'Video Production',
            'slug' => 'video-production',
            'icon' => 'fa-video',
            'short_description' => 'Engaging video content that captures attention and drives action.',
            'full_description' => '<p>Video is the most engaging content format online, and we help your brand harness its full potential. From concept development to final production, our video team creates compelling visual stories that capture attention, communicate your message, and drive action.</p><p>Whether you need corporate videos, product demos, social media reels, or animated explainers — we bring creative storytelling and technical expertise together to produce videos that deliver results and represent your brand at its best.</p>',
            'features' => ['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Social Media Videos', 'Explainer Videos', 'Video Editing', 'Animation', 'Storyboarding'],
            'image' => 'assets/images/services/video-production.png'
        ]
    ];

    $service = $fallback_services[$slug] ?? null;
}

// 404 if service not found
if (!$service) {
    header('HTTP/1.0 404 Not Found');
    include __DIR__ . '/../404.php';
    exit;
}

// Prepare service data
$svc_title = $service['title'];
$svc_slug = $service['slug'] ?? $slug;
$svc_icon = $service['icon'] ?? 'fa-cogs';
$svc_short = $service['short_description'] ?? '';
$svc_full = $service['full_description'] ?? $svc_short;
$svc_features = is_array($service['features']) ? $service['features'] : (json_decode($service['features'] ?? '[]', true) ?? []);
$svc_image = $service['image'] ?? '';
if (empty($svc_image)) {
    $fallback_img = 'assets/images/services/' . $svc_slug . '.png';
    if (file_exists(__DIR__ . '/../' . $fallback_img)) {
        $svc_image = $fallback_img;
    }
}

// Override SEO for this page
$page_seo = [
    'title' => $svc_title . ' Services in Kolkata - ' . SITE_NAME . ' | Content Marketing Company',
    'description' => strip_tags($svc_short) . ' Professional ' . strtolower($svc_title) . ' services by ' . SITE_NAME . ', a content marketing company and creative design house in Kolkata.',
];
$canonical_url = SITE_URL . '/services/' . $svc_slug;

// Include header
include __DIR__ . '/../includes/header.php';

// Get all other services for "Related Services" section
$all_services = getServicesFromDB(true);
if (empty($all_services)) {
    // Use fallback from config
    global $services;
    $all_services = $services;
}
$related_services = array_filter($all_services, function($s) use ($svc_title) {
    return ($s['title'] ?? '') !== $svc_title;
});
$related_services = array_slice($related_services, 0, 4);
?>

    <!-- JSON-LD: Service + BreadcrumbList -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Service",
        "name": <?php echo json_encode($svc_title); ?>,
        "description": <?php echo json_encode(strip_tags($svc_short)); ?>,
        "provider": {"@id": "<?php echo SITE_URL; ?>/#organization"},
        "areaServed": {"@type": "Country", "name": "India"},
        "url": "<?php echo $canonical_url; ?>"
    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home", "item": "<?php echo SITE_URL; ?>/"},
            {"@type": "ListItem", "position": 2, "name": "Services", "item": "<?php echo SITE_URL; ?>/services"},
            {"@type": "ListItem", "position": 3, "name": <?php echo json_encode($svc_title); ?>, "item": "<?php echo $canonical_url; ?>"}
        ]
    }
    </script>

    <!-- Breadcrumb + Hero -->
    <section class="svc-detail-hero">
        <div class="container">
            <nav class="svc-detail-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="svc-bc-sep">/</span>
                <a href="<?php echo SITE_URL; ?>/services">Services</a>
                <span class="svc-bc-sep">/</span>
                <span class="svc-bc-current"><?php echo htmlspecialchars($svc_title); ?></span>
            </nav>
            <div class="row align-items-center">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="svc-detail-hero-icon">
                        <i class="fas <?php echo htmlspecialchars($svc_icon); ?>"></i>
                    </div>
                    <h1 class="svc-detail-hero-title"><?php echo htmlspecialchars($svc_title); ?></h1>
                    <p class="svc-detail-hero-subtitle"><?php echo htmlspecialchars($svc_short); ?></p>
                    <a href="<?php echo SITE_URL; ?>/contact" class="btn-services-cta">
                        <span>Get a Free Quote</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <!-- Right Side Image Graphic -->
                <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
                    <?php if (!empty($svc_image)): ?>
                    <div class="svc-detail-hero-img">
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($svc_image); ?>" alt="<?php echo htmlspecialchars($svc_title); ?> Services Graphic">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Service Content -->
    <section class="svc-detail-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-7" data-aos="fade-up">
                    <h2 class="svc-detail-section-title">What We Offer</h2>
                    <div class="svc-detail-body">
                        <?php echo $svc_full; ?>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="100">
                    <?php if (!empty($svc_features)): ?>
                    <div class="svc-detail-features-card">
                        <h3 class="svc-detail-features-heading">What's Included</h3>
                        <ul class="svc-detail-features-list">
                            <?php foreach ($svc_features as $feature): ?>
                            <li>
                                <i class="fas fa-check"></i>
                                <span><?php echo htmlspecialchars($feature); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?php echo SITE_URL; ?>/contact" class="svc-detail-features-btn">
                            Start a Project <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Services -->
    <?php if (!empty($related_services)): ?>
    <section class="svc-detail-related">
        <div class="container">
            <h2 class="svc-detail-section-title text-center" data-aos="fade-up">Explore Other Services</h2>
            <p class="svc-detail-section-sub text-center" data-aos="fade-up" data-aos-delay="50">Discover more ways we can help grow your brand</p>
            <div class="svc-detail-related-grid" data-aos="fade-up" data-aos-delay="100">
                <?php foreach ($related_services as $rs):
                    $rs_slug = $rs['slug'] ?? strtolower(str_replace(' ', '-', $rs['title']));
                ?>
                <a href="<?php echo SITE_URL; ?>/services/<?php echo htmlspecialchars($rs_slug); ?>" class="svc-related-card">
                    <div class="svc-related-icon">
                        <i class="fas <?php echo htmlspecialchars($rs['icon'] ?? 'fa-cogs'); ?>"></i>
                    </div>
                    <h3 class="svc-related-title"><?php echo htmlspecialchars($rs['title']); ?></h3>
                    <p class="svc-related-desc"><?php echo htmlspecialchars($rs['short_description'] ?? $rs['description'] ?? ''); ?></p>
                    <span class="svc-related-link">Learn More <i class="fas fa-arrow-right"></i></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="svc-detail-cta">
        <div class="container text-center">
            <h2 class="svc-detail-cta-title" data-aos="fade-up">Ready to Get Started?</h2>
            <p class="svc-detail-cta-text" data-aos="fade-up" data-aos-delay="100">Let's discuss how our <?php echo htmlspecialchars(strtolower($svc_title)); ?> services can help grow your business.</p>
            <a href="<?php echo SITE_URL; ?>/contact" class="btn-services-cta" data-aos="fade-up" data-aos-delay="200">
                <span>Get Enquiry Now</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
