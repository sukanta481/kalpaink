<?php
/**
 * Team Members Management
 * Kalpoink Admin CRM
 */

$page_title = 'Team Members';
require_once __DIR__ . '/includes/header.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: team.php');
        exit;
    }
    
    if (isset($_POST['save_member'])) {
        $data = [
            'name' => sanitize($_POST['name']),
            'position' => sanitize($_POST['position']),
            'bio' => sanitize($_POST['bio']),
            'tagline' => sanitize($_POST['tagline']),
            'email' => sanitize($_POST['email']),
            'phone' => sanitize($_POST['phone']),
            'linkedin' => sanitize($_POST['linkedin']),
            'twitter' => sanitize($_POST['twitter']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        // Handle image uploads
        $upload_dir = __DIR__ . '/../uploads/team/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $image = null;
        $image_fun = null;
        
        // Professional image
        if (!empty($_FILES['image_pro']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['image_pro']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = generateSlug($data['name']) . '-pro-' . time() . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image_pro']['tmp_name'], $upload_dir . $file_name)) {
                    $image = 'uploads/team/' . $file_name;
                }
            }
        }
        
        // Fun image
        if (!empty($_FILES['image_fun']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['image_fun']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = generateSlug($data['name']) . '-fun-' . time() . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image_fun']['tmp_name'], $upload_dir . $file_name)) {
                    $image_fun = 'uploads/team/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE team_members SET name = ?, position = ?, bio = ?, tagline = ?, email = ?, 
                    phone = ?, linkedin = ?, twitter = ?, sort_order = ?, is_active = ?";
            $params = array_values($data);
            
            if ($image) {
                $sql .= ", image = ?";
                $params[] = $image;
            }
            if ($image_fun) {
                $sql .= ", image_fun = ?";
                $params[] = $image_fun;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'team_member', $id, 'Updated team member: ' . $data['name']);
            setFlashMessage('success', 'Team member updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO team_members (name, position, bio, tagline, email, phone, linkedin, twitter, sort_order, is_active, image, image_fun) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = array_values($data);
            $params[] = $image;
            $params[] = $image_fun;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $newId = $db->lastInsertId();
            logActivity('create', 'team_member', $newId, 'Created team member: ' . $data['name']);
            setFlashMessage('success', 'Team member added successfully.');
        }
        
        header('Location: team.php');
        exit;
    }
    
    if (isset($_POST['delete_member']) && $id > 0) {
        $stmt = $db->prepare("SELECT name FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'team_member', $id, 'Deleted team member: ' . ($member['name'] ?? 'Unknown'));
        setFlashMessage('success', 'Team member deleted successfully.');
        
        header('Location: team.php');
        exit;
    }
}

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $member = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM team_members WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        
        if (!$member) {
            setFlashMessage('danger', 'Team member not found.');
            header('Location: team.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Team Member' : 'Edit Team Member'; ?></h1>
        <a href="<?php echo getAdminUrl('team.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="team.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="<?php echo htmlspecialchars($member['name'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position *</label>
                                <input type="text" class="form-control" id="position" name="position" required
                                       value="<?php echo htmlspecialchars($member['position'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="tagline" class="form-label">Tagline</label>
                                <input type="text" class="form-control" id="tagline" name="tagline"
                                       placeholder="e.g., Turning caffeine into creativity since 2016"
                                       value="<?php echo htmlspecialchars($member['tagline'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo htmlspecialchars($member['email'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="linkedin" class="form-label">LinkedIn URL</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin"
                                       value="<?php echo htmlspecialchars($member['linkedin'] ?? ''); ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="twitter" class="form-label">Twitter URL</label>
                                <input type="url" class="form-control" id="twitter" name="twitter"
                                       value="<?php echo htmlspecialchars($member['twitter'] ?? ''); ?>">
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
                                   value="<?php echo (int)($member['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($member['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Professional Photo</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($member['image'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($member['image']); ?>" 
                                 alt="Professional Photo" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="image_pro" name="image_pro" accept="image/*">
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Fun Photo (Hover)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($member['image_fun'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($member['image_fun']); ?>" 
                                 alt="Fun Photo" class="img-fluid rounded mb-2" style="max-height: 150px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="image_fun" name="image_fun" accept="image/*">
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_member" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Member
                    </button>
                    <a href="<?php echo getAdminUrl('team.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $stmt = $db->query("SELECT * FROM team_members ORDER BY sort_order ASC, created_at DESC");
    $members = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Team Members</h1>
        <a href="<?php echo getAdminUrl('team.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Member
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($members)): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <h5>No team members found</h5>
                <p>Start by adding your first team member.</p>
                <a href="<?php echo getAdminUrl('team.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Member
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Photo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <?php if ($member['image']): ?>
                                <img src="<?php echo getSiteUrl($member['image']); ?>" 
                                     alt="" class="image-preview" style="width: 50px; height: 50px; object-fit: cover;">
                                <?php else: ?>
                                <div class="image-preview bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($member['name']); ?></strong>
                                <?php if ($member['tagline']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($member['tagline']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($member['position']); ?></td>
                            <td>
                                <?php if ($member['email']): ?>
                                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>">
                                    <i class="fas fa-envelope"></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($member['linkedin']): ?>
                                <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" class="ms-2">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-dot <?php echo $member['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                <?php echo $member['is_active'] ? 'Active' : 'Inactive'; ?>
                            </td>
                            <td><?php echo $member['sort_order']; ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('team.php?action=edit&id=' . $member['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="team.php?id=<?php echo $member['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_member" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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

require_once __DIR__ . '/includes/footer.php';
?>
