<?php
/**
 * Hero Slides Management
 * Kalpoink Admin CRM
 */

$page_title = 'Hero Slides';
require_once __DIR__ . '/../includes/header.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: hero-slides.php');
        exit;
    }
    
    if (isset($_POST['save_slide'])) {
        $data = [
            'title' => sanitize($_POST['title']),
            'subtitle' => sanitize($_POST['subtitle']),
            'badge_text' => sanitize($_POST['badge_text']),
            'button1_text' => sanitize($_POST['button1_text']),
            'button1_link' => sanitize($_POST['button1_link']),
            'button2_text' => sanitize($_POST['button2_text']),
            'button2_link' => sanitize($_POST['button2_link']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle image uploads
        $upload_dir = __DIR__ . '/../../uploads/hero/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $images = ['image1', 'image2', 'image3'];
        $image_paths = [];
        
        foreach ($images as $img) {
            if (!empty($_FILES[$img]['name'])) {
                $file_ext = strtolower(pathinfo($_FILES[$img]['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $file_name = $img . '-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES[$img]['tmp_name'], $upload_dir . $file_name)) {
                        $image_paths[$img] = 'uploads/hero/' . $file_name;
                    }
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE hero_slides SET title = ?, subtitle = ?, badge_text = ?, 
                    button1_text = ?, button1_link = ?, button2_text = ?, button2_link = ?, 
                    sort_order = ?, is_active = ?";
            $params = array_values($data);
            
            foreach ($images as $img) {
                if (isset($image_paths[$img])) {
                    $sql .= ", $img = ?";
                    $params[] = $image_paths[$img];
                }
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'hero_slide', $id, 'Updated hero slide: ' . $data['title']);
            setFlashMessage('success', 'Hero slide updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO hero_slides (title, subtitle, badge_text, button1_text, button1_link, 
                    button2_text, button2_link, sort_order, is_active, image1, image2, image3) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = array_values($data);
            $params[] = $image_paths['image1'] ?? null;
            $params[] = $image_paths['image2'] ?? null;
            $params[] = $image_paths['image3'] ?? null;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('create', 'hero_slide', $db->lastInsertId(), 'Created hero slide: ' . $data['title']);
            setFlashMessage('success', 'Hero slide created successfully.');
        }
        
        header('Location: hero-slides.php');
        exit;
    }
    
    if (isset($_POST['delete_slide']) && $id > 0) {
        $stmt = $db->prepare("DELETE FROM hero_slides WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'hero_slide', $id, 'Deleted hero slide');
        setFlashMessage('success', 'Hero slide deleted successfully.');
        header('Location: hero-slides.php');
        exit;
    }
}

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $slide = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM hero_slides WHERE id = ?");
        $stmt->execute([$id]);
        $slide = $stmt->fetch();
        
        if (!$slide) {
            setFlashMessage('danger', 'Slide not found.');
            header('Location: hero-slides.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Hero Slide' : 'Edit Hero Slide'; ?></h1>
        <a href="<?php echo getAdminUrl('content/hero-slides.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="hero-slides.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Slide Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="badge_text" class="form-label">Badge Text</label>
                            <input type="text" class="form-control" id="badge_text" name="badge_text"
                                   placeholder="e.g., Creative Design Studio"
                                   value="<?php echo htmlspecialchars($slide['badge_text'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   placeholder="e.g., Reimagining with Purpose"
                                   value="<?php echo htmlspecialchars($slide['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="subtitle" class="form-label">Subtitle</label>
                            <textarea class="form-control" id="subtitle" name="subtitle" rows="3"
                                      placeholder="Brief description..."><?php echo htmlspecialchars($slide['subtitle'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button1_text" class="form-label">Button 1 Text</label>
                                    <input type="text" class="form-control" id="button1_text" name="button1_text"
                                           value="<?php echo htmlspecialchars($slide['button1_text'] ?? 'Get Quote'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button1_link" class="form-label">Button 1 Link</label>
                                    <input type="text" class="form-control" id="button1_link" name="button1_link"
                                           value="<?php echo htmlspecialchars($slide['button1_link'] ?? 'contact.php'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button2_text" class="form-label">Button 2 Text</label>
                                    <input type="text" class="form-control" id="button2_text" name="button2_text"
                                           value="<?php echo htmlspecialchars($slide['button2_text'] ?? 'Services'); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="button2_link" class="form-label">Button 2 Link</label>
                                    <input type="text" class="form-control" id="button2_link" name="button2_link"
                                           value="<?php echo htmlspecialchars($slide['button2_link'] ?? 'services.php'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Slide Images (Masonry Grid)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Image <?php echo $i; ?></label>
                                    <?php if (!empty($slide["image$i"])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo getSiteUrl($slide["image$i"]); ?>" 
                                             alt="Image <?php echo $i; ?>" class="img-fluid rounded" style="max-height: 100px;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="image<?php echo $i; ?>" accept="image/*">
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <small class="text-muted">Upload images for the masonry grid display. Recommended size: 400x500 pixels.</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                   value="<?php echo (int)($slide['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($slide['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_slide" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Slide
                    </button>
                    <a href="<?php echo getAdminUrl('content/hero-slides.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM hero_slides ORDER BY sort_order ASC, id DESC");
    $slides = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Hero Slides</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/hero-slides.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Slide
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($slides)): ?>
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h5>No hero slides found</h5>
                <p>Create slides for your homepage hero section.</p>
                <a href="<?php echo getAdminUrl('content/hero-slides.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Slide
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Badge</th>
                            <th>Title</th>
                            <th>Buttons</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($slides as $slide): ?>
                        <tr>
                            <td><?php echo $slide['sort_order']; ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($slide['badge_text']); ?></span></td>
                            <td>
                                <strong><?php echo htmlspecialchars($slide['title']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($slide['subtitle'], 0, 60)); ?>...</small>
                            </td>
                            <td>
                                <small>
                                    <?php echo htmlspecialchars($slide['button1_text']); ?> → <?php echo htmlspecialchars($slide['button1_link']); ?>
                                    <br>
                                    <?php echo htmlspecialchars($slide['button2_text']); ?> → <?php echo htmlspecialchars($slide['button2_link']); ?>
                                </small>
                            </td>
                            <td>
                                <span class="status-dot <?php echo $slide['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $slide['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td>
                                <a href="<?php echo getAdminUrl('content/hero-slides.php?action=edit&id=' . $slide['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="hero-slides.php?id=<?php echo $slide['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_slide" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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

require_once __DIR__ . '/../includes/footer.php';
?>
