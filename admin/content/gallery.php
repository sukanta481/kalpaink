<?php
/**
 * Gallery Management
 * Kalpanik Admin CRM
 */

// Load auth BEFORE any output
require_once __DIR__ . '/../config/auth.php';
requireRole('editor');

$db = getDB();
// Auto-migration: add group_banner and group_sort_order columns if not present
try { $db->exec("ALTER TABLE gallery ADD COLUMN group_banner VARCHAR(255) DEFAULT NULL"); } catch (PDOException $e) {}
try { $db->exec("ALTER TABLE gallery ADD COLUMN group_sort_order INT DEFAULT 0"); } catch (PDOException $e) {}
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token.');
        header('Location: gallery.php');
        exit;
    }
    
    // Group Rename Submit Handler
    if (isset($_POST['submit_rename_group'])) {
        $old_group_name = sanitize($_POST['old_group_name']);
        $new_group_name = sanitize($_POST['new_group_name']);
        $category = sanitize($_POST['group_category']);
        $new_category = sanitize($_POST['new_category'] ?? $category);
        $group_sort_order = isset($_POST['group_sort_order']) ? (int)$_POST['group_sort_order'] : 0;
        
        if (empty($new_group_name)) {
            setFlashMessage('danger', 'Folder name cannot be empty.');
            header('Location: gallery.php');
            exit;
        }
        
        // Handle banner upload
        $banner_path = null;
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        if (!empty($_FILES['group_banner']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['group_banner']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'banner-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['group_banner']['tmp_name'], $upload_dir . $file_name)) {
                    $banner_path = 'uploads/gallery/' . $file_name;
                }
            }
        }
        
        $stmt = $db->prepare("SELECT id, title FROM gallery WHERE category = ?");
        $stmt->execute([$category]);
        $items_to_update = $stmt->fetchAll();
        
        $updated_count = 0;
        foreach ($items_to_update as $item) {
            $item_base = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title']));
            if ($item_base === $old_group_name) {
                if ($item['title'] === $old_group_name) {
                    $new_title = $new_group_name;
                } else {
                    $suffix = substr($item['title'], strlen($item_base));
                    $new_title = $new_group_name . $suffix;
                }
                
                $sql = "UPDATE gallery SET title = ?, category = ?, group_sort_order = ?";
                $params = [$new_title, $new_category, $group_sort_order];
                
                if ($banner_path) {
                    $sql .= ", group_banner = ?";
                    $params[] = $banner_path;
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $item['id'];
                
                $update_stmt = $db->prepare($sql);
                $update_stmt->execute($params);
                $updated_count++;
            }
        }
        
        if ($updated_count > 0) {
            logActivity('update', 'gallery', 0, "Updated folder '$old_group_name' settings. New name: '$new_group_name', Category: '$new_category' ($updated_count items)");
            setFlashMessage('success', "Folder settings updated successfully. Updated $updated_count item(s).");
            header('Location: gallery.php?action=view_folder&folder_name=' . urlencode($new_group_name) . '&category=' . urlencode($new_category));
        } else {
            setFlashMessage('warning', 'No items were found to update.');
            header('Location: gallery.php');
        }
        exit;
    }

    // Delete Whole Folder POST Handler
    if (isset($_POST['delete_whole_folder'])) {
        $folder_name = sanitize($_POST['old_group_name']);
        $category = sanitize($_POST['group_category']);
        
        $stmt = $db->prepare("SELECT * FROM gallery WHERE category = ?");
        $stmt->execute([$category]);
        $all_category_items = $stmt->fetchAll();
        
        $deleted_count = 0;
        foreach ($all_category_items as $item) {
            $item_base = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title']));
            if ($item_base === $folder_name) {
                // Delete files
                if ($item['image'] && file_exists(__DIR__ . '/../../' . $item['image'])) {
                    unlink(__DIR__ . '/../../' . $item['image']);
                }
                if ($item['thumbnail'] && file_exists(__DIR__ . '/../../' . $item['thumbnail'])) {
                    unlink(__DIR__ . '/../../' . $item['thumbnail']);
                }
                
                // Delete from DB
                $delete_stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
                $delete_stmt->execute([$item['id']]);
                $deleted_count++;
            }
        }
        
        if ($deleted_count > 0) {
            logActivity('delete', 'gallery', 0, "Deleted folder '$folder_name' and all of its $deleted_count items");
            setFlashMessage('success', "Folder '$folder_name' and all of its $deleted_count image(s) deleted successfully.");
        } else {
            setFlashMessage('warning', 'No items were found to delete.');
        }
        header('Location: gallery.php');
        exit;
    }
    // Save Folder Changes POST Handler
    if (isset($_POST['save_folder_changes'])) {
        $folder_name = sanitize($_POST['folder_name']);
        $category = sanitize($_POST['category']);
        
        // Handle banner upload
        $banner_path = null;
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        if (!empty($_FILES['group_banner']['name'])) {
            $file_ext = strtolower(pathinfo($_FILES['group_banner']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $file_name = 'banner-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                if (move_uploaded_file($_FILES['group_banner']['tmp_name'], $upload_dir . $file_name)) {
                    $banner_path = 'uploads/gallery/' . $file_name;
                }
            }
        }
        
        // Update all individual items inside this folder
        if (!empty($_POST['items'])) {
            foreach ($_POST['items'] as $id => $item_data) {
                $item_id = (int)$id;
                $title = sanitize($item_data['title']);
                $description = sanitize($item_data['description']);
                $sort_order = (int)$item_data['sort_order'];
                $is_active = isset($item_data['is_active']) ? 1 : 0;
                
                // Update basic details of the item
                $sql = "UPDATE gallery SET title = ?, description = ?, sort_order = ?, is_active = ?";
                $params = [$title, $description, $sort_order, $is_active];
                
                if ($banner_path) {
                    $sql .= ", group_banner = ?";
                    $params[] = $banner_path;
                }
                
                $sql .= " WHERE id = ?";
                $params[] = $item_id;
                
                $stmt = $db->prepare($sql);
                $stmt->execute($params);
            }
        }
        
        // If banner was uploaded, make sure it is updated for all items in that group
        if ($banner_path) {
            $stmt = $db->prepare("SELECT id, title FROM gallery WHERE category = ?");
            $stmt->execute([$category]);
            $all_category_items = $stmt->fetchAll();
            
            foreach ($all_category_items as $item) {
                $item_base = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title']));
                if ($item_base === $folder_name) {
                    $update_stmt = $db->prepare("UPDATE gallery SET group_banner = ? WHERE id = ?");
                    $update_stmt->execute([$banner_path, $item['id']]);
                }
            }
        }
        
        logActivity('update', 'gallery', 0, "Saved changes for folder: $folder_name");
        setFlashMessage('success', "Folder details and sort order updated successfully.");
        header('Location: gallery.php?action=view_folder&folder_name=' . urlencode($folder_name) . '&category=' . urlencode($category));
        exit;
    }

    // Delete Folder Item POST Handler
    if (isset($_POST['delete_folder_item'])) {
        $delete_id = (int)$_POST['delete_folder_item'];
        $folder_name = sanitize($_POST['folder_name']);
        $category = sanitize($_POST['category']);
        
        // Fetch files for deletion
        $stmt = $db->prepare("SELECT image, thumbnail FROM gallery WHERE id = ?");
        $stmt->execute([$delete_id]);
        $item = $stmt->fetch();
        
        if ($item) {
            if ($item['image'] && file_exists(__DIR__ . '/../../' . $item['image'])) {
                unlink(__DIR__ . '/../../' . $item['image']);
            }
            if ($item['thumbnail'] && file_exists(__DIR__ . '/../../' . $item['thumbnail'])) {
                unlink(__DIR__ . '/../../' . $item['thumbnail']);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$delete_id]);
        logActivity('delete', 'gallery', $delete_id, "Deleted folder asset from group: $folder_name");
        
        setFlashMessage('success', "Asset deleted from folder successfully.");
        
        // Check if there are any items left in that folder
        $all_cat = $db->prepare("SELECT title FROM gallery WHERE category = ?");
        $all_cat->execute([$category]);
        $remaining = $all_cat->fetchAll();
        
        $has_remaining = false;
        foreach ($remaining as $r) {
            if (trim(preg_replace('/\s*-\s*\d+$/', '', $r['title'])) === $folder_name) {
                $has_remaining = true;
                break;
            }
        }
        
        if ($has_remaining) {
            header('Location: gallery.php?action=view_folder&folder_name=' . urlencode($folder_name) . '&category=' . urlencode($category));
        } else {
            header('Location: gallery.php');
        }
        exit;
    }
    
    if (isset($_POST['save_item'])) {
        $data = [
            'category' => sanitize($_POST['category']),
            'title' => sanitize($_POST['title']),
            'description' => sanitize($_POST['description']),
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'image_alt_text' => sanitize($_POST['image_alt_text'] ?? '')
        ];

        // Auto-migration: add alt_text column
        try { $db->exec("ALTER TABLE gallery ADD COLUMN image_alt_text VARCHAR(255) DEFAULT NULL"); } catch (PDOException $e) {}
        
        // Handle image upload
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        if ($id > 0) {
            // Edit Mode: Single image replacement
            $image_uploaded = false;
            if (!empty($_FILES['image']['name'])) {
                $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $file_name = 'gallery-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $file_name)) {
                        $data['image'] = 'uploads/gallery/' . $file_name;
                        $image_uploaded = true;
                    }
                }
            }
            
            // Handle thumbnail
            if (!empty($_FILES['thumbnail']['name'])) {
                $file_ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
                if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $file_name = 'thumb-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                    if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $file_name)) {
                        $data['thumbnail'] = 'uploads/gallery/' . $file_name;
                    }
                }
            }
            
            // Update
            $sql = "UPDATE gallery SET category = ?, title = ?, description = ?, sort_order = ?, is_active = ?, image_alt_text = ?";
            $params = [$data['category'], $data['title'], $data['description'], $data['sort_order'], $data['is_active'], $data['image_alt_text']];
            
            if (isset($data['image'])) {
                $sql .= ", image = ?";
                $params[] = $data['image'];
            }
            if (isset($data['thumbnail'])) {
                $sql .= ", thumbnail = ?";
                $params[] = $data['thumbnail'];
            }
            
            $sql .= " WHERE id = ?";
            $params[] = $id;
            
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            logActivity('update', 'gallery', $id, 'Updated gallery item: ' . $data['title']);
            setFlashMessage('success', 'Gallery item updated successfully.');
        } else {
            // Add Mode: Multiple image upload
            $uploaded_count = 0;
            if (!empty($_FILES['images']['name'][0])) {
                $total_files = count($_FILES['images']['name']);
                
                // Get count of existing items with this prefix to start sequential suffix from next number
                $count_stmt = $db->prepare("SELECT COUNT(*) FROM gallery WHERE category = ? AND title LIKE ?");
                $count_stmt->execute([$data['category'], $data['title'] . '%']);
                $current_existing = (int)$count_stmt->fetchColumn();
                
                $sql = "INSERT INTO gallery (category, title, description, image, sort_order, is_active, image_alt_text)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $db->prepare($sql);
                
                foreach ($_FILES['images']['name'] as $key => $name) {
                    if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                        $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $file_name = 'gallery-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                            if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $upload_dir . $file_name)) {
                                $item_title = $data['title'];
                                if ($current_existing > 0 || $total_files > 1) {
                                    $item_title .= ' - ' . ($current_existing + $uploaded_count + 1);
                                }
                                
                                $image_path = 'uploads/gallery/' . $file_name;
                                $params = [
                                    $data['category'],
                                    $item_title,
                                    $data['description'],
                                    $image_path,
                                    $data['sort_order'] + $uploaded_count,
                                    $data['is_active'],
                                    $data['image_alt_text']
                                ];
                                $stmt->execute($params);
                                $uploaded_count++;
                            }
                        }
                    }
                }
            }
            
            if ($uploaded_count > 0) {
                logActivity('create', 'gallery', 0, "Created $uploaded_count gallery items for event/campaign: " . $data['title']);
                setFlashMessage('success', "$uploaded_count gallery items created successfully.");
            } else {
                setFlashMessage('danger', 'Please upload at least one valid image.');
                header('Location: gallery.php?action=add');
                exit;
            }
        }
        
        if (isset($_POST['folder_redirect'])) {
            header('Location: gallery.php?action=view_folder&folder_name=' . urlencode($_POST['folder_name']) . '&category=' . urlencode($_POST['folder_category']));
        } else {
            header('Location: gallery.php');
        }
        exit;
    }
    
    if (isset($_POST['delete_item']) && $id > 0) {
        // Get image paths for deletion
        $stmt = $db->prepare("SELECT image, thumbnail FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
        
        // Delete files
        if ($item) {
            if ($item['image'] && file_exists(__DIR__ . '/../../' . $item['image'])) {
                unlink(__DIR__ . '/../../' . $item['image']);
            }
            if ($item['thumbnail'] && file_exists(__DIR__ . '/../../' . $item['thumbnail'])) {
                unlink(__DIR__ . '/../../' . $item['thumbnail']);
            }
        }
        
        $stmt = $db->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'gallery', $id, 'Deleted gallery item');
        setFlashMessage('success', 'Gallery item deleted successfully.');
        if (isset($_POST['folder_redirect'])) {
            $folder_name = sanitize($_POST['folder_name']);
            $category = sanitize($_POST['folder_category']);
            $all_cat = $db->prepare("SELECT title FROM gallery WHERE category = ?");
            $all_cat->execute([$category]);
            $remaining = $all_cat->fetchAll();
            
            $has_remaining = false;
            foreach ($remaining as $r) {
                if (trim(preg_replace('/\s*-\s*\d+$/', '', $r['title'])) === $folder_name) {
                    $has_remaining = true;
                    break;
                }
            }
            
            if ($has_remaining) {
                header('Location: gallery.php?action=view_folder&folder_name=' . urlencode($folder_name) . '&category=' . urlencode($category));
            } else {
                header('Location: gallery.php');
            }
        } else {
            header('Location: gallery.php');
        }
        exit;
    }
    
    // Bulk upload
    if (isset($_POST['bulk_upload'])) {
        $category = sanitize($_POST['bulk_category']);
        $upload_dir = __DIR__ . '/../../uploads/gallery/';
        
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $uploaded = 0;
        if (!empty($_FILES['bulk_images']['name'][0])) {
            foreach ($_FILES['bulk_images']['name'] as $key => $name) {
                if ($_FILES['bulk_images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $file_name = 'gallery-' . time() . '-' . rand(1000,9999) . '.' . $file_ext;
                        if (move_uploaded_file($_FILES['bulk_images']['tmp_name'][$key], $upload_dir . $file_name)) {
                            $title = pathinfo($name, PATHINFO_FILENAME);
                            $stmt = $db->prepare("INSERT INTO gallery (category, title, image, is_active) VALUES (?, ?, ?, 1)");
                            $stmt->execute([$category, $title, 'uploads/gallery/' . $file_name]);
                            $uploaded++;
                        }
                    }
                }
            }
        }
        
        if ($uploaded > 0) {
            logActivity('create', 'gallery', 0, "Bulk uploaded $uploaded gallery items");
            setFlashMessage('success', "$uploaded images uploaded successfully.");
        } else {
            setFlashMessage('warning', 'No images were uploaded.');
        }
        header('Location: gallery.php');
        exit;
    }
}

