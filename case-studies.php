<?php 
$page_title = 'Case Studies';
include 'includes/header.php'; 

// Extended portfolio items with high-quality images - varied sizes for masonry
$portfolio_items = [
    [
        'title' => 'FoodKa Branding',
        'category' => 'Branding,Logo',
        'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Complete brand identity for a food delivery startup',
        'size' => 'normal'
    ],
    [
        'title' => 'Shohoj Kotha Podcast',
        'category' => 'Branding,YouTube',
        'image' => 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=600&h=800&fit=crop',
        'tags' => ['Branding', 'YouTube'],
        'description' => 'Podcast branding and YouTube channel design',
        'size' => 'tall'
    ],
    [
        'title' => 'ServiceZet UI/UX',
        'category' => 'UI/UX,Web',
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop',
        'tags' => ['UI/UX', 'Web'],
        'description' => 'Modern UI/UX design for a service marketplace',
        'size' => 'wide'
    ],
    [
        'title' => 'KLUBB10 Brand Identity',
        'category' => 'Branding,Logo',
        'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Sports club branding and merchandise design',
        'size' => 'normal'
    ],
    [
        'title' => 'Travel Live Campaign',
        'category' => 'SMM,Branding',
        'image' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=600&h=800&fit=crop',
        'tags' => ['SMM', 'Branding'],
        'description' => 'Travel agency social media campaign',
        'size' => 'tall'
    ],
    [
        'title' => 'E-commerce Website',
        'category' => 'Web,UI/UX',
        'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
        'tags' => ['Web', 'UI/UX'],
        'description' => 'Full e-commerce website design and development',
        'size' => 'normal'
    ],
    [
        'title' => 'Restaurant Rebranding',
        'category' => 'Branding,Print',
        'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=400&fit=crop',
        'tags' => ['Branding', 'Print'],
        'description' => 'Complete restaurant rebranding with menu design',
        'size' => 'wide'
    ],
    [
        'title' => 'Tech Startup Logo',
        'category' => 'Logo,Branding',
        'image' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=600&h=400&fit=crop',
        'tags' => ['Logo', 'Branding'],
        'description' => 'Modern logo design for a tech startup',
        'size' => 'normal'
    ],
    [
        'title' => 'Film Publicity Campaign',
        'category' => 'Film,Print',
        'image' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&h=400&fit=crop',
        'tags' => ['Film', 'Print'],
        'description' => 'Film poster and publicity material design',
        'size' => 'normal'
    ]
];

$categories = ['All', 'Branding', 'UI/UX', 'Web', 'YouTube', 'Print', 'Film'];

// Sample images for floating gallery
$gallery_previews = [
    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=300&h=200&fit=crop',
    'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=300&h=200&fit=crop',
    'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=300&h=200&fit=crop',
    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300&h=200&fit=crop'
];
?>

    <!-- Case Studies Hero Section - The Trophy Room -->
    <section class="trophy-room-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <!-- Floating Gallery -->
                    <div class="floating-gallery">
                        <div class="gallery-float-item item-1">
                            <img src="<?php echo $gallery_previews[0]; ?>" alt="Project Preview">
                        </div>
                        <div class="gallery-float-item item-2">
                            <img src="<?php echo $gallery_previews[1]; ?>" alt="Project Preview">
                        </div>
                        <div class="gallery-float-item item-3">
                            <img src="<?php echo $gallery_previews[2]; ?>" alt="Project Preview">
                        </div>
                        <div class="gallery-float-item item-4">
                            <img src="<?php echo $gallery_previews[3]; ?>" alt="Project Preview">
                        </div>
                        <!-- Decorative elements -->
                        <div class="gallery-decoration dec-circle"></div>
                        <div class="gallery-decoration dec-dots"></div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="trophy-content">
                        <span class="trophy-badge">Our Portfolio</span>
                        <h1 class="trophy-title">Proof of Impact.</h1>
                        <p class="trophy-subtitle">Every project we take on is a collision of bold thinking and meaningful intent. Dive into a showcase of campaigns that didn't just perform—they redefined what's possible for brands.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section with Masonry Grid -->
    <section class="case-studies-section section-padding">
        <div class="container">
            <h2 class="section-title mb-4" data-aos="fade-up">Case Studies</h2>
            
            <!-- Swipeable Filter Bar -->
            <div class="filter-scroll-wrapper" data-aos="fade-up" data-aos-delay="100">
                <div class="portfolio-filter-scroll">
                    <?php foreach ($categories as $index => $category): ?>
                    <button class="filter-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-filter="<?php echo $category === 'All' ? 'all' : $category; ?>">
                        <?php echo $category; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Custom Cursor for Desktop -->
            <div class="custom-cursor-view">
                <span>VIEW</span>
            </div>
            
            <!-- Masonry Portfolio Grid -->
            <div class="masonry-portfolio-grid">
                <?php foreach ($portfolio_items as $index => $item): ?>
                <div class="masonry-item masonry-<?php echo $item['size']; ?> portfolio-item" data-category="<?php echo $item['category']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 100; ?>">
                    <div class="case-study-card-v2">
                        <div class="case-study-image-v2">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" loading="lazy">
                            <div class="case-study-overlay-v2">
                                <a href="#" class="overlay-link">
                                    <span class="sr-only">View Project</span>
                                </a>
                            </div>
                        </div>
                        <div class="case-study-content-v2">
                            <h5 class="case-study-title-v2"><?php echo $item['title']; ?></h5>
                            <div class="case-study-tags-v2">
                                <?php foreach ($item['tags'] as $tag): ?>
                                <span class="case-study-tag-v2"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="case-study-desc-v2"><?php echo $item['description']; ?></p>
                            <a href="#" class="view-details-link-v2">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Progress Bar Indicator (Mobile Only) -->
            <div class="swipe-progress-bar d-lg-none">
                <div class="progress-track">
                    <div class="progress-fill" id="caseStudyProgress"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Holographic CTA Section (Black Hole) -->
    <section class="cta-holographic">
        <div class="cta-glow-bg"></div>
        <div class="container">
            <div class="cta-content text-center" data-aos="zoom-in" data-aos-duration="1000">
                <h2 class="cta-headline">Have a Project in Mind?</h2>
                <p class="cta-subtext">Let's collaborate and create something amazing together. Your brand deserves the best!</p>
                <a href="contact.php" class="cta-pulse-btn">
                    <span class="btn-text">Start a Project</span>
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
