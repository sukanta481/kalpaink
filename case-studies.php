<?php 
$page_title = 'Case Studies';
include 'includes/header.php'; 

// Extended portfolio items with high-quality images
$portfolio_items = [
    [
        'title' => 'FoodKa Branding',
        'category' => 'Branding,Logo',
        'image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Complete brand identity for a food delivery startup'
    ],
    [
        'title' => 'Shohoj Kotha Podcast',
        'category' => 'Branding,YouTube',
        'image' => 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'YouTube'],
        'description' => 'Podcast branding and YouTube channel design'
    ],
    [
        'title' => 'ServiceZet UI/UX',
        'category' => 'UI/UX,Web',
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop',
        'tags' => ['UI/UX', 'Web'],
        'description' => 'Modern UI/UX design for a service marketplace'
    ],
    [
        'title' => 'KLUBB10 Brand Identity',
        'category' => 'Branding,Logo',
        'image' => 'https://images.unsplash.com/photo-1579952363873-27f3bade9f55?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Logo'],
        'description' => 'Sports club branding and merchandise design'
    ],
    [
        'title' => 'Travel Live Campaign',
        'category' => 'SMM,Branding',
        'image' => 'https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=600&h=400&fit=crop',
        'tags' => ['SMM', 'Branding'],
        'description' => 'Travel agency social media campaign'
    ],
    [
        'title' => 'E-commerce Website',
        'category' => 'Web,UI/UX',
        'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop',
        'tags' => ['Web', 'UI/UX'],
        'description' => 'Full e-commerce website design and development'
    ],
    [
        'title' => 'Restaurant Rebranding',
        'category' => 'Branding,Print',
        'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop',
        'tags' => ['Branding', 'Print'],
        'description' => 'Complete restaurant rebranding with menu design'
    ],
    [
        'title' => 'Tech Startup Logo',
        'category' => 'Logo,Branding',
        'image' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=600&h=400&fit=crop',
        'tags' => ['Logo', 'Branding'],
        'description' => 'Modern logo design for a tech startup'
    ],
    [
        'title' => 'Film Publicity Campaign',
        'category' => 'Film,Print',
        'image' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&h=400&fit=crop',
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
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" loading="lazy">
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
