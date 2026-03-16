<?php
/**
 * Case Study Detail Page
 * Route: /case-study/{slug}
 * Displays full case study with client logo and presentation image
 */

// Set working directory to project root
chdir(__DIR__ . '/..');

$page_title = 'Case Study';
$current_nav = 'case-studies';

require_once __DIR__ . '/../config.php';

// Get slug from URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if (empty($slug)) {
    header('Location: ' . SITE_URL . '/case-studies');
    exit;
}

// Load project from database
$project = getProjectBySlug($slug);

// Fallback: check hardcoded projects
if (!$project) {
    header('HTTP/1.0 404 Not Found');
    include __DIR__ . '/../404.php';
    exit;
}

// Prepare project data
$p_title = $project['title'];
$p_slug = $project['slug'] ?? $slug;
$p_short = $project['short_description'] ?? '';
$p_full = $project['full_description'] ?? '';
$p_client = $project['client_name'] ?? '';
$p_category = $project['category'] ?? '';
$p_tags = is_array($project['tags']) ? $project['tags'] : (json_decode($project['tags'] ?? '[]', true) ?? []);
$p_url = $project['project_url'] ?? '';
$p_date = $project['project_date'] ?? '';
$p_featured_image = $project['featured_image'] ?? '';
$p_case_study_image = $project['case_study_image'] ?? '';
$p_client_logo = $project['client_logo'] ?? '';

// Override SEO
$page_seo = [
    'title' => $p_title . ' - Case Study | ' . SITE_NAME . ' Content Marketing Company',
    'description' => strip_tags($p_short) . ' A case study by ' . SITE_NAME . ', a content marketing company and creative design house in Kolkata.',
];
$canonical_url = SITE_URL . '/case-study/' . $p_slug;

include __DIR__ . '/../includes/header.php';

