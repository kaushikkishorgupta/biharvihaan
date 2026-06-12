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
        'title' => 'Test Image',
        'category' => 'Test Cat',
        'description' => 'Test Desc',
        'location' => 'Test Loc',
        'photographer' => 'Test Photo',
        'image_url' => 'http://test.com/img.jpg',
        'status' => 'published'
    ];
    
    $stmt = $db->prepare("INSERT INTO gallery_images (title, slug, category, description, location, photographer, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], 'test-image-slug', $_POST['category'], $_POST['description'], $_POST['location'], $_POST['photographer'], $_POST['image_url'], $_POST['status']]);
    $id = $db->lastInsertId();
    echo "INSERT_SUCCESS (ID: $id)\n";

    // 2. Test Update
    $_POST['id'] = $id;
    $_POST['title'] = 'Updated Image';
    $stmt = $db->prepare("UPDATE gallery_images SET title=? WHERE id=?");
    $stmt->execute([$_POST['title'], $id]);
    echo "UPDATE_SUCCESS\n";

    // 3. Test Delete
    $stmt = $db->prepare("DELETE FROM gallery_images WHERE id=?");
    $stmt->execute([$id]);
    echo "DELETE_SUCCESS\n";

} catch (Exception $e) {
    echo "TEST_FAILED: " . $e->getMessage();
}
