<?php
/**
 * Activity Log
 * Kalpoink Admin CRM
 */

$page_title = 'Activity Log';
require_once __DIR__ . '/includes/header.php';
requireRole('admin');

$db = getDB();

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 50;
$offset = ($page - 1) * $perPage;

// Get total count
$totalStmt = $db->query("SELECT COUNT(*) FROM activity_log");
$totalRecords = $totalStmt->fetchColumn();
$totalPages = ceil($totalRecords / $perPage);

// Get activity logs
$stmt = $db->prepare("
    SELECT a.*, u.full_name as user_name, u.username 
    FROM activity_log a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.created_at DESC 
    LIMIT ? OFFSET ?
");
$stmt->execute([$perPage, $offset]);
$activities = $stmt->fetchAll();
?>

<div class="page-header">
    <h1 class="page-title">Activity Log</h1>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($activities)): ?>
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <h5>No activity recorded</h5>
            <p>Activity logs will appear here once users start using the system.</p>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td>
                            <?php if ($activity['user_name']): ?>
                            <strong><?php echo htmlspecialchars($activity['user_name']); ?></strong>
                            <br><small class="text-muted">@<?php echo htmlspecialchars($activity['username']); ?></small>
                            <?php else: ?>
                            <span class="text-muted">System</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?php 
                                echo match($activity['action']) {
                                    'login' => 'success',
                                    'logout' => 'secondary',
                                    'create' => 'primary',
                                    'update' => 'info',
                                    'delete' => 'danger',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo ucfirst($activity['action']); ?>
                            </span>
                            <?php if ($activity['entity_type']): ?>
                            <span class="ms-1 text-muted"><?php echo htmlspecialchars($activity['entity_type']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($activity['details'] ?? '-'); ?>
                        </td>
                        <td>
                            <small class="text-muted"><?php echo htmlspecialchars($activity['ip_address'] ?? '-'); ?></small>
                        </td>
                        <td>
                            <?php echo date('M j, Y g:i A', strtotime($activity['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
        <nav aria-label="Activity log pagination" class="mt-4">
            <ul class="pagination justify-content-center mb-0">
                <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                for ($i = $start; $i <= $end; $i++): 
                ?>
                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
