<?php
/**
 * Page Content Management
 * Kalpoink Admin CRM
 */

$page_title = 'Page Content';
require_once __DIR__ . '/../includes/header.php';
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
            'content_extra' => $_POST['content_extra'] ?? null,
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
            // Update
            $sql = "UPDATE page_content SET page_name = ?, section_key = ?, content_title = ?, 
                    content_subtitle = ?, content_body = ?, content_extra = ?, is_active = ?";
            $params = array_values($data);
            
            if (isset($data['content_image'])) {
                $sql .= ", content_image = ?";
                $params[] = $data['content_image'];
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

// Pages and sections configuration
$pages_config = [
    'home' => ['hero', 'intro', 'about_preview', 'why_choose_us', 'cta'],
    'about' => ['header', 'story', 'mission', 'vision', 'values', 'team_intro'],
    'services' => ['header', 'intro', 'process', 'cta'],
    'contact' => ['header', 'info', 'form_intro'],
    'blog' => ['header', 'sidebar'],
    'case_studies' => ['header', 'intro']
];

// Handle different actions
if ($action === 'add' || $action === 'edit') {
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
                                    <label for="section_key" class="form-label">Section Key *</label>
                                    <input type="text" class="form-control" id="section_key" name="section_key" required
                                           placeholder="e.g., hero, about_intro, cta"
                                           value="<?php echo htmlspecialchars($content['section_key'] ?? ''); ?>">
                                    <small class="text-muted">Unique identifier for this section</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="content_title" name="content_title"
                                   value="<?php echo htmlspecialchars($content['content_title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="content_subtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" id="content_subtitle" name="content_subtitle"
                                   value="<?php echo htmlspecialchars($content['content_subtitle'] ?? ''); ?>">
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
                <i class="fas fa-plus me-2"></i>Add Content
            </a>
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
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-file me-2"></i><?php echo ucfirst(str_replace('_', ' ', $page_name)); ?> Page
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><code><?php echo htmlspecialchars($item['section_key']); ?></code></td>
                            <td><?php echo htmlspecialchars($item['content_title'] ?: '-'); ?></td>
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
    
    <?php endif; ?>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
