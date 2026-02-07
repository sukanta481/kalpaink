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
        'id' => 'graphics',
        'icon' => 'fa-palette',
        'title' => 'Graphics Design',
        'summary' => 'Eye-catching visuals that capture your brand essence.',
        'description' => 'From stunning logos to complete visual identities, our graphics design team creates eye-catching visuals that capture your brand essence and leave lasting impressions.',
        'features' => ['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design']
    ],
    [
        'id' => 'branding',
        'icon' => 'fa-bullhorn',
        'title' => 'Brand Identity',
        'summary' => 'Build a memorable brand that stands out.',
        'description' => 'Build a memorable brand with consistent visual identity across all touchpoints. We create comprehensive brand guidelines that ensure your brand stands out.',
        'features' => ['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging']
    ],
    [
        'id' => 'smm',
        'icon' => 'fa-share-nodes',
        'title' => 'Social Media Marketing',
        'summary' => 'Strategic campaigns that engage and convert.',
        'description' => 'Strategic social media campaigns that engage audiences and drive conversions. We manage your social presence across all major platforms.',
        'features' => ['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management']
    ],
    [
        'id' => 'web',
        'icon' => 'fa-code',
        'title' => 'Web Development',
        'summary' => 'Modern, responsive websites that deliver.',
        'description' => 'Modern, responsive websites that deliver exceptional user experiences. From landing pages to complex web applications, we build it all.',
        'features' => ['Custom Website Design', 'E-commerce Solutions', 'WordPress Development', 'Web Applications', 'Mobile Responsive', 'Maintenance & Support']
    ],
    [
        'id' => 'seo',
        'icon' => 'fa-magnifying-glass',
        'title' => 'SEO Services',
        'summary' => 'Boost your search rankings and visibility.',
        'description' => 'Improve your search rankings and drive organic traffic to your website. Our SEO experts use proven strategies to boost your online visibility.',
        'features' => ['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building', 'Local SEO', 'SEO Audits']
    ],
    [
        'id' => 'content',
        'icon' => 'fa-pen-nib',
        'title' => 'Content Marketing',
        'summary' => 'Compelling content that connects and converts.',
        'description' => 'Compelling content that tells your story and connects with your audience. We create content that educates, entertains, and converts.',
        'features' => ['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing', 'Content Strategy', 'eBooks & Whitepapers']
    ],
    [
        'id' => 'print',
        'icon' => 'fa-print',
        'title' => 'Print Design',
        'summary' => 'High-quality print materials that impress.',
        'description' => 'High-quality print materials that make a lasting impression. From business cards to large format displays, we handle all your print needs.',
        'features' => ['Brochures', 'Posters & Banners', 'Business Stationery', 'Catalogs', 'Magazines', 'Signage']
    ],
    [
        'id' => 'video',
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
                        <a href="contact.php" class="btn-services-cta">
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

    <!-- Services Grid -->
    <section class="services-grid-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive digital solutions tailored to your needs</p>
            </div>
            
            <!-- Desktop: Hover Hologram Grid | Mobile: Accordion Stack -->
            <div class="row g-4 services-hologram-grid">
                <?php foreach ($detailed_services as $index => $service): ?>
                <div class="col-lg-6" id="<?php echo $service['id']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4 + 1) * 100; ?>">
                    <div class="service-hologram-card" data-service-id="<?php echo $service['id']; ?>">
                        <!-- Card Header (Always Visible) -->
                        <div class="hologram-header">
                            <div class="hologram-icon">
                                <i class="fas <?php echo $service['icon']; ?>"></i>
                            </div>
                            <div class="hologram-title-wrap">
                                <h4 class="hologram-title"><?php echo $service['title']; ?></h4>
                                <p class="hologram-summary"><?php echo $service['summary']; ?></p>
                            </div>
                            <!-- Mobile Expand Toggle -->
                            <div class="hologram-toggle">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                        
                        <!-- Expandable Content (Mobile: Hidden by default, Desktop: Slides in on hover) -->
                        <div class="hologram-body">
                            <p class="hologram-description"><?php echo $service['description']; ?></p>
                            <div class="hologram-features">
                                <?php foreach ($service['features'] as $featureIndex => $feature): ?>
                                <div class="hologram-feature-item" style="--feature-index: <?php echo $featureIndex; ?>">
                                    <i class="fas fa-check"></i>
                                    <span><?php echo $feature; ?></span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section - The Blueprint -->
    <section class="cta-blueprint-section">
        <!-- Blueprint Grid Background -->
        <div class="blueprint-bg">
            <div class="blueprint-grid"></div>
            <div class="blueprint-lines">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <!-- Wireframe shapes -->
                    <rect x="5%" y="10%" width="120" height="80" fill="none" stroke="rgba(253,215,40,0.15)" stroke-width="1" rx="8"/>
                    <circle cx="85%" cy="25%" r="45" fill="none" stroke="rgba(253,215,40,0.1)" stroke-width="1"/>
                    <polygon points="15,180 75,130 135,180" fill="none" stroke="rgba(253,215,40,0.12)" stroke-width="1" transform="translate(50, 50)"/>
                    <rect x="70%" y="60%" width="100" height="60" fill="none" stroke="rgba(253,215,40,0.1)" stroke-width="1" rx="4" transform="rotate(-15, 75%, 70%)"/>
                    <line x1="20%" y1="80%" x2="40%" y2="60%" stroke="rgba(253,215,40,0.08)" stroke-width="1" stroke-dasharray="5,5"/>
                    <line x1="60%" y1="85%" x2="80%" y2="75%" stroke="rgba(253,215,40,0.08)" stroke-width="1" stroke-dasharray="5,5"/>
                </svg>
            </div>
        </div>
        
        <div class="container">
            <div class="blueprint-content text-center" data-aos="fade-up">
                <h3 class="blueprint-title"><?php echo htmlspecialchars($svc_cta['content_title'] ?? 'Need a Custom Solution?'); ?></h3>
                <p class="blueprint-text"><?php echo htmlspecialchars($svc_cta['content_body'] ?? 'We understand that every business is unique. Let\'s discuss your specific needs and create a tailored solution for you.'); ?></p>
                <a href="<?php echo htmlspecialchars($svc_cta['extra']['button_link'] ?? 'contact.php'); ?>" class="btn-build-package">
                    <span class="btn-text"><?php echo htmlspecialchars($svc_cta['extra']['button_text'] ?? 'Build Your Package'); ?></span>
                    <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