// Pre-fetch edit item before header output
$item = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    if (!$item) {
        setFlashMessage('danger', 'Gallery item not found.');
        header('Location: gallery.php');
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'Gallery';
require_once __DIR__ . '/../includes/header.php';

// Gallery categories
$categories = ['portfolio', 'team', 'office', 'events', 'clients', 'other'];

// Handle different actions
if ($action === 'view_folder') {
    $folder_name = $_GET['folder_name'] ?? '';
    $category = $_GET['category'] ?? '';
    
    // Fetch all items in this category
    $stmt = $db->prepare("SELECT * FROM gallery WHERE category = ? ORDER BY sort_order ASC, id DESC");
    $stmt->execute([$category]);
    $all_items = $stmt->fetchAll();
    
    $group_items = [];
    foreach ($all_items as $item) {
        $item_base = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title']));
        if ($item_base === $folder_name) {
            $group_items[] = $item;
        }
    }
    
    if (empty($group_items)) {
        setFlashMessage('danger', 'Folder not found or empty.');
        header('Location: gallery.php');
        exit;
    }
    
    $banner_path = $group_items[0]['group_banner'] ?? '';
    ?>
    <div class="page-header d-none">
        <!-- Hidden header to satisfy breadcrumbs / page structure without duplicating content -->
        <h1 class="page-title"><?php echo htmlspecialchars($folder_name); ?></h1>
    </div>

    <!-- Premium dark card folder header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between p-4 bg-dark text-white shadow-sm border border-secondary border-opacity-25" style="border-radius: 16px;">
                <div class="d-flex align-items-center flex-wrap gap-3">
                    <h2 class="h3 fw-bold text-white mb-0" style="font-family: 'Outfit', 'Inter', sans-serif; letter-spacing: -0.02em;"><?php echo htmlspecialchars($folder_name); ?></h2>
                    <button type="button" class="btn btn-sm btn-outline-light border-0 px-2.5 py-1.5 rounded-circle shadow-sm" 
                            style="background: rgba(255,255,255,0.08); transition: all 0.2s;"
                            data-bs-toggle="modal" data-bs-target="#editFolderModal" title="Edit Folder Settings & Banner">
                        <i class="fas fa-pencil-alt text-white" style="font-size: 0.85rem;"></i>
                    </button>
                    <div class="d-flex align-items-center ms-md-3">
                        <span class="text-muted me-2 small" style="font-size: 0.8rem; font-weight: 500;">Category:</span>
                        <span class="badge bg-secondary text-white px-3 py-2 text-uppercase" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.05em; border-radius: 20px; background: rgba(255, 255, 255, 0.12) !important;"><?php echo htmlspecialchars($category); ?></span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <span class="badge bg-primary px-4 py-2.5 text-white fw-bold shadow-sm" style="border-radius: 24px; font-size: 0.82rem; letter-spacing: 0.02em;">
                        <?php echo count($group_items); ?> Image<?php echo count($group_items) !== 1 ? 's' : ''; ?>
                    </span>
                    <button type="button" class="btn btn-primary btn-sm px-3 py-2 ms-2" 
                            style="border-radius: 24px; font-size: 0.8rem; font-weight: 600; background-color: #0d6efd;"
                            data-bs-toggle="modal" data-bs-target="#addImagesModal" title="Add More Images to Folder">
                        <i class="fas fa-plus me-1"></i> Add Images
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm px-3 py-2 ms-2" 
                            style="border-radius: 24px; border-color: rgba(220,53,69,0.5); font-size: 0.8rem; font-weight: 600;"
                            data-bs-toggle="modal" data-bs-target="#deleteFolderModal" title="Delete Folder and All Contents">
                        <i class="fas fa-trash-alt me-1"></i> Delete Folder
                    </button>
                    <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-light btn-sm px-3 py-2 ms-2" style="border-radius: 24px; border-color: rgba(255,255,255,0.25); font-size: 0.8rem; font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Back to Folders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-6 g-4">
        <?php foreach ($group_items as $item): ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm text-center folder-item-card" 
                 style="border-radius: 16px; overflow: hidden; background: #fff; border: 1px solid rgba(0,0,0,0.06); transition: all 0.25s ease;">
                
                <!-- Image section with "Active" badge -->
                <div class="position-relative w-100" style="aspect-ratio: 1/1; background: #f8f9fa; overflow: hidden;">
                    <img src="<?php echo getSiteUrl($item['thumbnail'] ?: $item['image']); ?>" 
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         class="w-100 h-100"
                         style="object-fit: cover;">
                         
                    <!-- Active badge top-left -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <?php if ($item['is_active']): ?>
                            <span class="badge bg-success px-2.5 py-1.5 fw-bold" style="border-radius: 6px; font-size: 0.65rem; letter-spacing: 0.05em; text-transform: uppercase;">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger px-2.5 py-1.5 fw-bold" style="border-radius: 6px; font-size: 0.65rem; letter-spacing: 0.05em; text-transform: uppercase;">Inactive</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="card-title fw-bold text-dark mb-1" style="font-size: 0.9rem; line-height: 1.3; font-family: 'Inter', sans-serif;">
                            <?php echo htmlspecialchars($item['title']); ?>
                        </h6>
                        <p class="text-muted mb-3" style="font-size: 0.78rem; font-family: 'Inter', sans-serif; font-weight: 500;">
                            Order: <?php echo (int)$item['sort_order']; ?>
                        </p>
                    </div>
                    
                    <!-- Action Buttons centered at the bottom -->
                    <div class="d-flex justify-content-center gap-2 pt-2 border-top" style="border-color: rgba(0,0,0,0.06) !important;">
                        <!-- Edit Button triggers item edit modal -->
                        <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center btn-icon" 
                                style="width: 32px; height: 32px; border-radius: 8px; border-color: rgba(13, 110, 253, 0.25);"
                                data-bs-toggle="modal" data-bs-target="#editItemModal<?php echo $item['id']; ?>"
                                title="Edit Asset Details">
                            <i class="fas fa-pencil-alt" style="font-size: 0.8rem;"></i>
                        </button>
                        
                        <!-- Delete Button triggers delete confirmation modal -->
                        <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center btn-icon" 
                                style="width: 32px; height: 32px; border-radius: 8px; border-color: rgba(220, 53, 69, 0.25);"
                                data-bs-toggle="modal" data-bs-target="#deleteItemModal<?php echo $item['id']; ?>"
                                title="Delete Asset">
                            <i class="fas fa-trash" style="font-size: 0.8rem;"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Item Modal for this specific item -->
        <div class="modal fade" id="editItemModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="editItemModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg text-start" style="border-radius: 16px;">
                    <form method="POST" action="gallery.php?id=<?php echo $item['id']; ?>" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($item['category']); ?>">
                        <input type="hidden" name="folder_redirect" value="1">
                        <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                        <input type="hidden" name="folder_category" value="<?php echo htmlspecialchars($category); ?>">
                        
                        <div class="modal-header bg-dark text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="modal-title text-white" id="editItemModalLabel<?php echo $item['id']; ?>"><i class="fas fa-edit me-2"></i>Edit Image Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-dark">
                            <!-- Current image preview -->
                            <div class="mb-3 text-center">
                                <label class="form-label fw-bold d-block text-dark mb-2">Current Image</label>
                                <img src="<?php echo getSiteUrl($item['image']); ?>" alt="Current Asset" class="img-thumbnail rounded shadow-sm mb-2" style="max-height: 140px; aspect-ratio: 4/3; object-fit: cover;">
                            </div>
                            
                            <div class="mb-3">
                                <label for="title<?php echo $item['id']; ?>" class="form-label fw-bold text-dark">Title *</label>
                                <input type="text" class="form-control text-dark border-1" id="title<?php echo $item['id']; ?>" name="title" required value="<?php echo htmlspecialchars($item['title']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="description<?php echo $item['id']; ?>" class="form-label fw-bold text-dark">Description</label>
                                <textarea class="form-control text-dark border-1" id="description<?php echo $item['id']; ?>" name="description" rows="2" placeholder="Enter asset description..."><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order<?php echo $item['id']; ?>" class="form-label fw-bold text-dark">Sort Order *</label>
                                    <input type="number" class="form-control text-dark border-1" id="sort_order<?php echo $item['id']; ?>" name="sort_order" required value="<?php echo (int)$item['sort_order']; ?>">
                                </div>
                                <div class="col-md-6 mb-3 d-flex align-items-end">
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_active<?php echo $item['id']; ?>" name="is_active" <?php echo $item['is_active'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold text-dark ms-2" for="is_active<?php echo $item['id']; ?>">Active Status</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="image_alt_text<?php echo $item['id']; ?>" class="form-label fw-bold text-dark">Image Alt Text (Accessibility)</label>
                                <input type="text" class="form-control text-dark border-1" id="image_alt_text<?php echo $item['id']; ?>" name="image_alt_text" placeholder="Describe the image..." value="<?php echo htmlspecialchars($item['image_alt_text'] ?? ''); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="replace_image<?php echo $item['id']; ?>" class="form-label fw-bold text-dark">Replace Image (Optional)</label>
                                <input type="file" class="form-control" id="replace_image<?php echo $item['id']; ?>" name="image" accept="image/*">
                                <small class="text-muted d-block mt-1">Leave blank to keep current image. JPG, PNG, WebP supported.</small>
                            </div>
                        </div>
                        <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="save_item" class="btn btn-primary px-4 fw-bold">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Item Modal for this specific item -->
        <div class="modal fade" id="deleteItemModal<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="deleteItemModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow-lg text-start" style="border-radius: 16px;">
                    <form method="POST" action="gallery.php?id=<?php echo $item['id']; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                        <input type="hidden" name="folder_redirect" value="1">
                        <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                        <input type="hidden" name="folder_category" value="<?php echo htmlspecialchars($category); ?>">
                        
                        <div class="modal-header bg-danger text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                            <h5 class="modal-title text-white" id="deleteItemModalLabel<?php echo $item['id']; ?>"><i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 text-dark text-center">
                            <div class="mb-3 text-danger">
                                <i class="fas fa-trash-alt fa-3x animate-pulse"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Delete Gallery Asset?</h5>
                            <p class="text-muted mb-0">Are you sure you want to permanently delete <strong><?php echo htmlspecialchars($item['title']); ?></strong> from the folder?<br>This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="delete_item" class="btn btn-danger px-4 fw-bold">
                                <i class="fas fa-trash-alt me-2"></i>Delete Asset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Edit Folder Settings & Banner Modal -->
    <div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg text-start" style="border-radius: 16px;">
                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="old_group_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <input type="hidden" name="group_category" value="<?php echo htmlspecialchars($category); ?>">
                    
                    <div class="modal-header bg-dark text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="modal-title text-white" id="editFolderModalLabel"><i class="fas fa-folder-open me-2"></i>Edit Folder Settings</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-dark">
                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold text-dark mb-2">Folder Banner (1:1 Aspect Ratio)</label>
                            <?php if (!empty($banner_path)): ?>
                                <img src="<?php echo getSiteUrl($banner_path); ?>" alt="Folder Banner" 
                                     class="img-thumbnail img-fluid rounded-4 shadow-sm mb-3" style="max-height: 180px; aspect-ratio: 1/1; object-fit: cover; border-radius: 12px;">
                            <?php else: ?>
                                <div class="bg-light rounded-4 p-4 d-flex flex-column align-items-center justify-content-center mx-auto mb-3 shadow-sm" 
                                     style="aspect-ratio: 1/1; border: 2px dashed rgba(0,0,0,0.12); max-height: 180px; width: 100%; max-width: 180px; border-radius: 12px;">
                                    <i class="fas fa-folder-open text-muted fa-2x mb-2"></i>
                                    <span class="text-muted small">No custom banner set.<br>First image will be used.</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" class="form-control border-1" name="group_banner" accept="image/*">
                            <small class="text-muted d-block mt-2"><strong>Recommended:</strong> Square 1:1 image, max 2MB.</small>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="mb-3">
                            <label for="new_group_name" class="form-label fw-bold text-dark">Folder / Group Name *</label>
                            <input type="text" class="form-control text-dark border-1" id="new_group_name" name="new_group_name" required value="<?php echo htmlspecialchars($folder_name); ?>">
                            <small class="text-muted mt-1 d-block">Renames the entire folder prefix for all contents.</small>
                        </div>

                        <div class="mb-3">
                            <label for="group_category" class="form-label fw-bold text-dark">Category *</label>
                            <select class="form-select text-dark border-1" id="group_category" name="new_category" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo $category === $cat ? 'selected' : ''; ?>><?php echo ucfirst($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="group_sort_order" class="form-label fw-bold text-dark">Folder Sort Order</label>
                            <input type="number" class="form-control text-dark border-1" id="group_sort_order" name="group_sort_order" required value="<?php echo (int)($group_items[0]['group_sort_order'] ?? 0); ?>">
                            <small class="text-muted mt-1 d-block">Lower numbers will display first on the frontend "All" view.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light d-flex justify-content-between" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                        <button type="button" class="btn btn-outline-danger px-3 fw-bold me-auto" data-bs-toggle="modal" data-bs-target="#deleteFolderModal">
                            <i class="fas fa-trash-alt me-2"></i>Delete Folder
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="submit_rename_group" class="btn btn-primary px-4 fw-bold">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Confirm Delete Whole Folder Modal -->
    <div class="modal fade" id="deleteFolderModal" tabindex="-1" aria-labelledby="deleteFolderModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg text-start" style="border-radius: 16px;">
                <form method="POST" action="gallery.php">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="old_group_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <input type="hidden" name="group_category" value="<?php echo htmlspecialchars($category); ?>">
                    
                    <div class="modal-header bg-danger text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="modal-title text-white" id="deleteFolderModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Delete Entire Folder</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-dark text-center">
                        <div class="mb-3 text-danger text-center">
                            <i class="fas fa-folder-minus fa-4x animate-pulse"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Delete "<?php echo htmlspecialchars($folder_name); ?>" Folder?</h5>
                        <p class="text-muted mb-0">Are you sure you want to permanently delete this folder and <strong>all <?php echo count($group_items); ?> image(s)</strong> inside it?<br>All images will be permanently erased from disk. This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_whole_folder" class="btn btn-danger px-4 fw-bold">
                            <i class="fas fa-trash-alt me-2"></i>Delete Everything
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Images to Folder Modal -->
    <div class="modal fade" id="addImagesModal" tabindex="-1" aria-labelledby="addImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg text-start" style="border-radius: 16px;">
                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                    <input type="hidden" name="title" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <input type="hidden" name="description" value="<?php echo htmlspecialchars($group_items[0]['description'] ?? ''); ?>">
                    <input type="hidden" name="folder_redirect" value="1">
                    <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder_name); ?>">
                    <input type="hidden" name="folder_category" value="<?php echo htmlspecialchars($category); ?>">
                    
                    <div class="modal-header bg-dark text-white border-0 py-3" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                        <h5 class="modal-title text-white" id="addImagesModalLabel"><i class="fas fa-plus me-2"></i>Add Images to Folder</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 text-dark">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Target Folder</label>
                            <input type="text" class="form-control bg-light text-dark" value="<?php echo htmlspecialchars($folder_name); ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_images" class="form-label fw-bold text-dark">Select Images *</label>
                            <input type="file" class="form-control border-1" id="new_images" name="images[]" accept="image/*" multiple required>
                            <small class="text-muted d-block mt-2">Select one or multiple images at once (hold Ctrl/Cmd to select multiple).<br>Format: JPG, PNG, WebP | Max: 5MB each</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="add_image_alt" class="form-label fw-bold text-dark">Image Alt Text (Shared if multiple)</label>
                            <input type="text" class="form-control text-dark border-1" id="add_image_alt" name="image_alt_text" placeholder="Describe the images for accessibility...">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?php
                                $next_sort = 0;
                                foreach ($group_items as $gi) {
                                    if ((int)$gi['sort_order'] >= $next_sort) {
                                        $next_sort = (int)$gi['sort_order'] + 1;
                                    }
                                }
                                ?>
                                <label for="add_sort_order" class="form-label fw-bold text-dark">Starting Sort Order</label>
                                <input type="number" class="form-control text-dark border-1" id="add_sort_order" name="sort_order" required value="<?php echo $next_sort; ?>">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="add_is_active" name="is_active" checked>
                                    <label class="form-check-label fw-bold text-dark ms-2" for="add_is_active">Active Status</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save_item" class="btn btn-primary px-4 fw-bold">
                            <i class="fas fa-upload me-2"></i>Upload Images
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
    .folder-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
    }
    .btn-icon:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    </style>
    <?php
} elseif ($action === 'add' || $action === 'edit') {
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add Gallery Item' : 'Edit Gallery Item'; ?></h1>
        <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Gallery
        </a>
    </div>
    
    <form method="POST" action="gallery.php<?php echo $id ? '?id=' . $id : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Item Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($item['category'] ?? '') === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required
                                   value="<?php echo htmlspecialchars($item['title'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row">
                            <?php if ($action === 'add'): ?>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="images" class="form-label">Images *</label>
                                    <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
                                    <small class="text-muted">Select one or multiple images at once (hold Ctrl/Cmd to select multiple).<br><strong>Size:</strong> 800×600px or larger &nbsp;|&nbsp; <strong>Format:</strong> JPG, PNG, WebP &nbsp;|&nbsp; <strong>Max:</strong> 5MB each</small>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Replace Image</label>
                                    <?php if (!empty($item['image'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo getSiteUrl($item['image']); ?>" alt="Gallery Image" 
                                             class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Leave blank to keep existing image.<br><strong>Size:</strong> 800×600px or larger &nbsp;|&nbsp; <strong>Format:</strong> JPG, PNG, WebP &nbsp;|&nbsp; <strong>Max:</strong> 5MB</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="thumbnail" class="form-label">Thumbnail (Optional)</label>
                                    <?php if (!empty($item['thumbnail'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo getSiteUrl($item['thumbnail']); ?>" alt="Thumbnail" 
                                             class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                                    <small class="text-muted"><strong>Size:</strong> 300×300px &nbsp;|&nbsp; <strong>Format:</strong> JPG, PNG, WebP &nbsp;|&nbsp; <strong>Max:</strong> 1MB</small>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="col-12 mt-2">
                                <div class="mb-3">
                                    <label for="image_alt_text" class="form-label">Image Alt Text (Shared if multiple)</label>
                                    <input type="text" class="form-control" id="image_alt_text" name="image_alt_text"
                                           placeholder="Describe the image for accessibility"
                                           value="<?php echo htmlspecialchars($item['image_alt_text'] ?? ''); ?>">
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
                                   value="<?php echo (int)($item['sort_order'] ?? 0); ?>">
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($item['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_item" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Item
                    </button>
                    <a href="<?php echo getAdminUrl('content/gallery.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $filter = $_GET['category'] ?? 'all';
    $where = $filter !== 'all' ? "WHERE category = :cat" : "";
    
    $stmt = $db->prepare("SELECT * FROM gallery $where ORDER BY group_sort_order ASC, category, sort_order ASC, id DESC");
    if ($filter !== 'all') {
        $stmt->execute(['cat' => $filter]);
    } else {
        $stmt->execute();
    }
    $items = $stmt->fetchAll();
    
    // Group admin items by their base event/campaign/client name
    $grouped_admin_items = [];
    foreach ($items as $item) {
        $base_name = trim(preg_replace('/\s*-\s*\d+$/', '', $item['title']));
        $grouped_admin_items[$base_name][] = $item;
    }
    
    // Get counts per category
    $counts = [];
    $count_stmt = $db->query("SELECT category, COUNT(*) as cnt FROM gallery GROUP BY category");
    while ($row = $count_stmt->fetch()) {
        $counts[$row['category']] = $row['cnt'];
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Gallery</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#bulkUploadModal">
                <i class="fas fa-upload me-2"></i>Bulk Upload
            </button>
            <a href="<?php echo getAdminUrl('content/gallery.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Image
            </a>
        </div>
    </div>
    
    <!-- Category Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="gallery.php" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    All (<?php echo array_sum($counts); ?>)
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="gallery.php?category=<?php echo $cat; ?>" 
                   class="btn btn-sm <?php echo $filter === $cat ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <?php echo ucfirst($cat); ?> (<?php echo $counts[$cat] ?? 0; ?>)
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($items)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <h5>No gallery items found</h5>
                <p>Upload images to your gallery.</p>
                <a href="<?php echo getAdminUrl('content/gallery.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Image
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <div class="row">
        <?php foreach ($grouped_admin_items as $group_name => $group_items): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <!-- Dynamic Folder Card -->
            <div class="card h-100 border-0 shadow-sm folder-card position-relative" 
                 style="border-radius: 16px; overflow: hidden; background: #1a1a1a; cursor: pointer; transition: transform 0.25s ease, box-shadow 0.25s ease;"
                 onclick="location.href='<?php echo getAdminUrl('content/gallery.php?action=view_folder&folder_name=' . urlencode($group_name) . '&category=' . urlencode($group_items[0]['category'])); ?>'">
                
                <!-- 1:1 Aspect Ratio Banner Cover -->
                <div class="position-relative w-100" style="aspect-ratio: 1/1; background: #000; overflow: hidden;">
                    <?php 
                    $banner_src = !empty($group_items[0]['group_banner']) ? $group_items[0]['group_banner'] : ($group_items[0]['thumbnail'] ?: $group_items[0]['image']);
                    ?>
                    <img src="<?php echo getSiteUrl($banner_src); ?>" 
                         alt="<?php echo htmlspecialchars($group_name); ?>"
                         class="w-100 h-100"
                         style="object-fit: cover; transition: transform 0.4s ease;">
                    
                    <!-- Sequential count badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary px-3 py-2 fw-bold" style="border-radius: 20px; font-size: 0.78rem; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            <i class="fas fa-folder me-1"></i><?php echo count($group_items); ?> Image<?php echo count($group_items) !== 1 ? 's' : ''; ?>
                        </span>
                    </div>
                </div>
                
                <!-- Folder description/meta -->
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div>
                        <h6 class="card-title fw-bold text-white mb-2" style="font-size: 1rem; line-height: 1.3;"><?php echo htmlspecialchars($group_name); ?></h6>
                        <span class="badge bg-secondary text-white" style="font-size: 0.7rem; font-weight: 500;"><?php echo ucfirst(htmlspecialchars($group_items[0]['category'])); ?></span>
                        <span class="badge bg-info text-dark ms-2" style="font-size: 0.7rem; font-weight: 600; background-color: #0dcaf0 !important;">Order: <?php echo (int)($group_items[0]['group_sort_order'] ?? 0); ?></span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top" style="border-color: rgba(255,255,255,0.08) !important;">
                        <span class="text-muted small" style="font-size: 0.75rem;"><i class="fas fa-images me-1"></i>Click to Edit Assets</span>
                        <span class="text-primary small fw-bold" style="font-size: 0.75rem;">Open Folder <i class="fas fa-chevron-right ms-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
    <style>
    .folder-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4) !important;
    }
    .folder-card:hover img {
        transform: scale(1.05);
    }
    </style>
    
    <!-- Bulk Upload Modal -->
    <div class="modal fade" id="bulkUploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="gallery.php" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Upload Images</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulk_category" class="form-label">Category</label>
                            <select class="form-select" id="bulk_category" name="bulk_category" required>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat; ?>"><?php echo ucfirst($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bulk_images" class="form-label">Select Images</label>
                            <input type="file" class="form-control" id="bulk_images" name="bulk_images[]" 
                                   accept="image/*" multiple required>
                            <small class="text-muted">Select multiple images to upload at once. Filenames will be used as titles.<br><strong>Size:</strong> 800×600px or larger &nbsp;|&nbsp; <strong>Format:</strong> JPG, PNG, WebP &nbsp;|&nbsp; <strong>Max:</strong> 5MB each</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="bulk_upload" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
