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
    // Sorted A-Z by title
    $fallback_services = [
        'brand-identity' => [
            'title' => 'Brand Identity',
            'slug' => 'brand-identity',
            'icon' => 'fa-bullhorn',
            'short_description' => 'Build a memorable brand with consistent visual identity across all touchpoints.',
            'full_description' => '<p>Your brand is more than a logo — it\'s the complete experience your audience has with your business. At Kalpanik, we craft comprehensive brand identities that resonate with your target audience and differentiate you from the competition.</p><p>From initial brand strategy and positioning to visual identity systems and brand guidelines, we ensure every touchpoint tells a cohesive, compelling story. Our branding process digs deep into what makes your business unique and translates that into a visual language that speaks volumes.</p>',
            'features' => ['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging', 'Color Palette & Typography', 'Brand Voice Development'],
            'image' => 'assets/images/services/brand-identity.png'
        ],
        'communication-design' => [
            'title' => 'Communication Design',
            'slug' => 'communication-design',
            'icon' => 'fa-layer-group',
            'short_description' => 'Clear, compelling visual communication that conveys your message with impact.',
            'full_description' => '<p>Communication design is the art of conveying ideas visually — bridging the gap between your brand and your audience through intentional, strategic design. At Kalpanik, we craft communication pieces that are not just beautiful but purposefully effective.</p><p>From infographics and presentation decks to reports, brochures, and marketing collateral — we ensure every visual touchpoint communicates your message clearly, builds trust, and drives the response you want from your audience.</p>',
            'features' => ['Infographics', 'Presentation Design', 'Marketing Collateral', 'Annual Reports', 'Brochures & Leaflets', 'Email Templates', 'Digital Banners', 'Editorial Design'],
            'image' => 'assets/images/services/communication-design.png'
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
        'flyers' => [
            'title' => 'Flyers',
            'slug' => 'flyers',
            'icon' => 'fa-file-image',
            'short_description' => 'Eye-catching flyer designs that get noticed and drive action for events and promotions.',
            'full_description' => '<p>A well-designed flyer can stop someone in their tracks. At Kalpanik, we design flyers that do exactly that — combining bold visuals, clear hierarchy, and persuasive copy to make sure your message gets across and your audience takes action.</p><p>Whether it\'s for a grand opening, event promotion, sale announcement, or community outreach, we tailor every flyer design to your specific goal and target audience. We handle both print-ready and digital formats optimized for sharing across platforms.</p>',
            'features' => ['Event Flyers', 'Promotional Flyers', 'Digital Flyers', 'A4 & A5 Print Formats', 'Social Media Adaptations', 'Double-sided Design', 'Bulk Variations', 'Brand-consistent Layouts'],
            'image' => 'assets/images/services/print-design.png'
        ],
        'graphics-design' => [
            'title' => 'Graphics Design',
            'slug' => 'graphics-design',
            'icon' => 'fa-palette',
            'short_description' => 'Eye-catching visuals that capture your brand essence. From logos to complete brand identity packages.',
            'full_description' => '<p>At Kalpanik, our graphics design team transforms ideas into stunning visual experiences. Whether you need a logo that tells your story, marketing collateral that converts, or social media graphics that stop the scroll — we deliver designs that leave lasting impressions.</p><p>We combine creative flair with strategic thinking to ensure every design element serves a purpose. Our team stays on top of the latest design trends while maintaining timeless quality that represents your brand for years to come.</p>',
            'features' => ['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design', 'Poster & Banner Design', 'Illustration'],
            'image' => 'assets/images/services/graphics-design.png'
        ],
        'poster-design' => [
            'title' => 'Poster Design',
            'slug' => 'poster-design',
            'icon' => 'fa-image',
            'short_description' => 'Bold, striking poster designs that communicate powerfully and leave a lasting impression.',
            'full_description' => '<p>Posters are one of the most powerful visual communication tools — when done right. At Kalpanik, we design posters that command attention, tell a story at a glance, and represent your brand with confidence whether displayed in print or shared digitally.</p><p>From film and event posters to promotional and motivational designs, our team brings creative direction, typography mastery, and print expertise together to produce poster work that genuinely stands out. We deliver files ready for large-format printing or digital use.</p>',
            'features' => ['Event Posters', 'Film & Entertainment Posters', 'Promotional Posters', 'Large Format Print', 'Digital Poster Formats', 'Typography-led Designs', 'Illustration Posters', 'Series & Campaign Posters'],
            'image' => 'assets/images/services/print-design.png'
        ],
        'print-design' => [
            'title' => 'Print Design',
            'slug' => 'print-design',
            'icon' => 'fa-print',
            'short_description' => 'Tangible brand experiences. From high-quality marketing collaterals and custom merchandise to premium packaging, we bring your visual identity into the physical world.',
            'full_description' => '<p>Tangible brand experiences matter. In a world saturated with digital noise, physical touchpoints create deeper, more memorable connections with your audience. At Kalpanik, our Print Design service bridges the gap between your digital brand and the real world — transforming your visual identity into high-quality marketing collaterals, custom merchandise, and premium packaging that people can hold, feel, and remember.</p><p>From business stationery and brochures to large-format displays, custom merchandise, and luxury packaging — we handle every detail with precision. We understand the nuances of print production — color management, paper selection, finishing techniques — and ensure every piece we design is print-ready, on-brand, and built to leave a lasting impression.</p>',
            'features' => ['Marketing Collaterals', 'Custom Merchandise', 'Premium Packaging', 'Business Stationery', 'Brochures & Catalogs', 'Posters & Banners', 'Signage & Displays', 'Event Materials'],
            'image' => 'assets/images/services/print-design.png'
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
        'social-media-marketing' => [
            'title' => 'Social Media Marketing',
            'slug' => 'social-media-marketing',
            'icon' => 'fa-share-nodes',
            'short_description' => 'Strategic social media campaigns that engage audiences and drive conversions.',
            'full_description' => '<p>Social media is where your audience lives, and we make sure your brand is part of the conversation. Our social media marketing strategies are built on data-driven insights, creative content, and consistent engagement that turns followers into loyal customers.</p><p>We manage your presence across all major platforms — Instagram, Facebook, LinkedIn, Twitter, and more — with customized strategies that align with your business goals. From content calendars to paid advertising, we handle every aspect of your social media growth.</p>',
            'features' => ['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management', 'Platform Optimization', 'Trend Monitoring'],
            'image' => 'assets/images/services/social-media-marketing.png'
        ],
        'video-production' => [
            'title' => 'Video Production',
            'slug' => 'video-production',
            'icon' => 'fa-video',
            'short_description' => 'Engaging video content that captures attention and drives action.',
            'full_description' => '<p>Video is the most engaging content format online, and we help your brand harness its full potential. From concept development to final production, our video team creates compelling visual stories that capture attention, communicate your message, and drive action.</p><p>Whether you need corporate videos, product demos, social media reels, or animated explainers — we bring creative storytelling and technical expertise together to produce videos that deliver results and represent your brand at its best.</p>',
            'features' => ['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Social Media Videos', 'Explainer Videos', 'Video Editing', 'Animation', 'Storyboarding'],
            'image' => 'assets/images/services/video-production.png'
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

    <!-- ═══════════════════════════════════════════════════
         HERO — Immersive gradient backdrop with floating orbs
    ═══════════════════════════════════════════════════ -->
    <section class="sdp-hero">
        <!-- Animated background orbs -->
        <div class="sdp-hero__orb sdp-hero__orb--1"></div>
        <div class="sdp-hero__orb sdp-hero__orb--2"></div>
        <div class="sdp-hero__orb sdp-hero__orb--3"></div>
        <div class="sdp-hero__grid-overlay"></div>

        <div class="container">
            <!-- Breadcrumb -->
            <nav class="sdp-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="sdp-breadcrumb__sep"><i class="fas fa-chevron-right"></i></span>
                <a href="<?php echo SITE_URL; ?>/services">Services</a>
                <span class="sdp-breadcrumb__sep"><i class="fas fa-chevron-right"></i></span>
                <span class="sdp-breadcrumb__current"><?php echo htmlspecialchars($svc_title); ?></span>
            </nav>

            <div class="sdp-hero__content">
                <!-- Icon badge -->
                <div class="sdp-hero__icon-badge" data-aos="zoom-in" data-aos-duration="600">
                    <i class="fas <?php echo htmlspecialchars($svc_icon); ?>"></i>
                </div>

                <h1 class="sdp-hero__title" data-aos="fade-up" data-aos-delay="100">
                    <?php echo htmlspecialchars($svc_title); ?>
                </h1>

                <p class="sdp-hero__desc" data-aos="fade-up" data-aos-delay="200">
                    <?php echo htmlspecialchars($svc_short); ?>
                </p>

                <div class="sdp-hero__actions" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?php echo SITE_URL; ?>/contact" class="sdp-btn sdp-btn--primary">
                        <span>Get a Free Quote</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#sdp-features" class="sdp-btn sdp-btn--ghost">
                        <span>Explore Features</span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                </div>

                <!-- Quick Stats -->
                <?php if (!empty($svc_features)): ?>
                <div class="sdp-hero__stats" data-aos="fade-up" data-aos-delay="400">
                    <div class="sdp-hero__stat">
                        <span class="sdp-hero__stat-value"><?php echo count($svc_features); ?>+</span>
                        <span class="sdp-hero__stat-label">Deliverables</span>
                    </div>
                    <div class="sdp-hero__stat-divider"></div>
                    <div class="sdp-hero__stat">
                        <span class="sdp-hero__stat-value">100%</span>
                        <span class="sdp-hero__stat-label">Custom Work</span>
                    </div>
                    <div class="sdp-hero__stat-divider"></div>
                    <div class="sdp-hero__stat">
                        <span class="sdp-hero__stat-value">24/7</span>
                        <span class="sdp-hero__stat-label">Support</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="sdp-hero__scroll-hint">
            <div class="sdp-scroll-mouse">
                <div class="sdp-scroll-wheel"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         CONTENT — Description + Feature Pills
    ═══════════════════════════════════════════════════ -->
    <section class="sdp-content" id="sdp-features">
        <div class="container">
            <!-- Section label -->
            <div class="sdp-content__label" data-aos="fade-up">
                <span class="sdp-label-tag"><i class="fas <?php echo htmlspecialchars($svc_icon); ?>"></i> About this service</span>
            </div>

            <div class="sdp-content__grid">
                <!-- Left: Body text -->
                <div class="sdp-content__text" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="sdp-content__heading">What We <span class="sdp-text-accent">Offer</span></h2>
                    <div class="sdp-content__body">
                        <?php echo $svc_full; ?>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/contact" class="sdp-inline-cta">
                        Discuss your project <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Right: Feature card -->
                <?php if (!empty($svc_features)): ?>
                <div class="sdp-content__features" data-aos="fade-up" data-aos-delay="200">
                    <div class="sdp-features-card">
                        <div class="sdp-features-card__header">
                            <div class="sdp-features-card__icon">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <div>
                                <h3 class="sdp-features-card__title">What's Included</h3>
                                <p class="sdp-features-card__sub"><?php echo count($svc_features); ?> key deliverables</p>
                            </div>
                        </div>

                        <div class="sdp-features-pills">
                            <?php foreach ($svc_features as $i => $feature): ?>
                            <div class="sdp-feature-pill" style="--pill-delay: <?php echo $i * 0.05; ?>s">
                                <span class="sdp-feature-pill__num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                                <span class="sdp-feature-pill__text"><?php echo htmlspecialchars($feature); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <a href="<?php echo SITE_URL; ?>/contact" class="sdp-features-card__btn">
                            <span>Start a Project</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         WHY CHOOSE US — Process Steps
    ═══════════════════════════════════════════════════ -->
    <section class="sdp-process">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <span class="sdp-label-tag"><i class="fas fa-route"></i> Our Process</span>
                <h2 class="sdp-process__title">How We <span class="sdp-text-accent">Work</span></h2>
                <p class="sdp-process__sub">A streamlined approach to delivering exceptional <?php echo htmlspecialchars(strtolower($svc_title)); ?> results.</p>
            </div>

            <div class="sdp-process__steps">
                <div class="sdp-process__step" data-aos="fade-up" data-aos-delay="100">
                    <div class="sdp-step__number">01</div>
                    <div class="sdp-step__content">
                        <h3 class="sdp-step__title">Discovery</h3>
                        <p class="sdp-step__desc">We dive deep into your brand, goals, and audience to build a solid foundation.</p>
                    </div>
                </div>
                <div class="sdp-process__connector"></div>
                <div class="sdp-process__step" data-aos="fade-up" data-aos-delay="200">
                    <div class="sdp-step__number">02</div>
                    <div class="sdp-step__content">
                        <h3 class="sdp-step__title">Strategy</h3>
                        <p class="sdp-step__desc">We craft a tailored plan aligned with your objectives and market positioning.</p>
                    </div>
                </div>
                <div class="sdp-process__connector"></div>
                <div class="sdp-process__step" data-aos="fade-up" data-aos-delay="300">
                    <div class="sdp-step__number">03</div>
                    <div class="sdp-step__content">
                        <h3 class="sdp-step__title">Execution</h3>
                        <p class="sdp-step__desc">Our creative team brings the vision to life with meticulous attention to detail.</p>
                    </div>
                </div>
                <div class="sdp-process__connector"></div>
                <div class="sdp-process__step" data-aos="fade-up" data-aos-delay="400">
                    <div class="sdp-step__number">04</div>
                    <div class="sdp-step__content">
                        <h3 class="sdp-step__title">Delivery</h3>
                        <p class="sdp-step__desc">Polished, production-ready deliverables with ongoing support and revisions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         RELATED SERVICES
    ═══════════════════════════════════════════════════ -->
    <?php if (!empty($related_services)): ?>
    <section class="sdp-related">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <span class="sdp-label-tag"><i class="fas fa-th-large"></i> More Services</span>
                <h2 class="sdp-related__title">Explore Other <span class="sdp-text-accent">Services</span></h2>
                <p class="sdp-related__sub">Discover more ways we can help grow your brand</p>
            </div>

            <div class="sdp-related__grid">
                <?php foreach ($related_services as $rs):
                    $rs_slug = $rs['slug'] ?? strtolower(str_replace(' ', '-', $rs['title']));
                ?>
                <a href="<?php echo SITE_URL; ?>/services/<?php echo htmlspecialchars($rs_slug); ?>" class="sdp-related__card" data-aos="fade-up">
                    <div class="sdp-related__card-icon">
                        <i class="fas <?php echo htmlspecialchars($rs['icon'] ?? 'fa-cogs'); ?>"></i>
                    </div>
                    <h3 class="sdp-related__card-title"><?php echo htmlspecialchars($rs['title']); ?></h3>
                    <p class="sdp-related__card-desc"><?php echo htmlspecialchars($rs['short_description'] ?? $rs['description'] ?? ''); ?></p>
                    <span class="sdp-related__card-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ═══════════════════════════════════════════════════
         CTA — Final call to action
    ═══════════════════════════════════════════════════ -->
    <section class="sdp-cta">
        <div class="sdp-cta__particles">
            <span></span><span></span><span></span><span></span><span></span><span></span>
        </div>
        <div class="container">
            <div class="sdp-cta__inner" data-aos="zoom-in">
                <h2 class="sdp-cta__title">Ready to Get Started?</h2>
                <p class="sdp-cta__text">Let's discuss how our <?php echo htmlspecialchars(strtolower($svc_title)); ?> services can help grow your business.</p>
                <a href="<?php echo SITE_URL; ?>/contact" class="sdp-btn sdp-btn--primary sdp-btn--lg">
                    <span>Get Enquiry Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

