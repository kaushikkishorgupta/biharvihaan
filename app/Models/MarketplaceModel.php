<?php

namespace App\Models;

use App\Core\Database;

class MarketplaceModel {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getFilteredProducts($category = 'All', $sort = 'newest', $limit = 12, $offset = 0) {
        $params = [];
        $where = "WHERE p.status = 'active'";
        
        if ($category && $category !== 'All') {
            $where .= " AND p.category = ?";
            $params[] = $category;
        }

        $orderBy = "ORDER BY p.created_at DESC";
        switch ($sort) {
            case 'price_low':
                $orderBy = "ORDER BY p.price ASC";
                break;
            case 'price_high':
                $orderBy = "ORDER BY p.price DESC";
                break;
            case 'popular':
                $orderBy = "ORDER BY p.rating DESC, p.reviews_count DESC";
                break;
        }

        $sql = "SELECT p.*, a.name as artisan_name 
                FROM products p 
                LEFT JOIN artisans a ON p.artisan_id = a.id 
                $where 
                $orderBy 
                LIMIT ? OFFSET ?";
                
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->query($sql, $params);
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, a.name as artisan_name, a.bio as artisan_bio, a.experience_years as artisan_exp, a.image_url as artisan_image
                FROM products p 
                LEFT JOIN artisans a ON p.artisan_id = a.id 
                WHERE p.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function getFeaturedArtisans() {
        $sql = "SELECT * FROM artisans WHERE is_verified = 1 ORDER BY id DESC LIMIT 4";
        return $this->db->query($sql);
    }

    public function getBestSellers() {
        $sql = "SELECT p.*, a.name as artisan_name 
                FROM products p 
                LEFT JOIN artisans a ON p.artisan_id = a.id 
                WHERE p.status = 'active' AND p.is_bestseller = 1 
                ORDER BY p.rating DESC LIMIT 6";
        return $this->db->query($sql);
    }

    public function getNewArrivals() {
        $sql = "SELECT p.*, a.name as artisan_name 
                FROM products p 
                LEFT JOIN artisans a ON p.artisan_id = a.id 
                WHERE p.status = 'active' 
                ORDER BY p.created_at DESC LIMIT 6";
        return $this->db->query($sql);
    }

    public function createOrder($userId, $totalAmount, $items) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
            $this->db->execute($sql, [$userId, $totalAmount]);
            $orderId = $this->db->lastInsertId();

            $itemSql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            foreach ($items as $item) {
                $this->db->execute($itemSql, [$orderId, $item['product_id'], $item['quantity'], $item['price']]);
                
                // Decrement stock
                $this->db->execute("UPDATE products SET stock = stock - ? WHERE id = ?", [$item['quantity'], $item['product_id']]);
            }
            
            $this->db->getConnection()->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->getConnection()->rollBack();
            return false;
        }
    }
}
