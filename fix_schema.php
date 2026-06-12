<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

try {
    $db = App\Core\Database::getInstance()->getConnection();
    
    // Check if slug exists in destinations
    $stmt = $db->query("SHOW COLUMNS FROM destinations LIKE 'slug'");
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE destinations ADD COLUMN slug VARCHAR(255) NULL AFTER name");
        $db->exec("UPDATE destinations SET slug = LOWER(REPLACE(name, ' ', '-'))");
        echo "Added slug to destinations. \n";
    }

    // Check if slug exists in businesses
    $stmt = $db->query("SHOW COLUMNS FROM businesses LIKE 'slug'");
    if ($stmt->rowCount() == 0) {
        $db->exec("ALTER TABLE businesses ADD COLUMN slug VARCHAR(255) NULL AFTER name");
        $db->exec("UPDATE businesses SET slug = LOWER(REPLACE(name, ' ', '-'))");
        echo "Added slug to businesses. \n";
    }

    echo "SCHEMA_FIXED";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
