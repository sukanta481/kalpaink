<?php 
$page_title = 'Home';
include 'includes/header.php'; 

// Get hero slides from CRM (auto-sync)
$crm_hero_slides = getHeroSlides();

// Default gradient icons for slides without images
$default_gradients = [
    ['gradient-1', 'fa-palette'],
    ['gradient-2', 'fa-code'],
    ['gradient-3', 'fa-bullhorn'],
    ['gradient-5', 'fa-gem'],
    ['gradient-6', 'fa-swatchbook'],
    ['gradient-7', 'fa-layer-group'],
];
?>

    <!-- Hero Section with Slider -->
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <?php if (!empty($crm_hero_slides)): ?>
                    <?php foreach ($crm_hero_slides as $index => $slide): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="Slide <?php echo $index + 1; ?>"></button>
                    <?php endforeach; ?>
                <?php else: ?>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <?php endif; ?>
            </div>

            <!-- Carousel Slides -->
            <div class="carousel-inner">
                <?php if (!empty($crm_hero_slides)): ?>
                    <?php foreach ($crm_hero_slides as $index => $slide): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6 hero-text-col">
                                <div class="hero-content" <?php echo $index === 0 ? 'data-aos="fade-right"' : ''; ?>>
                                    <span class="hero-badge"><?php echo htmlspecialchars($slide['badge_text'] ?? ''); ?></span>
                                    <h1 class="hero-title"><?php echo htmlspecialchars($slide['title']); ?></h1>
                                    <p class="hero-subtitle"><?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?></p>
                                    <div class="hero-buttons">
                                        <?php if (!empty($slide['button1_text'])): ?>
                                        <a href="<?php echo htmlspecialchars($slide['button1_link'] ?? 'contact.php'); ?>" class="btn btn-ghost-white btn-lg"><?php echo htmlspecialchars($slide['button1_text']); ?></a>
                                        <?php endif; ?>
                                        <?php if (!empty($slide['button2_text'])): ?>
                                        <a href="<?php echo htmlspecialchars($slide['button2_link'] ?? 'services.php'); ?>" class="btn btn-outline-white btn-lg"><?php echo htmlspecialchars($slide['button2_text']); ?></a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-visual-col">
                                <div class="hero-masonry" data-parallax-container>
                                    <?php 
                                    // Check if slide has uploaded images
                                    $hasImages = !empty($slide['image1']) || !empty($slide['image2']) || !empty($slide['image3']);
                                    
                                    if ($hasImages): 
                                        $images = [$slide['image1'] ?? '', $slide['image2'] ?? '', $slide['image3'] ?? ''];
                                        $parallax = ['0.03', '0.05', '0.04'];
                                        foreach ($images as $imgIndex => $img): 
                                            if (!empty($img)):
                                    ?>
                                    <div class="masonry-item item-<?php echo $imgIndex + 1; ?>" data-parallax="<?php echo $parallax[$imgIndex]; ?>">
                                        <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>">
                                    </div>
                                    <?php 
                                            endif;
                                        endforeach; 
                                    else: 
                                        // Use placeholder gradients
                                        $gradientSet = $index % 2 === 0 ? [['gradient-1', 'fa-palette'], ['gradient-2', 'fa-code'], ['gradient-3', 'fa-bullhorn']] : [['gradient-5', 'fa-gem'], ['gradient-6', 'fa-swatchbook'], ['gradient-7', 'fa-layer-group']];
                                        $parallax = ['0.03', '0.05', '0.04'];
                                        foreach ($gradientSet as $gIndex => $gradient):
                                    ?>
                                    <div class="masonry-item item-<?php echo $gIndex + 1; ?>" data-parallax="<?php echo $parallax[$gIndex]; ?>">
                                        <div class="masonry-placeholder <?php echo $gradient[0]; ?>">
                                            <i class="fas <?php echo $gradient[1]; ?>"></i>
                                        </div>
                                    </div>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <!-- Fallback: Default slides if no CRM data -->
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6 hero-text-col">
                                <div class="hero-content" data-aos="fade-right">
                                    <span class="hero-badge">Creative Design Studio</span>
                                    <h1 class="hero-title">Reimagining with Purpose</h1>
                                    <p class="hero-subtitle">Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing.</p>
                                    <div class="hero-buttons">
                                        <a href="contact.php" class="btn btn-ghost-white btn-lg">Get Quote</a>
                                        <a href="services.php" class="btn btn-outline-white btn-lg">Services</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-visual-col">
                                <div class="hero-masonry" data-parallax-container>
                                    <div class="masonry-item item-1" data-parallax="0.03">
                                        <div class="masonry-placeholder gradient-1"><i class="fas fa-palette"></i></div>
                                    </div>
                                    <div class="masonry-item item-2" data-parallax="0.05">
                                        <div class="masonry-placeholder gradient-2"><i class="fas fa-code"></i></div>
                                    </div>
                                    <div class="masonry-item item-3" data-parallax="0.04">
                                        <div class="masonry-placeholder gradient-3"><i class="fas fa-bullhorn"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Carousel Controls (hidden on mobile) -->
            <button class="carousel-control-prev hero-nav" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next hero-nav" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Mobile Swipe Deck (visible only on mobile/tablet) -->
        <div class="mobile-hero-deck">
            <div class="swipe-deck-container" id="swipeDeck">
                <div class="swipe-card" data-index="0" data-service="logo">
                    <div class="swipe-card-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h4 class="swipe-card-title">Logo Design</h4>
                    <p class="swipe-card-desc">Unique brand identities that make lasting impressions</p>
                </div>
                <div class="swipe-card" data-index="1" data-service="web">
                    <div class="swipe-card-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h4 class="swipe-card-title">Web Development</h4>
                    <p class="swipe-card-desc">Modern, responsive websites that convert visitors</p>
                </div>
                <div class="swipe-card" data-index="2" data-service="marketing">
                    <div class="swipe-card-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h4 class="swipe-card-title">Digital Marketing</h4>
                    <p class="swipe-card-desc">Strategic campaigns that grow your online presence</p>
                </div>
            </div>
            <div class="swipe-indicators">
                <span class="swipe-indicator active" data-index="0"></span>
                <span class="swipe-indicator" data-index="1"></span>
                <span class="swipe-indicator" data-index="2"></span>
            </div>
            <div class="swipe-hint">
                <i class="fas fa-hand-point-left"></i> Swipe to explore <i class="fas fa-hand-point-right"></i>
            </div>
        </div>
    </section>

    <!-- Client Trust Bar - Infinite Marquee -->
    <section class="client-marquee">
        <div class="marquee-track">
            <div class="marquee-content">
                <span class="client-logo-item">Acme Corp</span>
                <span class="client-logo-item">TechFlow</span>
                <span class="client-logo-item">Brandify</span>
                <span class="client-logo-item">DigitalPro</span>
                <span class="client-logo-item">MediaMax</span>
                <span class="client-logo-item">StartupXYZ</span>
                <span class="client-logo-item">CloudNine</span>
                <span class="client-logo-item">Innovate Inc</span>
                <!-- Duplicate for seamless loop -->
                <span class="client-logo-item">Acme Corp</span>
                <span class="client-logo-item">TechFlow</span>
                <span class="client-logo-item">Brandify</span>
                <span class="client-logo-item">DigitalPro</span>
                <span class="client-logo-item">MediaMax</span>
                <span class="client-logo-item">StartupXYZ</span>
                <span class="client-logo-item">CloudNine</span>
                <span class="client-logo-item">Innovate Inc</span>
            </div>
        </div>
    </section>

    <!-- Welcome Section - Fusion Concept -->
    <section class="welcome-section section-padding" id="about">
        <div class="container">
            <div class="welcome-card" data-aos="fade-up">
                <!-- Mobile: Sandwich Layout (Headline → Image → Content) -->
                <div class="welcome-header-mobile d-lg-none text-center">
                    <span class="welcome-badge">Who We Are</span>
                    <h2 class="fusion-headline">We <span class="text-gradient-sculpt">Sculpt</span> Brands.</h2>
                    <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 welcome-image-col">
                        <div class="welcome-image fusion-image image-comparison" data-comparison>
                            <div class="comparison-container">
                                <img src="assets/images/about-fusion.png" alt="Raw Concept to Brand Creation - We transform ideas into masterpieces" class="comparison-image">
                                <div class="comparison-overlay"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 welcome-text-col">
                        <!-- Desktop: Show header here -->
                        <div class="welcome-header-desktop d-none d-lg-block">
                            <span class="welcome-badge">Who We Are</span>
                            <h2 class="fusion-headline">We <span class="text-gradient-sculpt">Sculpt</span> Brands.</h2>
                            <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                        </div>
                        <p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                        <p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we're the creative studio that brings visions to life.</p>
                        <div class="welcome-stats">
                            <div class="stat-item">
                                <span class="stat-number" data-count="150" data-suffix="+">0+</span>
                                <span class="stat-label">Brands Sculpted</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" data-count="5" data-suffix="+">0+</span>
                                <span class="stat-label">Years Crafting</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" data-count="98" data-suffix="%">0%</span>
                                <span class="stat-label">Happy Clients</span>
                            </div>
                        </div>
                        <a href="about.php" class="btn btn-primary btn-magnetic">Discover Our Story</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section - Horizontal Gallery -->
    <section class="services-section section-padding" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive digital solutions to elevate your brand</p>
            </div>
            
            <!-- Desktop Grid / Mobile Horizontal Scroll -->
            <div class="services-gallery" data-aos="fade-up">
                <div class="services-track">
                    <?php foreach ($services as $index => $service): ?>
                    <div class="service-card-wrapper">
                        <div class="service-card">
                            <div class="service-icon-3d">
                                <i class="fas <?php echo $service['icon']; ?>"></i>
                            </div>
                            <h4 class="service-title"><?php echo $service['title']; ?></h4>
                            <p class="service-description"><?php echo $service['description']; ?></p>
                            <a href="services.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mobile Scroll Indicator -->
            <div class="services-scroll-hint d-lg-none">
                <i class="fas fa-hand-point-left"></i> Swipe for more <i class="fas fa-hand-point-right"></i>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="services.php" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Case Studies Section - Interactive Gallery -->
    <section class="case-studies-section section-padding" id="portfolio">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Case Studies</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Some of our recent creative work</p>
            </div>
            
            <!-- Pinterest-Style Masonry Layout -->
            <?php 
            $displayed_cases = array_slice($case_studies, 0, 8);
            
            // Masonry pattern for 8 items - fills grid perfectly
            $masonry_sizes = ['tall', 'normal', 'wide', 'normal', 'tall', 'normal', 'wide', 'normal'];
            ?>
            <div class="case-gallery" data-aos="fade-up">
                <div class="case-masonry-grid">
                    <?php foreach ($displayed_cases as $index => $case): ?>
                    <?php $size_class = $masonry_sizes[$index]; ?>
                    <div class="case-masonry-item case-<?php echo $size_class; ?>">
                        <div class="case-card">
                            <div class="case-card-image">
                                <img src="<?php echo $case['image']; ?>" alt="<?php echo $case['title']; ?>" loading="lazy">
                                <div class="case-card-overlay">
                                    <a href="case-studies.php" class="btn btn-white">View Case Study</a>
                                </div>
                            </div>
                            <div class="case-card-content">
                                <h5 class="case-card-title"><?php echo $case['title']; ?></h5>
                                <div class="case-card-tags">
                                    <?php foreach ($case['tags'] as $tag): ?>
                                    <span class="case-tag"><?php echo $tag; ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Mobile Scroll Indicator -->
            <div class="case-scroll-hint d-lg-none">
                <i class="fas fa-hand-point-left"></i> Swipe to explore <i class="fas fa-hand-point-right"></i>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="case-studies.php" class="btn btn-primary">View All Projects</a>
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

    <!-- FAQ Section - Split Screen -->
    <section class="faq-section section-padding">
        <div class="container">
            <div class="row">
                <!-- Left: Sticky Headline -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="faq-sticky-header" data-aos="fade-right">
                        <div class="faq-icon-float">
                            <i class="fas fa-question"></i>
                        </div>
                        <h2 class="faq-headline">Got Questions?</h2>
                        <p class="faq-subtext">We've got answers. Find quick solutions to your most common queries.</p>
                        <a href="contact.php" class="faq-contact-link">
                            <span>Still have questions?</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Right: Accordion -->
                <div class="col-lg-7">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <?php foreach ($faqs as $index => $faq): ?>
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 50; ?>">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $index; ?>">
                                    <?php echo $faq['question']; ?>
                                </button>
                            </h2>
                            <div id="faq<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo $faq['answer']; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Holographic CTA Section -->
    <section class="cta-holographic">
        <div class="cta-glow-bg"></div>
        <div class="container">
            <div class="cta-content text-center" data-aos="zoom-in" data-aos-duration="1000">
                <h2 class="cta-headline">Ready to Sculpt Your Legacy?</h2>
                <p class="cta-subtext">Let's transform your vision into a digital masterpiece</p>
                <a href="contact.php" class="cta-pulse-btn">
                    <span class="btn-text">Let's Create Together</span>
                    <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
        </div>
        <!-- Floating particles for depth -->
        <div class="cta-particles">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
