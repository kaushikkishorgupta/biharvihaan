<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

session_start();
$_SESSION['user_id'] = 1;
$_SESSION['role_id'] = 1;

try {
    $db = App\Core\Database::getInstance()->getConnection();
    
    // 1. Test Insert
    $_POST = [
        'name' => 'Test Destination',
        'category' => 'Heritage',
        'location' => 'Test Loc',
        'description' => 'Test Desc',
        'image_url' => 'http://test.com/img.jpg'
    ];
    
    $name = $_POST['name'];
    $slug = 'test-destination';
    $stmt = $db->prepare("INSERT INTO destinations (name, slug, category, location, description, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $slug, $_POST['category'], $_POST['location'], $_POST['description'], $_POST['image_url']]);
    $id = $db->lastInsertId();
    echo "INSERT_SUCCESS (ID: $id)\n";

    // 2. Test Update
    $_POST['id'] = $id;
    $_POST['name'] = 'Updated Destination';
    $stmt = $db->prepare("UPDATE destinations SET name=? WHERE id=?");
    $stmt->execute([$_POST['name'], $id]);
    echo "UPDATE_SUCCESS\n";

    // 3. Test Delete
    $stmt = $db->prepare("DELETE FROM destinations WHERE id=?");
    $stmt->execute([$id]);
    echo "DELETE_SUCCESS\n";

} catch (Exception $e) {
    echo "TEST_FAILED: " . $e->getMessage();
}
