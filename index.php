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
                <!-- Slide 1 - Graphics Design -->
                <div class="carousel-item active">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6">
                                <div class="hero-content" data-aos="fade-right">
                                    <span class="hero-badge">Creative Design Studio</span>
                                    <h1 class="hero-title">Reimagining with Purpose</h1>
                                    <p class="hero-subtitle">Transform your brand with creative design solutions. We specialize in graphics, branding, and digital marketing that makes an impact.</p>
                                    <div class="hero-buttons">
                                        <a href="contact.php" class="btn btn-outline-dark">Get Started</a>
                                        <a href="case-studies.php" class="btn btn-white">Our Work</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="uploads/1st slider.jpeg" alt="Creative Design" class="img-fluid hero-banner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Digital Marketing -->
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6">
                                <div class="hero-content">
                                    <span class="hero-badge">Digital Marketing</span>
                                    <h1 class="hero-title">Grow Your Digital Presence</h1>
                                    <p class="hero-subtitle">Strategic digital marketing solutions to boost your brand visibility, engage your audience, and drive measurable results.</p>
                                    <div class="hero-buttons">
                                        <a href="services.php" class="btn btn-outline-dark">Our Services</a>
                                        <a href="contact.php" class="btn btn-white">Get Quote</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <div class="placeholder-image hero">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Brand Identity -->
                <div class="carousel-item">
                    <div class="container">
                        <div class="row align-items-center min-vh-hero">
                            <div class="col-lg-6">
                                <div class="hero-content">
                                    <span class="hero-badge">Brand Identity</span>
                                    <h1 class="hero-title">Build Your Unique Brand</h1>
                                    <p class="hero-subtitle">Create a memorable brand identity that stands out. From logos to complete brand guidelines, we craft identities that resonate.</p>
                                    <div class="hero-buttons">
                                        <a href="about.php" class="btn btn-outline-dark">About Us</a>
                                        <a href="case-studies.php" class="btn btn-white">View Portfolio</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <div class="placeholder-image hero">
                                        <i class="fas fa-gem"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Client Logos -->
    <section class="client-logos">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-auto">
                    <div class="client-logo-item" data-aos="fade-up" data-aos-delay="100">
                        <span style="font-weight: 600; color: #333;">Client 1</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="client-logo-item" data-aos="fade-up" data-aos-delay="200">
                        <span style="font-weight: 600; color: #333;">Client 2</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="client-logo-item" data-aos="fade-up" data-aos-delay="300">
                        <span style="font-weight: 600; color: #333;">Client 3</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="client-logo-item" data-aos="fade-up" data-aos-delay="400">
                        <span style="font-weight: 600; color: #333;">Client 4</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="client-logo-item" data-aos="fade-up" data-aos-delay="500">
                        <span style="font-weight: 600; color: #333;">Client 5</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section class="welcome-section section-padding">
        <div class="container">
            <div class="welcome-card" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="welcome-image">
                            <div class="placeholder-image" style="height: 350px; border-radius: 15px;">
                                <i class="fas fa-chess-king"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <span class="welcome-badge">Who We Are</span>
                        <h2>Welcome to <span class="text-yellow">Kalpoink</span></h2>
                        <p>We are a creative digital marketing agency based in Kolkata, specializing in graphics design and comprehensive digital marketing solutions. Our team combines artistic vision with strategic thinking to deliver exceptional results for our clients.</p>
                        <p>With expertise in everything from logo design to complete brand identity packages, social media marketing, and web development, we're your one-stop solution for all things digital.</p>
                        <a href="about.php" class="btn btn-primary">Learn More About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section section-padding bg-light-gray" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Our Services</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Comprehensive digital solutions to elevate your brand</p>
            </div>
            
            <div class="row g-4">
                <?php foreach ($services as $index => $service): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="fas <?php echo $service['icon']; ?>"></i>
                        </div>
                        <h4 class="service-title"><?php echo $service['title']; ?></h4>
                        <p class="service-description"><?php echo $service['description']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="services.php" class="btn btn-primary">View All Services</a>
            </div>
        </div>
    </section>

    <!-- Case Studies Section -->
    <section class="case-studies-section section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Case Studies</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">Some of our recent creative work</p>
            </div>
            
            <div class="row g-4">
                <?php foreach (array_slice($case_studies, 0, 6) as $index => $case): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="case-study-card">
                        <div class="case-study-image">
                            <div class="placeholder-image portfolio">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="case-study-overlay">
                                <a href="case-studies.php" class="btn btn-white btn-sm">View Project</a>
                            </div>
                        </div>
                        <div class="case-study-content">
                            <h5 class="case-study-title"><?php echo $case['title']; ?></h5>
                            <div class="case-study-tags">
                                <?php foreach ($case['tags'] as $tag): ?>
                                <span class="case-study-tag"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <a href="case-studies.php" class="view-details-link">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="case-studies.php" class="btn btn-primary">View All Projects</a>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section section-padding">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-8">
                    <h2 class="section-title" data-aos="fade-right">Our Team</h2>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="team-nav" data-aos="fade-left">
                        <button class="team-nav-btn"><i class="fas fa-chevron-left"></i></button>
                        <button class="team-nav-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center g-4">
                <?php foreach ($team_members as $index => $member): ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <div class="team-card">
                        <div class="team-image-wrapper">
                            <div class="placeholder-image team">
                                <i class="fas fa-user"></i>
                            </div>
                            <a href="<?php echo $member['linkedin']; ?>" class="team-linkedin" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                        <h5 class="team-name"><?php echo $member['name']; ?></h5>
                        <p class="team-position"><?php echo $member['position']; ?></p>
                        <p class="team-experience"><?php echo $member['experience']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
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
