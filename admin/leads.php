<?php
/**
 * Leads Management
 * Kalpoink Admin CRM
 */

$page_title = 'Leads';
require_once __DIR__ . '/includes/header.php';

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token. Please try again.');
        header('Location: leads.php');
        exit;
    }
    
    if (isset($_POST['save_lead'])) {
        $data = [
            'name' => sanitize($_POST['name']),
            'email' => sanitize($_POST['email']),
            'phone' => sanitize($_POST['phone']),
            'country' => sanitize($_POST['country']),
            'message' => sanitize($_POST['message']),
            'source' => sanitize($_POST['source']),
            'status' => sanitize($_POST['status']),
            'priority' => sanitize($_POST['priority']),
            'notes' => sanitize($_POST['notes']),
            'assigned_to' => !empty($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null
        ];
        
        if ($id > 0) {
            // Update
            $sql = "UPDATE leads SET name = ?, email = ?, phone = ?, country = ?, message = ?, 
                    source = ?, status = ?, priority = ?, notes = ?, assigned_to = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([...array_values($data), $id]);
            logActivity('update', 'lead', $id, 'Updated lead: ' . $data['name']);
            setFlashMessage('success', 'Lead updated successfully.');
        } else {
            // Insert
            $sql = "INSERT INTO leads (name, email, phone, country, message, source, status, priority, notes, assigned_to) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($data));
            $newId = $db->lastInsertId();
            logActivity('create', 'lead', $newId, 'Created lead: ' . $data['name']);
            setFlashMessage('success', 'Lead created successfully.');
        }
        
        header('Location: leads.php');
        exit;
    }
    
    if (isset($_POST['delete_lead']) && $id > 0) {
        $stmt = $db->prepare("SELECT name FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $lead = $stmt->fetch();
        
        $stmt = $db->prepare("DELETE FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'lead', $id, 'Deleted lead: ' . ($lead['name'] ?? 'Unknown'));
        setFlashMessage('success', 'Lead deleted successfully.');
        
        header('Location: leads.php');
        exit;
    }
}

// Get users for assignment dropdown
$users = $db->query("SELECT id, full_name FROM users WHERE is_active = 1")->fetchAll();

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    $lead = null;
    if ($action === 'edit' && $id > 0) {
        $stmt = $db->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->execute([$id]);
        $lead = $stmt->fetch();
        
        if (!$lead) {
            setFlashMessage('danger', 'Lead not found.');
            header('Location: leads.php');
            exit;
        }
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add New Lead' : 'Edit Lead'; ?></h1>
        <a href="<?php echo getAdminUrl('leads.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="leads.php<?php echo $id ? '?id=' . $id : ''; ?>" class="needs-validation" novalidate>
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required
                               value="<?php echo htmlspecialchars($lead['name'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required
                               value="<?php echo htmlspecialchars($lead['email'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                               value="<?php echo htmlspecialchars($lead['phone'] ?? ''); ?>">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" name="country">
                            <option value="">Select Country</option>
                            <?php 
                            $countries = ['India', 'USA', 'UK', 'Canada', 'Australia', 'Other'];
                            foreach ($countries as $c): 
                            ?>
                            <option value="<?php echo $c; ?>" <?php echo ($lead['country'] ?? '') === $c ? 'selected' : ''; ?>>
                                <?php echo $c; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required><?php echo htmlspecialchars($lead['message'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="source" class="form-label">Source</label>
                        <select class="form-select" id="source" name="source">
                            <?php 
                            $sources = ['contact_form', 'email', 'phone', 'social_media', 'referral', 'other'];
                            foreach ($sources as $s): 
                            ?>
                            <option value="<?php echo $s; ?>" <?php echo ($lead['source'] ?? 'contact_form') === $s ? 'selected' : ''; ?>>
                                <?php echo ucwords(str_replace('_', ' ', $s)); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <?php 
                            $statuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
                            foreach ($statuses as $s): 
                            ?>
                            <option value="<?php echo $s; ?>" <?php echo ($lead['status'] ?? 'new') === $s ? 'selected' : ''; ?>>
                                <?php echo ucfirst($s); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <?php 
                            $priorities = ['low', 'medium', 'high'];
                            foreach ($priorities as $p): 
                            ?>
                            <option value="<?php echo $p; ?>" <?php echo ($lead['priority'] ?? 'medium') === $p ? 'selected' : ''; ?>>
                                <?php echo ucfirst($p); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="assigned_to" class="form-label">Assigned To</label>
                        <select class="form-select" id="assigned_to" name="assigned_to">
                            <option value="">Unassigned</option>
                            <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['id']; ?>" 
                                    <?php echo ($lead['assigned_to'] ?? '') == $user['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user['full_name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12">
                        <label for="notes" class="form-label">Internal Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo htmlspecialchars($lead['notes'] ?? ''); ?></textarea>
                        <small class="text-muted">These notes are only visible to admin users.</small>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex gap-2">
                    <button type="submit" name="save_lead" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Lead
                    </button>
                    <a href="<?php echo getAdminUrl('leads.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <?php
} elseif ($action === 'view' && $id > 0) {
    $stmt = $db->prepare("SELECT l.*, u.full_name as assigned_name FROM leads l LEFT JOIN users u ON l.assigned_to = u.id WHERE l.id = ?");
    $stmt->execute([$id]);
    $lead = $stmt->fetch();
    
    if (!$lead) {
        setFlashMessage('danger', 'Lead not found.');
        header('Location: leads.php');
        exit;
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Lead Details</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('leads.php?action=edit&id=' . $id); ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="<?php echo getAdminUrl('leads.php'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Contact Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p><?php echo htmlspecialchars($lead['name']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p><a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>"><?php echo htmlspecialchars($lead['email']); ?></a></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <p><?php echo htmlspecialchars($lead['phone'] ?: 'N/A'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Country:</strong>
                            <p><?php echo htmlspecialchars($lead['country'] ?: 'N/A'); ?></p>
                        </div>
                        <div class="col-12">
                            <strong>Message:</strong>
                            <p class="bg-light p-3 rounded"><?php echo nl2br(htmlspecialchars($lead['message'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($lead['notes']): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Internal Notes</h5>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($lead['notes'])); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Lead Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge badge-<?php echo $lead['status']; ?> ms-2"><?php echo ucfirst($lead['status']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Priority:</strong>
                        <span class="badge badge-<?php echo $lead['priority']; ?> ms-2"><?php echo ucfirst($lead['priority']); ?></span>
                    </div>
                    <div class="mb-3">
                        <strong>Source:</strong>
                        <p class="mb-0"><?php echo ucwords(str_replace('_', ' ', $lead['source'])); ?></p>
                    </div>
                    <div class="mb-3">
                        <strong>Assigned To:</strong>
                        <p class="mb-0"><?php echo htmlspecialchars($lead['assigned_name'] ?: 'Unassigned'); ?></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <p class="mb-0"><?php echo date('M j, Y \a\t g:i A', strtotime($lead['created_at'])); ?></p>
                    </div>
                    <div>
                        <strong>Last Updated:</strong>
                        <p class="mb-0"><?php echo date('M j, Y \a\t g:i A', strtotime($lead['updated_at'])); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                        <?php if ($lead['phone']): ?>
                        <a href="tel:<?php echo htmlspecialchars($lead['phone']); ?>" class="btn btn-outline-success">
                            <i class="fas fa-phone me-2"></i>Call
                        </a>
                        <?php endif; ?>
                        <form method="POST" action="leads.php?id=<?php echo $id; ?>" class="d-grid">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                            <button type="submit" name="delete_lead" class="btn btn-outline-danger delete-btn">
                                <i class="fas fa-trash me-2"></i>Delete Lead
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
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
    
    $stmt = $db->prepare("SELECT * FROM leads" . $where . " ORDER BY created_at DESC");
    $stmt->execute($params);
    $leads = $stmt->fetchAll();
    ?>
    
    <div class="page-header">
        <h1 class="page-title">Leads Management</h1>
        <a href="<?php echo getAdminUrl('leads.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Lead
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
                        <?php 
                        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
                        foreach ($statuses as $s): 
                        ?>
                        <option value="<?php echo $s; ?>" <?php echo $status_filter === $s ? 'selected' : ''; ?>>
                            <?php echo ucfirst($s); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php if ($status_filter): ?>
                <div class="col-auto">
                    <a href="<?php echo getAdminUrl('leads.php'); ?>" class="btn btn-outline-secondary">Clear Filter</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($leads)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>No leads found</h5>
                <p>Start by adding a new lead or wait for contact form submissions.</p>
                <a href="<?php echo getAdminUrl('leads.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add Lead
                </a>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Source</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($lead['name']); ?></strong>
                            </td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>">
                                    <?php echo htmlspecialchars($lead['email']); ?>
                                </a>
                                <?php if ($lead['phone']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($lead['phone']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $lead['status']; ?>">
                                    <?php echo ucfirst($lead['status']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $lead['priority']; ?>">
                                    <?php echo ucfirst($lead['priority']); ?>
                                </span>
                            </td>
                            <td><?php echo ucwords(str_replace('_', ' ', $lead['source'])); ?></td>
                            <td><?php echo date('M j, Y', strtotime($lead['created_at'])); ?></td>
                            <td>
                                <a href="<?php echo getAdminUrl('leads.php?action=view&id=' . $lead['id']); ?>" 
                                   class="btn btn-action btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo getAdminUrl('leads.php?action=edit&id=' . $lead['id']); ?>" 
                                   class="btn btn-action btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
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
