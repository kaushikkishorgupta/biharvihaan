<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class MediaModel {

    /**
     * Get all media files
     */
    public static function getAllMedia($folder = null) {
        $db = Database::getInstance()->getConnection();
        if ($folder && $folder !== 'all') {
            $stmt = $db->prepare("SELECT * FROM media WHERE folder = :folder ORDER BY created_at DESC");
            $stmt->execute(['folder' => $folder]);
        } else {
            $stmt = $db->query("SELECT * FROM media ORDER BY created_at DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get unique folders
     */
    public static function getFolders() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT DISTINCT folder, COUNT(id) as file_count FROM media GROUP BY folder");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Save media record to database
     */
    public static function saveMediaRecord($fileName, $filePath, $fileType, $sizeBytes, $folder, $userId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("
            INSERT INTO media (file_name, file_path, file_type, size_bytes, folder, uploaded_by)
            VALUES (:file_name, :file_path, :file_type, :size_bytes, :folder, :uploaded_by)
        ");
        $stmt->execute([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'size_bytes' => $sizeBytes,
            'folder' => $folder,
            'uploaded_by' => $userId
        ]);
        return $db->lastInsertId();
    }

    /**
     * Delete media record
     */
    public static function deleteMedia($id) {
        $db = Database::getInstance()->getConnection();
        
        // First get the path to delete the physical file
        $stmt = $db->prepare("SELECT file_path FROM media WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $media = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($media) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/biharvihaan' . $media['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            
            $delStmt = $db->prepare("DELETE FROM media WHERE id = :id");
            return $delStmt->execute(['id' => $id]);
        }
        return false;
    }
}
