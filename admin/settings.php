<?php
/**
 * Settings Management
 * Kalpoink Admin CRM
 */

$page_title = 'Settings';
require_once __DIR__ . '/includes/header.php';
requireRole('admin');

$db = getDB();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: settings.php');
        exit;
    }
    
    // Update settings
    foreach ($_POST['settings'] as $key => $value) {
        $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$value, $key]);
    }
    
    logActivity('update', 'settings', null, 'Updated site settings');
    setFlashMessage('success', 'Settings updated successfully.');
    
    header('Location: settings.php');
    exit;
}

// Get all settings grouped by category
$stmt = $db->query("SELECT * FROM settings ORDER BY category, id");
$allSettings = $stmt->fetchAll();

$settings = [];
foreach ($allSettings as $setting) {
    $settings[$setting['category']][] = $setting;
}
?>

<div class="page-header">
    <h1 class="page-title">Site Settings</h1>
</div>

<form method="POST" action="settings.php">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
    
    <div class="row">
        <div class="col-lg-8">
            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-cog me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($settings['general'])): ?>
                    <?php foreach ($settings['general'] as $setting): ?>
                    <div class="mb-3">
                        <label for="<?php echo $setting['setting_key']; ?>" class="form-label">
                            <?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?>
                        </label>
                        <?php if ($setting['setting_type'] === 'textarea'): ?>
                        <textarea class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                                  name="settings[<?php echo $setting['setting_key']; ?>]" rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                        <?php elseif ($setting['setting_type'] === 'boolean'): ?>
                        <select class="form-select" id="<?php echo $setting['setting_key']; ?>" 
                                name="settings[<?php echo $setting['setting_key']; ?>]">
                            <option value="1" <?php echo $setting['setting_value'] == '1' ? 'selected' : ''; ?>>Yes</option>
                            <option value="0" <?php echo $setting['setting_value'] == '0' ? 'selected' : ''; ?>>No</option>
                        </select>
                        <?php else: ?>
                        <input type="text" class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                               name="settings[<?php echo $setting['setting_key']; ?>]" 
                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Contact Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-address-book me-2"></i>Contact Information</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($settings['contact'])): ?>
                    <?php foreach ($settings['contact'] as $setting): ?>
                    <div class="mb-3">
                        <label for="<?php echo $setting['setting_key']; ?>" class="form-label">
                            <?php echo ucwords(str_replace(['contact_', '_'], ['', ' '], $setting['setting_key'])); ?>
                        </label>
                        <?php if ($setting['setting_type'] === 'textarea'): ?>
                        <textarea class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                                  name="settings[<?php echo $setting['setting_key']; ?>]" rows="3"><?php echo htmlspecialchars($setting['setting_value']); ?></textarea>
                        <?php else: ?>
                        <input type="text" class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                               name="settings[<?php echo $setting['setting_key']; ?>]" 
                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Social Media Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-share-nodes me-2"></i>Social Media Links</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($settings['social'])): ?>
                    <div class="row">
                    <?php foreach ($settings['social'] as $setting): ?>
                    <div class="col-md-6 mb-3">
                        <label for="<?php echo $setting['setting_key']; ?>" class="form-label">
                            <i class="fab fa-<?php echo str_replace('social_', '', $setting['setting_key']); ?> me-1"></i>
                            <?php echo ucwords(str_replace(['social_', '_'], ['', ' '], $setting['setting_key'])); ?>
                        </label>
                        <input type="url" class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                               name="settings[<?php echo $setting['setting_key']; ?>]" 
                               placeholder="https://"
                               value="<?php echo htmlspecialchars($setting['setting_value']); ?>">
                    </div>
                    <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Save Changes</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Make sure to save your changes after editing any settings.</p>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save All Settings
                    </button>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo getAdminUrl('users.php'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="<?php echo getAdminUrl('activity.php'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i>Activity Log
                        </a>
                        <a href="<?php echo getSiteUrl(); ?>" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-external-link-alt me-2"></i>View Website
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">System Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>PHP Version</td>
                            <td class="text-end"><?php echo phpversion(); ?></td>
                        </tr>
                        <tr>
                            <td>MySQL Version</td>
                            <td class="text-end"><?php echo $db->query("SELECT VERSION()")->fetchColumn(); ?></td>
                        </tr>
                        <tr>
                            <td>Server</td>
                            <td class="text-end"><?php echo php_uname('s'); ?></td>
                        </tr>
                        <tr>
                            <td>CRM Version</td>
                            <td class="text-end">1.0.0</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
