<?php
require __DIR__ . '/admin/config/database.php';
$db = getDB();

$stmt = $db->query('SELECT id, title, slug, image FROM services');
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

$img_map = [
    'graphics' => 'graphics-design.png',
    'branding' => 'brand-identity.png',
    'smm' => 'social-media-marketing.png',
    'web' => 'web-development.png',
    'seo' => 'seo-services.png',
    'content' => 'content-marketing.png',
    'print' => 'print-design.png',
    'video' => 'video-production.png',
    
    // In case they are using original slugs
    'graphics-design' => 'graphics-design.png',
    'brand-identity' => 'brand-identity.png',
    'social-media-marketing' => 'social-media-marketing.png',
    'web-development' => 'web-development.png',
    'seo-services' => 'seo-services.png',
    'content-marketing' => 'content-marketing.png',
    'print-design' => 'print-design.png',
    'video-production' => 'video-production.png'
];

foreach ($services as $svc) {
    if (isset($img_map[$svc['slug']])) {
        // Only update if image is empty or null
        if (empty($svc['image'])) {
            $image_path = 'assets/images/services/' . $img_map[$svc['slug']];
            $update = $db->prepare('UPDATE services SET image = ? WHERE id = ?');
            $update->execute([$image_path, $svc['id']]);
            echo "Updated {$svc['slug']} to {$image_path}\n";
        } else {
            echo "Skipped {$svc['slug']} (already has image: {$svc['image']})\n";
        }
    } else {
        echo "Warning: No map found for slug: {$svc['slug']}\n";
    }
}
