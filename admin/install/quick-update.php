<?php
/**
 * Quick Schema Update
 * Run directly from CLI
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $db = new PDO('mysql:host=localhost;dbname=kalpoink_crm;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "=== Updating Database Schema ===\n\n";
    
    // Update team_members table
    echo "Updating team_members...\n";
    try {
        $db->exec("ALTER TABLE team_members ADD COLUMN image VARCHAR(255) AFTER bio");
        echo "  Added 'image' column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "  'image' column already exists\n";
        } else {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
    
    try {
        $db->exec("ALTER TABLE team_members ADD COLUMN instagram VARCHAR(255) AFTER twitter");
        echo "  Added 'instagram' column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "  'instagram' column already exists\n";
        } else {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Update projects table
    echo "\nUpdating projects...\n";
    $projectColumns = [
        ['short_description', 'TEXT', 'AFTER slug'],
        ['full_description', 'LONGTEXT', 'AFTER short_description'],
        ['project_date', 'DATE', 'AFTER client_name'],
        ['gallery_images', 'TEXT', 'AFTER featured_image'],
        ['is_active', 'TINYINT(1) DEFAULT 1', 'AFTER is_featured']
    ];
    
    foreach ($projectColumns as $col) {
        try {
            $db->exec("ALTER TABLE projects ADD COLUMN {$col[0]} {$col[1]} {$col[2]}");
            echo "  Added '{$col[0]}' column\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column') !== false) {
                echo "  '{$col[0]}' column already exists\n";
            } else {
                echo "  Error: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Copy data from old columns
    $db->exec("UPDATE projects SET short_description = description WHERE short_description IS NULL AND description IS NOT NULL");
    $db->exec("UPDATE projects SET full_description = content WHERE full_description IS NULL AND content IS NOT NULL");
    $db->exec("UPDATE projects SET project_date = completed_date WHERE project_date IS NULL AND completed_date IS NOT NULL");
    $db->exec("UPDATE projects SET is_active = CASE WHEN status = 'published' THEN 1 ELSE 0 END WHERE is_active IS NULL");
    echo "  Copied data from old columns\n";
    
    // Update services table
    echo "\nUpdating services...\n";
    try {
        $db->exec("ALTER TABLE services ADD COLUMN is_featured TINYINT(1) DEFAULT 0 AFTER sort_order");
        echo "  Added 'is_featured' column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "  'is_featured' column already exists\n";
        } else {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
    
    try {
        $db->exec("ALTER TABLE services ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER is_featured");
        echo "  Added 'is_active' column\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
            echo "  'is_active' column already exists\n";
        } else {
            echo "  Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Copy data from status
    $db->exec("UPDATE services SET is_active = CASE WHEN status = 'active' THEN 1 ELSE 0 END WHERE is_active IS NULL");
    echo "  Copied data from status column\n";
    
    echo "\n=== Schema Update Complete! ===\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
