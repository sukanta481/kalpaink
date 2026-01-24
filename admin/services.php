<?php
/**
 * Services Management
 * Kalpoink Admin CRM
 */

$page_title = 'Services';
require_once __DIR__ . '/includes/header.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: services.php');
        exit;
    }
    
    if (isset($_POST['save_service'])) {
        $title = sanitize($_POST['title']);
        $slug = sanitize($_POST['slug']) ?: generateSlug($title);
        
        // Check for duplicate slug
        $checkSql = "SELECT id FROM services WHERE slug = ? AND id != ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([$slug, $id]);
        if ($checkStmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        $is_active = isset($_POST['is_active']) ? 1 : (($_POST['status'] ?? 'active') === 'active' ? 1 : 0);
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'icon' => sanitize($_POST['icon']),
            'short_description' => sanitize($_POST['short_description']),
            'full_description' => $_POST['full_description'],
            'features' => sanitize($_POST['features']),
            'price_range' => sanitize($_POST['price_range']),
            'is_featured' => $is_featured,
            'is_active' => $is_active,
            'sort_order' => (int)$_POST['sort_order']
        ];
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE services SET title = ?, slug = ?, icon = ?, short_description = ?, full_description = ?, 
                    features = ?, price_range = ?, is_featured = ?, is_active = ?, sort_order = ? WHERE id = ?";
            $params = array_values($data);
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'service', $id, 'Updated service: ' . $data['title']);
            setFlashMessage('success', 'Service updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO services (title, slug, icon, short_description, full_description, features, price_range, is_featured, is_active, sort_order) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($data));
            $newId = $db->lastInsertId();
            logActivity('create', 'service', $newId, 'Created service: ' . $data['title']);
            setFlashMessage('success', 'Service created successfully.');
        }
        
        header('Location: services.php');
        exit;
    }
    
    if (isset($_POST['delete_service']) && $id > 0) {
        $stmt = $db->prepare("SELECT title FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'service', $id, 'Deleted service: ' . ($service['title'] ?? 'Unknown'));
        setFlashMessage('success', 'Service deleted successfully.');
        
        header('Location: services.php');
        exit;
    }
}

// Font Awesome icons for services
$icons = [
    'fa-palette' => 'Palette (Design)',
    'fa-bullhorn' => 'Bullhorn (Marketing)',
    'fa-share-nodes' => 'Share (Social)',
    'fa-code' => 'Code (Development)',
    'fa-magnifying-glass' => 'Search (SEO)',
    'fa-pen-nib' => 'Pen (Content)',
    'fa-photo-film' => 'Photo/Film',
    'fa-chart-line' => 'Chart (Analytics)',
    'fa-mobile-screen' => 'Mobile',
    'fa-laptop' => 'Laptop',
    'fa-globe' => 'Globe (Web)',
    'fa-envelope' => 'Envelope (Email)',
    'fa-video' => 'Video',
    'fa-camera' => 'Camera',
    'fa-lightbulb' => 'Lightbulb (Ideas)'
];

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $service = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch();
        
        if (!$service) {
            setFlashMessage('danger', 'Service not found.');
            header('Location: services.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New Service' : 'Edit Service'; ?></h1>
        <a href="<?php echo getAdminUrl('services.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="services.php<?php echo $id ? '?id=' . $id : ''; ?>" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($service['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="<?php echo htmlspecialchars($service['slug'] ?? ''); ?>">
                            <small class="text-muted">Leave empty to auto-generate from title.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description *</label>
                            <textarea class="form-control" id="short_description" name="short_description" rows="3" required><?php echo htmlspecialchars($service['short_description'] ?? ''); ?></textarea>
                            <small class="text-muted">Brief description shown on the services page.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_description" class="form-label">Full Description</label>
                            <textarea class="form-control tinymce-editor" id="full_description" name="full_description" rows="10"><?php echo htmlspecialchars($service['full_description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="features" class="form-label">Features</label>
                            <textarea class="form-control" id="features" name="features" rows="4"><?php echo htmlspecialchars($service['features'] ?? ''); ?></textarea>
                            <small class="text-muted">Enter each feature on a new line.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Service Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       <?php echo ($service['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                       <?php echo ($service['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_featured">Featured</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <select class="form-select" id="icon" name="icon">
                                <option value="">Select Icon</option>
                                <?php foreach ($icons as $iconClass => $iconLabel): ?>
                                <option value="<?php echo $iconClass; ?>" <?php echo ($service['icon'] ?? '') === $iconClass ? 'selected' : ''; ?>>
                                    <?php echo $iconLabel; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Font Awesome icon class.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="price_range" class="form-label">Price Range</label>
                            <input type="text" class="form-control" id="price_range" name="price_range"
                                   placeholder="e.g., ₹5,000 - ₹50,000"
                                   value="<?php echo htmlspecialchars($service['price_range'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                   value="<?php echo (int)($service['sort_order'] ?? 0); ?>">
                            <small class="text-muted">Lower numbers appear first.</small>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_service" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Service
                    </button>
                    <a href="<?php echo getAdminUrl('services.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM services ORDER BY sort_order ASC, created_at DESC");
    $services = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Services</h1>
        <a href="<?php echo getAdminUrl('services.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Service
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($services)): ?>
            <div class="empty-state">
                <i class="fas fa-cogs"></i>
                <h5>No services found</h5>
                <p>Start by creating your first service.</p>
                <a href="<?php echo getAdminUrl('services.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Service
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Order</th>
                            <th style="width: 50px;">Icon</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Price Range</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo $service['sort_order']; ?></td>
                            <td>
                                <?php if ($service['icon']): ?>
                                <i class="fas <?php echo htmlspecialchars($service['icon']); ?> fa-lg text-primary"></i>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($service['title']); ?></strong></td>
                            <td>
                                <small><?php echo htmlspecialchars(substr($service['short_description'], 0, 80)); ?>...</small>
                            </td>
                            <td><?php echo htmlspecialchars($service['price_range'] ?: '-'); ?></td>
                            <td>
                                <?php if ($service['is_featured']): ?>
                                <span class="badge bg-warning text-dark me-1">Featured</span>
                                <?php endif; ?>
                                <span class="badge bg-<?php echo $service['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $service['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo getAdminUrl('services.php?action=edit&id=' . $service['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="services.php?id=<?php echo $service['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_service" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
}

require_once __DIR__ . '/includes/footer.php';
?>
