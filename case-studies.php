<?php 
$page_title = 'Case Studies';
include 'includes/header.php'; 

// Get page content from CMS (auto-sync)
$cs_content = getPageContent('case_studies');
$cs_hero = $cs_content['hero'] ?? null;

// Get projects from CRM database (auto-sync)
$projects_from_db = getProjectsFromDB();

// Use CRM projects if available, otherwise use fallback
if (!empty($projects_from_db)) {
    $portfolio_items = [];
    $sizes = ['normal', 'tall', 'wide', 'normal', 'tall', 'normal', 'wide', 'normal', 'normal'];
    
    foreach ($projects_from_db as $index => $project) {
        $tags = is_array($project['tags']) ? $project['tags'] : (json_decode($project['tags'], true) ?? []);
        $portfolio_items[] = [
            'title' => $project['title'],
            'category' => implode(',', $tags),
            'image' => $project['featured_image'] ?? 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=600&h=400&fit=crop',
            'tags' => $tags,
            'description' => $project['short_description'] ?? '',
            'size' => $sizes[$index % count($sizes)],
            'slug' => $project['slug'] ?? ''
        ];
    }
    
    // Build categories dynamically from projects
    $all_tags = [];
    foreach ($portfolio_items as $item) {
        $all_tags = array_merge($all_tags, $item['tags']);
    }
    $categories = array_merge(['All'], array_unique($all_tags));
} else {
    // Fallback to static content
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
}

// Sample images for floating gallery
$gallery_previews = [];
foreach (array_slice($portfolio_items, 0, 4) as $item) {
    $gallery_previews[] = $item['image'];
}
if (count($gallery_previews) < 4) {
    $gallery_previews = [
        'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=300&h=200&fit=crop',
        'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=300&h=200&fit=crop',
        'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=300&h=200&fit=crop',
        'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300&h=200&fit=crop'
    ];
}
?>

    <!-- Case Studies Hero Section -->
    <section class="case-hero">
        <div class="case-hero-noise"></div>
        <div class="case-hero-glow"></div>
        <div class="case-hero-glow case-hero-glow--2"></div>
        
        <!-- Scattered floating project images -->
        <div class="case-hero-scattered">
            <?php 
            $scattered_positions = [
                ['class' => 'sc-1', 'size' => '220x150'],
                ['class' => 'sc-2', 'size' => '180x130'],
                ['class' => 'sc-3', 'size' => '200x140'],
                ['class' => 'sc-4', 'size' => '160x120'],
                ['class' => 'sc-5', 'size' => '190x135'],
                ['class' => 'sc-6', 'size' => '170x125'],
            ];
            foreach (array_slice($portfolio_items, 0, 6) as $i => $item): 
                $pos = $scattered_positions[$i];
            ?>
            <div class="sc-card <?php echo $pos['class']; ?>" data-speed="<?php echo 0.02 + ($i * 0.008); ?>">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" loading="lazy">
                <div class="sc-card-shine"></div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="container">
            <div class="case-hero-content" data-aos="fade-up">
                <div class="case-hero-eyebrow">
                    <span class="eyebrow-line"></span>
                    <span class="eyebrow-text"><?php echo htmlspecialchars($cs_hero['content_subtitle'] ?? 'Our Portfolio'); ?></span>
                    <span class="eyebrow-line"></span>
                </div>
                <h1 class="case-hero-title">
                    <span class="title-line"><?php echo htmlspecialchars($cs_hero['content_title'] ?? 'Proof of'); ?></span>
                    <span class="title-line title-line--accent"><?php echo htmlspecialchars($cs_hero['extra']['accent_text'] ?? 'Impact'); ?><span class="title-dot">.</span></span>
                </h1>
                <p class="case-hero-subtitle"><?php echo htmlspecialchars($cs_hero['content_body'] ?? 'Every project we take on is a collision of bold thinking and meaningful intent. Campaigns that didn\'t just perform—they redefined what\'s possible.'); ?></p>
                <div class="case-hero-stats">
                    <div class="hero-stat">
                        <span class="hero-stat-number">50+</span>
                        <span class="hero-stat-label">Projects</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">35+</span>
                        <span class="hero-stat-label">Clients</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">4.9</span>
                        <span class="hero-stat-label">Rating</span>
                    </div>
                </div>
                <a href="#portfolio" class="case-hero-cta">
                    <span>Explore Work</span>
                    <i class="fas fa-arrow-down"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="case-studies-section section-padding" id="portfolio">
        <div class="container">
            <!-- Swipeable Filter Bar -->
            <div class="filter-scroll-wrapper" data-aos="fade-up">
                <div class="portfolio-filter-scroll">
                    <?php foreach ($categories as $index => $category): ?>
                    <button class="filter-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-filter="<?php echo $category === 'All' ? 'all' : $category; ?>">
                        <?php echo $category; ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Portfolio Grid -->
            <div class="row g-4 portfolio-grid-v3">
                <?php foreach ($portfolio_items as $index => $item): ?>
                <div class="col-lg-4 col-md-6 portfolio-item" data-category="<?php echo $item['category']; ?>" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 80; ?>">
                    <div class="case-card-v3">
                        <div class="case-card-v3-image">
                            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" loading="lazy">
                            <div class="case-card-v3-overlay">
                                <a href="#" class="case-card-v3-view">
                                    <i class="fas fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </div>
                        <div class="case-card-v3-body">
                            <h5 class="case-card-v3-title"><?php echo $item['title']; ?></h5>
                            <div class="case-card-v3-tags">
                                <?php foreach ($item['tags'] as $tag): ?>
                                <span class="case-card-v3-tag"><?php echo $tag; ?></span>
                                <?php endforeach; ?>
                            </div>
                            <p class="case-card-v3-desc"><?php echo $item['description']; ?></p>
                            <a href="#" class="case-card-v3-link">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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
