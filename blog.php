<?php 
$page_title = 'Blog';
include 'includes/header.php'; 

// Demo blog posts
$blog_posts = [
    [
        'title' => '10 Graphic Design Trends to Watch in 2026',
        'category' => 'Design',
        'excerpt' => 'Stay ahead of the curve with these emerging design trends that are shaping the visual landscape this year.',
        'date' => 'January 10, 2026',
        'read_time' => '5 min read',
        'image' => 'assets/images/blog/blog1.jpg'
    ],
    [
        'title' => 'How to Build a Strong Brand Identity',
        'category' => 'Branding',
        'excerpt' => 'Learn the essential steps to create a memorable brand identity that resonates with your target audience.',
        'date' => 'January 8, 2026',
        'read_time' => '7 min read',
        'image' => 'assets/images/blog/blog2.jpg'
    ],
    [
        'title' => 'Social Media Marketing Strategies That Work',
        'category' => 'Marketing',
        'excerpt' => 'Discover proven social media strategies to boost engagement and drive conversions for your business.',
        'date' => 'January 5, 2026',
        'read_time' => '6 min read',
        'image' => 'assets/images/blog/blog3.jpg'
    ],
    [
        'title' => 'The Power of Visual Storytelling',
        'category' => 'Design',
        'excerpt' => 'Explore how visual storytelling can transform your brand communication and connect with audiences.',
        'date' => 'January 3, 2026',
        'read_time' => '4 min read',
        'image' => 'assets/images/blog/blog4.jpg'
    ],
    [
        'title' => 'SEO Best Practices for 2026',
        'category' => 'SEO',
        'excerpt' => 'Updated SEO strategies and best practices to improve your website rankings in search engines.',
        'date' => 'January 1, 2026',
        'read_time' => '8 min read',
        'image' => 'assets/images/blog/blog5.jpg'
    ],
    [
        'title' => 'Creating Effective Email Marketing Campaigns',
        'category' => 'Marketing',
        'excerpt' => 'Tips and tricks for crafting email campaigns that get opened, read, and drive action.',
        'date' => 'December 28, 2025',
        'read_time' => '5 min read',
        'image' => 'assets/images/blog/blog6.jpg'
    ]
];
?>

    <!-- Blog Hero Section -->
    <section class="about-hero" style="padding-bottom: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8" data-aos="fade-up">
                    <h1 class="text-dark mb-3">Our Blog</h1>
                    <p class="text-dark mb-0">Insights, tips, and stories from our team of digital marketing experts.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Grid -->
    <section class="section-padding">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($blog_posts as $index => $post): ?>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($index % 3 + 1) * 100; ?>">
                    <article class="blog-card">
                        <div class="blog-image">
                            <div class="placeholder-image" style="height: 200px;">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </div>
                        <div class="blog-content">
                            <span class="blog-category"><?php echo $post['category']; ?></span>
                            <h5 class="blog-title">
                                <a href="#"><?php echo $post['title']; ?></a>
                            </h5>
                            <p class="blog-excerpt"><?php echo $post['excerpt']; ?></p>
                            <div class="blog-meta">
                                <span><i class="far fa-calendar-alt me-1"></i> <?php echo $post['date']; ?></span>
                                <span><i class="far fa-clock me-1"></i> <?php echo $post['read_time']; ?></span>
                            </div>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5" data-aos="fade-up">
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

    <!-- Newsletter Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h3 class="cta-title">Subscribe to Our Newsletter</h3>
                    <p class="cta-text">Get the latest insights and tips delivered straight to your inbox.</p>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <form class="d-flex gap-2 flex-wrap flex-md-nowrap">
                        <input type="email" class="form-control" placeholder="Enter your email" style="border-radius: 25px; padding: 12px 20px;">
                        <button type="submit" class="btn btn-outline-dark" style="white-space: nowrap;">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
