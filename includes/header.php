
<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kalpanik - Creative Digital Marketing Agency in Kolkata. Specializing in graphics design, branding, and digital marketing services.">
    <meta name="keywords" content="digital marketing, graphics design, branding, Kolkata, web development, SEO">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . SITE_NAME : SITE_NAME . ' - ' . SITE_TAGLINE; ?></title>
    
    <!-- Favicon -->
    <?php if (defined('SITE_FAVICON') && SITE_FAVICON): ?>
    <link rel="icon" href="<?php echo SITE_FAVICON; ?>">
    <link rel="apple-touch-icon" href="<?php echo SITE_FAVICON; ?>">
    <?php else: ?>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL; ?>/assets/images/kalpaink favicon.png">
    <link rel="apple-touch-icon" href="<?php echo SITE_URL; ?>/assets/images/kalpaink favicon.png">
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS (with auto cache-busting) -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime('assets/css/style.css'); ?>">
    
    <!-- Services Page Specific Styles -->
    <?php if (basename($_SERVER['PHP_SELF']) == 'services.php'): ?>
    <link rel="stylesheet" href="assets/css/services-page.css?v=<?php echo filemtime('assets/css/services-page.css'); ?>">
    <?php endif; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" data-bs-theme="dark">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
                <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" class="navbar-logo">
            </a>
            
            <!-- Mobile Toggle -->
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
                <span class="toggler-bar"></span>
            </button>
            
            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile menu header -->
                <div class="mobile-menu-header d-lg-none">
                    <a class="mobile-menu-logo" href="index.php">
                        <img src="<?php echo SITE_LOGO; ?>" alt="<?php echo SITE_NAME; ?>" height="30">
                    </a>
                </div>

                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <span class="nav-icon d-lg-none"><i class="fas fa-home"></i></span>
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php">
                            <span class="nav-icon d-lg-none"><i class="fas fa-users"></i></span>
                            About us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>" href="services.php">
                            <span class="nav-icon d-lg-none"><i class="fas fa-briefcase"></i></span>
                            Service
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'case-studies.php' ? 'active' : ''; ?>" href="case-studies.php">
                            <span class="nav-icon d-lg-none"><i class="fas fa-layer-group"></i></span>
                            Case Studies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vlog-reels.php' ? 'active' : ''; ?>" href="#vlog-reel">
                            <span class="nav-icon d-lg-none"><i class="fas fa-video"></i></span>
                            Vlog & Reels
                        </a>
                    </li>
                </ul>
                
                <!-- CTA Button -->
                <a href="contact.php" class="btn btn-primary cta-btn">
                    <span class="btn-text">Contact us</span>
                    <i class="fas fa-arrow-right btn-arrow"></i>
                </a>

                <!-- Mobile menu footer -->
                <div class="mobile-menu-footer d-lg-none">
                    <div class="mobile-menu-socials">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                    <p class="mobile-menu-tagline">Crafting digital experiences ✨</p>
                </div>
            </div>
        </div>
    </nav>
