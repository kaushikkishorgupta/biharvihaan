<?php
require_once __DIR__ . '/app/Config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // Create Table
    $schema = "CREATE TABLE IF NOT EXISTS gallery_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        description TEXT,
        location VARCHAR(255),
        category VARCHAR(100),
        image VARCHAR(255) NOT NULL,
        photographer VARCHAR(255),
        views INT DEFAULT 0,
        likes INT DEFAULT 0,
        status ENUM('active', 'pending', 'rejected') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    $db->execute($schema);
    echo "Table gallery_images created.\n";

    // Seed Data
    $seedData = [
        ['Mahabodhi Temple', 'mahabodhi-temple', 'The holiest Buddhist site in the world.', 'Bodh Gaya', 'Buddhist Circuit', 'https://images.unsplash.com/photo-1621217734181-4203774f17df?w=800', 'Admin'],
        ['Nalanda Ruins', 'nalanda-ruins', 'Ancient ruins of one of the oldest universities in the world.', 'Nalanda', 'Heritage Sites', 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?w=800', 'Admin'],
        ['Rajgir Hills', 'rajgir-hills', 'Scenic hills known for the Vishwa Shanti Stupa and glass bridge.', 'Rajgir', 'Nature & Eco Tourism', 'https://images.unsplash.com/photo-1625754593922-b5f63d76e40b?w=800', 'Admin'],
        ['Vaishali Stupa', 'vaishali-stupa', 'Ancient stupa housing the sacred relics of Lord Buddha.', 'Vaishali', 'Buddhist Circuit', 'https://images.unsplash.com/photo-1598506822452-f4185799aee9?w=800', 'Admin'],
        ['Madhubani Art', 'madhubani-art', 'Traditional Mithila painting showcasing intricate patterns.', 'Madhubani', 'Arts & Crafts', 'https://images.unsplash.com/photo-1614050800720-3b02ddba753a?w=800', 'Admin'],
        ['Chhath Festival', 'chhath-festival', 'Millions gathering to worship the Sun God at the sacred ghats.', 'Patna', 'Festivals', 'https://images.unsplash.com/photo-1574513693246-17b2b73bc102?w=800', 'Admin'],
        ['Patna Sahib', 'patna-sahib', 'Takht Sri Patna Sahib, the birthplace of Guru Gobind Singh Ji.', 'Patna', 'Temples', 'https://images.unsplash.com/photo-1591505322409-eb5b796eb009?w=800', 'Admin'],
        ['Valmiki Tiger Reserve', 'valmiki-tiger-reserve', 'The only national park in Bihar with diverse wildlife.', 'West Champaran', 'Nature & Eco Tourism', 'https://images.unsplash.com/photo-1549471013-3364d7220b75?w=800', 'Admin']
    ];

    // Check if table is empty
    $count = $db->queryRow("SELECT COUNT(*) as c FROM gallery_images")['c'];
    
    if ($count == 0) {
        $insertSql = "INSERT INTO gallery_images (title, slug, description, location, category, image, photographer) VALUES (?, ?, ?, ?, ?, ?, ?)";
        foreach ($seedData as $data) {
            $db->execute($insertSql, $data);
        }
        echo "Seed data inserted successfully.\n";
    } else {
        echo "Data already exists. Seed skipped.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
