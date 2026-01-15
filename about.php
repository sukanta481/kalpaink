<?php 
$page_title = 'About Us';
include 'includes/header.php'; 
?>

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Hero Image Area -->
                    <div class="d-flex align-items-end" style="min-height: 300px;">
                        <div class="placeholder-image" style="width: 100%; height: 250px; border-radius: 15px;">
                            <i class="fas fa-chess-queen" style="font-size: 4rem;"></i>
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
                        <h2>About Us</h2>
                        <p>At Kalpoink, creativity isn't just our passion – it's our heartbeat. We're a dynamic crew of young minds from diverse backgrounds, united by a shared love for all things digital. From creating visual content to designing brand new identities, our talented team lives to push the limits of digital storytelling.</p>
                        <p>With fresh ideas and an unbridled enthusiasm, we turn challenges into opportunities and dreams into realities. We are based in Kolkata and serve clients across India and beyond.</p>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="row mt-5">
                    <div class="col-md-3 col-6">
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                            <p class="mb-1"><strong>Successfully completed projects</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                            <p class="mb-1"><strong>Passionate team members ready to innovate</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                            <p class="mb-1"><strong>Brands trust us to amplify their digital presence</strong></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                            <p class="mb-1"><strong>Creative projects executed with excellence</strong></p>
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
                    <div class="who-we-are-image">
                        <div class="placeholder-image" style="height: 400px; border-radius: 15px;">
                            <i class="fas fa-users" style="font-size: 4rem;"></i>
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
                <div class="row align-items-center">
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

    <!-- Our Team Section -->
    <section class="team-section section-padding bg-light-gray">
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
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 150; ?>">
                    <div class="team-card">
                        <div class="team-image-wrapper">
                            <div class="placeholder-image team" style="width: 220px; height: 280px;">
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

<?php include 'includes/footer.php'; ?>
