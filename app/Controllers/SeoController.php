<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;

class SeoController extends Controller {

    public function sitemap() {
        header("Content-Type: text/xml; charset=utf-8");
        $baseUrl = BASE_URL;

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static routes
        $staticRoutes = [
            '/', '/tourism', '/directory', '/gallery', '/shop', '/contact', '/about'
        ];

        foreach ($staticRoutes as $route) {
            echo "<url>";
            echo "<loc>" . $baseUrl . $route . "</loc>";
            echo "<changefreq>daily</changefreq>";
            echo "<priority>0.9</priority>";
            echo "</url>";
        }

        $db = Database::getInstance()->getConnection();

        // Dynamic Destinations
        $destinations = $db->query("SELECT id, created_at FROM destinations WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($destinations as $d) {
            echo "<url>";
            echo "<loc>" . $baseUrl . "/tourism/" . $d['id'] . "</loc>";
            echo "<lastmod>" . date('Y-m-d', strtotime($d['created_at'] ?? 'now')) . "</lastmod>";
            echo "<changefreq>weekly</changefreq>";
            echo "<priority>0.8</priority>";
            echo "</url>";
        }

        // Dynamic Businesses
        $businesses = $db->query("SELECT id, created_at FROM businesses WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($businesses as $b) {
            echo "<url>";
            echo "<loc>" . $baseUrl . "/business/" . $b['id'] . "</loc>";
            echo "<lastmod>" . date('Y-m-d', strtotime($b['created_at'] ?? 'now')) . "</lastmod>";
            echo "<changefreq>monthly</changefreq>";
            echo "<priority>0.7</priority>";
            echo "</url>";
        }

        // Dynamic Products
        $products = $db->query("SELECT id, created_at FROM products WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $p) {
            echo "<url>";
            echo "<loc>" . $baseUrl . "/shop/quick-view?id=" . $p['id'] . "</loc>";
            echo "<lastmod>" . date('Y-m-d', strtotime($p['created_at'] ?? 'now')) . "</lastmod>";
            echo "<changefreq>weekly</changefreq>";
            echo "<priority>0.8</priority>";
            echo "</url>";
        }

        echo '</urlset>';
    }

    public function robots() {
        header("Content-Type: text/plain");
        $baseUrl = BASE_URL;
        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /user/\n";
        echo "Disallow: /checkout/\n";
        echo "Allow: /\n\n";
        echo "Sitemap: " . $baseUrl . "/sitemap.xml\n";
    }
}
