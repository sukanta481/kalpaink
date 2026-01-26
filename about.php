<?php 
$page_title = 'About Us';
include 'includes/header.php'; 
?>

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Hero Visual: Sculpting Concept -->
                    <div class="about-hero-visual">
                        <div class="sculpt-concept">
                            <div class="stone-block">
                                <i class="fas fa-cube"></i>
                            </div>
                            <div class="chisel-spark">
                                <span></span><span></span><span></span>
                            </div>
                            <div class="crown-emerging">
                                <i class="fas fa-crown"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Card -->
    <section class="section-padding" style="padding-top: 0;">
        <div class="container">
            <div class="about-card" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-8">
                        <span class="about-badge">Our Story</span>
                        <h2 class="about-headline">Behind The Chisel</h2>
                        <p>At Kalpoink, creativity isn't just our passion – it's our heartbeat. We're a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p>
                        <p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>
                    </div>
                </div>
                
                <!-- Stats Grid with Numbers -->
                <div class="row mt-5 stats-grid">
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
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="who-we-are">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5" data-aos="fade-right">
                    <div class="who-we-are-image workspace-image">
                        <!-- Creative Workspace Visual -->
                        <div class="workspace-mockup">
                            <div class="desk-items">
                                <div class="item laptop"><i class="fas fa-laptop-code"></i></div>
                                <div class="item coffee"><i class="fas fa-mug-hot"></i></div>
                                <div class="item pencil"><i class="fas fa-pencil-ruler"></i></div>
                                <div class="item palette"><i class="fas fa-palette"></i></div>
                            </div>
                            <p class="workspace-label">Our Creative Space</p>
                        </div>
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
                        <a href="contact.php" class="btn btn-white">Get In Touch</a>
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

<?php include 'includes/footer.php'; ?>
