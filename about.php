<?php 
$page_title = 'About Us';
include 'includes/header.php'; 

// Get statistics from CRM (auto-sync)
$crm_stats = getStatisticsFromDB();
$stats_map = [];
foreach ($crm_stats as $stat) {
    $stats_map[$stat['stat_key'] ?? $stat['label']] = $stat;
}

// Get all about page content from CMS (auto-sync)
$about_content = getPageContent('about');
$hero = $about_content['hero'] ?? null;
$about_card = $about_content['about_card'] ?? null;
$who_we_are = $about_content['who_we_are'] ?? null;
$join_us = $about_content['join_us'] ?? null;
$team_sec = $about_content['team_section'] ?? null;
$testimonials_sec = $about_content['testimonials_section'] ?? null;
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
                            <?php echo htmlspecialchars($hero['extra']['eyebrow'] ?? 'Our Story'); ?>
                        </div>
                        <h1 class="about-hero-title">
                            <?php echo htmlspecialchars($hero['content_title'] ?? 'We craft brands'); ?><br>
                            <span class="about-hero-accent"><?php echo htmlspecialchars($hero['content_subtitle'] ?? 'that resonate.'); ?></span>
                        </h1>
                        <p class="about-hero-desc"><?php echo htmlspecialchars($hero['content_body'] ?? 'A young, fearless crew of creatives from Kolkata — blending strategy, design, and technology to make businesses unforgettable.'); ?></p>
                        
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
                            <img src="<?php echo htmlspecialchars($hero['extra']['hero_image'] ?? 'assets/images/about-hero-dark.png'); ?>" 
                                 alt="Digital craftsmanship - creative design studio" 
                                 class="about-hero-img">
                        </div>
                        <!-- Floating badge -->
                        <div class="about-floating-badge" data-aos="fade-up" data-aos-delay="500">
                            <i class="fas fa-palette"></i>
                            <span><?php echo htmlspecialchars($hero['extra']['badge_text'] ?? 'Since 2018'); ?></span>
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
                        <span class="about-badge"><?php echo htmlspecialchars($about_card['content_subtitle'] ?? 'Behind The Chisel'); ?></span>
                        <h2 class="about-headline"><?php echo htmlspecialchars($about_card['content_title'] ?? 'Who We Really Are'); ?></h2>
                        <?php if (!empty($about_card['content_body'])): ?>
                            <?php echo $about_card['content_body']; ?>
                        <?php else: ?>
                        <p>At Kalpanik, creativity isn't just our passion – it's our heartbeat. We're a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p>
                        <p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>
                        <?php endif; ?>
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
                        <img src="<?php echo htmlspecialchars($who_we_are['content_image'] ?? 'assets/images/digital-desk-bento.png'); ?>" 
                             alt="Our Digital Workspace - VS Code, Sketchbook with Coffee, and Figma UI Kit" 
                             class="bento-collage">
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <h2><?php echo htmlspecialchars($who_we_are['content_title'] ?? 'Who we are'); ?></h2>
                    <?php if (!empty($who_we_are['content_body'])): ?>
                        <?php echo $who_we_are['content_body']; ?>
                    <?php else: ?>
                    <p>We're your digital dream team – young, inventive, and fearless. <a href="#" class="highlight-link">Kalpanik</a> blends strategy, creativity, and technology to create unique digital experiences that resonate.</p>
                    <p>Our diverse squad includes strategists who live for analytics, creative designers who turn pixels into perfection, and social media wizards who keep trends on a constant watch. When you partner with us, expect authenticity, creative flair, and results-driven creativity.</p>
                    <p>Founded by Suman Kundu and Souvik Das, we bring together years of experience in graphic design, branding, and digital marketing to help businesses stand out in the crowded digital landscape.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet The Creators Section — Same as Homepage -->
    <section class="creators-section-v2" id="team">
        <div class="container">
            <div class="creators-v2-header">
                <span class="v2-pill">● Our Team</span>
                <h2 class="v2-heading"><?php echo htmlspecialchars($team_sec['content_title'] ?? 'Meet The Creators'); ?></h2>
                <p class="v2-subtext"><?php echo htmlspecialchars($team_sec['content_body'] ?? 'Two dreamers who turned their passion into your brand\'s success story'); ?></p>
            </div>

            <div class="creators-v2-grid">
                <?php foreach ($team_members as $index => $member): ?>
                <div class="creator-v2-card grid-reveal-item">
                    <div class="creator-v2-img">
                        <img src="<?php echo $member['image_pro'] ?? $member['image']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" loading="lazy">
                    </div>
                    <div class="creator-v2-info">
                        <h3 class="creator-v2-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p class="creator-v2-role"><?php echo htmlspecialchars($member['position']); ?></p>
                    </div>
                    <?php if (!empty($member['linkedin'])): ?>
                    <div class="creator-v2-socials">
                        <a href="<?php echo $member['linkedin']; ?>" class="creator-v2-social" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section — Same Peek Slider as Homepage -->
    <?php if (!empty($testimonials)): ?>
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge"><?php echo htmlspecialchars($testimonials_sec['content_subtitle'] ?? 'Client Love'); ?></span>
                <h2 class="section-title"><?php echo htmlspecialchars($testimonials_sec['content_title'] ?? 'What Our Clients Say'); ?></h2>
                <p class="section-subtitle"><?php echo htmlspecialchars($testimonials_sec['content_body'] ?? 'Real feedback from real partners who trusted us with their brands'); ?></p>
            </div>
        </div>
        <div class="testimonials-slider-wrapper">
            <button class="testimonial-arrow testimonial-arrow-prev" aria-label="Previous testimonial">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="testimonials-peek-track">
                <?php foreach ($testimonials as $index => $testimonial): ?>
                <div class="testimonial-peek-card <?php echo $index === 0 ? 'is-center' : ''; ?>">
                    <div class="quote-icon">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <p class="testimonial-text">"<?php echo htmlspecialchars($testimonial['testimonial_text'] ?? ''); ?>"</p>
                    <div class="testimonial-rating">
                        <?php
                        $rating = $testimonial['rating'] ?? 5;
                        for ($i = 0; $i < 5; $i++):
                        ?>
                        <i class="fas fa-star <?php echo $i < $rating ? 'filled' : ''; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="testimonial-divider"></div>
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
            <button class="testimonial-arrow testimonial-arrow-next" aria-label="Next testimonial">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>
    <?php endif; ?>

<?php include 'includes/footer.php'; ?>
