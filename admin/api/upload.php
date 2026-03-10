<?php
/**
 * Image Upload API for TinyMCE Editor
 * Handles image uploads from the rich text editor
 * Returns JSON: { location: "path/to/image.ext" }
 */

require_once __DIR__ . '/../config/auth.php';

// Must be logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

header('Content-Type: application/json');

// Check for uploaded file
if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit.',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    ];
    $code = $_FILES['file']['error'] ?? UPLOAD_ERR_NO_FILE;
    http_response_code(400);
    echo json_encode(['error' => $errorMessages[$code] ?? 'Upload failed.']);
    exit;
}

$file = $_FILES['file'];

// Validate file type
$allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);

if (!in_array($mimeType, $allowedMimes)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP.']);
    exit;
}

// Validate file size (max 5MB)
$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['error' => 'File too large. Maximum size: 5MB.']);
    exit;
}

// Map mime to extension
$extMap = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];
$ext = $extMap[$mimeType] ?? 'jpg';

// Create upload directory if needed
$uploadDir = __DIR__ . '/../../uploads/content/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$filename = 'content-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
$filePath = $uploadDir . $filename;

// Move file
if (!move_uploaded_file($file['tmp_name'], $filePath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file.']);
    exit;
}

// Return the URL relative to site root (TinyMCE standard: { location: "..." })
$relativePath = 'uploads/content/' . $filename;
$imageUrl = getSiteUrl($relativePath);

// Log activity
logActivity('upload', 'content_image', null, 'Uploaded editor image: ' . $filename);

echo json_encode(['location' => $imageUrl]);
