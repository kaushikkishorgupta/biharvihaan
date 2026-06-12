<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

try {
    $db = App\Core\Database::getInstance()->getConnection();
    
    // Create Media Table
    $db->exec("
        CREATE TABLE IF NOT EXISTS media (
            id INT AUTO_INCREMENT PRIMARY KEY,
            filename VARCHAR(255) NOT NULL,
            file_path VARCHAR(255) NOT NULL,
            file_type VARCHAR(50) NOT NULL,
            file_size INT NOT NULL,
            uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ");

    // Create Settings Table
    $db->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT
        );
    ");

    // Create Upload Directory
    $uploadDir = __DIR__ . '/assets/uploads/media';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    echo "PHASE5_SETUP_SUCCESSFUL";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
