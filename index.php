<?php 
$page_title = 'Home';
include 'includes/header.php'; 

// Get page content from CMS (auto-sync)
$home_content = getPageContent('home');
$home_svc = $home_content['services_section'] ?? null;
$home_cta = $home_content['cta'] ?? null;

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
                        We <span class="hero-highlight">Design</span> Ideas<br>
                        That Think Before <span class="hero-outline">They</span> Speak.
                    </h1>
                    <p class="hero-v2-subtitle" data-aos="fade-up" data-aos-delay="100">
                        <strong>Branding</strong> · <span class="hero-cursive">Design</span> · <strong>VISUAL THINKING</strong>
                    </p>
                    <div class="hero-v2-buttons" data-aos="fade-up" data-aos-delay="200">
                        <a href="contact.php" class="btn-hero-white">get start <i class="fas fa-arrow-down-left"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Client Trust Bar -->
    <section class="client-trust-bar">
        <p class="trust-text" data-aos="fade-up">Trusted by 50+ business worldwide.</p>
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
                
                for ($loop = 0; $loop < 2; $loop++):
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
                endfor;
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
                        <h2 class="welcome-title-v2">WELCOME TO<br>KALPANIK DIGITAL!</h2>
                        <p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                        <p>From the initial sketch to the final polish—logo design, brand identity, web development, and digital marketing—we're the creative studio that brings visions to life.</p>
                        <a href="about.php" class="btn-outline-v2">know more <i class="fas fa-arrow-right"></i></a>
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
                // SVG illustration icons for each service type
                $service_illustrations = [
                    'fa-palette' => [
                        'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="60" cy="52" r="28" fill="rgba(255,255,255,0.15)"/><path d="M45 52c0-8.28 6.72-15 15-15s15 6.72 15 15c0 5.8-3.3 10.83-8.12 13.31" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/><circle cx="50" cy="47" r="3.5" fill="#FFD166"/><circle cx="60" cy="42" r="3.5" fill="#06D6A0"/><circle cx="70" cy="47" r="3.5" fill="#EF476F"/><circle cx="55" cy="58" r="3.5" fill="#118AB2"/><rect x="62" y="58" width="4" height="20" rx="2" transform="rotate(-30 62 58)" fill="rgba(255,255,255,0.9)"/><circle cx="60" cy="85" r="3" fill="rgba(255,255,255,0.3)"/><circle cx="45" cy="80" r="2" fill="rgba(255,255,255,0.2)"/><circle cx="78" cy="82" r="2.5" fill="rgba(255,255,255,0.2)"/></svg>'
                    ],
                    'fa-bullhorn' => [
                        'gradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="30" y="40" width="24" height="24" rx="4" fill="rgba(255,255,255,0.2)"/><rect x="33" y="43" width="18" height="18" rx="2" fill="rgba(255,255,255,0.9)"/><text x="42" y="55" text-anchor="middle" fill="#f5576c" font-size="10" font-weight="bold">K</text><path d="M62 38l20-8v44l-20-8" fill="rgba(255,255,255,0.25)" stroke="#fff" stroke-width="2"/><rect x="58" y="38" width="6" height="28" rx="2" fill="rgba(255,255,255,0.85)"/><path d="M82 46c4 3 6 8 6 14s-2 11-6 14" stroke="#fff" stroke-width="2" stroke-linecap="round" fill="none"/><path d="M88 40c6 5 10 13 10 20s-4 15-10 20" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round" fill="none"/><circle cx="36" cy="78" r="5" fill="rgba(255,255,255,0.3)"/><circle cx="75" cy="80" r="3" fill="rgba(255,255,255,0.2)"/></svg>'
                    ],
                    'fa-share-nodes' => [
                        'gradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="38" y="30" width="44" height="56" rx="8" fill="rgba(255,255,255,0.2)" stroke="#fff" stroke-width="2"/><circle cx="60" cy="50" r="12" fill="rgba(255,255,255,0.15)"/><path d="M55 50a5 5 0 1 1 10 0a5 5 0 1 1 -10 0" fill="#fff"/><path d="M48 68h24" stroke="rgba(255,255,255,0.6)" stroke-width="2" stroke-linecap="round"/><path d="M52 74h16" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round"/><circle cx="78" cy="36" r="8" fill="rgba(255,255,255,0.9)"/><text x="78" y="39" text-anchor="middle" fill="#4facfe" font-size="9" font-weight="bold">♥</text><circle cx="85" cy="65" r="6" fill="rgba(255,255,255,0.3)"/><circle cx="35" cy="72" r="4" fill="rgba(255,255,255,0.2)"/><path d="M30 42l-6-6M90 42l6-6" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" stroke-linecap="round"/></svg>'
                    ],
                    'fa-code' => [
                        'gradient' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="25" y="32" width="70" height="50" rx="6" fill="rgba(255,255,255,0.2)" stroke="#fff" stroke-width="2"/><rect x="25" y="32" width="70" height="12" rx="6" fill="rgba(255,255,255,0.3)"/><circle cx="34" cy="38" r="2.5" fill="#EF476F"/><circle cx="42" cy="38" r="2.5" fill="#FFD166"/><circle cx="50" cy="38" r="2.5" fill="#06D6A0"/><path d="M38 56l-8 8l8 8" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M82 56l8 8l-8 8" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M65 50l-10 28" stroke="rgba(255,255,255,0.7)" stroke-width="2" stroke-linecap="round"/><circle cx="60" cy="92" r="3" fill="rgba(255,255,255,0.3)"/><rect x="48" cy="88" width="24" height="3" rx="1.5" fill="rgba(255,255,255,0.2)"/></svg>'
                    ],
                    'fa-magnifying-glass' => [
                        'gradient' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="52" cy="50" r="20" fill="rgba(255,255,255,0.15)" stroke="#fff" stroke-width="2.5"/><path d="M66 64l18 18" stroke="#fff" stroke-width="3" stroke-linecap="round"/><path d="M42 70v-5l5 3l5-8l5 5l5-12l5 10l5-3v10" stroke="rgba(255,255,255,0.9)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/><path d="M42 70l5 3l5-8l5 5l5-12l5 10l5-3" fill="rgba(255,255,255,0.15)"/><circle cx="82" cy="32" r="6" fill="rgba(255,255,255,0.3)"/><text x="82" y="35" text-anchor="middle" fill="#fff" font-size="8">★</text><circle cx="35" cy="82" r="4" fill="rgba(255,255,255,0.2)"/><path d="M76 85l6 0l-3-5z" fill="rgba(255,255,255,0.3)"/></svg>'
                    ],
                    'fa-pen-nib' => [
                        'gradient' => 'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                        'svg' => '<svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="35" y="30" width="50" height="62" rx="4" fill="rgba(255,255,255,0.2)" stroke="#fff" stroke-width="2"/><line x1="42" y1="42" x2="78" y2="42" stroke="rgba(255,255,255,0.7)" stroke-width="2" stroke-linecap="round"/><line x1="42" y1="50" x2="70" y2="50" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round"/><line x1="42" y1="58" x2="75" y2="58" stroke="rgba(255,255,255,0.5)" stroke-width="2" stroke-linecap="round"/><line x1="42" y1="66" x2="62" y2="66" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round"/><path d="M72 60l15-15l8 8l-15 15l-10 2z" fill="rgba(255,255,255,0.85)" stroke="#fff" stroke-width="1.5"/><path d="M72 60l8 8" stroke="rgba(160,140,209,0.5)" stroke-width="1.5"/><circle cx="32" cy="80" r="3" fill="rgba(255,255,255,0.25)"/><path d="M80 85l8 0l-4-7z" fill="rgba(255,255,255,0.2)"/></svg>'
                    ]
                ];
                
                foreach ($services as $index => $service): 
                    $icon_key = $service['icon'] ?? 'fa-palette';
                    $illustration = $service_illustrations[$icon_key] ?? $service_illustrations['fa-palette'];
                ?>
                <div class="service-card-v2">
                    <div class="service-card-v2-img">
                        <div class="service-illustration-box" style="background: <?php echo $illustration['gradient']; ?>">
                            <?php echo $illustration['svg']; ?>
                        </div>
                    </div>
                    <h4 class="service-card-v2-title"><?php echo htmlspecialchars($service['title']); ?></h4>
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
                        <h2 class="about-title-v2">ABOUT</h2>
                        <p>We are a collective of young, curious minds brought together by a shared respect for thoughtful creativity. At Kalpanik, strategy, design, and digital thinking work side by side, each informing the other.</p>
                        <p>Our team includes planners who value clarity, designers who care deeply about form and meaning, and digital storytellers who understand how brands speak in today's world. We work collaboratively, guided by intent rather than urgency, with enthusiasm grounded in understanding.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet The Creators / Our Team Section -->
    <section class="creators-section-v2 section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge-v2" data-aos="fade-up"># OUR TEAM</span>
                <h2 class="section-title-v2" data-aos="fade-up" data-aos-delay="100">MEET THE CREATORS</h2>
                <p class="section-subtitle-v2" data-aos="fade-up" data-aos-delay="200">Two dreamers who turned their passion into your brand's success story</p>
            </div>
            
            <div class="creators-grid-v2" data-aos="fade-up" data-aos-delay="300">
                <?php foreach ($team_members as $index => $member): ?>
                <div class="creator-card-v2">
                    <div class="creator-photo-v2">
                        <?php if (!empty($member['image_pro'])): ?>
                            <img src="<?php echo $member['image_pro']; ?>" alt="<?php echo $member['name']; ?>">
                        <?php else: ?>
                            <div class="img-placeholder-box img-placeholder-tall"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <!-- Extra placeholder cards to match design (4 cards) -->
                <?php for ($i = count($team_members); $i < 4; $i++): ?>
                <div class="creator-card-v2">
                    <div class="creator-photo-v2">
                        <div class="img-placeholder-box img-placeholder-tall"></div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Case Studies Section -->
    <section class="case-studies-section-v2 section-padding" id="portfolio">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title-v2" data-aos="fade-up">CASE STUDIES</h2>
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
                <h2 class="section-title-v2" data-aos="fade-up">VLOG & REEL</h2>
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
                <h2 class="section-title-v2" data-aos="fade-up">WHAT OUR CLIENTS SAY</h2>
                <p class="section-subtitle-v2" data-aos="fade-up" data-aos-delay="100">Real feedback from real partners who trusted us with their brands</p>
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
