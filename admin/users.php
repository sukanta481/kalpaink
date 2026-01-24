<?php
/**
 * Users Management
 * Kalpoink Admin CRM
 */

$page_title = 'Users';
require_once __DIR__ . '/includes/header.php';
requireRole('admin');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: users.php');
        exit;
    }
    
    if (isset($_POST['save_user'])) {
        $data = [
            'username' => sanitize($_POST['username']),
            'email' => sanitize($_POST['email']),
            'full_name' => sanitize($_POST['full_name']),
            'role' => sanitize($_POST['role']),
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Check for duplicate username/email
        $checkSql = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([$data['username'], $data['email'], $id]);
        if ($checkStmt->fetch()) {
            setFlashMessage('danger', 'Username or email already exists.');
            header('Location: users.php?action=' . ($id ? 'edit&id=' . $id : 'add'));
            exit;
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE users SET username = ?, email = ?, full_name = ?, role = ?, is_active = ?";
            $params = array_values($data);
            
            // Update password if provided
            if (!empty($_POST['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'user', $id, 'Updated user: ' . $data['username']);
            setFlashMessage('success', 'User updated successfully.');
        } else {
            // Insert
            if (empty($_POST['password'])) {
                setFlashMessage('danger', 'Password is required for new users.');
                header('Location: users.php?action=add');
                exit;
            }
            
            $sql = "INSERT INTO users (username, email, password, full_name, role, is_active) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $params = [
                $data['username'],
                $data['email'],
                password_hash($_POST['password'], PASSWORD_DEFAULT),
                $data['full_name'],
                $data['role'],
                $data['is_active']
            ];
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $newId = $db->lastInsertId();
            logActivity('create', 'user', $newId, 'Created user: ' . $data['username']);
            setFlashMessage('success', 'User created successfully.');
        }
        
        header('Location: users.php');
        exit;
    }
    
    if (isset($_POST['delete_user']) && $id > 0) {
        // Prevent self-deletion
        if ($id == $_SESSION['admin_user_id']) {
            setFlashMessage('danger', 'You cannot delete your own account.');
            header('Location: users.php');
            exit;
        }
        
        $stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'user', $id, 'Deleted user: ' . ($user['username'] ?? 'Unknown'));
        setFlashMessage('success', 'User deleted successfully.');
        
        header('Location: users.php');
        exit;
    }
}

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $user = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            setFlashMessage('danger', 'User not found.');
            header('Location: users.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New User' : 'Edit User'; ?></h1>
        <a href="<?php echo getAdminUrl('users.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="users.php<?php echo $id ? '?id=' . $id : ''; ?>" class="needs-validation" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control" id="username" name="username" required
                                       pattern="[a-zA-Z0-9_]+" title="Only letters, numbers, and underscores"
                                       value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required
                                       value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    Password <?php echo $action === 'add' ? '*' : ''; ?>
                                </label>
                                <input type="password" class="form-control" id="password" name="password"
                                       <?php echo $action === 'add' ? 'required' : ''; ?>>
                                <?php if ($action === 'edit'): ?>
                                <small class="text-muted">Leave empty to keep current password.</small>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="viewer" <?php echo ($user['role'] ?? '') === 'viewer' ? 'selected' : ''; ?>>Viewer</option>
                                    <option value="editor" <?php echo ($user['role'] ?? '') === 'editor' ? 'selected' : ''; ?>>Editor</option>
                                    <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           <?php echo ($user['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_active">Active Account</label>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex gap-2">
                            <button type="submit" name="save_user" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save User
                            </button>
                            <a href="<?php echo getAdminUrl('users.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Role Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Admin</strong>
                        <p class="text-muted small mb-0">Full access to all features including user management and settings.</p>
                    </div>
                    <div class="mb-3">
                        <strong>Editor</strong>
                        <p class="text-muted small mb-0">Can manage content (blogs, projects, services, team) but not users or settings.</p>
                    </div>
                    <div class="mb-0">
                        <strong>Viewer</strong>
                        <p class="text-muted small mb-0">Read-only access to view dashboard and content.</p>
                    </div>
                </div>
            </div>
            
            <?php if ($action === 'edit' && $user): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">User Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td>Created</td>
                            <td class="text-end"><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                        </tr>
                        <tr>
                            <td>Last Login</td>
                            <td class="text-end"><?php echo $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Users</h1>
        <a href="<?php echo getAdminUrl('users.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add User
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-2" style="width: 36px; height: 36px; font-size: 0.875rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($user['full_name']); ?></strong>
                                        <br><small class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo match($user['role']) {
                                        'admin' => 'danger',
                                        'editor' => 'primary',
                                        default => 'secondary'
                                    };
                                ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-dot <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td>
                                <?php echo $user['last_login'] ? date('M j, Y g:i A', strtotime($user['last_login'])) : 'Never'; ?>
                            </td>
                            <td>
                                <a href="<?php echo getAdminUrl('users.php?action=edit&id=' . $user['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <?php if ($user['id'] != $_SESSION['admin_user_id']): ?>
                                <form method="POST" action="users.php?id=<?php echo $user['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_user" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <?php
}

require_once __DIR__ . '/includes/footer.php';
?>
