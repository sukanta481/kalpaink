<?php 
$page_title = 'About Us';
include 'includes/header.php'; 

// Get statistics from CRM (auto-sync)
$crm_stats = getStatisticsFromDB();
$stats_map = [];
foreach ($crm_stats as $stat) {
    $stats_map[$stat['stat_key'] ?? $stat['label']] = $stat;
}
?>

    <!-- About Hero Section -->
    <section class="about-hero-v3">
        <!-- Atmosphere -->
        <div class="about-hero-atm">
            <div class="about-hero-grain"></div>
            <div class="about-hero-orb about-hero-orb--1"></div>
            <div class="about-hero-orb about-hero-orb--2"></div>
            <div class="about-hero-grid"></div>
        </div>

        <div class="container position-relative" style="z-index:2;">
            <div class="row align-items-center min-vh-about-hero">
                <!-- Text Column -->
                <div class="col-lg-7" data-aos="fade-up">
                    <div class="about-hero-content">
                        <div class="about-hero-eyebrow">
                            <span class="eyebrow-dot"></span>
                            Our Story
                        </div>
                        <h1 class="about-hero-title">
                            We craft brands<br>
                            <span class="about-hero-accent">that resonate.</span>
                        </h1>
                        <p class="about-hero-desc">A young, fearless crew of creatives from Kolkata — blending strategy, design, and technology to make businesses unforgettable.</p>
                        
                        <!-- Inline Stats -->
                        <div class="about-hero-stats">
                            <?php if (!empty($crm_stats)): ?>
                                <?php foreach (array_slice($crm_stats, 0, 3) as $stat): ?>
                                <div class="about-hero-stat">
                                    <span class="about-hero-stat-val"><?php echo htmlspecialchars($stat['value'] . ($stat['suffix'] ?? '')); ?></span>
                                    <span class="about-hero-stat-lbl"><?php echo htmlspecialchars($stat['label']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="about-hero-stat">
                                    <span class="about-hero-stat-val">200+</span>
                                    <span class="about-hero-stat-lbl">Projects</span>
                                </div>
                                <div class="about-hero-stat">
                                    <span class="about-hero-stat-val">150+</span>
                                    <span class="about-hero-stat-lbl">Clients</span>
                                </div>
                                <div class="about-hero-stat">
                                    <span class="about-hero-stat-val">8+</span>
                                    <span class="about-hero-stat-lbl">Years</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="col-lg-5" data-aos="fade-left" data-aos-delay="200">
                    <div class="about-hero-visual">
                        <div class="about-hero-img-card">
                            <img src="assets/images/about-hero-dark.png" 
                                 alt="Digital craftsmanship - creative design studio" 
                                 class="about-hero-img">
                        </div>
                        <!-- Floating badge -->
                        <div class="about-floating-badge" data-aos="fade-up" data-aos-delay="500">
                            <i class="fas fa-palette"></i>
                            <span>Since 2018</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Card -->
    <section class="section-padding" style="background: #f5f5f7;">
        <div class="container">
            <div class="about-card" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-8">
                        <span class="about-badge">Behind The Chisel</span>
                        <h2 class="about-headline">Who We Really Are</h2>
                        <p>At Kalpoink, creativity isn't just our passion – it's our heartbeat. We're a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p>
                        <p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>
                    </div>
                </div>
                
                <!-- Stats Grid with Numbers - Auto-sync from CRM -->
                <div class="row mt-5 stats-grid">
                    <?php if (!empty($crm_stats)): ?>
                        <?php foreach ($crm_stats as $index => $stat): ?>
                        <div class="col-md-3 col-6">
                            <div class="stat-card" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                                <span class="stat-number"><?php echo htmlspecialchars($stat['value'] . ($stat['suffix'] ?? '')); ?></span>
                                <p class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-md-3 col-6">
                        <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                            <span class="stat-number">150+</span>
                            <p class="stat-label">Projects Delivered</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                            <span class="stat-number">100%</span>
                            <p class="stat-label">In-House Team</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                            <span class="stat-number">5+</span>
                            <p class="stat-label">Years Experience</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
                            <span class="stat-number">24/7</span>
                            <p class="stat-label">Creative Support</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="digital-desk-image animate-slide-up" data-aos="fade-up" data-aos-duration="800">
                        <!-- Digital Desk Bento Grid - Real workspace imagery -->
                        <img src="assets/images/digital-desk-bento.png" 
                             alt="Our Digital Workspace - VS Code, Sketchbook with Coffee, and Figma UI Kit" 
                             class="bento-collage">
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <h2>Who we are</h2>
                    <p>We're your digital dream team – young, inventive, and fearless. <a href="#" class="highlight-link">Kalpoink</a> blends strategy, creativity, and technology to create unique digital experiences that resonate.</p>
                    <p>Our diverse squad includes strategists who live for analytics, creative designers who turn pixels into perfection, and social media wizards who keep trends on a constant watch. When you partner with us, expect authenticity, creative flair, and results-driven creativity.</p>
                    <p>Founded by Suman Kundu and Souvik Das, we bring together years of experience in graphic design, branding, and digital marketing to help businesses stand out in the crowded digital landscape.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Join Us Section -->
    <section class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="join-us-section" data-aos="fade-up">
                <div class="join-us-pattern"></div>
                <div class="row align-items-center position-relative">
                    <div class="col-lg-8">
                        <h3 class="cta-title">Why Work With Us?</h3>
                        <p class="mb-0">Ready to elevate your brand? If you're creative, passionate, and driven by innovation, Kalpoink is your perfect partner. Let's disrupt the digital world together!</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                        <a href="contact.php" class="btn btn-white btn-pulse-ripple">Get In Touch</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- The Creators Section -->
    <section class="creators-section section-padding">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <span class="section-badge" data-aos="fade-up">The Minds Behind The Magic</span>
                <h2 class="section-title" data-aos="fade-up" data-aos-delay="100">Meet The Creators</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">Two dreamers who turned their passion into your brand's success story</p>
            </div>
            
            <!-- Desktop: Cutout Style -->
            <div class="creators-showcase d-none d-lg-block" data-aos="fade-up">
                <div class="creators-grid">
                    <?php foreach ($team_members as $index => $member): ?>
                    <div class="creator-cutout" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 150; ?>">
                        <div class="creator-photo-wrapper">
                            <!-- Flip Card -->
                            <div class="creator-flip-card">
                                <div class="flip-card-inner">
                                    <div class="flip-card-front">
                                        <img src="<?php echo $member['image_pro']; ?>" alt="<?php echo $member['name']; ?> - Professional">
                                        <div class="photo-label">Professional Mode 🎯</div>
                                    </div>
                                    <div class="flip-card-back">
                                        <img src="<?php echo $member['image_fun']; ?>" alt="<?php echo $member['name']; ?> - Creative">
                                        <div class="photo-label">Creative Mode 🎨</div>
                                    </div>
                                </div>
                            </div>
                            <!-- Decorative Elements -->
                            <div class="creator-decoration">
                                <span class="deco-circle"></span>
                                <span class="deco-dots"></span>
                            </div>
                        </div>
                        <div class="creator-info">
                            <h3 class="creator-name"><?php echo $member['name']; ?></h3>
                            <p class="creator-role"><?php echo $member['position']; ?></p>
                            <p class="creator-tagline">"<?php echo $member['tagline']; ?>"</p>
                            <a href="<?php echo $member['linkedin']; ?>" class="creator-linkedin" target="_blank">
                                <i class="fab fa-linkedin-in"></i> Connect
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="hover-hint text-center mt-4"><i class="fas fa-hand-pointer"></i> Hover to see our creative side!</p>
            </div>
            
            <!-- Mobile: Flip Cards (Tap to reveal Creative Side) -->
            <div class="creators-mobile d-lg-none">
                <?php foreach ($team_members as $index => $member): ?>
                <div class="creator-flip-mobile" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="flip-mobile-inner">
                        <!-- Front: Professional Side -->
                        <div class="flip-mobile-front">
                            <div class="flip-avatar">
                                <img src="<?php echo $member['image_pro'] ?? $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                            </div>
                            <div class="flip-content">
                                <h4 class="flip-name"><?php echo $member['name']; ?></h4>
                                <p class="flip-role"><?php echo $member['position']; ?></p>
                                <p class="flip-tagline">"<?php echo $member['tagline']; ?>"</p>
                            </div>
                            <div class="flip-hint">
                                <i class="fas fa-hand-pointer"></i> Tap to flip
                            </div>
                        </div>
                        <!-- Back: Creative/Fun Side -->
                        <div class="flip-mobile-back">
                            <div class="flip-avatar">
                                <img src="<?php echo $member['image_fun'] ?? $member['image_pro'] ?? $member['image']; ?>" alt="<?php echo $member['name']; ?> - Creative">
                            </div>
                            <div class="flip-content">
                                <h4 class="flip-name"><?php echo $member['name']; ?></h4>
                                <p class="flip-role flip-role-fun">
                                    <?php 
                                    // Fun titles based on position
                                    $fun_titles = [
                                        'Co-Founder & Creative Director' => '✨ Chief Visionary & Pixel Perfectionist',
                                        'Co-Founder & Strategy Lead' => '🚀 Master of Ideas & Caffeine Addict'
                                    ];
                                    echo $fun_titles[$member['position']] ?? '🎨 Creative Genius';
                                    ?>
                                </p>
                                <p class="flip-fact">
                                    <?php 
                                    // Fun facts
                                    $fun_facts = [
                                        0 => "☕ Runs on coffee & creative chaos. Has probably redesigned this card 47 times.",
                                        1 => "🎯 Believes every brand has a story. Also believes pineapple belongs on pizza."
                                    ];
                                    echo $fun_facts[$index] ?? "🎨 Making magic happen, one pixel at a time!";
                                    ?>
                                </p>
                            </div>
                            <a href="<?php echo $member['linkedin']; ?>" class="flip-linkedin" target="_blank">
                                <i class="fab fa-linkedin-in"></i> Let's Connect!
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <p class="tap-hint text-center mt-3"><i class="fas fa-sync-alt"></i> Tap cards to see our creative side!</p>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <?php if (!empty($testimonials)): ?>
    <section class="testimonials-section section-padding">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center mb-5">
                <span class="section-badge" data-aos="fade-up">Client Love</span>
                <h2 class="section-title" data-aos="fade-up" data-aos-delay="100">What Our Clients Say</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="200">Real feedback from real partners who trusted us with their brands</p>
            </div>
            
            <!-- Testimonials - Static Card with Sliding Content -->
            <div class="testimonials-wrapper" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-static-card">
                    <!-- Static Quote Icon -->
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    
                    <!-- Sliding Content -->
                    <div class="testimonials-slider">
                        <?php foreach ($testimonials as $index => $testimonial): ?>
                        <div class="testimonial-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                            <p class="testimonial-text">"<?php echo htmlspecialchars($testimonial['testimonial_text'] ?? ''); ?>"</p>
                            <div class="testimonial-rating">
                                <?php 
                                $rating = $testimonial['rating'] ?? 5;
                                for ($i = 0; $i < 5; $i++): 
                                ?>
                                <i class="fas fa-star <?php echo $i < $rating ? 'filled' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <?php if (!empty($testimonial['client_avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($testimonial['client_avatar']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name'] ?? ''); ?>">
                                    <?php else: ?>
                                    <div class="avatar-placeholder"><?php echo strtoupper(substr($testimonial['client_name'] ?? 'C', 0, 1)); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="author-info">
                                    <h4 class="author-name"><?php echo htmlspecialchars($testimonial['client_name'] ?? ''); ?></h4>
                                    <p class="author-position">
                                        <?php echo htmlspecialchars($testimonial['client_position'] ?? ''); ?>
                                        <?php if (!empty($testimonial['client_company'])): ?>
                                        <span class="author-company">at <?php echo htmlspecialchars($testimonial['client_company']); ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="testimonials-nav">
                    <button class="testimonial-nav-btn prev" aria-label="Previous testimonial">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="testimonials-dots"></div>
                    <button class="testimonial-nav-btn next" aria-label="Next testimonial">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include 'includes/footer.php'; ?>
