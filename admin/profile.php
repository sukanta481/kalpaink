<?php
/**
 * User Profile
 * Kalpoink Admin CRM
 */

$page_title = 'My Profile';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$user = getCurrentUser();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: profile.php');
        exit;
    }
    
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize($_POST['full_name']);
        $email = sanitize($_POST['email']);
        
        // Check for duplicate email
        $checkStmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $checkStmt->execute([$email, $user['id']]);
        if ($checkStmt->fetch()) {
            setFlashMessage('danger', 'Email already exists.');
            header('Location: profile.php');
            exit;
        }
        
        $stmt = $db->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        $stmt->execute([$full_name, $email, $user['id']]);
        
        logActivity('update', 'profile', $user['id'], 'Updated profile');
        setFlashMessage('success', 'Profile updated successfully.');
        header('Location: profile.php');
        exit;
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Get current password hash
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $userData = $stmt->fetch();
        
        if (!password_verify($current_password, $userData['password'])) {
            setFlashMessage('danger', 'Current password is incorrect.');
            header('Location: profile.php');
            exit;
        }
        
        if (strlen($new_password) < 6) {
            setFlashMessage('danger', 'New password must be at least 6 characters.');
            header('Location: profile.php');
            exit;
        }
        
        if ($new_password !== $confirm_password) {
            setFlashMessage('danger', 'New passwords do not match.');
            header('Location: profile.php');
            exit;
        }
        
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $user['id']]);
        
        logActivity('update', 'password', $user['id'], 'Changed password');
        setFlashMessage('success', 'Password changed successfully.');
        header('Location: profile.php');
        exit;
    }
}
?>

<div class="page-header">
    <h1 class="page-title">My Profile</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Profile Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="profile.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" 
                                   value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                            <small class="text-muted">Username cannot be changed.</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" 
                                   value="<?php echo ucfirst($user['role']); ?>" disabled>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">Full Name *</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required
                                   value="<?php echo htmlspecialchars($user['full_name']); ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required
                                   value="<?php echo htmlspecialchars($user['email']); ?>">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" name="update_profile" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Change Password -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="profile.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="confirm_password" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" name="change_password" class="btn btn-warning">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="fas fa-user"></i>
                </div>
                <h5 class="mb-1"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                <p class="text-muted mb-3">@<?php echo htmlspecialchars($user['username']); ?></p>
                <span class="badge bg-<?php 
                    echo match($user['role']) {
                        'admin' => 'danger',
                        'editor' => 'primary',
                        default => 'secondary'
                    };
                ?> fs-6">
                    <?php echo ucfirst($user['role']); ?>
                </span>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Account Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td>Last Login</td>
                        <td class="text-end">
                            <?php echo $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'N/A'; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
