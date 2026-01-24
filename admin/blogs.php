<?php
/**
 * Blog Posts Management
 * Kalpoink Admin CRM
 */

$page_title = 'Blog Posts';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: blogs.php');
        exit;
    }
    
    if (isset($_POST['save_blog'])) {
        $title = sanitize($_POST['title']);
        $slug = sanitize($_POST['slug']) ?: generateSlug($title);
        
        // Check for duplicate slug
        $checkSql = "SELECT id FROM blogs WHERE slug = ? AND id != ?";
        $checkStmt = $db->prepare($checkSql);
        $checkStmt->execute([$slug, $id]);
        if ($checkStmt->fetch()) {
            $slug = $slug . '-' . time();
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => sanitize($_POST['excerpt']),
            'content' => $_POST['content'], // Don't sanitize - contains HTML
            'category' => sanitize($_POST['category']),
            'tags' => sanitize($_POST['tags']),
            'status' => sanitize($_POST['status']),
            'read_time' => sanitize($_POST['read_time']),
            'meta_title' => sanitize($_POST['meta_title']),
            'meta_description' => sanitize($_POST['meta_description']),
            'author_id' => $_SESSION['admin_user_id']
        ];
        
        // Handle image upload
        $featured_image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $upload_dir = __DIR__ . '/../uploads/blog/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($file_ext, $allowed_ext)) {
                $file_name = $slug . '-' . time() . '.' . $file_ext;
                $file_path = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $file_path)) {
                    $featured_image = 'uploads/blog/' . $file_name;
                }
            }
        }
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE blogs SET title = ?, slug = ?, excerpt = ?, content = ?, category = ?, 
                    tags = ?, status = ?, read_time = ?, meta_title = ?, meta_description = ?, author_id = ?";
            $params = array_values($data);
            
            if ($featured_image) {
                $sql .= ", featured_image = ?";
                $params[] = $featured_image;
            }
            
            if ($data['status'] === 'published') {
                $sql .= ", published_at = COALESCE(published_at, NOW())";
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'blog', $id, 'Updated blog: ' . $data['title']);
            setFlashMessage('success', 'Blog post updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO blogs (title, slug, excerpt, content, category, tags, status, read_time, 
                    meta_title, meta_description, author_id, featured_image, published_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, " . ($data['status'] === 'published' ? 'NOW()' : 'NULL') . ")";
            $params = array_values($data);
            $params[] = $featured_image;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $newId = $db->lastInsertId();
            logActivity('create', 'blog', $newId, 'Created blog: ' . $data['title']);
            setFlashMessage('success', 'Blog post created successfully.');
        }
        
        header('Location: blogs.php');
        exit;
    }
    
    if (isset($_POST['delete_blog']) && $id > 0) {
        $stmt = $db->prepare("SELECT title FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'blog', $id, 'Deleted blog: ' . ($blog['title'] ?? 'Unknown'));
        setFlashMessage('success', 'Blog post deleted successfully.');
        
        header('Location: blogs.php');
        exit;
    }
}

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $blog = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM blogs WHERE id = ?");
        $stmt->execute([$id]);
        $blog = $stmt->fetch();
        
        if (!$blog) {
            setFlashMessage('danger', 'Blog post not found.');
            header('Location: blogs.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New Blog Post' : 'Edit Blog Post'; ?></h1>
        <a href="<?php echo getAdminUrl('blogs.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="blogs.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data" class="needs-validation" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($blog['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="<?php echo htmlspecialchars($blog['slug'] ?? ''); ?>">
                            <small class="text-muted">Leave empty to auto-generate from title.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($blog['excerpt'] ?? ''); ?></textarea>
                            <small class="text-muted">A short summary of the blog post.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control tinymce-editor" id="content" name="content" rows="15"><?php echo htmlspecialchars($blog['content'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- SEO Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                   value="<?php echo htmlspecialchars($blog['meta_title'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2"><?php echo htmlspecialchars($blog['meta_description'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Publish Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="draft" <?php echo ($blog['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                                <option value="published" <?php echo ($blog['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                                <option value="archived" <?php echo ($blog['status'] ?? '') === 'archived' ? 'selected' : ''; ?>>Archived</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Select Category</option>
                                <?php 
                                $categories = ['Design', 'Branding', 'Marketing', 'SEO', 'Web Development', 'Social Media'];
                                foreach ($categories as $cat): 
                                ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($blog['category'] ?? '') === $cat ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                   value="<?php echo htmlspecialchars($blog['tags'] ?? ''); ?>">
                            <small class="text-muted">Separate tags with commas.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="read_time" class="form-label">Read Time</label>
                            <input type="text" class="form-control" id="read_time" name="read_time"
                                   placeholder="e.g., 5 min read"
                                   value="<?php echo htmlspecialchars($blog['read_time'] ?? ''); ?>">
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Featured Image</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($blog['featured_image'])): ?>
                        <div class="mb-3">
                            <img src="<?php echo getSiteUrl($blog['featured_image']); ?>" 
                                 alt="Featured Image" class="img-fluid rounded mb-2" id="imagePreview">
                        </div>
                        <?php endif; ?>
                        <input type="file" class="form-control image-upload" id="featured_image" 
                               name="featured_image" accept="image/*" data-preview="imagePreview">
                        <small class="text-muted">Recommended size: 1200x630 pixels.</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_blog" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Blog Post
                    </button>
                    <a href="<?php echo getAdminUrl('blogs.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $status_filter = $_GET['status'] ?? '';
    $where = '';
    $params = [];
    
    if ($status_filter) {
        $where = " WHERE status = ?";
        $params[] = $status_filter;
    }
    
    $stmt = $db->prepare("SELECT b.*, u.full_name as author_name FROM blogs b LEFT JOIN users u ON b.author_id = u.id" . $where . " ORDER BY created_at DESC");
    $stmt->execute($params);
    $blogs = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Blog Posts</h1>
        <a href="<?php echo getAdminUrl('blogs.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Post
        </a>
    </div>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Filter by Status</label>
                    <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="draft" <?php echo $status_filter === 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $status_filter === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                    </select>
                </div>
                <?php if ($status_filter): ?>
                <div class="col-auto">
                    <a href="<?php echo getAdminUrl('blogs.php'); ?>" class="btn btn-outline-secondary">Clear Filter</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($blogs)): ?>
            <div class="empty-state">
                <i class="fas fa-newspaper"></i>
                <h5>No blog posts found</h5>
                <p>Start by creating your first blog post.</p>
                <a href="<?php echo getAdminUrl('blogs.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Post
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blogs as $blog): ?>
                        <tr>
                            <td>
                                <?php if ($blog['featured_image']): ?>
                                <img src="<?php echo getSiteUrl($blog['featured_image']); ?>" 
                                     alt="" class="image-preview">
                                <?php else: ?>
                                <div class="image-preview bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($blog['title']); ?></strong>
                                <br><small class="text-muted">/blog/<?php echo htmlspecialchars($blog['slug']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($blog['category'] ?: '-'); ?></td>
                            <td><?php echo htmlspecialchars($blog['author_name'] ?? 'Unknown'); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $blog['status']; ?>">
                                    <?php echo ucfirst($blog['status']); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($blog['views']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($blog['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('blogs.php?action=edit&id=' . $blog['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="blogs.php?id=<?php echo $blog['id']; ?>" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                    <button type="submit" name="delete_blog" class="btn btn-action btn-outline-danger delete-btn" title="Delete">
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
