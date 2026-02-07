<?php
/**
 * Settings Management
 * Kalpoink Admin CRM
 */

// Load auth BEFORE any output
require_once __DIR__ . '/config/auth.php';
requireRole('admin');

$db = getDB();

// Handle form submissions BEFORE including header
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: settings.php');
        exit;
    }
    
    // Handle file uploads (logo, favicon)
    $uploadDir = dirname(__DIR__) . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileFields = ['site_logo', 'site_favicon'];
    foreach ($fileFields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES[$field]['tmp_name'];
            $origName = basename($_FILES[$field]['name']);
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico'];
            if (in_array($ext, $allowed)) {
                $newName = $field . '_' . time() . '.' . $ext;
                $destPath = $uploadDir . $newName;
                
                if (move_uploaded_file($tmpName, $destPath)) {
                    // Delete old file if exists
                    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
                    $stmt->execute([$field]);
                    $oldVal = $stmt->fetchColumn();
                    if ($oldVal && file_exists(dirname(__DIR__) . '/' . $oldVal)) {
                        @unlink(dirname(__DIR__) . '/' . $oldVal);
                    }
                    
                    // Save relative path
                    $relativePath = 'uploads/' . $newName;
                    $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->execute([$relativePath, $field]);
                }
            }
        }
    }
    
    // Handle remove logo/favicon
    if (isset($_POST['remove_site_logo']) && $_POST['remove_site_logo'] === '1') {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'site_logo'");
        $stmt->execute();
        $oldVal = $stmt->fetchColumn();
        if ($oldVal && file_exists(dirname(__DIR__) . '/' . $oldVal)) {
            @unlink(dirname(__DIR__) . '/' . $oldVal);
        }
        $stmt = $db->prepare("UPDATE settings SET setting_value = '' WHERE setting_key = 'site_logo'");
        $stmt->execute();
    }
    if (isset($_POST['remove_site_favicon']) && $_POST['remove_site_favicon'] === '1') {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = 'site_favicon'");
        $stmt->execute();
        $oldVal = $stmt->fetchColumn();
        if ($oldVal && file_exists(dirname(__DIR__) . '/' . $oldVal)) {
            @unlink(dirname(__DIR__) . '/' . $oldVal);
        }
        $stmt = $db->prepare("UPDATE settings SET setting_value = '' WHERE setting_key = 'site_favicon'");
        $stmt->execute();
    }
    
    // Update text settings
    if (isset($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            $stmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
            $stmt->execute([$value, $key]);
        }
    }
    
    logActivity('update', 'settings', null, 'Updated site settings');
    setFlashMessage('success', 'Settings updated successfully.');
    
    header('Location: settings.php');
    exit;
}

// NOW include header (after all potential redirects)
$page_title = 'Settings';
require_once __DIR__ . '/includes/header.php';

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

<form method="POST" action="settings.php" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
    
    <?php
    // Extract file-type settings for the branding card
    $logoSetting = null;
    $faviconSetting = null;
    if (isset($settings['general'])) {
        foreach ($settings['general'] as $s) {
            if ($s['setting_key'] === 'site_logo') $logoSetting = $s;
            if ($s['setting_key'] === 'site_favicon') $faviconSetting = $s;
        }
    }
    $siteUrl = getSiteUrl();
    ?>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Branding Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-paint-brush me-2"></i>Branding</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Site Logo -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Site Logo</label>
                            <div class="border rounded p-3 text-center bg-light" style="min-height: 120px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <?php if (!empty($logoSetting['setting_value'])): ?>
                                <img src="<?php echo $siteUrl . '/' . htmlspecialchars($logoSetting['setting_value']); ?>" 
                                     alt="Current Logo" style="max-height: 80px; max-width: 100%; object-fit: contain;" id="logoPreview">
                                <?php else: ?>
                                <img src="<?php echo $siteUrl; ?>/assets/images/kalpaink-logo.png" 
                                     alt="Default Logo" style="max-height: 80px; max-width: 100%; object-fit: contain; opacity: 0.5;" id="logoPreview">
                                <small class="text-muted mt-1">Using default logo</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control mt-2" name="site_logo" id="site_logo" accept="image/*"
                                   onchange="if(this.files[0]){var r=new FileReader();r.onload=function(e){document.getElementById('logoPreview').src=e.target.result;document.getElementById('logoPreview').style.opacity='1';};r.readAsDataURL(this.files[0]);}">
                            <small class="text-muted">Recommended: PNG or SVG, max-height 55px display</small>
                            <?php if (!empty($logoSetting['setting_value'])): ?>
                            <div class="mt-2">
                                <label class="text-danger small" style="cursor: pointer;">
                                    <input type="checkbox" name="remove_site_logo" value="1" class="form-check-input me-1" style="transform: scale(0.8);">
                                    Remove current logo (revert to default)
                                </label>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Site Favicon -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Site Favicon</label>
                            <div class="border rounded p-3 text-center bg-light" style="min-height: 120px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <?php if (!empty($faviconSetting['setting_value'])): ?>
                                <img src="<?php echo $siteUrl . '/' . htmlspecialchars($faviconSetting['setting_value']); ?>" 
                                     alt="Current Favicon" style="max-height: 64px; max-width: 64px; object-fit: contain;" id="faviconPreview">
                                <?php else: ?>
                                <div id="faviconPreview" style="width: 64px; height: 64px; background: #dee2e6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-globe text-muted fa-2x"></i>
                                </div>
                                <small class="text-muted mt-1">No favicon set</small>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control mt-2" name="site_favicon" id="site_favicon" accept="image/*,.ico"
                                   onchange="if(this.files[0]){var r=new FileReader();r.onload=function(e){var el=document.getElementById('faviconPreview');if(el.tagName==='IMG'){el.src=e.target.result;}else{el.outerHTML='<img id=faviconPreview src='+e.target.result+' style=max-height:64px;max-width:64px;object-fit:contain>';}};r.readAsDataURL(this.files[0]);}">
                            <small class="text-muted">Recommended: 32x32 or 64x64 PNG/ICO</small>
                            <?php if (!empty($faviconSetting['setting_value'])): ?>
                            <div class="mt-2">
                                <label class="text-danger small" style="cursor: pointer;">
                                    <input type="checkbox" name="remove_site_favicon" value="1" class="form-check-input me-1" style="transform: scale(0.8);">
                                    Remove current favicon
                                </label>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-cog me-2"></i>General Settings</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($settings['general'])): ?>
                    <?php foreach ($settings['general'] as $setting): ?>
                    <?php if ($setting['setting_type'] === 'file') continue; // Skip file fields, shown in Branding card ?>
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
                    <p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i>All fields are optional. You can add or update one link at a time.</p>
                    <?php if (isset($settings['social'])): ?>
                    <div class="row">
                    <?php foreach ($settings['social'] as $setting): ?>
                    <div class="col-md-6 mb-3">
                        <label for="<?php echo $setting['setting_key']; ?>" class="form-label">
                            <i class="fab fa-<?php echo str_replace('social_', '', $setting['setting_key']); ?> me-1"></i>
                            <?php echo ucwords(str_replace(['social_', '_'], ['', ' '], $setting['setting_key'])); ?>
                        </label>
                        <input type="text" class="form-control" id="<?php echo $setting['setting_key']; ?>" 
                               name="settings[<?php echo $setting['setting_key']; ?>]" 
                               placeholder="https://..."
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
