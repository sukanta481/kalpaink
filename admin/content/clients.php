<?php
/**
 * Clients / Brand Marquee Management
 * Kalpoink Admin CRM
 */

// Load auth BEFORE any output
require_once __DIR__ . '/../config/auth.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token.');
        header('Location: ' . getAdminUrl('content/clients.php'));
        exit;
    }
    
    if (isset($_POST['save_client'])) {
        $data = [
            'client_name' => sanitize($_POST['client_name']),
            'website_url' => sanitize($_POST['website_url'] ?? ''),
            'sort_order' => (int)($_POST['sort_order'] ?? 0),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle logo upload
        $upload_dir = __DIR__ . '/../../uploads/clients/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $logo_uploaded = false;
        if (!empty($_FILES['client_logo']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['client_logo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            if (in_array($file_ext, $allowed)) {
                $file_name = 'client-' . time() . '-' . rand(1000, 9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['client_logo']['tmp_name'], $upload_dir . $file_name)) {
                    $data['client_logo'] = 'uploads/clients/' . $file_name;
                    $logo_uploaded = true;
                }
            } else {
                setFlashMessage('danger', 'Invalid file type. Allowed: JPG, PNG, GIF, WebP, SVG.');
                header('Location: ' . getAdminUrl('content/clients.php?action=' . ($id ? 'edit&id=' . $id : 'add')));
                exit;
            }
        }
        
        // Remove logo if requested
        if (isset($_POST['remove_logo']) && $id > 0) {
            $stmt = $db->prepare("SELECT client_logo FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            $old = $stmt->fetchColumn();
            if ($old) {
                $old_path = __DIR__ . '/../../' . $old;
                if (file_exists($old_path)) unlink($old_path);
            }
            $data['client_logo'] = null;
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE clients SET client_name = ?, website_url = ?, sort_order = ?, is_active = ?";
            $params = [$data['client_name'], $data['website_url'], $data['sort_order'], $data['is_active']];
            
            if ($logo_uploaded || isset($data['client_logo'])) {
                // Delete old logo if uploading a new one
                if ($logo_uploaded) {
                    $stmt = $db->prepare("SELECT client_logo FROM clients WHERE id = ?");
                    $stmt->execute([$id]);
                    $old_logo = $stmt->fetchColumn();
                    if ($old_logo) {
                        $old_path = __DIR__ . '/../../' . $old_logo;
                        if (file_exists($old_path)) unlink($old_path);
                    }
                }
                $sql .= ", client_logo = ?";
                $params[] = $data['client_logo'] ?? null;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
                logActivity('update', 'client', $id, 'Updated client: ' . $data['client_name']);
                setFlashMessage('success', 'Client updated successfully.');
            } catch (PDOException $e) {
                setFlashMessage('danger', 'Failed to update client: ' . $e->getMessage());
            }
        } else {
            // Insert
            try {
                $stmt = $db->prepare("INSERT INTO clients (client_name, client_logo, website_url, sort_order, is_active) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $data['client_name'],
                    $data['client_logo'] ?? null,
                    $data['website_url'],
                    $data['sort_order'],
                    $data['is_active']
                ]);
                logActivity('create', 'client', $db->lastInsertId(), 'Created client: ' . $data['client_name']);
                setFlashMessage('success', 'Client added successfully.');
            } catch (PDOException $e) {
                setFlashMessage('danger', 'Failed to add client: ' . $e->getMessage());
            }
        }
        
        header('Location: ' . getAdminUrl('content/clients.php'));
        exit;
    }
    
    if (isset($_POST['delete_client']) && $id > 0) {
        // Delete logo file
        $stmt = $db->prepare("SELECT client_logo, client_name FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $client = $stmt->fetch();
        
        if ($client && $client['client_logo']) {
            $logo_path = __DIR__ . '/../../' . $client['client_logo'];
            if (file_exists($logo_path)) unlink($logo_path);
        }
        
        try {
            $stmt = $db->prepare("DELETE FROM clients WHERE id = ?");
            $stmt->execute([$id]);
            logActivity('delete', 'client', $id, 'Deleted client: ' . ($client['client_name'] ?? 'Unknown'));
            setFlashMessage('success', 'Client deleted successfully.');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Failed to delete client: ' . $e->getMessage());
        }
        header('Location: ' . getAdminUrl('content/clients.php'));
        exit;
    }
}

// Handle edit action - check if client exists before outputting anything
$client = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    
    if (!$client) {
        setFlashMessage('danger', 'Client not found.');
        header('Location: ' . getAdminUrl('content/clients.php'));
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'Clients';
require_once __DIR__ . '/../includes/header.php';

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Client' : 'Edit Client'; ?></h1>
        <a href="<?php echo getAdminUrl('content/clients.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="clients.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <input type="hidden" name="save_client" value="1">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Client Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Client / Brand Name *</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" required
                                   placeholder="e.g. Acme Corp"
                                   value="<?php echo htmlspecialchars($client['client_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="website_url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="website_url" name="website_url"
                                   placeholder="https://www.example.com"
                                   value="<?php echo htmlspecialchars($client['website_url'] ?? ''); ?>">
                            <small class="text-muted">Optional — link to client's website.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="client_logo" class="form-label">Client Logo</label>
                            <?php if (!empty($client['client_logo'])): ?>
                                <div class="mb-2 p-3 border rounded bg-light d-flex align-items-center gap-3">
                                    <img src="<?php echo getSiteUrl($client['client_logo']); ?>" alt="<?php echo htmlspecialchars($client['client_name']); ?>" 
                                         style="max-height: 60px; max-width: 200px; object-fit: contain;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                                        <label class="form-check-label text-danger" for="remove_logo">
                                            <i class="fas fa-trash me-1"></i>Remove Logo
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control" id="client_logo" name="client_logo" accept="image/*,.svg">
                            <small class="text-muted">Recommended: Transparent PNG or SVG, max width 200px. If no logo is uploaded, the client name will display as text in the marquee.</small>
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
                                   value="<?php echo (int)($client['sort_order'] ?? 0); ?>">
                            <small class="text-muted">Lower numbers appear first in the marquee.</small>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($client['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active (visible on website)</label>
                        </div>
                    </div>
                </div>
                
                <?php if ($action === 'edit' && $client): ?>
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title text-danger">Danger Zone</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Permanently remove this client from the marquee.</p>
                        <button type="submit" name="delete_client" class="btn btn-outline-danger w-100"
                                onclick="return confirm('Delete this client? This cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Delete Client
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" name="save_client" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Client
                    </button>
                    <a href="<?php echo getAdminUrl('content/clients.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM clients ORDER BY sort_order ASC, client_name ASC");
    $clients = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Clients / Brand Marquee</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/clients.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Client
            </a>
        </div>
    </div>
    
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Manage the clients/brands that appear in the scrolling marquee on the homepage. You can upload logos or display brand names as text.
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($clients)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No clients added yet</h5>
                    <p class="text-muted">Add your first client to populate the homepage marquee.</p>
                    <a href="<?php echo getAdminUrl('content/clients.php?action=add'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First Client
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="clientsTable">
                        <thead>
                            <tr>
                                <th width="60">Order</th>
                                <th width="80">Logo</th>
                                <th>Client Name</th>
                                <th>Website</th>
                                <th width="80">Status</th>
                                <th width="120">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $item): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $item['sort_order']; ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($item['client_logo'])): ?>
                                        <img src="<?php echo getSiteUrl($item['client_logo']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['client_name']); ?>"
                                             style="max-height: 35px; max-width: 80px; object-fit: contain;">
                                    <?php else: ?>
                                        <span class="text-muted small"><i class="fas fa-font"></i> Text</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['client_name']); ?></strong>
                                </td>
                                <td>
                                    <?php if (!empty($item['website_url'])): ?>
                                        <a href="<?php echo htmlspecialchars($item['website_url']); ?>" target="_blank" class="text-primary small">
                                            <?php echo htmlspecialchars(parse_url($item['website_url'], PHP_URL_HOST) ?: $item['website_url']); ?>
                                            <i class="fas fa-external-link-alt ms-1"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo getAdminUrl('content/clients.php?action=edit&id=' . $item['id']); ?>" 
                                       class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="clients.php?id=<?php echo $item['id']; ?>" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                        <button type="submit" name="delete_client" class="btn btn-sm btn-outline-danger" 
                                                title="Delete" onclick="return confirm('Delete <?php echo htmlspecialchars($item['client_name']); ?>?')">
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
