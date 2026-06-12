<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

session_start();

try {
    $db = App\Core\Database::getInstance()->getConnection();
    
    // 1. Test SEO Insert
    $stmt = $db->prepare("INSERT INTO seo_settings (route, meta_title, meta_description, open_graph_image) VALUES (?, ?, ?, ?)");
    $stmt->execute(['/test', 'Test Title', 'Test Desc', 'test.jpg']);
    $id = $db->lastInsertId();
    echo "SEO_INSERT_SUCCESS (ID: $id)\n";

    // 2. Test SEO Update
    $stmt = $db->prepare("UPDATE seo_settings SET meta_title=? WHERE id=?");
    $stmt->execute(['Updated Title', $id]);
    echo "SEO_UPDATE_SUCCESS\n";

    // 3. Test SEO Delete
    $stmt = $db->prepare("DELETE FROM seo_settings WHERE id=?");
    $stmt->execute([$id]);
    echo "SEO_DELETE_SUCCESS\n";

    // 4. Test Global Settings Insert/Update
    $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value=?");
    $stmt->execute(['test_key', 'test_val', 'test_val']);
    echo "GLOBAL_SETTINGS_SUCCESS\n";

} catch (Exception $e) {
    echo "TEST_FAILED: " . $e->getMessage();
}