// Get related projects
$all_projects = getProjectsFromDB(8);
$related_projects = array_filter($all_projects, function($p) use ($p_title) {
    return ($p['title'] ?? '') !== $p_title;
});
$related_projects = array_slice($related_projects, 0, 3);
?>

    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/case-study-detail.css">

    <!-- JSON-LD: CreativeWork + BreadcrumbList -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CreativeWork",
        "name": <?php echo json_encode($p_title); ?>,
        "description": <?php echo json_encode(strip_tags($p_short)); ?>,
        "creator": {"@id": "<?php echo SITE_URL; ?>/#organization"},
        "url": "<?php echo $canonical_url; ?>"<?php if (!empty($p_featured_image)): ?>,
        "image": "<?php echo SITE_URL . '/' . htmlspecialchars($p_featured_image); ?>"<?php endif; ?><?php if (!empty($p_client)): ?>,
        "accountablePerson": {"@type": "Organization", "name": <?php echo json_encode($p_client); ?>}<?php endif; ?>

    }
    </script>
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {"@type": "ListItem", "position": 1, "name": "Home", "item": "<?php echo SITE_URL; ?>/"},
            {"@type": "ListItem", "position": 2, "name": "Case Studies", "item": "<?php echo SITE_URL; ?>/case-studies"},
            {"@type": "ListItem", "position": 3, "name": <?php echo json_encode($p_title); ?>, "item": "<?php echo $canonical_url; ?>"}
        ]
    }
    </script>

    <!-- Hero Section -->
    <section class="cs-detail-hero">
        <div class="cs-detail-hero-noise"></div>
        <div class="container">
            <nav class="cs-detail-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo SITE_URL; ?>/">Home</a>
                <span class="cs-bc-sep">/</span>
                <a href="<?php echo SITE_URL; ?>/case-studies">Case Studies</a>
                <span class="cs-bc-sep">/</span>
                <span class="cs-bc-current"><?php echo htmlspecialchars($p_title); ?></span>
            </nav>

            <div class="cs-detail-hero-content" data-aos="fade-up">
                <?php if (!empty($p_client_logo)): ?>
                <div class="cs-detail-client-logo">
                    <img src="<?php echo SITE_URL . '/' . htmlspecialchars($p_client_logo); ?>" alt="<?php echo htmlspecialchars($p_client); ?> Logo">
                </div>
                <?php endif; ?>

                <h1 class="cs-detail-hero-title"><?php echo htmlspecialchars($p_title); ?></h1>

                <?php if (!empty($p_short)): ?>
                <p class="cs-detail-hero-subtitle"><?php echo htmlspecialchars($p_short); ?></p>
                <?php endif; ?>

                <div class="cs-detail-meta">
                    <?php if (!empty($p_client)): ?>
                    <div class="cs-detail-meta-item">
                        <span class="cs-meta-label">Client</span>
                        <span class="cs-meta-value"><?php echo htmlspecialchars($p_client); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($p_category)): ?>
                    <div class="cs-detail-meta-item">
                        <span class="cs-meta-label">Category</span>
                        <span class="cs-meta-value"><?php echo htmlspecialchars($p_category); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($p_date)): ?>
                    <div class="cs-detail-meta-item">
                        <span class="cs-meta-label">Date</span>
                        <span class="cs-meta-value"><?php echo date('M Y', strtotime($p_date)); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($p_tags)): ?>
                <div class="cs-detail-tags">
                    <?php foreach ($p_tags as $tag): ?>
                    <span class="cs-detail-tag"><?php echo htmlspecialchars($tag); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($p_url)): ?>
                <a href="<?php echo htmlspecialchars($p_url); ?>" target="_blank" rel="noopener noreferrer" class="cs-detail-visit-btn">
                    <span>Visit Website</span>
                    <i class="fas fa-external-link-alt"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Case Study Presentation Image -->
    <?php if (!empty($p_case_study_image)): ?>
    <section class="cs-detail-presentation">
        <div class="container">
            <div class="cs-detail-image-wrap" data-aos="fade-up">
                <img src="<?php echo SITE_URL . '/' . htmlspecialchars($p_case_study_image); ?>" alt="<?php echo htmlspecialchars($p_title); ?> - Full Case Study" class="cs-detail-full-image" loading="lazy">
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Project Description (if has full description) -->
    <?php if (!empty($p_full)): ?>
    <section class="cs-detail-description">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <h2 class="cs-detail-section-title">About This Project</h2>
                    <div class="cs-detail-body">
                        <?php echo $p_full; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Related Projects -->
    <?php if (!empty($related_projects)): ?>
    <section class="cs-detail-related">
        <div class="container">
            <h2 class="cs-detail-section-title text-center" data-aos="fade-up">Explore More Work</h2>
            <p class="cs-detail-section-sub text-center" data-aos="fade-up" data-aos-delay="50">Check out other projects from our portfolio</p>
            <div class="cs-detail-related-grid" data-aos="fade-up" data-aos-delay="100">
                <?php foreach ($related_projects as $rp):
                    $rp_slug = $rp['slug'] ?? strtolower(str_replace(' ', '-', $rp['title']));
                    $rp_tags = is_array($rp['tags']) ? $rp['tags'] : (json_decode($rp['tags'] ?? '[]', true) ?? []);
                    $rp_image = $rp['featured_image'] ?? $rp['image'] ?? '';
                    $has_case_study = !empty($rp['case_study_image']);
                ?>
                <a href="<?php echo $has_case_study ? SITE_URL . '/case-study/' . htmlspecialchars($rp_slug) : '#'; ?>" class="cs-related-card">
                    <div class="cs-related-image">
                        <?php if (!empty($rp_image)): ?>
                        <img src="<?php echo SITE_URL . '/' . htmlspecialchars($rp_image); ?>" alt="<?php echo htmlspecialchars($rp['title']); ?>" loading="lazy">
                        <?php else: ?>
                        <div class="cs-related-placeholder"><i class="fas fa-image"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="cs-related-body">
                        <h3 class="cs-related-title"><?php echo htmlspecialchars($rp['title']); ?></h3>
                        <div class="cs-related-tags">
                            <?php foreach (array_slice($rp_tags, 0, 3) as $tag): ?>
                            <span class="cs-related-tag"><?php echo htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section class="cs-detail-cta">
        <div class="container text-center">
            <h2 class="cs-detail-cta-title" data-aos="fade-up">Have a Project in Mind?</h2>
            <p class="cs-detail-cta-text" data-aos="fade-up" data-aos-delay="100">Let's create something amazing together. Tell us about your vision.</p>
            <a href="<?php echo SITE_URL; ?>/contact" class="cs-detail-cta-btn" data-aos="fade-up" data-aos-delay="200">
                <span>Start a Conversation</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
