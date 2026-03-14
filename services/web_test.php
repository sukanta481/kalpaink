<?php
$fallback_img = 'assets/images/services/graphics-design.png';
$file_path = __DIR__ . '/../' . $fallback_img;
$exists = file_exists($file_path);
echo "File Path: " . $file_path . "<br>";
echo "Exists? " . ($exists ? 'YES' : 'NO') . "<br>";
echo "Is Readable? " . (is_readable($file_path) ? 'YES' : 'NO') . "<br>";
