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
        'name' => 'Test Business',
        'category' => 'Hotel',
        'address' => 'Test Address',
        'phone' => '1234567890',
        'email' => 'test@business.com',
        'website' => 'http://test.com',
        'image_url' => 'http://test.com/img.jpg',
        'description' => 'Test Desc'
    ];
    
    $name = $_POST['name'];
    $slug = 'test-business';
    $stmt = $db->prepare("INSERT INTO businesses (name, slug, category, description, address, phone, email, website, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $slug, $_POST['category'], $_POST['description'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['website'], $_POST['image_url']]);
    $id = $db->lastInsertId();
    echo "INSERT_SUCCESS (ID: $id)\n";

    // 2. Test Update
    $_POST['id'] = $id;
    $_POST['name'] = 'Updated Business';
    $stmt = $db->prepare("UPDATE businesses SET name=? WHERE id=?");
    $stmt->execute([$_POST['name'], $id]);
    echo "UPDATE_SUCCESS\n";

    // 3. Test Delete
    $stmt = $db->prepare("DELETE FROM businesses WHERE id=?");
    $stmt->execute([$id]);
    echo "DELETE_SUCCESS\n";

} catch (Exception $e) {
    echo "TEST_FAILED: " . $e->getMessage();
}
