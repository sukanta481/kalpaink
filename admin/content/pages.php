<?php
/**
 * Page Content Management
 * Kalpoink Admin CRM
 */

// Load auth BEFORE any output
require_once __DIR__ . '/../config/auth.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token.');
        header('Location: pages.php');
        exit;
    }
    
    if (isset($_POST['save_content'])) {
        $data = [
            'page_name' => sanitize($_POST['page_name']),
            'section_key' => sanitize($_POST['section_key']),
            'content_title' => sanitize($_POST['content_title']),
            'content_subtitle' => sanitize($_POST['content_subtitle']),
            'content_body' => $_POST['content_body'],
            'content_extra' => !empty(trim($_POST['content_extra'] ?? '')) ? trim($_POST['content_extra']) : null,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle image upload
        if (!empty($_FILES['content_image']['name'])) {
            $upload_dir = __DIR__ . '/../../uploads/content/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['content_image']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $file_name = $data['page_name'] . '-' . $data['section_key'] . '-' . time() . '.' . $file_ext;
                if (move_uploaded_file($_FILES['content_image']['tmp_name'], $upload_dir . $file_name)) {
                    $data['content_image'] = 'uploads/content/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update — extract image separately to avoid param misalignment
            $contentImage = $data['content_image'] ?? null;
            unset($data['content_image']);
            
            $sql = "UPDATE page_content SET page_name = ?, section_key = ?, content_title = ?, 
                    content_subtitle = ?, content_body = ?, content_extra = ?, is_active = ?";
            $params = array_values($data);
            
            if ($contentImage) {
                $sql .= ", content_image = ?";
                $params[] = $contentImage;
            }
            
            $sql .= ", updated_at = NOW() WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'page_content', $id, 'Updated content: ' . $data['page_name'] . '/' . $data['section_key']);
            setFlashMessage('success', 'Content updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO page_content (page_name, section_key, content_title, content_subtitle, 
                    content_body, content_image, content_extra, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $data['page_name'],
                $data['section_key'],
                $data['content_title'],
                $data['content_subtitle'],
                $data['content_body'],
                $data['content_image'] ?? null,
                $data['content_extra'],
                $data['is_active']
            ];
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('create', 'page_content', $db->lastInsertId(), 'Created content: ' . $data['page_name'] . '/' . $data['section_key']);
            setFlashMessage('success', 'Content created successfully.');
        }
        
        header('Location: pages.php');
        exit;
    }
    
    if (isset($_POST['delete_content']) && $id > 0) {
        $stmt = $db->prepare("DELETE FROM page_content WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'page_content', $id, 'Deleted page content');
        setFlashMessage('success', 'Content deleted successfully.');
        header('Location: pages.php');
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'Page Content';
require_once __DIR__ . '/../includes/header.php';

// Pages and sections configuration
$pages_config = [
    'home' => [
        'hero' => 'Hero Banner — main heading, subtext, CTA button',
        'services_section' => 'Services Section — title, subtitle',
        'about_preview' => 'About Preview — short intro with link to About page',
        'cta' => 'Call To Action — bottom CTA banner'
    ],
    'about' => [
        'hero' => 'Hero Banner — title, accent text, description, eyebrow, badge',
        'about_card' => 'About Card — "Who We Really Are" section',
        'who_we_are' => 'Who We Are — description with image',
        'join_us' => 'Join Us CTA — "Why Work With Us?" banner',
        'team_section' => 'Team Section — title, badge, subtitle',
        'testimonials_section' => 'Testimonials Section — title, badge, subtitle'
    ],
    'services' => [
        'hero' => 'Hero Banner — heading, subtitle, description',
        'process' => 'Process Section — "How We Work" heading',
        'cta' => 'Call To Action — bottom CTA banner'
    ],
    'contact' => [
        'hero' => 'Hero Banner — heading, subtitle, description',
        'form_intro' => 'Form Introduction — text above contact form'
    ],
    'blog' => [
        'hero' => 'Hero Banner — heading, subtitle, description'
    ],
    'case_studies' => [
        'hero' => 'Hero Banner — heading, subtitle, description'
    ]
];

$page_icons = [
    'home' => 'fas fa-home',
    'about' => 'fas fa-users',
    'services' => 'fas fa-briefcase',
    'contact' => 'fas fa-envelope',
    'blog' => 'fas fa-pen-nib',
    'case_studies' => 'fas fa-layer-group'
];

// Pre-fetch edit item before header output
$content = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM page_content WHERE id = ?");
    $stmt->execute([$id]);
    $content = $stmt->fetch();
    if (!$content) {
        setFlashMessage('danger', 'Content not found.');
        header('Location: pages.php');
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'Page Content';
require_once __DIR__ . '/../includes/header.php';

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Page Content' : 'Edit Page Content'; ?></h1>
        <a href="<?php echo getAdminUrl('content/pages.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="pages.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Content Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="page_name" class="form-label">Page *</label>
                                    <select class="form-select" id="page_name" name="page_name" required>
                                        <option value="">Select Page</option>
                                        <?php foreach ($pages_config as $page => $sections): ?>
                                        <option value="<?php echo $page; ?>" <?php echo ($content['page_name'] ?? '') === $page ? 'selected' : ''; ?>>
                                            <?php echo ucfirst(str_replace('_', ' ', $page)); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_key" class="form-label">Section *</label>
                                    <select class="form-select" id="section_key" name="section_key" required>
                                        <option value="">Select section</option>
                                        <?php if (!empty($content['page_name']) && isset($pages_config[$content['page_name']])): ?>
                                        <?php foreach ($pages_config[$content['page_name']] as $key => $desc): ?>
                                        <option value="<?php echo $key; ?>" <?php echo ($content['section_key'] ?? '') === $key ? 'selected' : ''; ?>>
                                            <?php echo ucwords(str_replace('_', ' ', $key)); ?>
                                        </option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="text-muted" id="section_desc">
                                        <?php 
                                        if (!empty($content['page_name']) && !empty($content['section_key']) && isset($pages_config[$content['page_name']][$content['section_key']])) {
                                            echo $pages_config[$content['page_name']][$content['section_key']];
                                        } else {
                                            echo 'Choose a page to see available sections';
                                        }
                                        ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="content_title" name="content_title"
                                   value="<?php echo htmlspecialchars($content['content_title'] ?? ''); ?>">
                            <small class="text-muted">Main heading text for this section</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_subtitle" class="form-label">Subtitle / Badge Text</label>
                            <input type="text" class="form-control" id="content_subtitle" name="content_subtitle"
                                   value="<?php echo htmlspecialchars($content['content_subtitle'] ?? ''); ?>">
                            <small class="text-muted">Secondary heading, badge label, or accent text</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_body" class="form-label">Body Content</label>
                            <textarea class="form-control tinymce-editor" id="content_body" name="content_body" rows="10"><?php echo htmlspecialchars($content['content_body'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_extra" class="form-label">Extra Content (JSON)</label>
                            <textarea class="form-control" id="content_extra" name="content_extra" rows="3"
                                      placeholder='{"button_text": "Learn More", "button_link": "/about"}'><?php echo htmlspecialchars($content['content_extra'] ?? ''); ?></textarea>
                            <small class="text-muted">Additional data in JSON format</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Image</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($content['content_image'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($content['content_image']); ?>" 
                                 alt="Content Image" class="img-fluid rounded">
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="content_image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control" id="content_image" name="content_image" accept="image/*">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($content['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_content" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Content
                    </button>
                    <a href="<?php echo getAdminUrl('content/pages.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view grouped by page
    $stmt = $db->query("SELECT * FROM page_content ORDER BY page_name ASC, section_key ASC");
    $all_content = $stmt->fetchAll();
    
    // Group by page
    $grouped = [];
    foreach ($all_content as $item) {
        $grouped[$item['page_name']][] = $item;
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Page Content</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/pages.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Section
            </a>
        </div>
    </div>
    
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
        <i class="fas fa-info-circle me-3 fs-5"></i>
        <div>
            <strong>How it works:</strong> Each page has multiple sections you can edit independently. 
            Changes here will automatically reflect on the live website. 
            Click <i class="fas fa-edit"></i> to edit any section's title, text, image, or extra data.
        </div>
    </div>
    
    <?php if (empty($all_content)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-file-alt"></i>
                <h5>No page content found</h5>
                <p>Add content sections for your website pages.</p>
                <a href="<?php echo getAdminUrl('content/pages.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Content
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <?php foreach ($grouped as $page_name => $items): ?>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="<?php echo $page_icons[$page_name] ?? 'fas fa-file'; ?> me-2"></i><?php echo ucfirst(str_replace('_', ' ', $page_name)); ?> Page
                <span class="badge bg-secondary ms-2"><?php echo count($items); ?> sections</span>
            </h5>
            <a href="<?php echo getAdminUrl('content/pages.php?action=add'); ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus me-1"></i>Add Section
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <code><?php echo htmlspecialchars($item['section_key']); ?></code>
                                <?php if (isset($pages_config[$page_name][$item['section_key']])): ?>
                                <br><small class="text-muted"><?php echo $pages_config[$page_name][$item['section_key']]; ?></small>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($item['content_title'] ?: '-'); ?></strong></td>
                            <td><?php echo htmlspecialchars(substr($item['content_subtitle'] ?? '', 0, 50)); ?></td>
                            <td>
                                <span class="status-dot <?php echo $item['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($item['updated_at'])); ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('content/pages.php?action=edit&id=' . $item['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="pages.php?id=<?php echo $item['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_content" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php 
    // Show pages that have no sections yet
    $missing_pages = array_diff(array_keys($pages_config), array_keys($grouped));
    if (!empty($missing_pages)): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-plus-circle me-2"></i>Pages Without Content</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">These pages don't have any editable content sections yet. Add sections to make them editable from the CMS.</p>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($missing_pages as $page): ?>
                <a href="<?php echo getAdminUrl('content/pages.php?action=add'); ?>" class="btn btn-outline-primary">
                    <i class="<?php echo $page_icons[$page] ?? 'fas fa-file'; ?> me-1"></i>
                    <?php echo ucfirst(str_replace('_', ' ', $page)); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php endif; ?>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>

<script>
// Dynamic section dropdown based on page selection
const pagesConfig = <?php echo json_encode($pages_config); ?>;

document.getElementById('page_name')?.addEventListener('change', function() {
    const sectionSelect = document.getElementById('section_key');
    const sectionDesc = document.getElementById('section_desc');
    const page = this.value;
    
    sectionSelect.innerHTML = '<option value="">Select section</option>';
    sectionDesc.textContent = 'Choose a page to see available sections';
    
    if (page && pagesConfig[page]) {
        Object.entries(pagesConfig[page]).forEach(([key, desc]) => {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            sectionSelect.appendChild(opt);
        });
    }
});

document.getElementById('section_key')?.addEventListener('change', function() {
    const page = document.getElementById('page_name').value;
    const sectionDesc = document.getElementById('section_desc');
    
    if (page && this.value && pagesConfig[page] && pagesConfig[page][this.value]) {
        sectionDesc.textContent = pagesConfig[page][this.value];
    } else {
        sectionDesc.textContent = '';
    }
});
</script>
