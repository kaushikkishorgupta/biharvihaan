<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class PartnersController extends Controller {

    public function show($params) {
        $slug = $params['slug'] ?? '';
        
        if (empty($slug)) {
            $this->redirect('/');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM partners WHERE slug = ? AND status = 'active'");
        $stmt->execute([$slug]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$partner) {
            $this->redirect('/');
        }

        $galleryStmt = $db->prepare("SELECT * FROM partner_gallery WHERE partner_id = ? ORDER BY created_at DESC");
        $galleryStmt->execute([$partner['id']]);
        $gallery = $galleryStmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('partners/show', [
            'title' => $partner['name'] . ' | Bihar Vihaan Enterprise',
            'partner' => $partner,
            'gallery' => $gallery
        ]);
    }
}
