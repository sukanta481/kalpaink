<?php
$page_title = 'Home';
include 'includes/header.php';

// Get page content from CMS (auto-sync)
$home_content = getPageContent('home');
$home_svc = $home_content['services_section'] ?? null;
$home_cta = $home_content['cta'] ?? null;

// Get clients for marquee (auto-sync)
$marquee_clients = getClientsFromDB();
?>

    <!-- Hero Section - Royal Blue Carousel -->
    <section class="hero-section">
        <div class="hero-carousel" id="heroCarousel">
            <!-- Slide 1 — Brand Identity -->
            <div class="hero-slide hero-slide-bg-1 active" data-slide="0">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-inner">
                    <div class="hero-text-col">
                        <div class="hero-content">
                            <span class="hero-pill">Creative Design Studio</span>
                            <h1 class="hero-headline">
                                We Design Ideas<br>
                                That Think Before<br>
                                They Speak.
                            </h1>
                            <p class="hero-subtext">Branding &middot; Design &middot; VISUAL THINKING</p>
                            <div class="hero-buttons">
                                <a href="contact.php" class="hero-btn hero-btn-primary">Get Started</a>
                                <a href="services.php" class="hero-btn hero-btn-secondary">Our Services</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 — Digital Marketing -->
            <div class="hero-slide hero-slide-bg-2" data-slide="1">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-inner">
                    <div class="hero-text-col">
                        <div class="hero-content">
                            <span class="hero-pill">Social Media &amp; Growth</span>
                            <h1 class="hero-headline">
                                Reimagining<br>
                                Brands with<br>
                                Purpose
                            </h1>
                            <p class="hero-subtext">Strategy &middot; Content &middot; DIGITAL PRESENCE</p>
                            <div class="hero-buttons">
                                <a href="contact.php" class="hero-btn hero-btn-primary">Get Quote</a>
                                <a href="case-studies.php" class="hero-btn hero-btn-secondary">Case Studies</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3 — Web Development -->
            <div class="hero-slide hero-slide-bg-3" data-slide="2">
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-inner">
                    <div class="hero-text-col">
                        <div class="hero-content">
                            <span class="hero-pill">Web &amp; Digital Experience</span>
                            <h1 class="hero-headline">
                                Strategy First.<br>
                                Design<br>
                                Always.
                            </h1>
                            <p class="hero-subtext">Development &middot; SEO &middot; VISUAL EXPERIENCE</p>
                            <div class="hero-buttons">
                                <a href="case-studies.php" class="hero-btn hero-btn-primary">View Work</a>
                                <a href="contact.php" class="hero-btn hero-btn-secondary">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button class="hero-arrow hero-arrow-prev" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-arrow hero-arrow-next" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Navigation Dots -->
            <div class="hero-dots">
                <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
                <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
                <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </section>

    <!-- Trusted Brands Section -->
    <section class="trusted-brands">
        <div class="trusted-brands-header">
            <p class="trusted-brands-heading">Trusted by 250+ business worldwide</p>
            <span class="trusted-brands-divider"></span>
        </div>

        <?php
        $fallback_clients = [
            ['client_name' => 'Acme Corp', 'client_logo' => null],
            ['client_name' => 'TechFlow', 'client_logo' => null],
            ['client_name' => 'Brandify', 'client_logo' => null],
            ['client_name' => 'DigitalPro', 'client_logo' => null],
            ['client_name' => 'MediaMax', 'client_logo' => null],
            ['client_name' => 'StartupXYZ', 'client_logo' => null],
            ['client_name' => 'CloudNine', 'client_logo' => null],
            ['client_name' => 'Innovate Inc', 'client_logo' => null],
        ];
        $clients_list = !empty($marquee_clients) ? $marquee_clients : $fallback_clients;
        ?>

        <!-- Desktop: Static Grid -->
        <div class="brands-grid">
            <?php foreach ($clients_list as $mc): ?>
            <span class="brand-item">
                <?php if (!empty($mc['client_logo'])): ?>
                    <img src="<?php echo SITE_URL . '/' . $mc['client_logo']; ?>"
                         alt="<?php echo htmlspecialchars($mc['client_name']); ?>">
                <?php else: ?>
                    <?php echo htmlspecialchars($mc['client_name']); ?>
                <?php endif; ?>
            </span>
            <?php endforeach; ?>
        </div>

        <!-- Mobile: Marquee Strip -->
        <div class="brands-marquee">
            <div class="brands-marquee-track">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach ($clients_list as $mc): ?>
                    <span class="brand-item-mobile">
                        <?php if (!empty($mc['client_logo'])): ?>
                            <img src="<?php echo SITE_URL . '/' . $mc['client_logo']; ?>"
                                 alt="<?php echo htmlspecialchars($mc['client_name']); ?>">
                        <?php else: ?>
                            <?php echo htmlspecialchars($mc['client_name']); ?>
                        <?php endif; ?>
                    </span>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Welcome Section - Fusion Concept -->
    <section class="welcome-section section-padding" id="about">
        <div class="container">
            <div class="welcome-card">
                <!-- Mobile: Sandwich Layout (Headline → Image → Content) -->
                <div class="welcome-header-mobile d-lg-none text-center" data-aos="fade-up">
                    <span class="welcome-badge">Who We Are</span>
                    <h2 class="fusion-headline">We <span class="text-gradient-sculpt">Sculpt</span> Brands.</h2>
                    <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 welcome-image-col" data-aos="fade-right" data-aos-duration="1000">
                        <div class="welcome-image fusion-image image-comparison" data-comparison>
                            <div class="comparison-container">
                                <img src="assets/images/about-fusion.png" alt="Raw Concept to Brand Creation - We transform ideas into masterpieces" class="comparison-image">
                                <div class="comparison-overlay"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 welcome-text-col" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Desktop: Show header here -->
                        <div class="welcome-header-desktop d-none d-lg-block">
                            <span class="welcome-badge">Who We Are</span>
                            <h2 class="fusion-headline">We <span class="text-gradient-sculpt">Sculpt</span> Brands.</h2>
                            <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                        </div>
                        <p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                        <p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we're the creative studio that brings visions to life.</p>
                        <div class="welcome-stats" data-aos="fade-up" data-aos-delay="400">
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
                        <a href="about.php" class="btn btn-primary btn-magnetic" data-aos="fade-up" data-aos-delay="500">Discover Our Story</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section - Illustration Cards -->
    <section class="services-section section-padding" id="services-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up"><?php echo htmlspecialchars($home_svc['content_title'] ?? 'Our Services'); ?></h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($home_svc['content_body'] ?? 'Comprehensive digital solutions to help your business grow and thrive in the digital landscape.'); ?></p>
            </div>
            
            <div class="services-gallery" data-aos="fade-up">
                <div class="services-track">
                    <?php foreach ($services as $index => $service): ?>
                    <div class="service-card-wrapper">
                        <div class="service-card">
                            <div class="service-illustration-wrap">
                                <img src="<?php echo $service['image']; ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="service-illustration" loading="lazy">
                            </div>
                            <h4 class="service-title"><?php echo $service['title']; ?></h4>
                            <div class="service-card-details">
                                <p class="service-description"><?php echo $service['description']; ?></p>
                                <a href="services.php" class="service-link">Learn More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="services.php" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Meet The Creators Section — Clean Light Grid -->
    <section class="creators-section-v2" id="team">
        <div class="container">
            <div class="creators-v2-header">
                <span class="v2-pill">● Our Team</span>
                <h2 class="v2-heading">Meet The Creators</h2>
                <p class="v2-subtext">Two dreamers who turned their passion into your brand's success story</p>
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
                        <?php if (!empty($member['linkedin'])): ?>
                        <a href="<?php echo $member['linkedin']; ?>" class="creator-v2-social" target="_blank" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Case Studies Section — Clean 3-Column Grid -->
    <section class="case-studies-v2" id="portfolio">
        <div class="container">
            <div class="cases-v2-header">
                <h2 class="v2-heading">Case Studies</h2>
                <p class="v2-subtext">Some of our recent creative work</p>
            </div>

            <?php $displayed_cases = array_slice($case_studies, 0, 6); ?>
            <div class="cases-v2-grid">
                <?php foreach ($displayed_cases as $index => $case): ?>
                <div class="case-v2-card grid-reveal-item">
                    <div class="case-v2-img">
                        <img src="<?php echo $case['image']; ?>" alt="<?php echo htmlspecialchars($case['title']); ?>" loading="lazy">
                    </div>
                    <div class="case-v2-content">
                        <h3 class="case-v2-title"><?php echo htmlspecialchars($case['title']); ?></h3>
                        <div class="case-v2-tags">
                            <?php if (!empty($case['tags'])): ?>
                            <span class="case-v2-pill"><?php echo htmlspecialchars($case['tags'][0]); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="case-studies.php" class="case-v2-link">View Details <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center" style="margin-top: 48px;">
                <a href="case-studies.php" class="btn btn-primary">View All Projects</a>
            </div>
        </div>
    </section>

    <!-- Vlog & Reel Section -->
    <section class="vlog-section">
        <div class="container">
            <h2 class="vlog-heading">VLOG & REEL</h2>
            <div class="vlog-grid">
                <?php
                $reels = [
                    ['thumb' => 'assets/images/reels/reel-1.jpg', 'title' => 'Brand Identity Process'],
                    ['thumb' => 'assets/images/reels/reel-2.jpg', 'title' => 'Social Media Tips'],
                    ['thumb' => 'assets/images/reels/reel-3.jpg', 'title' => 'Web Design Timelapse'],
                    ['thumb' => 'assets/images/reels/reel-4.jpg', 'title' => 'Client Testimonial'],
                    ['thumb' => 'assets/images/reels/reel-5.jpg', 'title' => 'Behind The Scenes'],
                ];
                foreach ($reels as $reel):
                ?>
                <div class="vlog-card">
                    <img src="<?php echo $reel['thumb']; ?>" alt="<?php echo htmlspecialchars($reel['title']); ?>" loading="lazy">
                    <button class="vlog-play" aria-label="Play <?php echo htmlspecialchars($reel['title']); ?>">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section — Peek Slider -->
    <?php if (!empty($testimonials)): ?>
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge">Client Love</span>
                <h2 class="section-title">What Our Clients Say</h2>
                <p class="section-subtitle">Real feedback from real partners who trusted us with their brands</p>
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

<?php include 'includes/footer.php'; ?>
