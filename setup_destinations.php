<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Altering destinations table for Module 4 requirements...\n";

    $columnExists = function($table, $column) use ($db) {
        $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        return $stmt->rowCount() > 0;
    };

    $columns = [
        'district' => "VARCHAR(100) NULL AFTER location",
        'history' => "TEXT NULL AFTER description",
        'travel_tips' => "TEXT NULL AFTER history",
        'gallery_json' => "TEXT NULL AFTER image_url",
        'meta_title' => "VARCHAR(255) NULL",
        'meta_description' => "TEXT NULL",
        'circuits' => "VARCHAR(255) NULL",
        'nearby_attractions' => "TEXT NULL"
    ];

    foreach ($columns as $col => $def) {
        if (!$columnExists('destinations', $col)) {
            $db->exec("ALTER TABLE destinations ADD COLUMN `$col` $def");
            echo "Added column '$col' to 'destinations'.\n";
        }
    }

    echo "Destinations table setup successfully!\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
