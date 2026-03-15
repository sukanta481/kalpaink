<?php
/**
 * Vlogs / Reels Management
 * Kalpanik Admin CRM
 */

// Include auth first (without HTML output) to handle redirects
require_once __DIR__ . '/../config/auth.php';
requireAuth();
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Auto-create vlogs table if not exists
try {
    $db->exec("CREATE TABLE IF NOT EXISTS vlogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        thumbnail VARCHAR(255),
        video_url VARCHAR(500) NOT NULL,
        video_type ENUM('youtube', 'instagram', 'local') DEFAULT 'youtube',
        description TEXT,
        sort_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (PDOException $e) {
    // Table already exists
}

// Auto-migration: add alt_text column
try { $db->exec("ALTER TABLE vlogs ADD COLUMN image_alt_text VARCHAR(255) DEFAULT NULL"); } catch (PDOException $e) {}

// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: vlogs.php');
        exit;
    }

    if (isset($_POST['save_vlog'])) {
        $data = [
            'title' => sanitize($_POST['title']),
            'video_url' => sanitize($_POST['video_url']),
            'video_type' => sanitize($_POST['video_type']),
            'description' => sanitize($_POST['description']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'image_alt_text' => sanitize($_POST['image_alt_text'] ?? '')
        ];

        // Handle thumbnail upload
        $upload_dir = __DIR__ . '/../../uploads/vlogs/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $thumbnail = null;
        if (!empty($_FILES['thumbnail']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'vlog-' . time() . '-' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
                    $thumbnail = 'uploads/vlogs/' . $file_name;
                }
            }
        }

        if ($id > 0) {
            // Update
            $sql = "UPDATE vlogs SET title = ?, video_url = ?, video_type = ?, description = ?, sort_order = ?, is_active = ?, image_alt_text = ?";
            $params = array_values($data);

            if ($thumbnail) {
                $sql .= ", thumbnail = ?";
                $params[] = $thumbnail;
            }

            $sql .= " WHERE id = ?";
            $params[] = $id;

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'vlog', $id, 'Updated vlog: ' . $data['title']);
            setFlashMessage('success', 'Vlog updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO vlogs (title, video_url, video_type, description, sort_order, is_active, image_alt_text, thumbnail)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = array_values($data);
            $params[] = $thumbnail;

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('create', 'vlog', $db->lastInsertId(), 'Created vlog: ' . $data['title']);
            setFlashMessage('success', 'Vlog added successfully.');
        }

        header('Location: vlogs.php');
        exit;
    }

    if (isset($_POST['delete_vlog']) && $id > 0) {
        $stmt = $db->prepare("SELECT title FROM vlogs WHERE id = ?");
        $stmt->execute([$id]);
        $vlog = $stmt->fetch();

        $stmt = $db->prepare("DELETE FROM vlogs WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'vlog', $id, 'Deleted vlog: ' . ($vlog['title'] ?? 'Unknown'));
        setFlashMessage('success', 'Vlog deleted successfully.');

        header('Location: vlogs.php');
        exit;
    }
}

// Pre-fetch data before header
$vlog = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM vlogs WHERE id = ?");
    $stmt->execute([$id]);
    $vlog = $stmt->fetch();

    if (!$vlog) {
        setFlashMessage('danger', 'Vlog not found.');
        header('Location: vlogs.php');
        exit;
    }
}

$vlogs = [];
if ($action === 'list') {
    $stmt = $db->query("SELECT * FROM vlogs ORDER BY sort_order ASC, created_at DESC");
    $vlogs = $stmt->fetchAll();
}

// NOW include the header
$page_title = 'Vlogs / Reels';
require_once __DIR__ . '/../includes/header.php';

