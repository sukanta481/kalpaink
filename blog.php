<?php 
$page_title = 'Blog';
include 'includes/header.php'; 

// Get page content from CMS (auto-sync)
$blog_content = getPageContent('blog');
$blog_hero = $blog_content['hero'] ?? null;

// Category colors for typography cards
$category_colors = [
    'Design' => ['bg' => '#fdd728', 'text' => '#1a1a1a'],
    'Branding' => ['bg' => '#1a1a1a', 'text' => '#ffffff'],
    'Marketing' => ['bg' => '#667eea', 'text' => '#ffffff'],
    'SEO' => ['bg' => '#43e97b', 'text' => '#1a1a1a'],
];

// Get blogs from CRM database (auto-sync)
$blogs_from_db = getBlogsFromDB();

// If CRM has blogs, use them
if (!empty($blogs_from_db)) {
    // Featured post (first published blog)
    $first_blog = $blogs_from_db[0];
    $featured_post = [
        'title' => $first_blog['title'],
        'category' => $first_blog['category'] ?? 'Design',
        'excerpt' => $first_blog['excerpt'] ?? substr(strip_tags($first_blog['content']), 0, 200) . '...',
        'date' => date('F j, Y', strtotime($first_blog['published_at'] ?? $first_blog['created_at'])),
        'read_time' => ceil(str_word_count(strip_tags($first_blog['content'])) / 200) . ' min read',
        'image' => $first_blog['featured_image'] ?? 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop',
        'slug' => $first_blog['slug'] ?? ''
    ];
    
    // Trending posts (next 4 blogs)
    $trending_posts = [];
    for ($i = 1; $i < min(5, count($blogs_from_db)); $i++) {
        $blog = $blogs_from_db[$i];
        $trending_posts[] = [
            'title' => $blog['title'],
            'category' => $blog['category'] ?? 'Design',
            'excerpt' => $blog['excerpt'] ?? substr(strip_tags($blog['content']), 0, 100) . '...',
            'date' => date('F j, Y', strtotime($blog['published_at'] ?? $blog['created_at'])),
            'read_time' => ceil(str_word_count(strip_tags($blog['content'])) / 200) . ' min read',
            'image' => $blog['featured_image'] ?? '',
            'slug' => $blog['slug'] ?? ''
        ];
    }
    
    // Recent posts (remaining blogs)
    $recent_posts = [];
    for ($i = 5; $i < count($blogs_from_db); $i++) {
        $blog = $blogs_from_db[$i];
        $recent_posts[] = [
            'title' => $blog['title'],
            'category' => $blog['category'] ?? 'Design',
            'date' => date('F j, Y', strtotime($blog['published_at'] ?? $blog['created_at'])),
            'read_time' => ceil(str_word_count(strip_tags($blog['content'])) / 200) . ' min read',
            'slug' => $blog['slug'] ?? ''
        ];
    }
} else {
    // Fallback to static content
    $featured_post = [
        'title' => '10 Graphic Design Trends to Watch in 2026',
        'category' => 'Design',
        'excerpt' => 'Stay ahead of the curve with these emerging design trends that are shaping the visual landscape this year. From bold typography to immersive 3D experiences, discover what\'s defining modern design.',
        'date' => 'January 10, 2026',
        'read_time' => '5 min read',
        'image' => 'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=800&h=600&fit=crop'
    ];

    $trending_posts = [
        [
            'title' => 'How to Build a Strong Brand Identity',
            'category' => 'Branding',
            'excerpt' => 'Learn the essential steps to create a memorable brand identity.',
            'date' => 'January 8, 2026',
            'read_time' => '7 min read',
            'image' => 'https://images.unsplash.com/photo-1493421419110-74f4e85ba126?w=600&h=400&fit=crop'
        ],
        [
            'title' => 'Social Media Marketing Strategies That Work',
            'category' => 'Marketing',
            'excerpt' => 'Discover proven social media strategies to boost engagement.',
            'date' => 'January 5, 2026',
            'read_time' => '6 min read',
            'image' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=600&h=400&fit=crop'
        ],
        [
            'title' => 'The Power of Visual Storytelling',
            'category' => 'Design',
            'excerpt' => 'Explore how visual storytelling can transform your brand.',
            'date' => 'January 3, 2026',
            'read_time' => '4 min read',
            'image' => 'https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=600&h=400&fit=crop'
        ],
        [
            'title' => 'SEO Best Practices for 2026',
            'category' => 'SEO',
            'excerpt' => 'Updated SEO strategies to improve your website rankings.',
            'date' => 'January 1, 2026',
            'read_time' => '8 min read',
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop'
        ]
    ];

    $recent_posts = [
        ['title' => 'Creating Effective Email Marketing Campaigns', 'category' => 'Marketing', 'date' => 'December 28, 2025', 'read_time' => '5 min read'],
        ['title' => 'Logo Design: From Concept to Creation', 'category' => 'Design', 'date' => 'December 25, 2025', 'read_time' => '6 min read'],
        ['title' => 'Content Marketing ROI: Measuring Success', 'category' => 'Marketing', 'date' => 'December 22, 2025', 'read_time' => '4 min read'],
        ['title' => 'Website Speed Optimization Tips', 'category' => 'SEO', 'date' => 'December 20, 2025', 'read_time' => '7 min read'],
        ['title' => 'Color Psychology in Brand Design', 'category' => 'Branding', 'date' => 'December 18, 2025', 'read_time' => '5 min read']
    ];
}
?>

    <!-- Featured Story Hero Section -->
    <section class="blog-featured-hero">
        <div class="container">
            <div class="row align-items-stretch">
                <!-- Featured Image -->
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="featured-image-wrapper">
                        <img src="<?php echo $featured_post['image']; ?>" alt="<?php echo $featured_post['title']; ?>">
                        <span class="featured-badge">Featured</span>
                    </div>
                </div>
                <!-- Featured Content -->
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="featured-content-wrapper">
                        <span class="featured-category"><?php echo $featured_post['category']; ?></span>
                        <h1 class="featured-title"><?php echo $featured_post['title']; ?></h1>
                        <p class="featured-excerpt"><?php echo $featured_post['excerpt']; ?></p>
                        <div class="featured-meta">
                            <span><i class="far fa-calendar-alt"></i> <?php echo $featured_post['date']; ?></span>
                            <span><i class="far fa-clock"></i> <?php echo $featured_post['read_time']; ?></span>
                        </div>
                        <a href="#" class="btn btn-primary mt-4">Read Article <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Featured Card (Instagram Story Style) -->
        <div class="mobile-featured-card d-lg-none">
            <div class="mobile-featured-bg" style="background-image: url('<?php echo $featured_post['image']; ?>');"></div>
            <div class="mobile-featured-overlay"></div>
            <div class="mobile-featured-content">
                <span class="featured-badge">Featured</span>
                <span class="featured-category"><?php echo $featured_post['category']; ?></span>
                <h2 class="mobile-featured-title"><?php echo $featured_post['title']; ?></h2>
                <p class="mobile-featured-excerpt"><?php echo $featured_post['excerpt']; ?></p>
                <a href="#" class="btn btn-primary">Read Article</a>
            </div>
        </div>
    </section>

    <!-- Trending Now Section (Horizontal Swipe on Mobile) -->
    <section class="blog-trending-section section-padding">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="section-title mb-1" data-aos="fade-up">Trending Now</h2>
                    <p class="text-muted mb-0 d-none d-md-block" data-aos="fade-up" data-aos-delay="100">Popular articles this week</p>
                </div>
                <a href="#" class="btn btn-outline-dark btn-sm d-none d-md-inline-block" data-aos="fade-up">View All</a>
            </div>
            
            <!-- Trending Cards Grid (Desktop) / Swipe Deck (Mobile) -->
            <div class="trending-cards-wrapper">
                <?php foreach ($trending_posts as $index => $post): ?>
                <?php $colors = $category_colors[$post['category']] ?? ['bg' => '#fdd728', 'text' => '#1a1a1a']; ?>
                <div class="trending-card-item" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 100; ?>">
                    <article class="blog-card-v2">
                        <!-- Typography Card Background (No Grey Boxes!) -->
                        <div class="blog-card-visual" style="background-color: <?php echo $colors['bg']; ?>;">
                            <?php if (!empty($post['image'])): ?>
                            <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>" class="blog-card-img">
                            <?php else: ?>
                            <div class="typography-card" style="color: <?php echo $colors['text']; ?>;">
                                <span class="typo-category"><?php echo $post['category']; ?></span>
                                <span class="typo-title"><?php echo $post['title']; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="blog-card-body">
                            <span class="blog-category-v2"><?php echo $post['category']; ?></span>
                            <h5 class="blog-title-v2">
                                <a href="#"><?php echo $post['title']; ?></a>
                            </h5>
                            <p class="blog-excerpt-v2"><?php echo $post['excerpt']; ?></p>
                            <div class="blog-meta-v2">
                                <span><i class="far fa-calendar-alt"></i> <?php echo $post['date']; ?></span>
                                <span><i class="far fa-clock"></i> <?php echo $post['read_time']; ?></span>
                            </div>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Mobile Progress Bar -->
            <div class="blog-swipe-progress d-lg-none">
                <div class="progress-track">
                    <div class="progress-fill" id="blogTrendingProgress"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Articles Section (Compact List on Mobile) -->
    <section class="blog-recent-section section-padding bg-light">
        <div class="container">
            <div class="section-header d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0" data-aos="fade-up">Recent Articles</h2>
                <a href="#" class="btn btn-outline-dark btn-sm" data-aos="fade-up">View All</a>
            </div>
            
            <!-- Desktop: Grid Layout | Mobile: Compact List -->
            <div class="recent-articles-grid">
                <?php foreach ($recent_posts as $index => $post): ?>
                <?php $colors = $category_colors[$post['category']] ?? ['bg' => '#fdd728', 'text' => '#1a1a1a']; ?>
                <article class="recent-article-item" data-aos="fade-up" data-aos-delay="<?php echo ($index + 1) * 50; ?>">
                    <!-- Desktop Card View -->
                    <div class="recent-card-desktop">
                        <div class="recent-card-visual" style="background-color: <?php echo $colors['bg']; ?>;">
                            <div class="typography-card-sm" style="color: <?php echo $colors['text']; ?>;">
                                <span><?php echo $post['category']; ?></span>
                            </div>
                        </div>
                        <div class="recent-card-content">
                            <span class="blog-category-v2"><?php echo $post['category']; ?></span>
                            <h5 class="blog-title-v2"><a href="#"><?php echo $post['title']; ?></a></h5>
                            <div class="blog-meta-v2">
                                <span><i class="far fa-calendar-alt"></i> <?php echo $post['date']; ?></span>
                                <span><i class="far fa-clock"></i> <?php echo $post['read_time']; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Compact List View -->
                    <a href="#" class="recent-list-mobile">
                        <div class="recent-list-thumb" style="background-color: <?php echo $colors['bg']; ?>;">
                            <span style="color: <?php echo $colors['text']; ?>;"><?php echo substr($post['category'], 0, 1); ?></span>
                        </div>
                        <div class="recent-list-content">
                            <h6 class="recent-list-title"><?php echo $post['title']; ?></h6>
                            <div class="recent-list-meta">
                                <span><?php echo $post['date']; ?></span>
                                <span><?php echo $post['read_time']; ?></span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right recent-list-arrow"></i>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination (Desktop Only) -->
            <div class="d-none d-lg-flex justify-content-center mt-5" data-aos="fade-up">
                <nav aria-label="Blog pagination">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </section>

    <!-- Newsletter Section - The Insider Club -->
    <section class="newsletter-insider-section">
        <div class="newsletter-glow-bg"></div>
        <div class="container">
            <div class="newsletter-content text-center" data-aos="zoom-in">
                <span class="newsletter-badge">Join the Insider Club</span>
                <h2 class="newsletter-headline">Get Unfair Advantages</h2>
                <p class="newsletter-subtext">Join 5,000+ marketers getting exclusive insights, tips, and strategies delivered straight to their inbox.</p>
                
                <form class="newsletter-form">
                    <div class="newsletter-input-group">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit">
                            <span class="btn-text">Subscribe</span>
                            <span class="btn-icon"><i class="fas fa-arrow-right"></i></span>
                        </button>
                    </div>
                    <p class="newsletter-disclaimer">No spam. Unsubscribe anytime.</p>
                </form>
            </div>
        </div>
        <!-- Floating particles -->
        <div class="newsletter-particles">
            <span></span><span></span><span></span><span></span><span></span>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
