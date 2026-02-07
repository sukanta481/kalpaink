<?php
/**
 * Testimonials Management
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
        header('Location: testimonials.php');
        exit;
    }
    
    if (isset($_POST['save_testimonial'])) {
        $data = [
            'client_name' => sanitize($_POST['client_name']),
            'client_position' => sanitize($_POST['client_position']),
            'client_company' => sanitize($_POST['client_company']),
            'testimonial_text' => sanitize($_POST['testimonial_text']),
            'rating' => (int)$_POST['rating'],
            'sort_order' => (int)$_POST['sort_order'],
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle avatar upload
        if (!empty($_FILES['client_avatar']['name'])) {
            $upload_dir = __DIR__ . '/../../uploads/testimonials/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['client_avatar']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'avatar-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['client_avatar']['tmp_name'], $upload_dir . $file_name)) {
                    $data['client_avatar'] = 'uploads/testimonials/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE testimonials SET client_name = ?, client_position = ?, client_company = ?, 
                    testimonial_text = ?, rating = ?, sort_order = ?, is_featured = ?, is_active = ?";
            $params = [$data['client_name'], $data['client_position'], $data['client_company'], 
                       $data['testimonial_text'], $data['rating'], $data['sort_order'], 
                       $data['is_featured'], $data['is_active']];
            
            if (isset($data['client_avatar'])) {
                $sql .= ", client_avatar = ?";
                $params[] = $data['client_avatar'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'testimonial', $id, 'Updated testimonial from: ' . $data['client_name']);
            setFlashMessage('success', 'Testimonial updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO testimonials (client_name, client_position, client_company, client_avatar, 
                    testimonial_text, rating, sort_order, is_featured, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $data['client_name'],
                $data['client_position'],
                $data['client_company'],
                $data['client_avatar'] ?? null,
                $data['testimonial_text'],
                $data['rating'],
                $data['sort_order'],
                $data['is_featured'],
                $data['is_active']
            ];
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('create', 'testimonial', $db->lastInsertId(), 'Created testimonial from: ' . $data['client_name']);
            setFlashMessage('success', 'Testimonial created successfully.');
        }
        
        header('Location: testimonials.php');
        exit;
    }
    
    if (isset($_POST['delete_testimonial']) && $id > 0) {
        $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'testimonial', $id, 'Deleted testimonial');
        setFlashMessage('success', 'Testimonial deleted successfully.');
        header('Location: testimonials.php');
        exit;
    }
}

// Pre-fetch edit item before header output
$testimonial = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $testimonial = $stmt->fetch();
    if (!$testimonial) {
        setFlashMessage('danger', 'Testimonial not found.');
        header('Location: testimonials.php');
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'Testimonials';
require_once __DIR__ . '/../includes/header.php';

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Testimonial' : 'Edit Testimonial'; ?></h1>
        <a href="<?php echo getAdminUrl('content/testimonials.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="testimonials.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Client Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="client_name" class="form-label">Client Name *</label>
                                    <input type="text" class="form-control" id="client_name" name="client_name" required
                                           value="<?php echo htmlspecialchars($testimonial['client_name'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="client_position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="client_position" name="client_position"
                                           placeholder="e.g., CEO, Marketing Director"
                                           value="<?php echo htmlspecialchars($testimonial['client_position'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="client_company" class="form-label">Company</label>
                                    <input type="text" class="form-control" id="client_company" name="client_company"
                                           value="<?php echo htmlspecialchars($testimonial['client_company'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="testimonial_text" class="form-label">Testimonial Text *</label>
                            <textarea class="form-control" id="testimonial_text" name="testimonial_text" rows="5" required
                                      placeholder="What the client said about your service..."><?php echo htmlspecialchars($testimonial['testimonial_text'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" id="rating" name="rating" style="max-width: 150px;">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($testimonial['rating'] ?? 5) == $i ? 'selected' : ''; ?>>
                                    <?php echo str_repeat('★', $i); ?><?php echo str_repeat('☆', 5-$i); ?> (<?php echo $i; ?>)
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Client Photo</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($testimonial['client_avatar'])): ?>
                        <img src="<?php echo getSiteUrl($testimonial['client_avatar']); ?>" 
                             alt="Client Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user fa-2x text-muted"></i>
                        </div>
                        <?php endif; ?>
                        
                        <input type="file" class="form-control" name="client_avatar" accept="image/*">
                        <small class="text-muted d-block mt-2">Square image recommended (150x150 px)</small>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                   value="<?php echo (int)($testimonial['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                   <?php echo ($testimonial['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_featured">
                                <i class="fas fa-star text-warning me-1"></i>Featured
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($testimonial['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_testimonial" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Testimonial
                    </button>
                    <a href="<?php echo getAdminUrl('content/testimonials.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM testimonials ORDER BY is_featured DESC, sort_order ASC, id DESC");
    $testimonials = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Testimonials</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/testimonials.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Testimonial
            </a>
        </div>
    </div>
    
    <?php if (empty($testimonials)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-quote-right"></i>
                <h5>No testimonials found</h5>
                <p>Add client testimonials to build trust.</p>
                <a href="<?php echo getAdminUrl('content/testimonials.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Testimonial
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <div class="row">
        <?php foreach ($testimonials as $testimonial): ?>
        <div class="col-lg-6 mb-4">
            <div class="card h-100 <?php echo $testimonial['is_featured'] ? 'border-warning' : ''; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <?php if (!empty($testimonial['client_avatar'])): ?>
                            <img src="<?php echo getSiteUrl($testimonial['client_avatar']); ?>" 
                                 class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;"
                                 alt="<?php echo htmlspecialchars($testimonial['client_name']); ?>">
                            <?php else: ?>
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-0"><?php echo htmlspecialchars($testimonial['client_name']); ?></h6>
                                <small class="text-muted">
                                    <?php 
                                    $subtitle = [];
                                    if ($testimonial['client_position']) $subtitle[] = $testimonial['client_position'];
                                    if ($testimonial['client_company']) $subtitle[] = $testimonial['client_company'];
                                    echo htmlspecialchars(implode(' at ', $subtitle));
                                    ?>
                                </small>
                            </div>
                        </div>
                        <div>
                            <?php if ($testimonial['is_featured']): ?>
                            <span class="badge bg-warning text-dark"><i class="fas fa-star"></i> Featured</span>
                            <?php endif; ?>
                            <span class="badge bg-<?php echo $testimonial['is_active'] ? 'success' : 'secondary'; ?>">
                                <?php echo $testimonial['is_active'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="text-warning mb-2">
                        <?php echo str_repeat('★', $testimonial['rating']); ?>
                        <?php echo str_repeat('☆', 5 - $testimonial['rating']); ?>
                    </div>
                    
                    <p class="card-text fst-italic">
                        "<?php echo htmlspecialchars(substr($testimonial['testimonial_text'], 0, 200)); ?><?php echo strlen($testimonial['testimonial_text']) > 200 ? '...' : ''; ?>"
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Order: <?php echo $testimonial['sort_order']; ?></small>
                        <div>
                            <a href="<?php echo getAdminUrl('content/testimonials.php?action=edit&id=' . $testimonial['id']); ?>" 
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="testimonials.php?id=<?php echo $testimonial['id']; ?>" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                <button type="submit" name="delete_testimonial" class="btn btn-sm btn-outline-danger delete-btn" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
