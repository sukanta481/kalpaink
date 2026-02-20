<?php 
$page_title = 'Home';
include 'includes/header.php'; 

// Get page content from CMS (auto-sync)
$home_content = getPageContent('home');
$home_hero = $home_content['hero'] ?? null;
$home_trust = $home_content['trust_bar'] ?? null;
$home_welcome = $home_content['welcome'] ?? null;
$home_svc = $home_content['services_section'] ?? null;
$home_about = $home_content['about'] ?? null;
$home_team = $home_content['team'] ?? null;
$home_cases = $home_content['case_studies'] ?? null;
$home_vlog = $home_content['vlog_reel'] ?? null;
$home_testimonials = $home_content['testimonials'] ?? null;
$home_cta = $home_content['cta'] ?? null;

// Parse JSON extras
$hero_extra = !empty($home_hero['content_extra']) ? json_decode($home_hero['content_extra'], true) : [];
$welcome_extra = !empty($home_welcome['content_extra']) ? json_decode($home_welcome['content_extra'], true) : [];

// Get clients for marquee (auto-sync)
$marquee_clients = getClientsFromDB();

// Get hero slides from CRM (auto-sync)
$crm_hero_slides = getHeroSlides();
?>

    <!-- Hero Section - Blue Rounded Card -->
    <section class="hero-section-v2-wrapper">
        <div class="container">
            <div class="hero-section-v2-card">
                <div class="hero-v2-content text-center">
                    <h1 class="hero-v2-title" data-aos="fade-up">
                        We <span class="hero-highlight">Design</span> Ideas<br>That Think Before <span class="hero-outline">They</span> Speak.
                    </h1>
                    <p class="hero-v2-subtitle" data-aos="fade-up" data-aos-delay="100">
                        <strong>Branding</strong> · <span class="hero-cursive">Design</span> · <strong>VISUAL THINKING</strong>
                    </p>
                    <div class="hero-v2-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="<?php echo $hero_extra['button_link'] ?? 'contact.php'; ?>" class="btn-hero-white"><?php echo $hero_extra['button_text'] ?? 'get start'; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Trust Bar -->
    <section class="client-trust-bar">
        <p class="trust-text" data-aos="fade-up"><?php echo htmlspecialchars($home_trust['content_title'] ?? 'Trusted by 50+ business worldwide.'); ?></p>
        <div class="brands-ticker">
            <div class="brands-ticker-track">
                <?php
                $fallback_clients = [
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                    ['client_name' => 'BRANDS', 'client_logo' => null],
                ];
                $clients_list = !empty($marquee_clients) ? $marquee_clients : $fallback_clients;
                
                foreach ($clients_list as $mc):
                ?>
                <span class="brand-name-item">
                    <?php if (!empty($mc['client_logo'])): ?>
                        <img src="<?php echo SITE_URL . '/' . $mc['client_logo']; ?>" 
                             alt="<?php echo htmlspecialchars($mc['client_name']); ?>"
                             style="max-height: 30px; max-width: 140px; object-fit: contain;">
                    <?php else: ?>
                        <?php echo htmlspecialchars($mc['client_name']); ?>
                    <?php endif; ?>
                </span>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="welcome-section-v2 section-padding" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="welcome-image-placeholder">
                        <?php if (file_exists('assets/images/about-fusion.png')): ?>
                            <img src="assets/images/about-fusion.png" alt="Welcome to Kalpanik Digital">
                        <?php else: ?>
                            <div class="img-placeholder-box"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="welcome-text-v2">
                        <h2 class="welcome-title-v2"><?php echo $home_welcome['content_title'] ?? 'WELCOME TO<br>KALPANIK DIGITAL!'; ?></h2>
                        <?php echo $home_welcome['content_body'] ?? '<p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                        <p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we\'re the creative studio that brings visions to life.</p>'; ?>
                        <a href="<?php echo $welcome_extra['button_link'] ?? 'about.php'; ?>" class="btn-outline-v2"><?php echo $welcome_extra['button_text'] ?? 'know more'; ?> <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Services Section -->
    <section class="services-section-v2 section-padding" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-v2" data-aos="fade-up"><?php echo htmlspecialchars($home_svc['content_title'] ?? 'OUR SERVICES'); ?></h2>
                <p class="section-subtitle-v2" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($home_svc['content_body'] ?? 'Comprehensive digital solutions to help your business grow and thrive in the digital landscape.'); ?></p>
            </div>
            
            <div class="services-grid-v2" data-aos="fade-up" data-aos-delay="200">
                <?php 
                // Map service titles to illustration image filenames
                $service_images = [
                    'Graphics Design' => 'Graphics Design.png',
                    'Brand Identity' => 'Brand Identity.png',
                    'Social Media Marketing' => 'Social Media Marketing.png',
                    'Web Development' => 'Web Development.png',
                    'SEO Services' => 'SEO Services.png',
                    'Content Marketing' => 'Content Marketing.png',
                ];
                
                // SVG fallback gradients for services without images
                $service_gradients = [
                    'fa-code' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                    'fa-magnifying-glass' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                    'fa-pen-nib' => 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                ];
                
                foreach ($services as $index => $service): 
                    $title = $service['title'];
                    $has_image = isset($service_images[$title]);
                ?>
                <div class="service-card-v2">
                    <div class="service-card-v2-img">
                        <?php if ($has_image): ?>
                        <div class="service-illustration-box service-illustration-img">
                            <img src="<?php echo SITE_URL; ?>/assets/images/Services/<?php echo rawurlencode($service_images[$title]); ?>" 
                                 alt="<?php echo htmlspecialchars($title); ?>" loading="lazy">
                        </div>
                        <?php else: ?>
                        <div class="service-illustration-box" style="background: <?php echo $service_gradients[$service['icon']] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'; ?>">
                            <i class="fas <?php echo htmlspecialchars($service['icon']); ?>" style="font-size: 3rem; color: rgba(255,255,255,0.9);"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="service-card-v2-title"><?php echo htmlspecialchars($title); ?></h4>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section-v2 section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="about-image-placeholder">
                        <div class="img-placeholder-box img-placeholder-tall"></div>
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="about-text-v2">
                        <h2 class="about-title-v2"><?php echo htmlspecialchars($home_about['content_title'] ?? 'ABOUT'); ?></h2>
                        <?php echo $home_about['content_body'] ?? '<p>We are a collective of young, curious minds brought together by a shared respect for thoughtful creativity. At Kalpanik, strategy, design, and digital thinking work side by side, each informing the other.</p>
                        <p>Our team includes planners who value clarity, designers who care deeply about form and meaning, and digital storytellers who understand how brands speak in today\'s world. We work collaboratively, guided by intent rather than urgency, with enthusiasm grounded in understanding.</p>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet The Creators / Our Team Section -->
    <section class="creators-section-v2 section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge-v2" data-aos="fade-up"><?php echo htmlspecialchars($home_team['content_subtitle'] ?? '# OUR TEAM'); ?></span>
                <h2 class="section-title-v2" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($home_team['content_title'] ?? 'MEET THE CREATORS'); ?></h2>
                <p class="section-subtitle-v2" data-aos="fade-up" data-aos-delay="200"><?php echo htmlspecialchars($home_team['content_body'] ?? 'Two dreamers who turned their passion into your brand\'s success story'); ?></p>
            </div>
            
            <div class="swiper creators-swiper">
                <div class="swiper-wrapper creators-grid-v2" data-aos="fade-up" data-aos-delay="300">
                    <?php foreach ($team_members as $index => $member): ?>
                    <div class="swiper-slide creator-card-v2">
                        <div class="creator-photo-v2">
                            <?php if (!empty($member['image_pro'])): ?>
                                <img src="<?php echo $member['image_pro']; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                            <?php else: ?>
                                <div class="img-placeholder-box img-placeholder-tall"></div>
                            <?php endif; ?>
                            
                            <div class="creator-info-v2">
                                <div class="creator-details-v2">
                                    <h3><?php echo htmlspecialchars($member['name'] ?? 'Creator Name', ENT_QUOTES, 'UTF-8', false); ?></h3>
                                    <span><?php echo htmlspecialchars($member['position'] ?? 'Role / Position', ENT_QUOTES, 'UTF-8', false); ?></span>
                                </div>
                                <div class="creator-social-v2">
                                    <?php if (!empty($member['linkedin'])): ?>
                                        <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($member['twitter'])): ?>
                                        <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- Pagination for mobile -->
                <div class="swiper-pagination creators-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Case Studies Section -->
    <section class="case-studies-section-v2 section-padding" id="portfolio">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-v2" data-aos="fade-up"><?php echo htmlspecialchars($home_cases['content_title'] ?? 'CASE STUDIES'); ?></h2>
            </div>
            
            <?php 
            $displayed_cases = array_slice($case_studies, 0, 3);
            ?>
            <div class="case-studies-grid-v2" data-aos="fade-up" data-aos-delay="100">
                <?php foreach ($displayed_cases as $index => $case): ?>
                <div class="case-card-v2">
                    <div class="case-card-v2-image">
                        <img src="<?php echo $case['image']; ?>" alt="<?php echo $case['title']; ?>" loading="lazy">
                    </div>
                    <div class="case-card-v2-content">
                        <h5 class="case-card-v2-title">Brand Name</h5>
                        <div class="case-card-v2-tags">
                            <?php foreach ($case['tags'] as $tag): ?>
                            <span class="case-tag-v2"><?php echo $tag; ?></span>
                            <?php endforeach; ?>
                        </div>
                        <a href="case-studies.php" class="case-link-v2">View Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="case-studies.php" class="btn-outline-v2">view more <i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </section>

    <!-- Vlog & Reel Section -->
    <section class="vlog-reel-section section-padding" id="vlog-reel">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-v2" data-aos="fade-up"><?php echo htmlspecialchars($home_vlog['content_title'] ?? 'VLOG & REEL'); ?></h2>
            </div>
            
            <div class="vlog-reel-grid" data-aos="fade-up" data-aos-delay="100">
                <div class="vlog-card">
                    <div class="img-placeholder-box img-placeholder-vlog"></div>
                </div>
                <div class="vlog-card">
                    <div class="img-placeholder-box img-placeholder-vlog"></div>
                </div>
                <div class="vlog-card">
                    <div class="img-placeholder-box img-placeholder-vlog"></div>
                </div>
                <div class="vlog-card">
                    <div class="img-placeholder-box img-placeholder-vlog"></div>
                </div>
                <div class="vlog-card">
                    <div class="img-placeholder-box img-placeholder-vlog"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- What Our Clients Say / Testimonials -->
    <?php if (!empty($testimonials)): ?>
    <section class="testimonials-section-v2 section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-v2" data-aos="fade-up"><?php echo htmlspecialchars($home_testimonials['content_title'] ?? 'WHAT OUR CLIENTS SAY'); ?></h2>
                <p class="section-subtitle-v2" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($home_testimonials['content_subtitle'] ?? 'Real feedback from real partners who trusted us with their brands'); ?></p>
            </div>
            
            <div class="testimonials-row-v2" data-aos="fade-up" data-aos-delay="200">
                <?php foreach ($testimonials as $index => $testimonial): ?>
                <div class="testimonial-card-v2">
                    <div class="testimonial-card-v2-inner">
                        <div class="testimonial-avatar-v2">
                            <?php if (!empty($testimonial['client_avatar'])): ?>
                            <img src="<?php echo htmlspecialchars($testimonial['client_avatar']); ?>" alt="<?php echo htmlspecialchars($testimonial['client_name'] ?? ''); ?>">
                            <?php else: ?>
                            <div class="avatar-placeholder-v2"><?php echo strtoupper(substr($testimonial['client_name'] ?? 'C', 0, 1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <p class="testimonial-text-v2">"<?php echo htmlspecialchars($testimonial['testimonial_text'] ?? ''); ?>"</p>
                        <div class="testimonial-author-v2">
                            <h5><?php echo htmlspecialchars($testimonial['client_name'] ?? ''); ?></h5>
                            <p><?php echo htmlspecialchars($testimonial['client_position'] ?? ''); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php include 'includes/footer.php'; ?>
