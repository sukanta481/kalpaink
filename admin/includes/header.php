<?php
/**
 * Admin Header Template
 * Kalpoink Admin CRM
 */

require_once __DIR__ . '/../config/auth.php';
requireAuth();

$currentUser = getCurrentUser();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Kalpoink Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo getAdminUrl('assets/css/admin.css'); ?>">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <?php
            // Get logo from settings
            $sidebarLogoPath = 'assets/images/kalpaink-logo.png';
            try {
                $logoDB = getDB();
                $logoStmt = $logoDB->prepare("SELECT setting_value FROM settings WHERE setting_key = 'site_logo'");
                $logoStmt->execute();
                $logoVal = $logoStmt->fetchColumn();
                if (!empty($logoVal)) $sidebarLogoPath = $logoVal;
            } catch (Exception $e) {}
            ?>
            <a href="<?php echo getAdminUrl('index.php'); ?>" class="sidebar-brand">
                <img src="<?php echo getSiteUrl($sidebarLogoPath); ?>" alt="Kalpoink" style="max-height: 40px;">
            </a>
            <button class="sidebar-toggle d-lg-none" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('index.php'); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-header">CRM</li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'leads' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('leads.php'); ?>">
                        <i class="fas fa-user-tie"></i>
                        <span>Leads</span>
                    </a>
                </li>
                
                <li class="nav-header">Content</li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'content' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('content.php'); ?>">
                        <i class="fas fa-layer-group"></i>
                        <span>Content Manager</span>
                        <span class="badge bg-success ms-auto" style="font-size: 0.65rem;">Auto-Sync</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'blogs' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('blogs.php'); ?>">
                        <i class="fas fa-blog"></i>
                        <span>Blog Posts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'projects' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('projects.php'); ?>">
                        <i class="fas fa-briefcase"></i>
                        <span>Projects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'services' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('services.php'); ?>">
                        <i class="fas fa-cogs"></i>
                        <span>Services</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'team' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('team.php'); ?>">
                        <i class="fas fa-users"></i>
                        <span>Team Members</span>
                    </a>
                </li>
                
                <?php if (hasRole('admin')): ?>
                <li class="nav-header">Administration</li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'users' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('users.php'); ?>">
                        <i class="fas fa-user-shield"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'settings' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('settings.php'); ?>">
                        <i class="fas fa-sliders-h"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $currentPage == 'activity' ? 'active' : ''; ?>" href="<?php echo getAdminUrl('activity.php'); ?>">
                        <i class="fas fa-history"></i>
                        <span>Activity Log</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo getSiteUrl(); ?>" target="_blank" class="btn btn-outline-light btn-sm w-100">
                <i class="fas fa-external-link-alt me-2"></i>View Website
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar -->
        <nav class="topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?php echo getAdminUrl('index.php'); ?>">Home</a></li>
                        <?php if (isset($page_title)): ?>
                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                        <?php endif; ?>
                    </ol>
                </nav>
            </div>
            
            <div class="topbar-right">
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle user-dropdown" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            <?php if ($currentUser['avatar']): ?>
                                <img src="<?php echo $currentUser['avatar']; ?>" alt="Avatar">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <span class="d-none d-md-inline"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><span class="dropdown-item-text text-muted small"><?php echo htmlspecialchars($currentUser['email']); ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo getAdminUrl('profile.php'); ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="<?php echo getAdminUrl('settings.php'); ?>"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="<?php echo getAdminUrl('logout.php'); ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="content-wrapper">
            <?php 
            $flash = getFlashMessage();
            if ($flash): 
            ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
