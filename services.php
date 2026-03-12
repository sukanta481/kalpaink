<?php 
$page_title = 'Services';
include 'includes/header.php'; 

// Get page content from CMS (auto-sync)
$svc_content = getPageContent('services');
$svc_hero = $svc_content['hero'] ?? null;
$svc_cta = $svc_content['cta'] ?? null;

// Get services from CRM database (auto-sync)
$services_from_db = getServicesFromDB(false);

// Use CRM services if available, otherwise use fallback
$detailed_services = !empty($services_from_db) ? array_map(function($s) {
    return [
        'id' => $s['slug'] ?? strtolower(str_replace(' ', '-', $s['title'])),
        'icon' => $s['icon'] ?? 'fa-cogs',
        'title' => $s['title'],
        'summary' => $s['short_description'],
        'description' => $s['full_description'] ?? $s['short_description'],
        'features' => is_array($s['features']) ? $s['features'] : (json_decode($s['features'], true) ?? [])
    ];
}, $services_from_db) : [
    [
        'id' => 'graphics-design',
        'icon' => 'fa-palette',
        'title' => 'Graphics Design',
        'summary' => 'Eye-catching visuals that capture your brand essence.',
        'description' => 'From stunning logos to complete visual identities, our graphics design team creates eye-catching visuals that capture your brand essence and leave lasting impressions.',
        'features' => ['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design']
    ],
    [
        'id' => 'brand-identity',
        'icon' => 'fa-bullhorn',
        'title' => 'Brand Identity',
        'summary' => 'Build a memorable brand that stands out.',
        'description' => 'Build a memorable brand with consistent visual identity across all touchpoints. We create comprehensive brand guidelines that ensure your brand stands out.',
        'features' => ['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging']
    ],
    [
        'id' => 'social-media-marketing',
        'icon' => 'fa-share-nodes',
        'title' => 'Social Media Marketing',
        'summary' => 'Strategic campaigns that engage and convert.',
        'description' => 'Strategic social media campaigns that engage audiences and drive conversions. We manage your social presence across all major platforms.',
        'features' => ['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management']
    ],
    [
        'id' => 'web-development',
        'icon' => 'fa-code',
        'title' => 'Web Development',
        'summary' => 'Modern, responsive websites that deliver.',
        'description' => 'Modern, responsive websites that deliver exceptional user experiences. From landing pages to complex web applications, we build it all.',
        'features' => ['Custom Website Design', 'E-commerce Solutions', 'WordPress Development', 'Web Applications', 'Mobile Responsive', 'Maintenance & Support']
    ],
    [
        'id' => 'seo-services',
        'icon' => 'fa-magnifying-glass',
        'title' => 'SEO Services',
        'summary' => 'Boost your search rankings and visibility.',
        'description' => 'Improve your search rankings and drive organic traffic to your website. Our SEO experts use proven strategies to boost your online visibility.',
        'features' => ['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building', 'Local SEO', 'SEO Audits']
    ],
    [
        'id' => 'content-marketing',
        'icon' => 'fa-pen-nib',
        'title' => 'Content Marketing',
        'summary' => 'Compelling content that connects and converts.',
        'description' => 'Compelling content that tells your story and connects with your audience. We create content that educates, entertains, and converts.',
        'features' => ['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing', 'Content Strategy', 'eBooks & Whitepapers']
    ],
    [
        'id' => 'print-design',
        'icon' => 'fa-print',
        'title' => 'Print Design',
        'summary' => 'High-quality print materials that impress.',
        'description' => 'High-quality print materials that make a lasting impression. From business cards to large format displays, we handle all your print needs.',
        'features' => ['Brochures', 'Posters & Banners', 'Business Stationery', 'Catalogs', 'Magazines', 'Signage']
    ],
    [
        'id' => 'video-production',
        'icon' => 'fa-video',
        'title' => 'Video Production',
        'summary' => 'Engaging video content that drives action.',
        'description' => 'Engaging video content that captures attention and drives action. From promotional videos to animations, we bring your vision to life.',
        'features' => ['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Social Media Videos', 'Explainer Videos', 'Video Editing']
    ]
];
?>

    <!-- Services Hero Section - Digital Toolkit -->
    <section class="services-hero-toolkit">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="toolkit-content">
                        <h1 class="toolkit-title"><?php echo $svc_hero['content_title'] ?? 'Everything You Need to <span class="text-accent">Grow</span>.'; ?></h1>
                        <p class="toolkit-subtitle"><?php echo htmlspecialchars($svc_hero['content_body'] ?? 'Comprehensive digital solutions tailored to your needs — from design to development, marketing to branding.'); ?></p>
                        <a href="<?php echo getSitePath('contact.php'); ?>" class="btn-services-cta">
                            <span><?php echo htmlspecialchars($svc_hero['extra']['button_text'] ?? 'Start a Project'); ?></span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <!-- Holographic Dashboard Card -->
                    <div class="toolkit-visual">
                        <div class="floating-tools-container">
                            <!-- Floating Tool Icons -->
                            <div class="floating-tool tool-pen" data-speed="0.8">
                                <i class="fas fa-pen-nib"></i>
                            </div>
                            <div class="floating-tool tool-code" data-speed="1.2">
                                <i class="fas fa-code"></i>
                            </div>
                            <div class="floating-tool tool-megaphone" data-speed="0.6">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="floating-tool tool-palette" data-speed="1">
                                <i class="fas fa-palette"></i>
                            </div>
                            <div class="floating-tool tool-chart" data-speed="0.9">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            
                            <!-- Dark Glassmorphism Dashboard -->
                            <div class="glass-toolkit-card">
                                <div class="glass-card-header">
                                    <span class="glass-status-dot"></span>
                                    <span class="glass-card-label">Project Dashboard</span>
                                </div>
                                <div class="glass-card-content">
                                    <div class="glass-metric-row">
                                        <div class="glass-metric">
                                            <span class="glass-metric-value">98%</span>
                                            <span class="glass-metric-label">Satisfaction</span>
                                        </div>
                                        <div class="glass-metric">
                                            <span class="glass-metric-value">200+</span>
                                            <span class="glass-metric-label">Projects</span>
                                        </div>
                                    </div>
                                    <div class="glass-progress-section">
                                        <div class="glass-progress-item">
                                            <div class="glass-progress-head">
                                                <span>Design</span>
                                                <span>92%</span>
                                            </div>
                                            <div class="glass-progress-bar">
                                                <div class="glass-progress-fill" style="width: 92%"></div>
                                            </div>
                                        </div>
                                        <div class="glass-progress-item">
                                            <div class="glass-progress-head">
                                                <span>Development</span>
                                                <span>87%</span>
                                            </div>
                                            <div class="glass-progress-bar">
                                                <div class="glass-progress-fill fill-blue" style="width: 87%"></div>
                                            </div>
                                        </div>
                                        <div class="glass-progress-item">
                                            <div class="glass-progress-head">
                                                <span>Marketing</span>
                                                <span>95%</span>
                                            </div>
                                            <div class="glass-progress-bar">
                                                <div class="glass-progress-fill fill-green" style="width: 95%"></div>
                                            </div>
                                        </div>
                                        <div class="glass-progress-item">
                                            <div class="glass-progress-head">
                                                <span>Branding</span>
                                                <span>90%</span>
                                            </div>
                                            <div class="glass-progress-bar">
                                                <div class="glass-progress-fill fill-purple" style="width: 90%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid - Icon Cards -->
    <section class="services-grid-section section-padding" id="services-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive digital solutions tailored to your needs</p>
            </div>
            
            <?php
            // SVG icon map for services (line-art style)
            $svc_svg_icons = [
                'fa-palette' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 52L30 8h4l18 44" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 36h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M44 52h-8l-2-8h-4l-2 8h-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="48" cy="16" r="4" stroke="currentColor" stroke-width="2.5"/><path d="M8 48c0-4 4-8 8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
                'fa-bullhorn' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 24h8v16H8a2 2 0 01-2-2V26a2 2 0 012-2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 24l24-12v40L16 40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 40v10a4 4 0 004 4h4a4 4 0 004-4V40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="50" cy="20" r="3" stroke="currentColor" stroke-width="2.5"/><path d="M48 28h8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="54" cy="36" r="2" stroke="currentColor" stroke-width="2"/></svg>',
                'fa-share-nodes' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="14" width="24" height="20" rx="3" stroke="currentColor" stroke-width="2.5"/><path d="M12 28l6-6 4 4 8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M34 18h24a2 2 0 012 2v24a2 2 0 01-2 2H34" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="46" cy="30" r="5" stroke="currentColor" stroke-width="2.5"/><path d="M43 35l-2 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M49 35l2 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><rect x="10" y="38" width="16" height="16" rx="3" stroke="currentColor" stroke-width="2.5"/><path d="M18 44v6M15 47h6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
                'fa-code' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="10" width="52" height="40" rx="4" stroke="currentColor" stroke-width="2.5"/><path d="M6 20h52" stroke="currentColor" stroke-width="2.5"/><circle cx="12" cy="15" r="1.5" fill="currentColor"/><circle cx="18" cy="15" r="1.5" fill="currentColor"/><circle cx="24" cy="15" r="1.5" fill="currentColor"/><path d="M20 32l-6 4 6 4M36 32l6 4-6 4M26 42l4-16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'fa-magnifying-glass' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="28" cy="28" r="16" stroke="currentColor" stroke-width="2.5"/><path d="M40 40l14 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M20 32V24l4 4 4-8 4 6 4-4v10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="50" cy="14" r="4" stroke="currentColor" stroke-width="2.5"/><path d="M50 12v4M48 14h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
                'fa-pen-nib' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 12h40a4 4 0 014 4v8H8V12z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 24v28h32V24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 32h16M20 38h10M20 44h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M48 20l8-8M52 8l4 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="52" cy="44" r="6" stroke="currentColor" stroke-width="2.5"/><path d="M52 41v6M49 44h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
                'fa-print' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 20V8h32v12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><rect x="8" y="20" width="48" height="24" rx="4" stroke="currentColor" stroke-width="2.5"/><path d="M16 36h32v20H16V36z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M22 44h20M22 50h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="44" cy="28" r="2" fill="currentColor"/></svg>',
                'fa-video' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="16" width="36" height="32" rx="4" stroke="currentColor" stroke-width="2.5"/><path d="M42 28l16-8v24l-16-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="32" r="8" stroke="currentColor" stroke-width="2.5"/><path d="M21 32l4 3v-6l4 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                'fa-cogs' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="32" r="8" stroke="currentColor" stroke-width="2.5"/><path d="M32 6v8M32 50v8M6 32h8M50 32h8M13.5 13.5l5.6 5.6M44.9 44.9l5.6 5.6M13.5 50.5l5.6-5.6M44.9 19.1l5.6-5.6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
            ];
            ?>
            <div class="svc-slider-wrap" data-aos="fade-up">
                <button class="svc-slider-arrow svc-slider-arrow--prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="services-icon-grid">
                    <?php foreach ($detailed_services as $index => $service): ?>
                    <div class="svc-icon-card">
                        <div class="svc-icon-wrap">
                            <?php echo $svc_svg_icons[$service['icon']] ?? '<i class="fas ' . htmlspecialchars($service['icon']) . '"></i>'; ?>
                        </div>
                        <h3 class="svc-icon-title"><?php echo htmlspecialchars($service['title']); ?></h3>
                        <div class="svc-icon-details">
                            <p class="svc-icon-desc"><?php echo htmlspecialchars($service['summary']); ?></p>
                            <a href="<?php echo getSitePath('services/' . htmlspecialchars($service['id'])); ?>" class="svc-icon-link">Know More <span>&rarr;</span></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="svc-slider-arrow svc-slider-arrow--next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
