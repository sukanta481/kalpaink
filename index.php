<?php 
$page_title = 'Home';
include 'includes/header.php'; 
?>

    <!-- Hero Section with Slider -->
    <section class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>

            <!-- Carousel Slides -->
            <div class="carousel-inner">
                <!-- Slide 1 - Creative Design -->
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6 hero-text-col">
                                <div class="hero-content" data-aos="fade-right">
                                    <span class="hero-badge">Creative Design Studio</span>
                                    <h1 class="hero-title">Reimagining with Purpose</h1>
                                    <p class="hero-subtitle">Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing.</p>
                                    <div class="hero-buttons">
                                        <a href="contact.php" class="btn btn-dark btn-lg">Get Quote</a>
                                        <a href="services.php" class="btn btn-outline-dark btn-lg">Services</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-visual-col">
                                <!-- Masonry Grid with Parallax -->
                                <div class="hero-masonry" data-parallax-container>
                                    <div class="masonry-item item-1" data-parallax="0.03">
                                        <img src="uploads/portfolio_website.png" alt="Website Design Portfolio">
                                    </div>
                                    <div class="masonry-item item-2" data-parallax="0.05">
                                        <img src="uploads/portfolio_logo.png" alt="Logo Design Portfolio">
                                    </div>
                                    <div class="masonry-item item-3" data-parallax="0.04">
                                        <img src="uploads/portfolio_social.png" alt="Social Media Design">
                                    </div>
                                    <!-- <div class="masonry-item item-4" data-parallax="0.06">
                                        <img src="uploads/1st slider.jpeg" alt="Creative Design Work">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Digital Marketing -->
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6 hero-text-col">
                                <div class="hero-content">
                                    <span class="hero-badge">Digital Marketing</span>
                                    <h1 class="hero-title">Grow Your Digital Presence</h1>
                                    <p class="hero-subtitle">Strategic digital marketing to boost your brand visibility and drive measurable results.</p>
                                    <div class="hero-buttons">
                                        <a href="contact.php" class="btn btn-dark btn-lg">Get Quote</a>
                                        <a href="services.php" class="btn btn-outline-dark btn-lg">Services</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-visual-col">
                                <div class="hero-masonry" data-parallax-container>
                                    <div class="masonry-item item-1" data-parallax="0.03">
                                        <img src="uploads/portfolio_social.png" alt="Social Media Marketing">
                                    </div>
                                    <div class="masonry-item item-2" data-parallax="0.05">
                                        <img src="uploads/portfolio_website.png" alt="Website Design">
                                    </div>
                                    <div class="masonry-item item-3" data-parallax="0.04">
                                        <img src="uploads/portfolio_logo.png" alt="Brand Identity">
                                    </div>
                                    <!-- <div class="masonry-item item-4" data-parallax="0.06">
                                        <img src="uploads/1st slider.jpeg" alt="Creative Design">
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Brand Identity -->
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6 hero-text-col">
                                <div class="hero-content">
                                    <span class="hero-badge">Brand Identity</span>
                                    <h1 class="hero-title">Build Your Unique Brand</h1>
                                    <p class="hero-subtitle">Create a memorable brand identity from logos to complete brand guidelines.</p>
                                    <div class="hero-buttons">
                                        <a href="contact.php" class="btn btn-dark btn-lg">Get Quote</a>
                                        <a href="case-studies.php" class="btn btn-outline-dark btn-lg">Portfolio</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 hero-visual-col">
                                <div class="hero-masonry" data-parallax-container>
                                    <div class="masonry-item item-1" data-parallax="0.03">
                                        <div class="masonry-placeholder gradient-5">
                                            <i class="fas fa-gem"></i>
                                        </div>
                                    </div>
                                    <div class="masonry-item item-2" data-parallax="0.05">
                                        <div class="masonry-placeholder gradient-6">
                                            <i class="fas fa-swatchbook"></i>
                                        </div>
                                    </div>
                                    <div class="masonry-item item-3" data-parallax="0.04">
                                        <div class="masonry-placeholder gradient-7">
                                            <i class="fas fa-layer-group"></i>
                                        </div>
                                    </div>
                                    <!-- <div class="masonry-item item-4" data-parallax="0.06">
                                        <div class="masonry-placeholder gradient-8">
                                            <i class="fas fa-signature"></i>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <h2 class="fusion-headline">We <span class="text-yellow">Sculpt</span> Brands.</h2>
                    <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 welcome-image-col">
                        <div class="welcome-image fusion-image">
                            <img src="assets/images/about-fusion.png" alt="Raw Concept to Brand Creation - We transform ideas into masterpieces">
                        </div>
                    </div>
                    <div class="col-lg-7 welcome-text-col">
                        <!-- Desktop: Show header here -->
                        <div class="welcome-header-desktop d-none d-lg-block">
                            <span class="welcome-badge">Who We Are</span>
                            <h2 class="fusion-headline">We <span class="text-yellow">Sculpt</span> Brands.</h2>
                            <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                        </div>
                        <p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                        <p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we're the creative studio that brings visions to life.</p>
                        <div class="welcome-stats">
                            <div class="stat-item">
                                <span class="stat-number">150+</span>
                                <span class="stat-label">Brands Sculpted</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">5+</span>
                                <span class="stat-label">Years Crafting</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">98%</span>
                                <span class="stat-label">Happy Clients</span>
                            </div>
                        </div>
                        <a href="about.php" class="btn btn-primary">Discover Our Story</a>
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

    <!-- FAQ Section -->
    <section class="faq-section section-padding bg-light-gray">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Frequently Asked Questions</h2>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
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

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h3 class="cta-title">Ready to Transform Your Brand?</h3>
                    <p class="cta-text">Let's create something amazing together. Get in touch with us today!</p>
                </div>
                <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                    <a href="contact.php" class="btn btn-white">Get Started</a>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
