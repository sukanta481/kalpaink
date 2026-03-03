<?php
/**
 * Homepage Section Visibility Management
 * Enable/disable individual homepage sections
 * Kalpanik Admin CRM
 */

// Include auth first (without HTML output) to handle redirects
require_once __DIR__ . '/../config/auth.php';
requireAuth();
requireRole('editor');

$db = getDB();

// Define all homepage sections with their settings keys, labels, and descriptions
$homepage_sections = [
    'hero' => [
        'label' => 'Hero Carousel',
        'description' => 'Main hero slider with slides, CTAs and navigation',
        'icon' => 'fas fa-images'
    ],
    'brands' => [
        'label' => 'Trusted Brands',
        'description' => 'Client brand logos marquee / grid section',
        'icon' => 'fas fa-building'
    ],
    'welcome' => [
        'label' => 'Welcome / About',
        'description' => 'Welcome section with image, description and stats',
        'icon' => 'fas fa-hand-sparkles'
    ],
    'services' => [
        'label' => 'Services',
        'description' => 'Services gallery with illustration cards',
        'icon' => 'fas fa-cogs'
    ],
    'team' => [
        'label' => 'Meet The Creators',
        'description' => 'Team members grid section',
        'icon' => 'fas fa-users'
    ],
    'case_studies' => [
        'label' => 'Case Studies',
        'description' => 'Portfolio / project showcase grid',
        'icon' => 'fas fa-briefcase'
    ],
    'vlogs' => [
        'label' => 'Vlog & Reel',
        'description' => 'Video content section with thumbnails',
        'icon' => 'fas fa-video'
    ],
    'testimonials' => [
        'label' => 'Testimonials',
        'description' => 'Client testimonials peek slider',
        'icon' => 'fas fa-quote-right'
    ],
    'faq' => [
        'label' => 'FAQ',
        'description' => 'Frequently asked questions split-screen accordion',
        'icon' => 'fas fa-question-circle'
    ]
];

// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: homepage-sections.php');
        exit;
    }

    if (isset($_POST['save_sections'])) {
        foreach ($homepage_sections as $key => $section) {
            $setting_key = 'homepage_section_' . $key;
            $value = isset($_POST['section_' . $key]) ? '1' : '0';

            // Upsert: update if exists, insert if not
            $stmt = $db->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
            $stmt->execute([$setting_key]);
            $exists = $stmt->fetchColumn() > 0;

            if ($exists) {
                $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                $stmt->execute([$value, $setting_key]);
            } else {
                $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, category) VALUES (?, ?, 'boolean', 'homepage')");
                $stmt->execute([$setting_key, $value]);
            }
        }

        logActivity('update', 'settings', 0, 'Updated homepage section visibility');
        setFlashMessage('success', 'Homepage sections updated successfully. Changes are live!');
        header('Location: homepage-sections.php');
        exit;
    }
}

// Fetch current settings
$current_settings = [];
try {
    $stmt = $db->query("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'homepage_section_%'");
    while ($row = $stmt->fetch()) {
        $current_settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    // Settings may not exist yet
}

// NOW include the header
$page_title = 'Homepage Sections';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="page-header">
    <h1 class="page-title">Homepage Sections</h1>
    <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Content
    </a>
</div>

<div class="alert alert-info d-flex align-items-center mb-4">
    <i class="fas fa-info-circle me-3 fs-5"></i>
    <div>
        <strong>Section Visibility Control:</strong> Toggle sections on/off to show or hide them on the homepage.
        All sections are <strong>enabled by default</strong>. Changes take effect immediately on the live website.
    </div>
</div>

<form method="POST" action="homepage-sections.php">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-toggle-on me-2"></i>Homepage Sections</h5>
            <div>
                <button type="button" class="btn btn-sm btn-outline-success me-1" id="enableAll">
                    <i class="fas fa-check-double me-1"></i>Enable All
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="disableAll">
                    <i class="fas fa-times me-1"></i>Disable All
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">Visible</th>
                            <th>Section</th>
                            <th>Description</th>
                            <th style="width: 100px;" class="text-center">Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $order = 1; foreach ($homepage_sections as $key => $section):
                            $setting_key = 'homepage_section_' . $key;
                            // Default to enabled (1) if no setting exists
                            $is_enabled = ($current_settings[$setting_key] ?? '1') === '1';
                        ?>
                        <tr class="<?php echo $is_enabled ? '' : 'table-light text-muted'; ?>">
                            <td class="text-center align-middle">
                                <div class="form-check form-switch d-flex justify-content-center mb-0">
                                    <input class="form-check-input section-toggle" type="checkbox"
                                           id="section_<?php echo $key; ?>"
                                           name="section_<?php echo $key; ?>"
                                           value="1"
                                           <?php echo $is_enabled ? 'checked' : ''; ?>
                                           style="width: 3em; height: 1.5em; cursor: pointer;">
                                </div>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="me-3" style="width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: <?php echo $is_enabled ? 'rgba(var(--bs-primary-rgb, 13,110,253), 0.1)' : '#f0f0f0'; ?>;">
                                        <i class="<?php echo $section['icon']; ?>" style="color: <?php echo $is_enabled ? 'var(--bs-primary, #0d6efd)' : '#aaa'; ?>;"></i>
                                    </div>
                                    <div>
                                        <strong><?php echo $section['label']; ?></strong>
                                        <span class="badge bg-<?php echo $is_enabled ? 'success' : 'secondary'; ?> ms-2 status-badge" style="font-size: 0.65rem;">
                                            <?php echo $is_enabled ? 'Visible' : 'Hidden'; ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <small class="text-muted"><?php echo $section['description']; ?></small>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge bg-light text-dark"><?php echo $order; ?></span>
                            </td>
                        </tr>
                        <?php $order++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                <i class="fas fa-sync-alt me-1"></i>Changes are reflected on the website immediately after saving.
            </small>
            <button type="submit" name="save_sections" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Save Changes
            </button>
        </div>
    </div>
</form>

<script>
// Enable All / Disable All buttons
document.getElementById('enableAll')?.addEventListener('click', function() {
    document.querySelectorAll('.section-toggle').forEach(cb => cb.checked = true);
});
document.getElementById('disableAll')?.addEventListener('click', function() {
    document.querySelectorAll('.section-toggle').forEach(cb => cb.checked = false);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
