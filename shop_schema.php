<?php
require_once __DIR__ . '/app/Config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // 1. Artisans Table
    $schemaArtisans = "CREATE TABLE IF NOT EXISTS artisans (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        bio TEXT,
        experience_years INT DEFAULT 0,
        specialization VARCHAR(255),
        awards TEXT,
        image_url VARCHAR(255),
        is_verified BOOLEAN DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $db->execute($schemaArtisans);
    
    // 2. Drop existing products and recreate to match luxury schema (Safe for Dev)
    $db->execute("SET FOREIGN_KEY_CHECKS = 0");
    $db->execute("DROP TABLE IF EXISTS products");
    $db->execute("SET FOREIGN_KEY_CHECKS = 1");
    
    $schemaProducts = "CREATE TABLE products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        artisan_id INT NULL,
        category VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL,
        description TEXT,
        materials VARCHAR(255),
        price DECIMAL(10,2) NOT NULL,
        stock INT DEFAULT 10,
        gst_rate DECIMAL(5,2) DEFAULT 18.00,
        rating DECIMAL(3,2) DEFAULT 5.00,
        reviews_count INT DEFAULT 0,
        is_handmade BOOLEAN DEFAULT 1,
        is_bestseller BOOLEAN DEFAULT 0,
        location VARCHAR(100),
        image_url VARCHAR(255),
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (artisan_id) REFERENCES artisans(id) ON DELETE SET NULL
    )";
    $db->execute($schemaProducts);
    
    echo "Tables artisans and products created/updated.\n";

    // 3. Seed Artisans
    $artisans = [
        ['Ramesh Paswan', 'Master craftsman of Madhubani art.', 20, 'Madhubani Painting', 'State Award 2018', 'https://images.unsplash.com/photo-1544717302-de2939b7ef71?w=400'],
        ['Sunita Devi', 'Expert in Sujani embroidery and rural motifs.', 15, 'Sujani Embroidery', 'National Award 2021', 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=400'],
        ['Rajesh Kumar', 'Traditional Sikki grass weaver.', 10, 'Sikki Craft', '', 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400']
    ];
    
    $insertArtisan = "INSERT INTO artisans (name, bio, experience_years, specialization, awards, image_url) VALUES (?, ?, ?, ?, ?, ?)";
    foreach ($artisans as $a) {
        $db->execute($insertArtisan, $a);
    }
    
    // 4. Seed Products
    $products = [
        [1, 'Madhubani Paintings', 'Madhubani Peacock Painting', 'madhubani-peacock', 'Authentic Madhubani peacock art painted on handmade paper.', 'Handmade paper, natural dyes', 4500.00, 15, 1, 1, 'Madhubani', 'https://images.unsplash.com/photo-1582201943021-e8e5b66d43cc?w=800'],
        [2, 'Traditional Decor', 'Handmade Sujani Quilt', 'sujani-quilt', 'Beautifully embroidered traditional Sujani quilt.', 'Cotton, silk threads', 8500.00, 5, 1, 0, 'Muzaffarpur', 'https://images.unsplash.com/photo-1620706857370-e1b9770e8bb1?w=800'],
        [3, 'Handicrafts', 'Sikki Grass Basket', 'sikki-basket', 'Eco-friendly handwoven basket made from golden Sikki grass.', 'Sikki grass', 1200.00, 20, 1, 1, 'Darbhanga', 'https://images.unsplash.com/photo-1605297926189-913a863771fb?w=800'],
        [1, 'Cultural Souvenirs', 'Bodh Gaya Buddha Sculpture', 'buddha-sculpture', 'Intricately carved stone sculpture of Lord Buddha.', 'Sandstone', 3500.00, 10, 1, 1, 'Bodh Gaya', 'https://images.unsplash.com/photo-1598506822452-f4185799aee9?w=800'],
        [1, 'Books & Heritage Collections', 'Nalanda Heritage Book Set', 'nalanda-books', 'A comprehensive collection of books on Nalanda history.', 'Paper', 2500.00, 50, 0, 0, 'Nalanda', 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800'],
        [2, 'Festival Collections', 'Chhath Festival Gift Box', 'chhath-gift-box', 'A premium gift box containing traditional Chhath Puja offerings.', 'Bamboo, wheat, jaggery', 1500.00, 100, 1, 1, 'Patna', 'https://images.unsplash.com/photo-1606788075765-d51452f12c19?w=800'],
        [3, 'Traditional Decor', 'Terracotta Village Art', 'terracotta-art', 'Decorative terracotta horse figure for home decor.', 'Baked Clay', 900.00, 25, 1, 0, 'Patna', 'https://images.unsplash.com/photo-1614050800720-3b02ddba753a?w=800']
    ];
    
    $insertProduct = "INSERT INTO products (artisan_id, category, name, slug, description, materials, price, stock, is_handmade, is_bestseller, location, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    foreach ($products as $p) {
        $db->execute($insertProduct, $p);
    }
    
    echo "Seed data inserted successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
