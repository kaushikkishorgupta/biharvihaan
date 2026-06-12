<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Creating social_feeds table for Module 6...\n";

    $db->exec("
        CREATE TABLE IF NOT EXISTS social_feeds (
            id INT AUTO_INCREMENT PRIMARY KEY,
            platform ENUM('youtube', 'instagram') NOT NULL,
            video_id VARCHAR(100) NOT NULL,
            title VARCHAR(255) NULL,
            category VARCHAR(100) DEFAULT 'General',
            is_featured TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    echo "Table 'social_feeds' checked/created.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
