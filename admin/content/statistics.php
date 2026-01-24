<?php
/**
 * Statistics Management
 * Kalpoink Admin CRM
 */

$page_title = 'Statistics';
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
        header('Location: statistics.php');
        exit;
    }
    
    if (isset($_POST['save_stat'])) {
        $data = [
            'stat_key' => sanitize($_POST['stat_key']),
            'stat_value' => sanitize($_POST['stat_value']),
            'stat_label' => sanitize($_POST['stat_label']),
            'stat_icon' => sanitize($_POST['stat_icon']),
            'stat_suffix' => sanitize($_POST['stat_suffix']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        if ($id > 0) {
            // Update
            $stmt = $db->prepare("UPDATE statistics SET stat_key = ?, stat_value = ?, stat_label = ?, 
                                  stat_icon = ?, stat_suffix = ?, sort_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$data['stat_key'], $data['stat_value'], $data['stat_label'], 
                           $data['stat_icon'], $data['stat_suffix'], $data['sort_order'], 
                           $data['is_active'], $id]);
            logActivity('update', 'statistic', $id, 'Updated statistic: ' . $data['stat_label']);
            setFlashMessage('success', 'Statistic updated successfully.');
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO statistics (stat_key, stat_value, stat_label, stat_icon, 
                                  stat_suffix, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$data['stat_key'], $data['stat_value'], $data['stat_label'], 
                           $data['stat_icon'], $data['stat_suffix'], $data['sort_order'], 
                           $data['is_active']]);
            logActivity('create', 'statistic', $db->lastInsertId(), 'Created statistic: ' . $data['stat_label']);
            setFlashMessage('success', 'Statistic created successfully.');
        }
        
        header('Location: statistics.php');
        exit;
    }
    
    if (isset($_POST['delete_stat']) && $id > 0) {
        $stmt = $db->prepare("DELETE FROM statistics WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'statistic', $id, 'Deleted statistic');
        setFlashMessage('success', 'Statistic deleted successfully.');
        header('Location: statistics.php');
        exit;
    }
}

