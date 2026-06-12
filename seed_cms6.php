<?php
require 'app/Config/config.php';
require 'app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance()->getConnection();

// Modify Roles Table to add slug if missing
try {
    $db->exec("ALTER TABLE roles ADD COLUMN slug VARCHAR(100) UNIQUE AFTER name");
} catch (PDOException $e) {
    // Column might already exist
}

// Create Permissions Table
try {
    $db->exec("ALTER TABLE permissions ADD COLUMN slug VARCHAR(100) UNIQUE AFTER name");
    $db->exec("ALTER TABLE permissions ADD COLUMN module VARCHAR(50) AFTER slug");
} catch (PDOException $e) {
    // Columns might already exist
}

// Create Role_Permissions Table
$db->exec("
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id INT NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Create Activities Table for Audit Logs
$db->exec("
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    module VARCHAR(50),
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Create Notifications Table
$db->exec("
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Create Page Versions Table
$db->exec("
CREATE TABLE IF NOT EXISTS page_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT NOT NULL,
    content_data JSON NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (page_id) REFERENCES cms_pages(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

// Seed Roles
$roles = [
    ['Super Admin', 'super_admin'],
    ['Admin', 'admin'],
    ['Editor', 'editor'],
    ['Contributor', 'contributor'],
    ['Moderator', 'moderator'],
    ['Viewer', 'viewer']
];

$stmt = $db->prepare("INSERT INTO roles (name, slug) VALUES (?, ?) ON DUPLICATE KEY UPDATE slug = VALUES(slug)");
foreach ($roles as $role) {
    $stmt->execute($role);
}

// Seed Basic Permissions
$permissions = [
    ['Manage Settings', 'manage_settings', 'Global'],
    ['Manage Users', 'manage_users', 'Users'],
    ['Create Page', 'create_page', 'Pages'],
    ['Edit Page', 'edit_page', 'Pages'],
    ['Delete Page', 'delete_page', 'Pages'],
    ['Publish Page', 'publish_page', 'Pages'],
    ['Manage Tourism', 'manage_tourism', 'Tourism'],
    ['Manage Gallery', 'manage_gallery', 'Gallery'],
    ['Manage Blogs', 'manage_blogs', 'Blogs'],
    ['Manage Events', 'manage_events', 'Events']
];

$stmt = $db->prepare("INSERT INTO permissions (name, slug, module) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE slug = VALUES(slug), module = VALUES(module)");
foreach ($permissions as $perm) {
    $stmt->execute($perm);
}

echo "CMS 6.0 Database tables created and seeded successfully.\n";
