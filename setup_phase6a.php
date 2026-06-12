<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

try {
    $db = App\Core\Database::getInstance()->getConnection();

    // 1. Create Wishlists Table
    $db->exec("CREATE TABLE IF NOT EXISTS wishlists (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY user_product (user_id, product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 2. Add shipping_status, razorpay_signature if missing from orders
    $db->exec("ALTER TABLE orders 
        ADD COLUMN IF NOT EXISTS razorpay_signature VARCHAR(255) NULL AFTER razorpay_payment_id,
        ADD COLUMN IF NOT EXISTS tracking_number VARCHAR(100) NULL AFTER delivery_status,
        ADD COLUMN IF NOT EXISTS refund_notes TEXT NULL AFTER tracking_number;
    ");

    // 3. Make sure carts table handles guests (session_id)
    $db->exec("ALTER TABLE carts 
        ADD COLUMN IF NOT EXISTS session_id VARCHAR(255) NULL AFTER user_id,
        MODIFY COLUMN user_id INT NULL;
    ");
    
    // 4. Products table fix if missing price/stock
    $db->exec("ALTER TABLE products
        ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER description,
        ADD COLUMN IF NOT EXISTS stock INT DEFAULT 10 AFTER price;
    ");

    echo "PHASE6A_SETUP_SUCCESSFUL\n";

} catch (PDOException $e) {
    echo "SETUP_FAILED: " . $e->getMessage() . "\n";
}
