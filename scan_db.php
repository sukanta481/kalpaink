<?php
require __DIR__ . '/admin/config/database.php';
$db = getDB();
$tables = ['settings', 'faqs', 'services', 'team', 'blogs', 'projects', 'testimonials', 'home_content', 'page_content'];
foreach ($tables as $table) {
    try {
        $stmt = $db->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            foreach ($row as $col => $val) {
                if (is_string($val) && preg_match('/kalpoink|kalpanikh|kalpanik/i', $val) && !preg_match('/kalpanik_crm/i', $val)) {
                    $preview = substr(strip_tags($val), 0, 100);
                    $id = $row['id'] ?? ($row['setting_key'] ?? 'unknown');
                    echo "Table: $table | ID: $id | Col: $col | Preview: $preview\n";
                }
            }
        }
    } catch (Exception $e) {}
}
