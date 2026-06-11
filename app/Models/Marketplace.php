<?php

namespace App\Models;

use App\Core\Database;

class Marketplace {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getProducts($category = null) {
        if ($category) {
            $sql = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.status = 'active' AND p.category = ?";
            return $this->db->query($sql, [$category]);
        }
        $sql = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.status = 'active'";
        return $this->db->query($sql);
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, u.name as seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?";
        return $this->db->queryRow($sql, [$id]);
    }

    public function createOrder($userId, $totalAmount, $items) {
        try {
            $this->db->getConnection()->beginTransaction();
            
            $sql = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
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
