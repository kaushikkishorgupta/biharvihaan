<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';
try {
    $db = App\Core\Database::getInstance()->getConnection();
    $stmt = $db->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $schema = [];
    foreach($tables as $table) {
        $desc = $db->query('DESCRIBE ' . $table)->fetchAll(PDO::FETCH_ASSOC);
        $schema[$table] = $desc;
    }
    echo json_encode($schema, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'DB_ERROR: ' . $e->getMessage();
}
