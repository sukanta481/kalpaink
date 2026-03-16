<?php
/**
 * Dynamic XML Sitemap Generator
 * Generates sitemap.xml dynamically from database and static pages
 * Route: /sitemap.xml (via .htaccess rewrite)
 */

header('Content-Type: application/xml; charset=UTF-8');

require_once __DIR__ . '/config.php';

// Use production URL if set, otherwise auto-detect
$base_url = SITE_URL;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Static Pages -->
    <url>
        <loc><?php echo $base_url; ?>/</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?php echo $base_url; ?>/about</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo $base_url; ?>/services</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?php echo $base_url; ?>/case-studies</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo $base_url; ?>/blog</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo $base_url; ?>/contact</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Dynamic Service Pages -->
<?php
$all_services = getServicesFromDB(false);
if (empty($all_services)) {
    // Fallback to known service slugs
    $all_services = [
        ['slug' => 'graphics-design'],
        ['slug' => 'brand-identity'],
        ['slug' => 'social-media-marketing'],
        ['slug' => 'web-development'],
        ['slug' => 'seo-services'],
        ['slug' => 'content-marketing'],
        ['slug' => 'print-design'],
        ['slug' => 'video-production'],
    ];
}
foreach ($all_services as $svc):
    $slug = $svc['slug'] ?? strtolower(str_replace(' ', '-', $svc['title'] ?? ''));
    if (empty($slug)) continue;
?>
    <url>
        <loc><?php echo $base_url; ?>/services/<?php echo htmlspecialchars($slug); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
<?php endforeach; ?>

    <!-- Dynamic Case Study Pages -->
<?php
$all_projects = getProjectsFromDB();
if (!empty($all_projects)):
    foreach ($all_projects as $project):
        if (empty($project['case_study_image'])) continue;
        $slug = $project['slug'] ?? '';
        if (empty($slug)) continue;
?>
    <url>
        <loc><?php echo $base_url; ?>/case-study/<?php echo htmlspecialchars($slug); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
<?php
    endforeach;
endif;
?>

    <!-- Dynamic Blog Posts -->
<?php
$all_blogs = getBlogsFromDB();
if (!empty($all_blogs)):
    foreach ($all_blogs as $blog):
        $slug = $blog['slug'] ?? '';
        if (empty($slug)) continue;
?>
    <url>
        <loc><?php echo $base_url; ?>/blog/<?php echo htmlspecialchars($slug); ?></loc>
        <lastmod><?php echo date('Y-m-d', strtotime($blog['updated_at'] ?? $blog['created_at'] ?? 'now')); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
<?php
    endforeach;
endif;
?>
</urlset>
