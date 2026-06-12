<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

try {
    $db = App\Core\Database::getInstance()->getConnection();
    $sql = "
        CREATE TABLE IF NOT EXISTS cms_pages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(100) UNIQUE NOT NULL,
            title VARCHAR(255) NOT NULL,
            content LONGTEXT,
            meta_data JSON,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS menus (
            id INT AUTO_INCREMENT PRIMARY KEY,
            position VARCHAR(50) NOT NULL,
            label VARCHAR(100) NOT NULL,
            url VARCHAR(255) NOT NULL,
            sort_order INT DEFAULT 0,
            status ENUM('active', 'hidden') DEFAULT 'active'
        );

        CREATE TABLE IF NOT EXISTS seo_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            route VARCHAR(100) UNIQUE NOT NULL,
            meta_title VARCHAR(255),
            meta_description TEXT,
            open_graph_image VARCHAR(255)
        );

        CREATE TABLE IF NOT EXISTS gallery_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            category VARCHAR(100),
            location VARCHAR(100),
            image_url VARCHAR(255) NOT NULL,
            photographer VARCHAR(100),
            status ENUM('published', 'pending', 'hidden') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS destinations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            category VARCHAR(100),
            location VARCHAR(100),
            image_url VARCHAR(255),
            rating DECIMAL(3,2) DEFAULT 0,
            reviews_count INT DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS businesses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(100),
            description TEXT,
            address TEXT,
            phone VARCHAR(50),
            email VARCHAR(100),
            website VARCHAR(255),
            image_url VARCHAR(255),
            rating DECIMAL(3,2) DEFAULT 0,
            is_verified TINYINT(1) DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) NOT NULL,
            token VARCHAR(255) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY email_idx (email),
            KEY token_idx (token)
        );
    ";
    
    $db->exec($sql);
    echo 'SCHEMA_CREATED_SUCCESSFULLY';
    
    // Seed Homepage CMS entry if not exists
    $stmt = $db->query("SELECT id FROM cms_pages WHERE slug = 'home'");
    if (!$stmt->fetch()) {
        $db->exec("INSERT INTO cms_pages (slug, title, content, meta_data) VALUES ('home', 'Bihar Vihaan Homepage', '{\"hero_title\": \"Discover Authentic Bihar Crafts\"}', '{}')");
    }

} catch (Exception $e) {
    echo 'DB_ERROR: ' . $e->getMessage();
}
