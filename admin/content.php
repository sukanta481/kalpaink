<?php
/**
 * Content Management - Main Hub
 * Kalpoink Admin CRM
 */

$page_title = 'Content Management';
require_once __DIR__ . '/includes/header.php';
requireRole('editor');

$db = getDB();

// Check if content tables exist
$tablesExist = true;
try {
    $db->query("SELECT 1 FROM hero_slides LIMIT 1");
} catch (PDOException $e) {
    $tablesExist = false;
}

// Get counts for each content type
$counts = [];

$tables = [
    'hero_slides' => 'Hero Slides',
    'page_content' => 'Page Content',
    'gallery' => 'Gallery Images',
    'testimonials' => 'Testimonials',
    'faqs' => 'FAQs',
    'statistics' => 'Statistics'
];

if ($tablesExist) {
    foreach ($tables as $table => $label) {
        try {
            $stmt = $db->query("SELECT COUNT(*) FROM $table");
            $counts[$table] = $stmt->fetchColumn();
        } catch (PDOException $e) {
            $counts[$table] = 0;
        }
    }
} else {
    foreach ($tables as $table => $label) {
        $counts[$table] = 0;
    }
}
?>

<div class="page-header">
    <h1 class="page-title">Content Management</h1>
</div>

<?php if (!$tablesExist): ?>
<div class="alert alert-warning mb-4">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Content tables not installed!</strong> You need to install the content management tables first.
    <a href="<?php echo getAdminUrl('install/content-tables.php'); ?>" class="btn btn-warning btn-sm ms-3">
        <i class="fas fa-download me-2"></i>Install Content Tables
    </a>
</div>
<?php else: ?>
<div class="alert alert-success mb-4">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <div>
            <strong><i class="fas fa-sync-alt me-2"></i>Auto-Sync Active!</strong><br>
            All content changes are automatically reflected on the live website. No manual publishing required!
        </div>
    </div>
</div>
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Content Management Center:</strong> Manage all website content from this central hub. Select a section below to edit.
</div>
<?php endif; ?>

<div class="row g-4">
    <!-- Hero Slides -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon primary mx-auto mb-3">
                    <i class="fas fa-images"></i>
                </div>
                <h5 class="card-title">Hero Slides</h5>
                <p class="text-muted">Manage homepage slider content, images, and call-to-action buttons.</p>
                <span class="badge bg-primary mb-3"><?php echo $counts['hero_slides']; ?> Slides</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/hero-slides.php'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Content -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon info mx-auto mb-3">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h5 class="card-title">Page Content</h5>
                <p class="text-muted">Edit text, titles, and descriptions on all website pages.</p>
                <span class="badge bg-info mb-3"><?php echo $counts['page_content']; ?> Items</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/pages.php'); ?>" class="btn btn-outline-info">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gallery -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon success mx-auto mb-3">
                    <i class="fas fa-photo-video"></i>
                </div>
                <h5 class="card-title">Gallery / Portfolio</h5>
                <p class="text-muted">Upload and manage portfolio images and project galleries.</p>
                <span class="badge bg-success mb-3"><?php echo $counts['gallery']; ?> Images</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-success">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon warning mx-auto mb-3">
                    <i class="fas fa-quote-right"></i>
                </div>
                <h5 class="card-title">Testimonials</h5>
                <p class="text-muted">Manage client testimonials and reviews displayed on the website.</p>
                <span class="badge bg-warning mb-3"><?php echo $counts['testimonials']; ?> Reviews</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/testimonials.php'); ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQs -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon danger mx-auto mb-3">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h5 class="card-title">FAQs</h5>
                <p class="text-muted">Manage frequently asked questions and their answers.</p>
                <span class="badge bg-danger mb-3"><?php echo $counts['faqs']; ?> Questions</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/faqs.php'); ?>" class="btn btn-outline-danger">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="stat-icon secondary mx-auto mb-3">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h5 class="card-title">Statistics</h5>
                <p class="text-muted">Update counter numbers like clients, projects, experience years.</p>
                <span class="badge bg-secondary mb-3"><?php echo $counts['statistics']; ?> Stats</span>
                <div class="d-grid">
                    <a href="<?php echo getAdminUrl('content/statistics.php'); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-edit me-2"></i>Manage
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links to Other Content -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Other Content Sections</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="<?php echo getAdminUrl('blogs.php'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-blog me-2"></i>Blog Posts
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo getAdminUrl('projects.php'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-briefcase me-2"></i>Projects
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo getAdminUrl('services.php'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-cogs me-2"></i>Services
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo getAdminUrl('team.php'); ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users me-2"></i>Team Members
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
