<?php
/**
 * Admin Dashboard
 * Kalpoink Admin CRM
 */

$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$db = getDB();

// Get statistics
$stats = [];

// Total Leads
$stmt = $db->query("SELECT COUNT(*) as count FROM leads");
$stats['leads'] = $stmt->fetch()['count'] ?? 0;

// New Leads (this week)
$stmt = $db->query("SELECT COUNT(*) as count FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$stats['new_leads'] = $stmt->fetch()['count'] ?? 0;

// Total Blog Posts
$stmt = $db->query("SELECT COUNT(*) as count FROM blogs WHERE status = 'published'");
$stats['blogs'] = $stmt->fetch()['count'] ?? 0;

// Total Projects
$stmt = $db->query("SELECT COUNT(*) as count FROM projects WHERE is_active = 1");
$stats['projects'] = $stmt->fetch()['count'] ?? 0;

// Recent Leads
$stmt = $db->query("SELECT * FROM leads ORDER BY created_at DESC LIMIT 5");
$recentLeads = $stmt->fetchAll();

// Recent Activity
$stmt = $db->query("SELECT a.*, u.full_name as user_name FROM activity_log a LEFT JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC LIMIT 10");
$recentActivity = $stmt->fetchAll();

// Leads by Status
$stmt = $db->query("SELECT status, COUNT(*) as count FROM leads GROUP BY status");
$leadsByStatus = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
    <div class="quick-actions">
        <a href="<?php echo getAdminUrl('leads.php?action=add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Lead
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['leads']); ?></div>
            <div class="stat-label">Total Leads</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['new_leads']); ?></div>
            <div class="stat-label">New Leads (7 days)</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-blog"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['blogs']); ?></div>
            <div class="stat-label">Published Blogs</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-value"><?php echo number_format($stats['projects']); ?></div>
            <div class="stat-label">Projects</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Leads -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Recent Leads</h5>
                <a href="<?php echo getAdminUrl('leads.php'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentLeads)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>No leads yet</h5>
                    <p>Leads from your contact form will appear here.</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentLeads as $lead): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($lead['name']); ?></strong>
                                    <?php if ($lead['phone']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($lead['phone']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($lead['email']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $lead['status']; ?>">
                                        <?php echo ucfirst($lead['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($lead['created_at'])); ?></td>
                                <td>
                                    <a href="<?php echo getAdminUrl('leads.php?action=view&id=' . $lead['id']); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
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
    </div>
    
    <!-- Lead Status Overview -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Lead Status Overview</h5>
            </div>
            <div class="card-body">
                <?php 
                $statuses = ['new', 'contacted', 'qualified', 'proposal', 'won', 'lost'];
                foreach ($statuses as $status):
                    $count = $leadsByStatus[$status] ?? 0;
                    $percentage = $stats['leads'] > 0 ? ($count / $stats['leads']) * 100 : 0;
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-capitalize"><?php echo $status; ?></span>
                        <span class="text-muted"><?php echo $count; ?></span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-<?php 
                            echo match($status) {
                                'new' => 'info',
                                'contacted' => 'warning',
                                'qualified' => 'primary',
                                'proposal' => 'secondary',
                                'won' => 'success',
                                'lost' => 'danger',
                                default => 'secondary'
                            };
                        ?>" style="width: <?php echo $percentage; ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Quick Links</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo getAdminUrl('blogs.php?action=add'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>New Blog Post
                    </a>
                    <a href="<?php echo getAdminUrl('projects.php?action=add'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>New Project
                    </a>
                    <a href="<?php echo getSiteUrl(); ?>" target="_blank" class="btn btn-outline-secondary">
                        <i class="fas fa-external-link-alt me-2"></i>Visit Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Recent Activity</h5>
                <?php if (hasRole('admin')): ?>
                <a href="<?php echo getAdminUrl('activity.php'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (empty($recentActivity)): ?>
                <p class="text-muted mb-0">No recent activity.</p>
                <?php else: ?>
                <?php foreach ($recentActivity as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon bg-light text-secondary">
                        <i class="fas fa-<?php 
                            echo match($activity['action']) {
                                'login' => 'sign-in-alt',
                                'logout' => 'sign-out-alt',
                                'create' => 'plus',
                                'update' => 'edit',
                                'delete' => 'trash',
                                default => 'circle'
                            };
                        ?>"></i>
                    </div>
                    <div class="activity-content">
                        <strong><?php echo htmlspecialchars($activity['user_name'] ?? 'System'); ?></strong>
                        <?php echo htmlspecialchars($activity['details'] ?? $activity['action']); ?>
                        <div class="activity-time">
                            <?php echo date('M j, Y \a\t g:i A', strtotime($activity['created_at'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
