<?php
/**
 * Projects/Case Studies Management
 * Kalpoink Admin CRM
 */

$page_title = 'Projects';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: projects.php');
        exit;
    }
    
    if (isset($_POST['save_project'])) {
        $title = sanitize($_POST['title']);
        $slug = sanitize($_POST['slug']) ?: generateSlug($title);
        
        // Check for duplicate slug
        $checkSql = "SELECT id FROM projects WHERE slug = ? AND id != ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([$slug, $id]);
        if ($checkStmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'short_description' => sanitize($_POST['description']),
            'full_description' => $_POST['content'],
            'client_name' => sanitize($_POST['client_name']),
            'category' => sanitize($_POST['category']),
            'tags' => sanitize($_POST['tags']),
            'project_url' => sanitize($_POST['project_url']),
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 1,
            'project_date' => !empty($_POST['completed_date']) ? $_POST['completed_date'] : null
        ];
        
        // Handle image upload
        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $upload_dir = __DIR__ . '/../uploads/portfolio/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_ext, $allowed_ext)) {
                $file_name = $slug . '-' . time() . '.' . $file_ext;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $file_path)) {
                    $featured_image = 'uploads/portfolio/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE projects SET title = ?, slug = ?, short_description = ?, full_description = ?, client_name = ?, 
                    category = ?, tags = ?, project_url = ?, is_featured = ?, is_active = ?, project_date = ?";
            $params = array_values($data);
            
            if ($featured_image) {
                $sql .= ", featured_image = ?";
                $params[] = $featured_image;
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'project', $id, 'Updated project: ' . $data['title']);
            setFlashMessage('success', 'Project updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO projects (title, slug, short_description, full_description, client_name, category, tags, 
                    project_url, is_featured, is_active, project_date, featured_image) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $params = array_values($data);
            $params[] = $featured_image;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $newId = $db->lastInsertId();
            logActivity('create', 'project', $newId, 'Created project: ' . $data['title']);
            setFlashMessage('success', 'Project created successfully.');
        }
        
        header('Location: projects.php');
        exit;
    }
    
    if (isset($_POST['delete_project']) && $id > 0) {
        $stmt = $db->prepare("SELECT title FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'project', $id, 'Deleted project: ' . ($project['title'] ?? 'Unknown'));
        setFlashMessage('success', 'Project deleted successfully.');
        
        header('Location: projects.php');
        exit;
    }
}

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $project = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch();
        
        if (!$project) {
            setFlashMessage('danger', 'Project not found.');
            header('Location: projects.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New Project' : 'Edit Project'; ?></h1>
        <a href="<?php echo getAdminUrl('projects.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="projects.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Project Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($project['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="<?php echo htmlspecialchars($project['slug'] ?? ''); ?>">
                            <small class="text-muted">Leave empty to auto-generate from title.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Short Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($project['short_description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Full Case Study</label>
                            <textarea class="form-control tinymce-editor" id="content" name="content" rows="15"><?php echo htmlspecialchars($project['full_description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Project Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       <?php echo ($project['is_active'] ?? 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_active">Active (Visible)</label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="client_name" name="client_name"
                                   value="<?php echo htmlspecialchars($project['client_name'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select Category</option>
                                <?php 
                                $categories = ['Branding', 'Web Design', 'Marketing', 'UI/UX', 'Photography', 'YouTube'];
                                foreach ($categories as $cat): 
                                ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($project['category'] ?? '') === $cat ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                   value="<?php echo htmlspecialchars($project['tags'] ?? ''); ?>">
                            <small class="text-muted">Separate tags with commas.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="project_url" class="form-label">Project URL</label>
                            <input type="url" class="form-control" id="project_url" name="project_url"
                                   value="<?php echo htmlspecialchars($project['project_url'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="completed_date" class="form-label">Project Date</label>
                            <input type="date" class="form-control" id="completed_date" name="completed_date"
                                   value="<?php echo $project['project_date'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                   <?php echo ($project['is_featured'] ?? 0) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_featured">Featured Project</label>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Featured Image</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($project['featured_image'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($project['featured_image']); ?>" 
                                 alt="Featured Image" class="img-fluid rounded mb-2" id="imagePreview">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control image-upload" id="featured_image" 
                               name="featured_image" accept="image/*" data-preview="imagePreview">
                        <small class="text-muted">Recommended size: 800x600 pixels.</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_project" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Project
                    </button>
                    <a href="<?php echo getAdminUrl('projects.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $active_filter = $_GET['active'] ?? '';
    $where = '';
    $params = [];
    
    if ($active_filter !== '') {
        $where = " WHERE is_active = ?";
        $params[] = (int)$active_filter;
    }
    
    $stmt = $db->prepare("SELECT * FROM projects" . $where . " ORDER BY created_at DESC");
    $stmt->execute($params);
    $projects = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Projects / Case Studies</h1>
        <a href="<?php echo getAdminUrl('projects.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Project
        </a>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="active" class="form-label">Filter by Status</label>
                    <select class="form-select" id="active" name="active" onchange="this.form.submit()">
                        <option value="">All Projects</option>
                        <option value="1" <?php echo $active_filter === '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo $active_filter === '0' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <?php if ($active_filter !== ''): ?>
                <div class="col-auto">
                    <a href="<?php echo getAdminUrl('projects.php'); ?>" class="btn btn-outline-secondary">Clear Filter</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($projects)): ?>
            <div class="empty-state">
                <i class="fas fa-briefcase"></i>
                <h5>No projects found</h5>
                <p>Start by creating your first project.</p>
                <a href="<?php echo getAdminUrl('projects.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Project
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $project): ?>
                        <tr>
                            <td>
                                <?php if ($project['featured_image']): ?>
                                <img src="<?php echo getSiteUrl($project['featured_image']); ?>" 
                                     alt="" class="image-preview">
                                <?php else: ?>
                                <div class="image-preview bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($project['title']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($project['client_name'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($project['category'] ?: '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $project['is_active'] ? 'success' : 'secondary'; ?>">
                                    <?php echo $project['is_active'] ? 'Active' : 'Inactive'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($project['is_featured']): ?>
                                <span class="badge bg-warning"><i class="fas fa-star"></i></span>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M j, Y', strtotime($project['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('projects.php?action=edit&id=' . $project['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="projects.php?id=<?php echo $project['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_project" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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
