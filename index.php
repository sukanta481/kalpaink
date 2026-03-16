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
    <?php if (isHomepageSectionEnabled('hero')): ?>
    <?php
    // Fallback hero slides if CRM has none
    $fallback_hero_slides = [
        [
            'badge_text' => 'Creative Design Studio',
            'title' => "We Design Ideas\nThat Think Before\nThey Speak.",
            'subtitle' => 'Branding &middot; Design &middot; VISUAL THINKING',
            'button1_text' => 'Get Started', 'button1_link' => 'contact.php',
            'button2_text' => 'Our Services', 'button2_link' => 'services.php',
            'background_class' => 'hero-slide-bg-1'
        ],
        [
            'badge_text' => 'Social Media & Growth',
            'title' => "Reimagining\nBrands with\nPurpose",
            'subtitle' => 'Strategy &middot; Content &middot; DIGITAL PRESENCE',
            'button1_text' => 'Get Quote', 'button1_link' => 'contact.php',
            'button2_text' => 'Case Studies', 'button2_link' => 'case-studies.php',
            'background_class' => 'hero-slide-bg-2'
        ],
        [
            'badge_text' => 'Web & Digital Experience',
            'title' => "Strategy First.\nDesign\nAlways.",
            'subtitle' => 'Development &middot; SEO &middot; VISUAL EXPERIENCE',
            'button1_text' => 'View Work', 'button1_link' => 'case-studies.php',
            'button2_text' => 'Contact Us', 'button2_link' => 'contact.php',
            'background_class' => 'hero-slide-bg-3'
        ]
    ];
    $active_slides = !empty($hero_slides) ? $hero_slides : $fallback_hero_slides;
    ?>
    <?php
    // Generate mobile image overrides if any slide has a mobile_image
    $mobile_overrides = '';
    foreach ($active_slides as $si => $sl) {
        if (!empty($sl['mobile_image'])) {
            $mob_url = htmlspecialchars($sl['mobile_image']);
            $mobile_overrides .= ".hero-slide[data-slide=\"{$si}\"] { background-image: url('{$mob_url}') !important; }\n        ";
        }
    }
    if ($mobile_overrides): ?>
    <style>
    @media (max-width: 767.98px) {
        <?php echo $mobile_overrides; ?>
    }
    </style>
    <?php endif; ?>
    <section class="hero-section">
        <div class="hero-carousel" id="heroCarousel">
            <?php foreach ($active_slides as $slideIndex => $slide): ?>
            <div class="hero-slide <?php echo htmlspecialchars($slide['background_class'] ?? 'hero-slide-bg-' . ($slideIndex + 1)); ?> <?php echo $slideIndex === 0 ? 'active' : ''; ?>" data-slide="<?php echo $slideIndex; ?>"<?php if (!empty($slide['background_image'])): ?> role="img" aria-label="<?php echo htmlspecialchars($slide['title'] ?? 'Hero slide ' . ($slideIndex + 1)); ?>" style="background-image: url('<?php echo htmlspecialchars($slide['background_image']); ?>'); background-size: cover; background-position: center;"<?php endif; ?>>
                <div class="hero-slide-overlay"></div>
                <div class="hero-slide-inner">
                    <div class="hero-text-col">
                        <div class="hero-content">
                            <span class="hero-pill"><?php echo htmlspecialchars($slide['badge_text'] ?? ''); ?></span>
                            <?php if ($slideIndex === 0): ?>
                            <h1 class="hero-headline">
                                <?php echo nl2br(htmlspecialchars($slide['title'] ?? '')); ?>
                            </h1>
                            <?php else: ?>
                            <p class="hero-headline" role="heading" aria-level="2">
                                <?php echo nl2br(htmlspecialchars($slide['title'] ?? '')); ?>
                            </p>
                            <?php endif; ?>
                            <p class="hero-subtext"><?php echo $slide['subtitle'] ?? ''; ?></p>
                            <div class="hero-buttons">
                                <a href="<?php echo htmlspecialchars($slide['button1_link'] ?? 'contact'); ?>" class="hero-btn hero-btn-primary"><?php echo htmlspecialchars($slide['button1_text'] ?? 'Get Started'); ?></a>
                                <a href="<?php echo htmlspecialchars($slide['button2_link'] ?? 'services'); ?>" class="hero-btn hero-btn-secondary"><?php echo htmlspecialchars($slide['button2_text'] ?? 'Our Services'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Navigation Arrows -->
            <button class="hero-arrow hero-arrow-prev" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-arrow hero-arrow-next" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Navigation Dots -->
            <div class="hero-dots">
                <?php foreach ($active_slides as $dotIndex => $slide): ?>
                <button class="hero-dot <?php echo $dotIndex === 0 ? 'active' : ''; ?>" data-slide="<?php echo $dotIndex; ?>" aria-label="Slide <?php echo $dotIndex + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Trusted Brands Section -->
    <?php if (isHomepageSectionEnabled('brands')): ?>
    <?php $trust_bar = $home_content['trust_bar'] ?? null; ?>
    <section class="trusted-brands">
        <div class="trusted-brands-header">
            <p class="trusted-brands-heading"><?php echo htmlspecialchars($trust_bar['content_title'] ?? 'Trusted by 250+ business worldwide'); ?></p>
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
                         alt="<?php echo htmlspecialchars(!empty($mc['logo_alt_text']) ? $mc['logo_alt_text'] : 'Logo of ' . $mc['client_name']); ?>">
                <?php else: ?>
                    <?php echo htmlspecialchars($mc['client_name']); ?>
                <?php endif; ?>
            </span>
            <?php endforeach; ?>
        </div>

        <!-- Mobile: Marquee Strip -->
        <div class="brands-marquee" id="brandsMarquee">
            <div class="brands-marquee-track">
                <?php for ($loop = 0; $loop < 2; $loop++): ?>
                    <?php foreach ($clients_list as $mc): ?>
                    <span class="brand-item-mobile">
                        <?php if (!empty($mc['client_logo'])): ?>
                            <img src="<?php echo SITE_URL . '/' . $mc['client_logo']; ?>"
                                 alt="<?php echo htmlspecialchars(!empty($mc['logo_alt_text']) ? $mc['logo_alt_text'] : 'Logo of ' . $mc['client_name']); ?>">
                        <?php else: ?>
                            <?php echo htmlspecialchars($mc['client_name']); ?>
                        <?php endif; ?>
                    </span>
                    <?php endforeach; ?>
                <?php endfor; ?>
            </div>
        </div>
    </section>
    <script>
    (function(){
        var m = document.getElementById('brandsMarquee');
        if (!m) return;
        function pauseMarquee() {
            var t = m.querySelector('.brands-marquee-track');
            if (t) { t.style.animationPlayState = 'paused'; clearTimeout(t._rt); t._rt = setTimeout(function(){ t.style.animationPlayState = 'running'; }, 3000); }
        }
        m.addEventListener('touchstart', pauseMarquee, {passive: true});
        m.addEventListener('click', pauseMarquee);
    })();
    </script>
    <?php endif; ?>

    <!-- Welcome Section - Fusion Concept -->
    <?php if (isHomepageSectionEnabled('welcome')): ?>
    <?php $welcome = $home_content['welcome'] ?? null; ?>
    <section class="welcome-section section-padding" id="about">
        <div class="container">
            <div class="welcome-card">
                <!-- Mobile: Sandwich Layout (Headline → Image → Content) -->
                <div class="welcome-header-mobile d-lg-none text-center" data-aos="fade-up">
                    <span class="welcome-badge"><?php echo htmlspecialchars($welcome['content_subtitle'] ?? 'Who We Are'); ?></span>
                    <h2 class="fusion-headline">
                        <?php 
                        $title = $welcome['content_title'] ?? 'We Sculpt Brands.'; 
                        // Try to preserve the trademark gradient style if they kept the word "Sculpt"
                        echo str_replace('Sculpt', '<span class="text-gradient-sculpt">Sculpt</span>', htmlspecialchars($title));
                        ?>
                    </h2>
                    <?php if (!empty($welcome['extra']['lead_text'])): ?>
                    <p class="lead-text"><?php echo $welcome['extra']['lead_text']; ?></p>
                    <?php else: ?>
                    <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                    <?php endif; ?>
                </div>

                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0 welcome-image-col" data-aos="fade-right" data-aos-duration="1000">
                        <div class="welcome-image fusion-image image-comparison" data-comparison>
                            <div class="comparison-container">
                                <img src="<?php echo !empty($welcome['content_image']) ? SITE_URL . '/' . $welcome['content_image'] : getSitePath('assets/images/about-fusion.png'); ?>" alt="<?php echo htmlspecialchars(!empty($welcome['image_alt_text']) ? $welcome['image_alt_text'] : ($welcome['content_title'] ?? 'Raw Concept to Brand Creation')); ?>" class="comparison-image" width="500" height="600" loading="lazy">
                                <div class="comparison-overlay"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7 welcome-text-col" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                        <!-- Desktop: Show header here -->
                        <div class="welcome-header-desktop d-none d-lg-block">
                            <span class="welcome-badge"><?php echo htmlspecialchars($welcome['content_subtitle'] ?? 'Who We Are'); ?></span>
                            <h2 class="fusion-headline">
                                <?php 
                                $title = $welcome['content_title'] ?? 'We Sculpt Brands.';
                                echo str_replace('Sculpt', '<span class="text-gradient-sculpt">Sculpt</span>', htmlspecialchars($title));
                                ?>
                            </h2>
                            <?php if (!empty($welcome['extra']['lead_text'])): ?>
                            <p class="lead-text"><?php echo $welcome['extra']['lead_text']; ?></p>
                            <?php else: ?>
                            <p class="lead-text">Where <strong>Art Meets Algorithm.</strong></p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($welcome['content_body'])): ?>
                            <div class="welcome-body-cms">
                                <?php echo $welcome['content_body']; ?>
                            </div>
                        <?php else: ?>
                            <p>Just like sculptors transform raw marble into masterpieces, we take your raw ideas and craft them into powerful brands that captivate and convert.</p>
                            <p>From the initial sketch to the final polish—<a href="services/graphics-design">logo design</a>, <a href="services/brand-identity">brand identity</a>, <a href="services/web-development">web development</a>, and <a href="services/content-marketing">content marketing</a>—we're the creative studio that brings visions to life.</p>
                        <?php endif; ?>
                        <div class="welcome-stats" data-aos="fade-up" data-aos-delay="400">
                            <?php if (!empty($statistics)): ?>
                                <?php foreach (array_slice($statistics, 0, 3) as $stat): ?>
                                <div class="stat-item">
                                    <span class="stat-number" data-count="<?php echo htmlspecialchars($stat['value']); ?>" data-suffix="<?php echo htmlspecialchars($stat['suffix'] ?? ''); ?>">0<?php echo htmlspecialchars($stat['suffix'] ?? ''); ?></span>
                                    <span class="stat-label"><?php echo htmlspecialchars($stat['label']); ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
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
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo getSitePath('about'); ?>" class="btn btn-primary btn-magnetic" data-aos="fade-up" data-aos-delay="500">Discover Our Story</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Services Section - Icon Cards -->
    <?php if (isHomepageSectionEnabled('services')): ?>
    <?php
    // SVG icon map for services (line-art style)
    $service_svg_icons = [
        'fa-palette' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 52L30 8h4l18 44" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 36h24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M44 52h-8l-2-8h-4l-2 8h-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="48" cy="16" r="4" stroke="currentColor" stroke-width="2.5"/><path d="M8 48c0-4 4-8 8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'fa-bullhorn' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 24h8v16H8a2 2 0 01-2-2V26a2 2 0 012-2z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 24l24-12v40L16 40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 40v10a4 4 0 004 4h4a4 4 0 004-4V40" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="50" cy="20" r="3" stroke="currentColor" stroke-width="2.5"/><path d="M48 28h8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="54" cy="36" r="2" stroke="currentColor" stroke-width="2"/></svg>',
        'fa-share-nodes' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="14" width="24" height="20" rx="3" stroke="currentColor" stroke-width="2.5"/><path d="M12 28l6-6 4 4 8-8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M34 18h24a2 2 0 012 2v24a2 2 0 01-2 2H34" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="46" cy="30" r="5" stroke="currentColor" stroke-width="2.5"/><path d="M43 35l-2 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M49 35l2 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M14 42l8 8M22 42l-8 8" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><rect x="10" y="38" width="16" height="16" rx="3" stroke="currentColor" stroke-width="2.5"/><path d="M18 44v6M15 47h6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'fa-code' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="6" y="10" width="52" height="40" rx="4" stroke="currentColor" stroke-width="2.5"/><path d="M6 20h52" stroke="currentColor" stroke-width="2.5"/><circle cx="12" cy="15" r="1.5" fill="currentColor"/><circle cx="18" cy="15" r="1.5" fill="currentColor"/><circle cx="24" cy="15" r="1.5" fill="currentColor"/><path d="M20 32l-6 4 6 4M36 32l6 4-6 4M26 42l4-16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'fa-magnifying-glass' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="28" cy="28" r="16" stroke="currentColor" stroke-width="2.5"/><path d="M40 40l14 14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M20 32V24l4 4 4-8 4 6 4-4v10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="50" cy="14" r="4" stroke="currentColor" stroke-width="2.5"/><path d="M50 12v4M48 14h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'fa-pen-nib' => '<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 12h40a4 4 0 014 4v8H8V12z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 24v28h32V24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 32h16M20 38h10M20 44h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M48 20l8-8M52 8l4 4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="52" cy="44" r="6" stroke="currentColor" stroke-width="2.5"/><path d="M52 41v6M49 44h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
    ];
    ?>
    <section class="services-section section-padding" id="services-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up"><?php echo htmlspecialchars($home_svc['content_title'] ?? 'Our Services'); ?></h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100"><?php echo htmlspecialchars($home_svc['content_body'] ?? 'Comprehensive digital solutions to help your business grow and thrive in the digital landscape.'); ?></p>
            </div>
            
            <div class="svc-slider-wrap" data-aos="fade-up">
                <button class="svc-slider-arrow svc-slider-arrow--prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                <div class="services-icon-grid">
                    <?php foreach ($services as $index => $service): ?>
                    <div class="svc-icon-card">
                        <div class="svc-icon-wrap">
                            <?php echo $service_svg_icons[$service['icon']] ?? '<i class="fas ' . htmlspecialchars($service['icon']) . '"></i>'; ?>
                        </div>
                        <h3 class="svc-icon-title"><?php echo htmlspecialchars($service['title']); ?></h3>
                        <div class="svc-icon-details">
                            <p class="svc-icon-desc"><?php echo htmlspecialchars($service['description']); ?></p>
                            <a href="services/<?php echo htmlspecialchars($service['slug'] ?? strtolower(str_replace(' ', '-', $service['title']))); ?>" class="svc-icon-link"><?php echo htmlspecialchars($service['title']); ?> <span>&rarr;</span></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button class="svc-slider-arrow svc-slider-arrow--next" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo getSitePath('services'); ?>" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Meet The Creators Section — Clean Light Grid -->
    <?php if (isHomepageSectionEnabled('team')): ?>
    <?php $home_team = $home_content['team'] ?? null; ?>
    <section class="creators-section-v2" id="team">
        <div class="container">
            <div class="creators-v2-header">
                <span class="v2-pill" data-aos="fade-down" data-aos-duration="600"><?php echo htmlspecialchars($home_team['content_subtitle'] ?? '● Our Team'); ?></span>
                <h2 class="v2-heading" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"><?php echo htmlspecialchars($home_team['content_title'] ?? 'Meet The Creators'); ?></h2>
                <p class="v2-subtext" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"><?php echo strip_tags($home_team['content_body'] ?? "Two dreamers who turned their passion into your brand's success story"); ?></p>
            </div>

            <div class="creators-v2-grid">
                <?php foreach ($team_members as $index => $member): ?>
                <div class="creator-v2-card grid-reveal-item">
                    <div class="creator-v2-img">
                        <img src="<?php echo $member['image_pro'] ?? $member['image']; ?>" alt="<?php echo htmlspecialchars(!empty($member['image_alt_text']) ? $member['image_alt_text'] : $member['name'] . ', ' . ($member['position'] ?? 'Team Member')); ?>" loading="lazy">
                    </div>
                    <div class="creator-v2-info">
                        <h3 class="creator-v2-name"><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p class="creator-v2-role"><?php echo htmlspecialchars($member['position'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', false); ?></p>
                    </div>
                    <?php if (!empty($member['linkedin'])): ?>
                    <div class="creator-v2-socials">
                        <a href="<?php echo $member['linkedin']; ?>" class="creator-v2-social" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Mobile: swipe hint + dots -->
            <div class="creators-swipe-hint">Swipe to see more <i class="fas fa-arrow-right"></i></div>
            <div class="creators-dots">
                <?php foreach ($team_members as $di => $dm): ?>
                <button class="creators-dot<?php echo $di === 0 ? ' active' : ''; ?>" data-index="<?php echo $di; ?>" aria-label="Go to team member <?php echo $di + 1; ?>"></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
    (function(){
        var grid = document.querySelector('.creators-v2-grid');
        var dots = document.querySelectorAll('.creators-dot');
        var hint = document.querySelector('.creators-swipe-hint');
        if (!grid || !dots.length) return;

        // Dot click → scroll to card
        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var card = grid.children[parseInt(this.dataset.index)];
                if (card) grid.scrollTo({ left: card.offsetLeft - grid.offsetLeft, behavior: 'smooth' });
            });
        });

        // Scroll → update active dot + hide swipe hint on first scroll
        var ticking = false;
        grid.addEventListener('scroll', function() {
            if (hint && grid.scrollLeft > 10) {
                hint.style.opacity = '0';
                hint.style.transition = 'opacity 0.3s ease';
                hint = null;
            }
            if (!ticking) {
                requestAnimationFrame(function() {
                    var scrollLeft = grid.scrollLeft;
                    var cardWidth = grid.children[0] ? grid.children[0].offsetWidth : 1;
                    var active = Math.round(scrollLeft / (cardWidth + 16));
                    dots.forEach(function(d, i) {
                        d.classList.toggle('active', i === active);
                    });
                    ticking = false;
                });
                ticking = true;
            }
        });
    })();
    </script>
    <?php endif; ?>

    <!-- Case Studies Section — Clean 3-Column Grid -->
    <?php if (isHomepageSectionEnabled('case_studies')): ?>
    <?php $home_cases = $home_content['case_studies'] ?? null; ?>
    <section class="case-studies-v2" id="portfolio">
        <div class="container">
            <div class="cases-v2-header">
                <h2 class="v2-heading"><?php echo htmlspecialchars($home_cases['content_title'] ?? 'Case Studies'); ?></h2>
                <p class="v2-subtext"><?php echo htmlspecialchars($home_cases['content_subtitle'] ?? 'Some of our recent creative work'); ?></p>
            </div>

            <?php $displayed_cases = array_slice($case_studies, 0, 6); ?>
            <div class="cases-v2-grid">
                <?php foreach ($displayed_cases as $index => $case): ?>
                <div class="case-v2-card grid-reveal-item">
                    <div class="case-v2-img">
                        <img src="<?php echo $case['image']; ?>" alt="<?php echo htmlspecialchars(!empty($case['image_alt_text']) ? $case['image_alt_text'] : $case['title']); ?>" loading="lazy">
                    </div>
                    <div class="case-v2-content">
                        <h3 class="case-v2-title"><?php echo htmlspecialchars($case['title']); ?></h3>
                        <div class="case-v2-tags">
                            <?php if (!empty($case['tags'])): ?>
                            <span class="case-v2-pill"><?php echo htmlspecialchars($case['tags'][0]); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo getSitePath('case-studies'); ?>" class="case-v2-link">View Details <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center" style="margin-top: 48px;">
                <a href="<?php echo getSitePath('case-studies'); ?>" class="btn btn-primary">View All Projects</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Vlog & Reel Section -->
    <?php if (isHomepageSectionEnabled('vlogs')): ?>
    <?php
    $vlogs_from_db = getVlogsFromDB();
    $fallback_reels = [
        ['thumbnail' => 'assets/images/reels/reel-1.jpg', 'title' => 'Brand Identity Process', 'video_url' => ''],
        ['thumbnail' => 'assets/images/reels/reel-2.jpg', 'title' => 'Social Media Tips', 'video_url' => ''],
        ['thumbnail' => 'assets/images/reels/reel-3.jpg', 'title' => 'Web Design Timelapse', 'video_url' => ''],
        ['thumbnail' => 'assets/images/reels/reel-4.jpg', 'title' => 'Client Testimonial', 'video_url' => ''],
        ['thumbnail' => 'assets/images/reels/reel-5.jpg', 'title' => 'Behind The Scenes', 'video_url' => ''],
    ];
    $reels_list = !empty($vlogs_from_db) ? $vlogs_from_db : $fallback_reels;
    ?>
    <section class="vlog-section">
        <?php $home_vlogs = $home_content['vlogs'] ?? null; ?>
        <div class="container">
            <h2 class="vlog-heading"><?php echo htmlspecialchars($home_vlogs['content_title'] ?? 'VLOG & REEL'); ?></h2>
            <div class="vlog-grid">
                <?php foreach ($reels_list as $reel): ?>
                <div class="vlog-card" <?php if (!empty($reel['video_url'])): ?>data-video-url="<?php echo htmlspecialchars($reel['video_url']); ?>"<?php endif; ?>>
                    <img src="<?php echo !empty($reel['thumbnail']) ? (strpos($reel['thumbnail'], 'http') === 0 ? $reel['thumbnail'] : SITE_URL . '/' . $reel['thumbnail']) : 'assets/images/reels/reel-1.jpg'; ?>" alt="<?php echo htmlspecialchars(!empty($reel['image_alt_text']) ? $reel['image_alt_text'] : $reel['title']); ?>" loading="lazy">
                    <button class="vlog-play" aria-label="Play <?php echo htmlspecialchars($reel['title']); ?>">
                        <i class="fas fa-play"></i>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section — Peek Slider -->
    <?php if (isHomepageSectionEnabled('testimonials') && !empty($testimonials)): ?>
    <?php $home_testimonials = $home_content['testimonials'] ?? null; ?>
    <section class="testimonials-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-badge" data-aos="fade-down" data-aos-duration="600"><?php echo htmlspecialchars($home_testimonials['content_subtitle'] ?? 'Client Love'); ?></span>
                <h2 class="section-title" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100"><?php echo htmlspecialchars($home_testimonials['content_title'] ?? 'What Our Clients Say'); ?></h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200"><?php echo strip_tags($home_testimonials['content_body'] ?? 'Real feedback from real partners who trusted us with their brands'); ?></p>
            </div>
        </div>
        <div class="testimonials-slider-wrapper" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
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
                            <img src="<?php echo htmlspecialchars($testimonial['client_avatar']); ?>" alt="<?php echo htmlspecialchars(!empty($testimonial['image_alt_text']) ? $testimonial['image_alt_text'] : 'Photo of ' . ($testimonial['client_name'] ?? 'client')); ?>">
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
    <?php if (isHomepageSectionEnabled('faq')): ?>
    <?php $home_faq = $home_content['faq'] ?? null; ?>
    <section class="faq-section section-padding">
        <div class="container">
            <div class="row">
                <!-- Left: Sticky Headline -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="faq-sticky-header" data-aos="fade-right">
                        <div class="faq-icon-float">
                            <i class="fas fa-question"></i>
                        </div>
                        <h2 class="faq-headline"><?php echo htmlspecialchars($home_faq['content_title'] ?? 'Got Questions?'); ?></h2>
                        <p class="faq-subtext"><?php echo htmlspecialchars($home_faq['content_subtitle'] ?? $home_faq['content_body'] ?? 'We\'ve got answers. Find quick solutions to your most common queries.'); ?></p>
                        <a href="<?php echo getSitePath('contact'); ?>" class="faq-contact-link">
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
    <?php endif; ?>

<?php include 'includes/footer.php'; ?>
