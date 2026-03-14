<?php
chdir(__DIR__ . '/..');
require_once __DIR__ . '/../config.php';

$slug = 'graphics-design';
$svc_slug = $slug;

$svc_image = '';
$fallback_img = 'assets/images/services/' . $svc_slug . '.png';
$file_path = __DIR__ . '/../' . $fallback_img;

$exists = file_exists($file_path);

echo "Dir: " . __DIR__ . "\n";
echo "Fallback Img: " . $fallback_img . "\n";
echo "File Path: " . $file_path . "\n";
echo "Exists? " . ($exists ? 'YES' : 'NO') . "\n";
echo "Realpath: " . realpath($file_path) . "\n";
