<?php
/**
 * FAQs Management
 * Kalpoink Admin CRM
 */

// Load auth BEFORE any output
require_once __DIR__ . '/../config/auth.php';
requireRole('editor');

$db = getDB();
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle form submissions BEFORE including header (which outputs HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    
    if (!verifyCsrfToken($csrf)) {
        setFlashMessage('danger', 'Invalid security token.');
        header('Location: faqs.php');
        exit;
    }
    
    if (isset($_POST['save_faq'])) {
        $data = [
            'category' => sanitize($_POST['category']),
            'question' => sanitize($_POST['question']),
            'answer' => $_POST['answer'],
            'sort_order' => (int)$_POST['sort_order'],
            'is_active' => isset($_POST['is_active']) ? 1 : 0
        ];
        
        if ($id > 0) {
            // Update
            $stmt = $db->prepare("UPDATE faqs SET category = ?, question = ?, answer = ?, 
                                  sort_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$data['category'], $data['question'], $data['answer'], 
                           $data['sort_order'], $data['is_active'], $id]);
            logActivity('update', 'faq', $id, 'Updated FAQ: ' . substr($data['question'], 0, 50));
            setFlashMessage('success', 'FAQ updated successfully.');
        } else {
            // Insert
            $stmt = $db->prepare("INSERT INTO faqs (category, question, answer, sort_order, is_active) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$data['category'], $data['question'], $data['answer'], 
                           $data['sort_order'], $data['is_active']]);
            logActivity('create', 'faq', $db->lastInsertId(), 'Created FAQ: ' . substr($data['question'], 0, 50));
            setFlashMessage('success', 'FAQ created successfully.');
        }
        
        header('Location: faqs.php');
        exit;
    }
    
    if (isset($_POST['delete_faq']) && $id > 0) {
        $stmt = $db->prepare("DELETE FROM faqs WHERE id = ?");
        $stmt->execute([$id]);
        logActivity('delete', 'faq', $id, 'Deleted FAQ');
        setFlashMessage('success', 'FAQ deleted successfully.');
        header('Location: faqs.php');
        exit;
    }
}

// FAQ categories
$faq_categories = ['general', 'services', 'pricing', 'process', 'support', 'other'];

// Handle edit action - check if FAQ exists before outputting anything
$faq = null;
if ($action === 'edit' && $id > 0) {
    $stmt = $db->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([$id]);
    $faq = $stmt->fetch();
    
    if (!$faq) {
        setFlashMessage('danger', 'FAQ not found.');
        header('Location: faqs.php');
        exit;
    }
}

// NOW include header (after all potential redirects)
$page_title = 'FAQs';
require_once __DIR__ . '/../includes/header.php';

// Handle different actions
if ($action === 'add' || $action === 'edit') {
    ?>
    
    <div class="page-header">
        <h1 class="page-title"><?php echo $action === 'add' ? 'Add FAQ' : 'Edit FAQ'; ?></h1>
        <a href="<?php echo getAdminUrl('content/faqs.php'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>
    
    <form method="POST" action="faqs.php<?php echo $id ? '?id=' . $id : ''; ?>">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">FAQ Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category *</label>
                            <select class="form-select" id="category" name="category" required>
                                <?php foreach ($faq_categories as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($faq['category'] ?? 'general') === $cat ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($cat); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="question" class="form-label">Question *</label>
                            <input type="text" class="form-control" id="question" name="question" required
                                   placeholder="Enter the frequently asked question"
                                   value="<?php echo htmlspecialchars($faq['question'] ?? ''); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="answer" class="form-label">Answer *</label>
                            <textarea class="form-control tinymce-editor" id="answer" name="answer" rows="8" required><?php echo htmlspecialchars($faq['answer'] ?? ''); ?></textarea>
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
                                   value="<?php echo (int)($faq['sort_order'] ?? 0); ?>">
                            <small class="text-muted">Lower numbers appear first</small>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                   <?php echo ($faq['is_active'] ?? 1) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" name="save_faq" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save FAQ
                    </button>
                    <a href="<?php echo getAdminUrl('content/faqs.php'); ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
    
    <?php
} else {
    // List view
    $filter = $_GET['category'] ?? 'all';
    $where = $filter !== 'all' ? "WHERE category = :cat" : "";
    
    $stmt = $db->prepare("SELECT * FROM faqs $where ORDER BY category, sort_order ASC, id DESC");
    if ($filter !== 'all') {
        $stmt->execute(['cat' => $filter]);
    } else {
        $stmt->execute();
    }
    $faqs = $stmt->fetchAll();
    
    // Get counts per category
    $counts = [];
    $count_stmt = $db->query("SELECT category, COUNT(*) as cnt FROM faqs GROUP BY category");
    while ($row = $count_stmt->fetch()) {
        $counts[$row['category']] = $row['cnt'];
    }
    ?>
    
    <div class="page-header">
        <h1 class="page-title">FAQs</h1>
        <div class="quick-actions">
            <a href="<?php echo getAdminUrl('content.php'); ?>" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back
            </a>
            <a href="<?php echo getAdminUrl('content/faqs.php?action=add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add FAQ
            </a>
        </div>
    </div>
    
    <!-- Category Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="faqs.php" class="btn btn-sm <?php echo $filter === 'all' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    All (<?php echo array_sum($counts); ?>)
                </a>
                <?php foreach ($faq_categories as $cat): ?>
                <a href="faqs.php?category=<?php echo $cat; ?>" 
                   class="btn btn-sm <?php echo $filter === $cat ? 'btn-primary' : 'btn-outline-primary'; ?>">
                    <?php echo ucfirst($cat); ?> (<?php echo $counts[$cat] ?? 0; ?>)
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($faqs)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-question-circle"></i>
                <h5>No FAQs found</h5>
                <p>Add frequently asked questions for your website.</p>
                <a href="<?php echo getAdminUrl('content/faqs.php?action=add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add FAQ
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
    
    <div class="card">
        <div class="card-body">
            <div class="accordion" id="faqAccordion">
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed d-flex align-items-center" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#faq<?php echo $faq['id']; ?>">
                            <span class="badge bg-secondary me-2"><?php echo ucfirst($faq['category']); ?></span>
                            <span class="flex-grow-1"><?php echo htmlspecialchars($faq['question']); ?></span>
                            <?php if (!$faq['is_active']): ?>
                            <span class="badge bg-warning ms-2">Inactive</span>
                            <?php endif; ?>
                        </button>
                    </h2>
                    <div id="faq<?php echo $faq['id']; ?>" class="accordion-collapse collapse" 
                         data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            <div class="mb-3">
                                <?php echo $faq['answer']; ?>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Sort Order: <?php echo $faq['sort_order']; ?></small>
                                <div>
                                    <a href="<?php echo getAdminUrl('content/faqs.php?action=edit&id=' . $faq['id']); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <form method="POST" action="faqs.php?id=<?php echo $faq['id']; ?>" class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                                        <button type="submit" name="delete_faq" class="btn btn-sm btn-outline-danger delete-btn">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php
}

require_once __DIR__ . '/../includes/footer.php';
?>
