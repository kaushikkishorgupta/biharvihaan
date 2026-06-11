<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GalleryModel;

class GalleryController extends Controller {

    public function index() {
        $galleryModel = new GalleryModel();
        
        $categories = [
            'All',
            'Heritage Sites',
            'Buddhist Circuit',
            'Temples',
            'Festivals',
            'Food & Cuisine',
            'Arts & Crafts',
            'Nature & Eco Tourism',
            'Villages & Rural Life'
        ];

        $initialImages = $galleryModel->getImages('All', 12, 0);

        $this->render('gallery', [
            'title' => 'Explore Bihar Through Images - Gallery',
            'categories' => $categories,
            'initialImages' => $initialImages
        ]);
    }

    public function loadMore() {
        header('Content-Type: application/json');
        
        $category = $_GET['category'] ?? 'All';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $galleryModel = new GalleryModel();
        $images = $galleryModel->getImages($category, $limit, $offset);

        echo json_encode(['success' => true, 'images' => $images]);
        exit;
    }
}
