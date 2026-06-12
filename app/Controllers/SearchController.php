<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class SearchController extends Controller {

    public function ajax() {
        $query = $_GET['q'] ?? '';
        if (strlen($query) < 2) {
            echo json_encode([]);
            return;
        }

        $db = Database::getInstance()->getConnection();
        $results = [];

        // Search Destinations
        $stmt = $db->prepare("SELECT id, name, 'Destination' as type, '/tourism/' as url_prefix FROM destinations WHERE name LIKE ? OR location LIKE ? LIMIT 3");
        $stmt->execute(["%$query%", "%$query%"]);
        $destinations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($destinations as $d) {
            $results[] = [
                'name' => $d['name'],
                'type' => $d['type'],
                'url' => BASE_URL . $d['url_prefix'] . $d['id']
            ];
        }

        // Search Businesses
        $stmtBiz = $db->prepare("SELECT id, name, category as type, '/business/' as url_prefix FROM businesses WHERE name LIKE ? OR address LIKE ? LIMIT 3");
        $stmtBiz->execute(["%$query%", "%$query%"]);
        $businesses = $stmtBiz->fetchAll(PDO::FETCH_ASSOC);
        foreach($businesses as $b) {
            $results[] = [
                'name' => $b['name'],
                'type' => $b['type'],
                'url' => BASE_URL . $b['url_prefix'] . $b['id']
            ];
        }

        // Search Products
        $stmtProd = $db->prepare("SELECT id, name, 'Product' as type, '/shop/quick-view?id=' as url_prefix FROM products WHERE name LIKE ? OR category LIKE ? LIMIT 3");
        $stmtProd->execute(["%$query%", "%$query%"]);
        $products = $stmtProd->fetchAll(PDO::FETCH_ASSOC);
        foreach($products as $p) {
            $results[] = [
                'name' => $p['name'],
                'type' => $p['type'],
                'url' => BASE_URL . $p['url_prefix'] . $p['id']
            ];
        }

        echo json_encode($results);
    }
}
