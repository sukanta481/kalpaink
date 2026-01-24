<?php
/**
 * Gallery Management
 * Kalpoink Admin CRM
 */

$page_title = 'Gallery';
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
        header('Location: gallery.php');
        exit;
    }
    
    if (isset($_POST['save_item'])) {
        $data = [
            'category' => sanitize($_POST['category']),
            'title' => sanitize($_POST['title']),
            'description' => sanitize($_POST['description']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle image upload
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $image_uploaded = false;
        if (!empty($_FILES['image']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'gallery-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
                    $data['image'] = 'uploads/gallery/' . $file_name;
                    $image_uploaded = true;
                }
            }
        }
        
        // Handle thumbnail
        if (!empty($_FILES['thumbnail']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'thumb-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
                    $data['thumbnail'] = 'uploads/gallery/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE gallery SET category = ?, title = ?, description = ?, sort_order = ?, is_active = ?";
            $params = [$data['category'], $data['title'], $data['description'], $data['sort_order'], $data['is_active']];
            
            if (isset($data['image'])) {
                $sql .= ", image = ?";
                $params[] = $data['image'];
            }
            if (isset($data['thumbnail'])) {
                $sql .= ", thumbnail = ?";
                $params[] = $data['thumbnail'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'gallery', $id, 'Updated gallery item: ' . $data['title']);
            setFlashMessage('success', 'Gallery item updated successfully.');
        } else {
            // Insert - requires image
            if (!$image_uploaded) {
                setFlashMessage('danger', 'Please upload an image.');
                header('Location: gallery.php?action=add');
                exit;
            }
            
            $sql = "INSERT INTO gallery (category, title, description, image, thumbnail, sort_order, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $params = [
                $data['category'],
                $data['title'],
                $data['description'],
                $data['image'],
                $data['thumbnail'] ?? null,
                $data['sort_order'],
                $data['is_active']
            ];
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('create', 'gallery', $db->lastInsertId(), 'Created gallery item: ' . $data['title']);
            setFlashMessage('success', 'Gallery item created successfully.');
        }
        
        header('Location: gallery.php');
        exit;
    }
    
    if (isset($_POST['delete_item']) && $id > 0) {
        // Get image paths for deletion
        $stmt = $db->prepare("SELECT image, thumbnail FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        
        // Delete files
        if ($item) {
            if ($item['image'] && file_exists(__DIR__ . '/../../' . $item['image'])) {
                unlink(__DIR__ . '/../../' . $item['image']);
            }
            if ($item['thumbnail'] && file_exists(__DIR__ . '/../../' . $item['thumbnail'])) {
                unlink(__DIR__ . '/../../' . $item['thumbnail']);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'gallery', $id, 'Deleted gallery item');
        setFlashMessage('success', 'Gallery item deleted successfully.');
        header('Location: gallery.php');
        exit;
    }
    
    // Bulk upload
    if (isset($_POST['bulk_upload'])) {
        $category = sanitize($_POST['bulk_category']);
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $uploaded = 0;
        if (!empty($_FILES['bulk_images']['name'][0])) {
            foreach ($_FILES['bulk_images']['name'] as $key => $name) {
                if ($_FILES['bulk_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $file_name = 'gallery-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                        if (move_uploaded_file($_FILES['bulk_images']['tmp_name'][$key], $upload_dir . $file_name)) {
                            $title = pathinfo($name, PATHINFO_FILENAME);
                            $stmt = $db->prepare("INSERT INTO gallery (category, title, image, is_active) VALUES (?, ?, ?, 1)");
                            $stmt->execute([$category, $title, 'uploads/gallery/' . $file_name]);
                            $uploaded++;
                        }
                    }
                }
            }
        }
        
        if ($uploaded > 0) {
            logActivity('create', 'gallery', 0, "Bulk uploaded $uploaded gallery items");
            setFlashMessage('success', "$uploaded images uploaded successfully.");
        } else {
            setFlashMessage('warning', 'No images were uploaded.');
        }
        header('Location: gallery.php');
        exit;
    }
}

// Gallery categories
$categories = ['portfolio', 'team', 'office', 'events', 'clients', 'other'];

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $item = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        
        if (!$item) {
            setFlashMessage('danger', 'Gallery item not found.');
            header('Location: gallery.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Gallery Item' : 'Edit Gallery Item'; ?></h1>
        <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Gallery
        </a>
    </div>
    
    <form method="POST" action="gallery.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Item Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($item['category'] ?? '') === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image *</label>
                                    <?php if (!empty($item['image'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo getSiteUrl($item['image']); ?>" alt="Gallery Image" 
                                             class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*"
                                           <?php echo $action === 'add' ? 'required' : ''; ?>>
                                    <small class="text-muted">Recommended: 800x600 pixels or larger</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail (Optional)</label>
                                    <?php if (!empty($item['thumbnail'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo getSiteUrl($item['thumbnail']); ?>" alt="Thumbnail" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                                    <small class="text-muted">Small preview image (300x300 px)</small>
                                </div>
                            </div>
                        </div>
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
                                   value="<?php echo (int)($item['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($item['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_item" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Item
                    </button>
                    <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $filter = $_GET['category'] ?? 'all';
    $where = $filter !== 'all' ? "WHERE category = :cat" : "";
    
    $stmt = $db->prepare("SELECT * FROM gallery $where ORDER BY category, sort_order ASC, id DESC");
    if ($filter !== 'all') {
        $stmt->execute(['cat' => $filter]);
    } else {
        $stmt->execute();
    }
    $items = $stmt->fetchAll();
    
    // Get counts per category
    $counts = [];
    $count_stmt = $db->query("SELECT category, COUNT(*) as cnt FROM gallery GROUP BY category");
    while ($row = $count_stmt->fetch()) {
        $counts[$row['category']] = $row['cnt'];
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Gallery</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="fas fa-upload me-2"></i>Bulk Upload
            </button>
            <a href="<?php echo getAdminUrl('content/gallery.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Image
            </a>
        </div>
    </div>
    
    <!-- Category Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="gallery.php" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    All (<?php echo array_sum($counts); ?>)
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="gallery.php?category=<?php echo $cat; ?>" 
                   class="btn btn-sm <?php echo $filter === $cat ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <?php echo ucfirst($cat); ?> (<?php echo $counts[$cat] ?? 0; ?>)
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($items)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h5>No gallery items found</h5>
                <p>Upload images to your gallery.</p>
                <a href="<?php echo getAdminUrl('content/gallery.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Image
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <div class="row">
        <?php foreach ($items as $item): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <div class="position-relative">
                    <img src="<?php echo getSiteUrl($item['thumbnail'] ?: $item['image']); ?>" 
                         class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>"
                         style="height: 180px; object-fit: cover;">
                    <span class="position-absolute top-0 start-0 m-2 badge bg-<?php echo $item['is_active'] ? 'success' : 'secondary'; ?>">
                        <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                    </span>
                    <span class="position-absolute top-0 end-0 m-2 badge bg-primary">
                        <?php echo ucfirst($item['category']); ?>
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h6>
                    <?php if ($item['description']): ?>
                    <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($item['description'], 0, 80)); ?>...</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">Order: <?php echo $item['sort_order']; ?></small>
                        <div>
                            <a href="<?php echo getAdminUrl('content/gallery.php?action=edit&id=' . $item['id']); ?>" 
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="gallery.php?id=<?php echo $item['id']; ?>" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                <button type="submit" name="delete_item" class="btn btn-sm btn-outline-danger delete-btn" title="Delete">
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
    
    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Upload Images</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulk_category" class="form-label">Category</label>
                            <select class="form-select" id="bulk_category" name="bulk_category" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulk_images" class="form-label">Select Images</label>
                            <input type="file" class="form-control" id="bulk_images" name="bulk_images[]" 
                                   accept="image/*" multiple required>
                            <small class="text-muted">Select multiple images to upload at once. Filenames will be used as titles.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="bulk_upload" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
