<?php
/**
 * Bihar Vihaan Enterprise CMS 7.0 Upgrade Database Migrations
 */
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Starting CMS 7.0 Database Migrations...\n";

    // Helper function to check if a column exists
    $columnExists = function($table, $column) use ($db) {
        $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        return $stmt->rowCount() > 0;
    };

    // 1. Create branding_settings table
    $db->exec("
        CREATE TABLE IF NOT EXISTS branding_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            site_name VARCHAR(100) NOT NULL,
            logo_url VARCHAR(255) NULL,
            favicon_url VARCHAR(255) NULL,
            footer_logo_url VARCHAR(255) NULL,
            hero_logo_url VARCHAR(255) NULL,
            primary_color VARCHAR(10) DEFAULT '#0B3D91',
            accent_color VARCHAR(10) DEFAULT '#FF9933',
            success_color VARCHAR(10) DEFAULT '#10B981',
            background_color VARCHAR(10) DEFAULT '#F8F4F0',
            dark_mode_bg VARCHAR(10) DEFAULT '#0F172A',
            font_family VARCHAR(50) DEFAULT 'Inter',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'branding_settings' checked/created.\n";

    // Seed default branding if empty
    $count = $db->query("SELECT COUNT(*) FROM branding_settings")->fetchColumn();
    if ($count == 0) {
        $stmt = $db->prepare("
            INSERT INTO branding_settings (site_name, primary_color, accent_color, success_color, background_color, dark_mode_bg, font_family)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'Bihar Vihaan',
            '#0B3D91',
            '#FF9933',
            '#10B981',
            '#F8F4F0',
            '#0F172A',
            'Inter'
        ]);
        echo "Default branding settings seeded.\n";
    }

    // 2. Create social_links table
    $db->exec("
        CREATE TABLE IF NOT EXISTS social_links (
            id INT AUTO_INCREMENT PRIMARY KEY,
            platform VARCHAR(50) UNIQUE NOT NULL,
            url VARCHAR(255) NULL,
            is_enabled TINYINT(1) DEFAULT 1,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'social_links' checked/created.\n";

    // Seed social media platforms
    $platforms = ['Facebook', 'Instagram', 'YouTube', 'LinkedIn', 'Pinterest', 'Twitter/X', 'WhatsApp'];
    $stmt = $db->prepare("INSERT IGNORE INTO social_links (platform, url, is_enabled) VALUES (?, ?, ?)");
    foreach ($platforms as $p) {
        $stmt->execute([$p, 'https://' . strtolower(str_replace('/X', '', $p)) . '.com/biharvihaan', 1]);
    }
    echo "Social media links seeded.\n";

    // 3. Modify seo_settings table
    $seoColumns = [
        'keywords' => "VARCHAR(255) NULL AFTER open_graph_image",
        'canonical_url' => "VARCHAR(255) NULL AFTER keywords",
        'og_title' => "VARCHAR(255) NULL AFTER canonical_url",
        'og_description' => "TEXT NULL AFTER og_title",
        'twitter_card' => "VARCHAR(50) DEFAULT 'summary_large_image' AFTER og_description",
        'schema_markup' => "TEXT NULL AFTER twitter_card",
        'robots_settings' => "VARCHAR(50) DEFAULT 'index, follow' AFTER schema_markup",
        'sitemap_settings' => "TEXT NULL AFTER robots_settings"
    ];
    foreach ($seoColumns as $col => $definition) {
        if (!$columnExists('seo_settings', $col)) {
            $db->exec("ALTER TABLE seo_settings ADD COLUMN `$col` $definition");
            echo "Added column '$col' to 'seo_settings'.\n";
        }
    }

    // 4. Modify notifications table
    $db->exec("ALTER TABLE notifications MODIFY COLUMN user_id INT NULL");
    echo "Modified 'notifications' table: user_id is now nullable.\n";

    // 5. Create media_library table
    $db->exec("
        CREATE TABLE IF NOT EXISTS media_library (
            id INT AUTO_INCREMENT PRIMARY KEY,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50) NOT NULL,
            file_size INT NOT NULL,
            folder VARCHAR(100) DEFAULT 'uploads',
            uploaded_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'media_library' checked/created.\n";

    // Copy existing media data if media_library is empty and media table exists
    $mlCount = $db->query("SELECT COUNT(*) FROM media_library")->fetchColumn();
    if ($mlCount == 0) {
        $hasMediaTable = $db->query("SHOW TABLES LIKE 'media'")->rowCount() > 0;
        if ($hasMediaTable) {
            $db->exec("
                INSERT INTO media_library (id, file_name, file_path, file_type, file_size, folder, uploaded_by, created_at)
                SELECT id, file_name, file_path, file_type, size_bytes, folder, uploaded_by, created_at FROM media
            ");
            echo "Imported existing records from 'media' to 'media_library'.\n";
        }
    }

    // 6. Create trip_planner_settings table
    $db->exec("
        CREATE TABLE IF NOT EXISTS trip_planner_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'trip_planner_settings' checked/created.\n";

    // Seed default trip planner settings
    $plannerDefaults = [
        'prompt_template' => "You are a professional tour guide in Bihar. Create a detailed itinerary for a {duration} day trip starting from {start_city} for a {group_type} group with a {budget} budget. The style should be {style} and interests are: {interests}.",
        'default_budget_breakdown' => '{"transport": 30, "accommodation": 40, "food": 20, "sightseeing": 10}',
        'seasonal_recommendations' => '{"Winter": ["Rajgir Hot Springs", "Sonepur Mela", "Patna Sahib"], "Monsoon": ["Kakolat Waterfall", "Telhar Kund", "Rohtasgarh"], "Summer": ["Bodh Gaya Meditation", "Nalanda Museum"]}',
        'hidden_gems' => '["Valmiki Nagar Tiger Reserve", "Sher Shah Suri Tomb", "Vikramshila Ruins", "Lomas Rishi Caves"]'
    ];
    $stmt = $db->prepare("INSERT IGNORE INTO trip_planner_settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($plannerDefaults as $key => $val) {
        $stmt->execute([$key, $val]);
    }
    echo "Default trip planner settings seeded.\n";

    // 7. Create content_versions table
    $db->exec("
        CREATE TABLE IF NOT EXISTS content_versions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            content_type VARCHAR(50) NOT NULL,
            content_id INT NOT NULL,
            version_data LONGTEXT NOT NULL,
            created_by INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_content (content_type, content_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "Table 'content_versions' checked/created.\n";

    // 8. Modify blogs table
    $blogColumns = [
        'slug' => "VARCHAR(255) NULL AFTER title",
        'status' => "ENUM('draft', 'published', 'scheduled') DEFAULT 'draft' AFTER content",
        'scheduled_at' => "TIMESTAMP NULL AFTER status",
        'category_id' => "INT NULL AFTER scheduled_at",
        'tags' => "VARCHAR(255) NULL AFTER category_id",
        'meta_title' => "VARCHAR(255) NULL AFTER tags",
        'meta_description' => "TEXT NULL AFTER meta_title"
    ];
    foreach ($blogColumns as $col => $definition) {
        if (!$columnExists('blogs', $col)) {
            $db->exec("ALTER TABLE blogs ADD COLUMN `$col` $definition");
            echo "Added column '$col' to 'blogs'.\n";
        }
    }

    echo "CMS 7.0 migrations completed successfully!\n";
} catch (Exception $e) {
    echo "MIGRATION_ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
