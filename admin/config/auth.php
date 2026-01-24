<?php
/**
 * Authentication Helper Functions
 * Kalpoink Admin CRM
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_user_id']) && !empty($_SESSION['admin_user_id']);
}

/**
 * Require authentication - redirect to login if not logged in
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: ' . getAdminUrl('login.php'));
        exit;
    }
}

/**
 * Get current logged in user
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, full_name, role, avatar, last_login FROM users WHERE id = ? AND is_active = 1");
    $stmt->execute([$_SESSION['admin_user_id']]);
    return $stmt->fetch();
}

/**
 * Login user
 */
function loginUser($username, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, password, full_name, role FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_user_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        
        // Update last login
        $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updateStmt->execute([$user['id']]);
        
        // Log activity
        logActivity('login', 'user', $user['id'], 'User logged in');
        
        return true;
    }
    
    return false;
}

/**
 * Logout user
 */
function logoutUser() {
    if (isset($_SESSION['admin_user_id'])) {
        logActivity('logout', 'user', $_SESSION['admin_user_id'], 'User logged out');
    }
    
    session_unset();
    session_destroy();
    
    header('Location: ' . getAdminUrl('login.php'));
    exit;
}

/**
 * Check if user has specific role
 */
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $roles = ['admin' => 3, 'editor' => 2, 'viewer' => 1];
    $userRole = $_SESSION['admin_role'] ?? 'viewer';
    
    return ($roles[$userRole] ?? 0) >= ($roles[$role] ?? 0);
}

/**
 * Require specific role
 */
function requireRole($role) {
    if (!hasRole($role)) {
        header('Location: ' . getAdminUrl('index.php?error=unauthorized'));
        exit;
    }
}

/**
 * Log activity
 */
function logActivity($action, $entityType = null, $entityId = null, $details = null) {
    try {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO activity_log (user_id, action, entity_type, entity_id, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['admin_user_id'] ?? null,
            $action,
            $entityType,
            $entityId,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    } catch (Exception $e) {
        // Silently fail - don't break the app for logging issues
    }
}

/**
 * Get admin URL
 */
function getAdminUrl($path = '') {
    $baseUrl = '/kalpoink/admin/';
    return $baseUrl . ltrim($path, '/');
}

/**
 * Get site URL
 */
function getSiteUrl($path = '') {
    $baseUrl = '/kalpoink/';
    return $baseUrl . ltrim($path, '/');
}

/**
 * Generate CSRF token
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate slug from string
 */
function generateSlug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Flash message helpers
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}
?>
