<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminMediaController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_media');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $folder = $_GET['folder'] ?? 'all';
        $search = $_GET['q'] ?? '';
        
        $sql = "SELECT * FROM media_library WHERE 1=1";
        $params = [];
        
        if ($folder !== 'all') {
            $sql .= " AND folder = ?";
            $params[] = $folder;
        }
        
        if (!empty($search)) {
            $sql .= " AND file_name LIKE ?";
            $params[] = "%$search%";
        }
        
        $sql .= " ORDER BY id DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch folder counts for sidebar
        $folders = [
            'all' => $db->query("SELECT COUNT(*) FROM media_library")->fetchColumn(),
            'images' => $db->query("SELECT COUNT(*) FROM media_library WHERE folder = 'images'")->fetchColumn(),
            'videos' => $db->query("SELECT COUNT(*) FROM media_library WHERE folder = 'videos'")->fetchColumn(),
            'documents' => $db->query("SELECT COUNT(*) FROM media_library WHERE folder = 'documents'")->fetchColumn()
        ];

        $this->renderAdmin('admin/media', [
            'title' => 'Media Library Hub | Bihar Vihaan',
            'items' => $items,
            'folders' => $folders,
            'currentFolder' => $folder,
            'searchQuery' => $search
        ]);
    }

    public function upload() {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Session::setFlash('error', 'Upload failed. No file received.');
            $this->redirect('/admin/media');
            return;
        }

        $db = Database::getInstance()->getConnection();
        $file = $_FILES['file'];
        $originalName = $file['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'mp4', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];

        if (!in_array($ext, $allowed)) {
            Session::setFlash('error', 'Invalid file extension: .' . $ext);
            $this->redirect('/admin/media');
            return;
        }

        // Determine Folder category
        $folder = 'documents';
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $folder = 'images';
        } elseif (in_array($ext, ['mp4', 'webm', 'mov', 'avi'])) {
            $folder = 'videos';
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $ext;
        $uploadDir = dirname(__DIR__, 2) . '/assets/uploads/media/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $uploadPath = $uploadDir . $filename;
        $publicPath = '/assets/uploads/media/' . $filename;
        $fileSize = $file['size'];

        // Perform WebP Compression optimization if GD is available
        if (in_array($ext, ['jpg', 'jpeg', 'png']) && extension_loaded('gd')) {
            $webpFilename = time() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', pathinfo($originalName, PATHINFO_FILENAME)) . '.webp';
            $webpPath = $uploadDir . $webpFilename;
            
            $img = null;
            if ($ext === 'png') {
                $img = @imagecreatefrompng($file['tmp_name']);
            } else {
                $img = @imagecreatefromjpeg($file['tmp_name']);
            }
            
            if ($img) {
                // Preserve transparency
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                
                if (imagewebp($img, $webpPath, 75)) { // 75% compression
                    imagedestroy($img);
                    $filename = $webpFilename;
                    $publicPath = '/assets/uploads/media/' . $filename;
                    $fileSize = filesize($webpPath);
                    $ext = 'webp';
                } else {
                    move_uploaded_file($file['tmp_name'], $uploadPath);
                }
            } else {
                move_uploaded_file($file['tmp_name'], $uploadPath);
            }
        } else {
            move_uploaded_file($file['tmp_name'], $uploadPath);
        }

        $stmt = $db->prepare("INSERT INTO media_library (file_name, file_path, file_type, file_size, folder, uploaded_by) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $originalName,
            $publicPath,
            $ext,
            $fileSize,
            $folder,
            Session::get('user_id')
        ]);

        Session::setFlash('success', 'File uploaded and optimized successfully.');
        $this->redirect('/admin/media');
    }

    public function delete() {
        $db = Database::getInstance()->getConnection();
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $db->prepare("SELECT file_path FROM media_library WHERE id=?");
            $stmt->execute([$id]);
            $media = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($media) {
                $filePath = dirname(__DIR__, 2) . $media['file_path'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
                
                $stmt = $db->prepare("DELETE FROM media_library WHERE id=?");
                $stmt->execute([$id]);
                Session::setFlash('success', 'File deleted permanently.');
            }
        }
        $this->redirect('/admin/media');
    }
}