if ($action === 'add' || $action === 'edit') {
    ?>

    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Vlog / Reel' : 'Edit Vlog / Reel'; ?></h1>
        <a href="<?php echo getAdminUrl('content/vlogs.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <form method="POST" action="vlogs.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Vlog Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   placeholder="e.g., Behind the Scenes - Brand Shoot"
                                   value="<?php echo htmlspecialchars($vlog['title'] ?? ''); ?>">
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">Video URL *</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" required
                                           placeholder="https://youtube.com/shorts/... or https://instagram.com/reel/..."
                                           value="<?php echo htmlspecialchars($vlog['video_url'] ?? ''); ?>">
                                    <small class="text-muted">YouTube Shorts, Instagram Reels, or direct video URL</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="video_type" class="form-label">Video Type</label>
                                    <select class="form-select" id="video_type" name="video_type">
                                        <option value="youtube" <?php echo ($vlog['video_type'] ?? 'youtube') === 'youtube' ? 'selected' : ''; ?>>YouTube</option>
                                        <option value="instagram" <?php echo ($vlog['video_type'] ?? '') === 'instagram' ? 'selected' : ''; ?>>Instagram</option>
                                        <option value="local" <?php echo ($vlog['video_type'] ?? '') === 'local' ? 'selected' : ''; ?>>Local/Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                      placeholder="Brief description of the vlog..."><?php echo htmlspecialchars($vlog['description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Thumbnail</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($vlog['thumbnail'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($vlog['thumbnail']); ?>"
                                 alt="Thumbnail" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <small class="text-muted"><strong>Size:</strong> 360x640px &nbsp;|&nbsp; <strong>Format:</strong> JPG, PNG, WebP &nbsp;|&nbsp; <strong>Max:</strong> 2MB</small>
                        <div class="mt-3">
                            <label for="image_alt_text" class="form-label">Thumbnail Alt Text</label>
                            <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                   placeholder="e.g., Behind the scenes brand shoot thumbnail"
                                   value="<?php echo htmlspecialchars($vlog['image_alt_text'] ?? ''); ?>">
                        </div>
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
                                   value="<?php echo (int)($vlog['sort_order'] ?? 0); ?>">
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($vlog['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" name="save_vlog" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Vlog
                    </button>
                    <a href="<?php echo getAdminUrl('content/vlogs.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    <?php
} else {
    // List view
    ?>

    <div class="page-header">
        <h1 class="page-title">Vlogs / Reels</h1>
        <a href="<?php echo getAdminUrl('content/vlogs.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Vlog
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if (empty($vlogs)): ?>
            <div class="empty-state">
                <i class="fas fa-video"></i>
                <h5>No vlogs found</h5>
                <p>Start by adding your first vlog or reel.</p>
                <a href="<?php echo getAdminUrl('content/vlogs.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Vlog
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Thumbnail</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($vlogs as $item): ?>
                        <tr>
                            <td>
                                <?php if ($item['thumbnail']): ?>
                                <img src="<?php echo getSiteUrl($item['thumbnail']); ?>"
                                     alt="" class="image-preview" style="width: 60px; height: 80px; object-fit: cover; border-radius: 8px;">
                                <?php else: ?>
                                <div class="image-preview bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 80px; border-radius: 8px;">
                                    <i class="fas fa-video text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                                <?php if ($item['description']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($item['description'], 0, 60)); ?><?php echo strlen($item['description']) > 60 ? '...' : ''; ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo match($item['video_type']) { 'youtube' => 'danger', 'instagram' => 'purple', default => 'secondary' }; ?>">
                                    <i class="fab fa-<?php echo $item['video_type'] === 'instagram' ? 'instagram' : ($item['video_type'] === 'youtube' ? 'youtube' : 'video'); ?> me-1"></i>
                                    <?php echo ucfirst($item['video_type']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo htmlspecialchars($item['video_url']); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">
                                    <?php echo htmlspecialchars($item['video_url']); ?>
                                </a>
                            </td>
                            <td>
                                <span class="status-dot <?php echo $item['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td><?php echo $item['sort_order']; ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('content/vlogs.php?action=edit&id=' . $item['id']); ?>"
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="vlogs.php?id=<?php echo $item['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_vlog" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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
