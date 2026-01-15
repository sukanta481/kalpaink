<?php 
$page_title = 'Services';
include 'includes/header.php'; 

// Extended services list for the services page
$detailed_services = [
    [
        'id' => 'graphics',
        'icon' => 'fa-palette',
        'title' => 'Graphics Design',
        'description' => 'From stunning logos to complete visual identities, our graphics design team creates eye-catching visuals that capture your brand essence and leave lasting impressions.',
        'features' => ['Logo Design', 'Business Cards', 'Brochures & Flyers', 'Social Media Graphics', 'Infographics', 'Packaging Design']
    ],
    [
        'id' => 'branding',
        'icon' => 'fa-bullhorn',
        'title' => 'Brand Identity',
        'description' => 'Build a memorable brand with consistent visual identity across all touchpoints. We create comprehensive brand guidelines that ensure your brand stands out.',
        'features' => ['Brand Strategy', 'Visual Identity System', 'Brand Guidelines', 'Rebranding', 'Brand Collateral', 'Brand Messaging']
    ],
    [
        'id' => 'smm',
        'icon' => 'fa-share-nodes',
        'title' => 'Social Media Marketing',
        'description' => 'Strategic social media campaigns that engage audiences and drive conversions. We manage your social presence across all major platforms.',
        'features' => ['Content Strategy', 'Community Management', 'Paid Social Ads', 'Influencer Marketing', 'Analytics & Reporting', 'Campaign Management']
    ],
    [
        'id' => 'web',
        'icon' => 'fa-code',
        'title' => 'Web Development',
        'description' => 'Modern, responsive websites that deliver exceptional user experiences. From landing pages to complex web applications, we build it all.',
        'features' => ['Custom Website Design', 'E-commerce Solutions', 'WordPress Development', 'Web Applications', 'Mobile Responsive', 'Maintenance & Support']
    ],
    [
        'id' => 'seo',
        'icon' => 'fa-magnifying-glass',
        'title' => 'SEO Services',
        'description' => 'Improve your search rankings and drive organic traffic to your website. Our SEO experts use proven strategies to boost your online visibility.',
        'features' => ['Keyword Research', 'On-Page SEO', 'Technical SEO', 'Link Building', 'Local SEO', 'SEO Audits']
    ],
    [
        'id' => 'content',
        'icon' => 'fa-pen-nib',
        'title' => 'Content Marketing',
        'description' => 'Compelling content that tells your story and connects with your audience. We create content that educates, entertains, and converts.',
        'features' => ['Blog Writing', 'Copywriting', 'Video Content', 'Email Marketing', 'Content Strategy', 'eBooks & Whitepapers']
    ],
    [
        'id' => 'print',
        'icon' => 'fa-print',
        'title' => 'Print Design',
        'description' => 'High-quality print materials that make a lasting impression. From business cards to large format displays, we handle all your print needs.',
        'features' => ['Brochures', 'Posters & Banners', 'Business Stationery', 'Catalogs', 'Magazines', 'Signage']
    ],
    [
        'id' => 'video',
        'icon' => 'fa-video',
        'title' => 'Video Production',
        'description' => 'Engaging video content that captures attention and drives action. From promotional videos to animations, we bring your vision to life.',
        'features' => ['Corporate Videos', 'Motion Graphics', 'Product Videos', 'Social Media Videos', 'Explainer Videos', 'Video Editing']
    ]
];
?>

    <!-- Services Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="placeholder-image" style="height: 250px; border-radius: 15px;">
                        <i class="fas fa-laptop-code" style="font-size: 4rem;"></i>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="bg-white p-4 rounded-4">
                        <h2 class="mb-3">See What Happens When Creativity Meets Purpose</h2>
                        <p class="mb-0">Every project we take on is a collision of bold thinking and meaningful intent. These are stories where redefined imagination, strategy, and creativity are transformed into impact.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive digital solutions tailored to your needs</p>
            </div>
            
            <div class="row g-4">
                <?php foreach ($detailed_services as $index => $service): ?>
                <div class="col-lg-6" id="<?php echo $service['id']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4 + 1) * 100; ?>">
                    <div class="service-card h-100" style="text-align: left; padding: 40px;">
                        <div class="d-flex align-items-start gap-4">
                            <div class="service-icon" style="flex-shrink: 0;">
                                <i class="fas <?php echo $service['icon']; ?>"></i>
                            </div>
                            <div>
                                <h4 class="service-title mb-3"><?php echo $service['title']; ?></h4>
                                <p class="service-description mb-3"><?php echo $service['description']; ?></p>
                                <div class="row g-2">
                                    <?php foreach ($service['features'] as $feature): ?>
                                    <div class="col-6">
                                        <span class="d-flex align-items-center gap-2" style="font-size: 0.9rem;">
                                            <i class="fas fa-check text-yellow"></i>
                                            <?php echo $feature; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h3 class="cta-title">Need a Custom Solution?</h3>
                    <p class="cta-text">We understand that every business is unique. Let's discuss your specific needs and create a tailored solution for you.</p>
                </div>
                <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                    <a href="contact.php" class="btn btn-white">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
