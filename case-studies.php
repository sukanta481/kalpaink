<?php 
$page_title = 'Case Studies';
include 'includes/header.php'; 

// Extended portfolio items
$portfolio_items = [
    [
        'title' => 'FoodKa Branding',
        'category' => 'Branding,Logo',
        'image' => 'assets/images/portfolio/foodka.jpg',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Complete brand identity for a food delivery startup'
    ],
    [
        'title' => 'Shohoj Kotha Podcast',
        'category' => 'Branding,YouTube',
        'image' => 'assets/images/portfolio/podcast.jpg',
        'tags' => ['Branding', 'YouTube'],
        'description' => 'Podcast branding and YouTube channel design'
    ],
    [
        'title' => 'ServiceZet UI/UX',
        'category' => 'UI/UX,Web',
        'image' => 'assets/images/portfolio/servicezet.jpg',
        'tags' => ['UI/UX', 'Web'],
        'description' => 'Modern UI/UX design for a service marketplace'
    ],
    [
        'title' => 'KLUBB10 Brand Identity',
        'category' => 'Branding,Logo',
        'image' => 'assets/images/portfolio/klubb10.jpg',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Sports club branding and merchandise design'
    ],
    [
        'title' => 'Travel Live Campaign',
        'category' => 'SMM,Branding',
        'image' => 'assets/images/portfolio/travellive.jpg',
        'tags' => ['SMM', 'Branding'],
        'description' => 'Travel agency social media campaign'
    ],
    [
        'title' => 'E-commerce Website',
        'category' => 'Web,UI/UX',
        'image' => 'assets/images/portfolio/ecommerce.jpg',
        'tags' => ['Web', 'UI/UX'],
        'description' => 'Full e-commerce website design and development'
    ],
    [
        'title' => 'Restaurant Rebranding',
        'category' => 'Branding,Print',
        'image' => 'assets/images/portfolio/restaurant.jpg',
        'tags' => ['Branding', 'Print'],
        'description' => 'Complete restaurant rebranding with menu design'
    ],
    [
        'title' => 'Tech Startup Logo',
        'category' => 'Logo,Branding',
        'image' => 'assets/images/portfolio/techlogo.jpg',
        'tags' => ['Logo', 'Branding'],
        'description' => 'Modern logo design for a tech startup'
    ],
    [
        'title' => 'Film Publicity Campaign',
        'category' => 'Film,Print',
        'image' => 'assets/images/portfolio/film.jpg',
        'tags' => ['Film', 'Print'],
        'description' => 'Film poster and publicity material design'
    ]
];

$categories = ['All', 'Branding', 'UI/UX', 'Web', 'YouTube', 'Print', 'Film'];
?>

    <!-- Case Studies Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="placeholder-image" style="height: 250px; border-radius: 15px;">
                        <i class="fas fa-briefcase" style="font-size: 4rem;"></i>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="bg-white p-4 rounded-4">
                        <h2 class="mb-3">See What Happens When Creativity Meets Purpose</h2>
                        <p class="mb-0">Every project we take on is a collision of bold thinking and meaningful intent. Dive into a showcase of campaigns that didn't just perform—they redefined what's possible for brands.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title mb-4" data-aos="fade-up">Case Studies</h2>
            
            <!-- Filter Buttons -->
            <div class="portfolio-filter" data-aos="fade-up" data-aos-delay="100">
                <?php foreach ($categories as $index => $category): ?>
                <button class="filter-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-filter="<?php echo $category === 'All' ? 'all' : $category; ?>">
                    <?php echo $category; ?>
                </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Portfolio Grid -->
            <div class="row g-4">
                <?php foreach ($portfolio_items as $index => $item): ?>
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?php echo $item['category']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 100; ?>">
                    <div class="case-study-card">
                        <div class="case-study-image">
                            <div class="placeholder-image portfolio">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="case-study-overlay">
                                <a href="#" class="btn btn-white btn-sm">View Project</a>
                            </div>
                        </div>
                        <div class="case-study-content">
                            <h5 class="case-study-title"><?php echo $item['title']; ?></h5>
                            <div class="case-study-tags">
                                <?php foreach ($item['tags'] as $tag): ?>
                                <span class="case-study-tag"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="text-muted mb-3" style="font-size: 0.9rem;"><?php echo $item['description']; ?></p>
                            <a href="#" class="view-details-link">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8" data-aos="fade-right">
                    <h3 class="cta-title">Have a Project in Mind?</h3>
                    <p class="cta-text">Let's collaborate and create something amazing together. Your brand deserves the best!</p>
                </div>
                <div class="col-lg-4 text-lg-end" data-aos="fade-left">
                    <a href="contact.php" class="btn btn-white">Start a Project</a>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