// Common icons for statistics
$common_icons = [
    'fas fa-users' => 'Users/Clients',
    'fas fa-project-diagram' => 'Projects',
    'fas fa-trophy' => 'Awards',
    'fas fa-calendar-check' => 'Years',
    'fas fa-coffee' => 'Coffee Cups',
    'fas fa-code' => 'Lines of Code',
    'fas fa-smile' => 'Happy Clients',
    'fas fa-globe' => 'Countries',
    'fas fa-building' => 'Offices',
    'fas fa-clock' => 'Hours',
    'fas fa-star' => 'Reviews',
    'fas fa-thumbs-up' => 'Likes',
    'fas fa-check-circle' => 'Completed',
    'fas fa-chart-line' => 'Growth',
    'fas fa-hand-holding-usd' => 'Revenue'
];

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $stat = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM statistics WHERE id = ?");
        $stmt->execute([$id]);
        $stat = $stmt->fetch();
        
        if (!$stat) {
            setFlashMessage('danger', 'Statistic not found.');
            header('Location: statistics.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Statistic' : 'Edit Statistic'; ?></h1>
        <a href="<?php echo getAdminUrl('content/statistics.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="statistics.php<?php echo $id ? '?id=' . $id : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Statistic Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stat_key" class="form-label">Unique Key *</label>
                                    <input type="text" class="form-control" id="stat_key" name="stat_key" required
                                           placeholder="e.g., total_clients, years_experience"
                                           value="<?php echo htmlspecialchars($stat['stat_key'] ?? ''); ?>">
                                    <small class="text-muted">Identifier for programmatic access</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stat_value" class="form-label">Value *</label>
                                    <input type="text" class="form-control" id="stat_value" name="stat_value" required
                                           placeholder="e.g., 500, 10, 1000"
                                           value="<?php echo htmlspecialchars($stat['stat_value'] ?? ''); ?>">
                                    <small class="text-muted">The numeric or text value to display</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="stat_label" class="form-label">Display Label *</label>
                                    <input type="text" class="form-control" id="stat_label" name="stat_label" required
                                           placeholder="e.g., Happy Clients, Years Experience"
                                           value="<?php echo htmlspecialchars($stat['stat_label'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stat_suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control" id="stat_suffix" name="stat_suffix"
                                           placeholder="e.g., +, %, K"
                                           value="<?php echo htmlspecialchars($stat['stat_suffix'] ?? ''); ?>">
                                    <small class="text-muted">Appended after value (e.g., 500+)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="stat_icon" class="form-label">Icon Class</label>
                            <div class="input-group">
                                <span class="input-group-text" id="icon-preview">
                                    <i class="<?php echo htmlspecialchars($stat['stat_icon'] ?? 'fas fa-chart-bar'); ?>"></i>
                                </span>
                                <input type="text" class="form-control" id="stat_icon" name="stat_icon"
                                       placeholder="fas fa-users"
                                       value="<?php echo htmlspecialchars($stat['stat_icon'] ?? ''); ?>">
                            </div>
                            <small class="text-muted">Font Awesome icon class. Click below to select common icons:</small>
                            <div class="mt-2 d-flex flex-wrap gap-2">
                                <?php foreach ($common_icons as $icon => $label): ?>
                                <button type="button" class="btn btn-sm btn-outline-secondary icon-select" 
                                        data-icon="<?php echo $icon; ?>" title="<?php echo $label; ?>">
                                    <i class="<?php echo $icon; ?>"></i>
                                </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Preview Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="text-center p-4 bg-light rounded">
                                    <div class="display-6 text-primary mb-2" id="preview-icon">
                                        <i class="<?php echo htmlspecialchars($stat['stat_icon'] ?? 'fas fa-chart-bar'); ?>"></i>
                                    </div>
                                    <div class="display-5 fw-bold" id="preview-value">
                                        <?php echo htmlspecialchars(($stat['stat_value'] ?? '0') . ($stat['stat_suffix'] ?? '')); ?>
                                    </div>
                                    <div class="text-muted" id="preview-label">
                                        <?php echo htmlspecialchars($stat['stat_label'] ?? 'Statistic Label'); ?>
                                    </div>
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
                                   value="<?php echo (int)($stat['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($stat['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_stat" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Statistic
                    </button>
                    <a href="<?php echo getAdminUrl('content/statistics.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Icon selection
        document.querySelectorAll('.icon-select').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.dataset.icon;
                document.getElementById('stat_icon').value = icon;
                document.getElementById('icon-preview').innerHTML = '<i class="' + icon + '"></i>';
                document.getElementById('preview-icon').innerHTML = '<i class="' + icon + '"></i>';
            });
        });
        
        // Live preview updates
        document.getElementById('stat_value').addEventListener('input', updatePreview);
        document.getElementById('stat_suffix').addEventListener('input', updatePreview);
        document.getElementById('stat_label').addEventListener('input', updatePreview);
        document.getElementById('stat_icon').addEventListener('input', function() {
            document.getElementById('icon-preview').innerHTML = '<i class="' + this.value + '"></i>';
            document.getElementById('preview-icon').innerHTML = '<i class="' + this.value + '"></i>';
        });
        
        function updatePreview() {
            const value = document.getElementById('stat_value').value || '0';
            const suffix = document.getElementById('stat_suffix').value || '';
            const label = document.getElementById('stat_label').value || 'Statistic Label';
            
            document.getElementById('preview-value').textContent = value + suffix;
            document.getElementById('preview-label').textContent = label;
        }
    });
    </script>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM statistics ORDER BY sort_order ASC, id ASC");
    $stats = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Statistics</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/statistics.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Statistic
            </a>
        </div>
    </div>
    
    <?php if (empty($stats)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-chart-bar"></i>
                <h5>No statistics found</h5>
                <p>Add statistics to showcase your achievements.</p>
                <a href="<?php echo getAdminUrl('content/statistics.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Statistic
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <!-- Preview Grid -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Live Preview</h5>
        </div>
        <div class="card-body bg-light">
            <div class="row g-4">
                <?php foreach ($stats as $stat): ?>
                <?php if ($stat['is_active']): ?>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center p-3">
                        <?php if ($stat['stat_icon']): ?>
                        <div class="h3 text-primary mb-2">
                            <i class="<?php echo htmlspecialchars($stat['stat_icon']); ?>"></i>
                        </div>
                        <?php endif; ?>
                        <div class="display-6 fw-bold">
                            <?php echo htmlspecialchars($stat['stat_value'] . $stat['stat_suffix']); ?>
                        </div>
                        <div class="text-muted"><?php echo htmlspecialchars($stat['stat_label']); ?></div>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <!-- Statistics Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Manage Statistics</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Icon</th>
                            <th>Key</th>
                            <th>Value</th>
                            <th>Label</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $stat): ?>
                        <tr>
                            <td><?php echo $stat['sort_order']; ?></td>
                            <td>
                                <?php if ($stat['stat_icon']): ?>
                                <i class="<?php echo htmlspecialchars($stat['stat_icon']); ?> fa-lg"></i>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><code><?php echo htmlspecialchars($stat['stat_key']); ?></code></td>
                            <td><strong><?php echo htmlspecialchars($stat['stat_value'] . $stat['stat_suffix']); ?></strong></td>
                            <td><?php echo htmlspecialchars($stat['stat_label']); ?></td>
                            <td>
                                <span class="status-dot <?php echo $stat['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $stat['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td>
                                <a href="<?php echo getAdminUrl('content/statistics.php?action=edit&id=' . $stat['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="statistics.php?id=<?php echo $stat['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_stat" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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
    <?php endif; ?>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
