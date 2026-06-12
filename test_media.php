<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

session_start();

try {
    $db = App\Core\Database::getInstance()->getConnection();
    
    // Simulate File Upload
    $filename = 'test_image_' . time() . '.jpg';
    $uploadPath = __DIR__ . '/assets/uploads/media/' . $filename;
    file_put_contents($uploadPath, 'dummy data');
    $publicPath = '/assets/uploads/media/' . $filename;

    $stmt = $db->prepare("INSERT INTO media (file_name, file_path, file_type, size_bytes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$filename, $publicPath, 'jpg', 100]);
    $id = $db->lastInsertId();
    echo "UPLOAD_SIMULATION_SUCCESS (ID: $id)\n";

    // Simulate File Delete using Controller Logic
    $stmt = $db->prepare("SELECT file_path FROM media WHERE id=?");
    $stmt->execute([$id]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($media) {
        $filePath = __DIR__ . $media['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
            echo "FILE_DELETED_SUCCESSFULLY\n";
        }
        
        $stmt = $db->prepare("DELETE FROM media WHERE id=?");
        $stmt->execute([$id]);
        echo "DELETE_SIMULATION_SUCCESS\n";
    }

} catch (Exception $e) {
    echo "TEST_FAILED: " . $e->getMessage();
}
