<?php
/**
 * RBAC Database Clean-up & Normalization Migration Script
 */

require_once __DIR__ . '/app/Config/config.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");

    // 1. Clear and re-populate roles table
    $db->exec("TRUNCATE TABLE roles;");
    $roles = [
        [1, 'Super Admin', 'super_admin', 'Full platform administrative control and configuration rights.'],
        [2, 'Admin', 'admin', 'Manages content, directories, SEO settings, and files.'],
        [3, 'Editor', 'editor', 'Edits and drafts destinations, blogs, events, and marketplace products.'],
        [4, 'Contributor', 'contributor', 'Creates draft posts, reviews, and edits content submission drafts.'],
        [8, 'User', 'user', 'General frontend access to tours, marketplace purchases, bookings, and profile.']
    ];

    $roleInsert = $db->prepare("INSERT INTO roles (id, name, slug, description) VALUES (?, ?, ?, ?)");
    foreach ($roles as $r) {
        $roleInsert->execute($r);
    }
    echo "Roles re-seeded successfully.\n";

    // 2. Clear and re-populate permissions table
    $db->exec("TRUNCATE TABLE permissions;");
    $permissions = [
        [1, 'Manage Settings', 'manage_settings', 'Global', 'Modify site config, database, and system variables.'],
        [2, 'Manage Users', 'manage_users', 'Users', 'Add, edit, remove, and manage user roles.'],
        [3, 'Manage CMS', 'manage_cms', 'Pages', 'Update static pages layout, variables, and HTML.'],
        [4, 'Manage SEO', 'manage_seo', 'SEO', 'Manage canonicals, sitemaps, metatags, and OG records.'],
        [5, 'Manage Media', 'manage_media', 'Media', 'Drag-drop media hub directories, sizing, WebP compressions.'],
        [6, 'Manage Tourism', 'manage_tourism', 'Tourism', 'Coordinate circuits, district fields, attractions CRUD.'],
        [7, 'Manage Events', 'manage_events', 'Events', 'Control ticket settings, organizer, banner configurations.'],
        [8, 'Manage Gallery', 'manage_gallery', 'Gallery', 'Add photographer entries, categories, and lazy-loaders.'],
        [9, 'Manage Blogs', 'manage_blogs', 'Blogs', 'Schedule posts, tags, autosaves, and revisions rollback.'],
        [10, 'Manage Marketplace', 'manage_marketplace', 'Marketplace', 'Track product catalog, stocks, and order dispatch.'],
        [11, 'Manage Directory', 'manage_directory', 'Directory', 'Approve business directories, badges, and feedback.'],
        [12, 'Manage Social', 'manage_social', 'Social', 'Embed YouTube feeds, Instagram Reels, and links.'],
        [13, 'Manage Branding', 'manage_branding', 'Branding', 'Modify official brand colors, logos, and favicons.'],
        [14, 'View Activity Logs', 'view_activity_logs', 'Logs', 'Inspect user device login audits.'],
        [15, 'View Notifications', 'view_notifications', 'Notifications', 'Inspect unread system counts.']
    ];

    $permInsert = $db->prepare("INSERT INTO permissions (id, name, slug, module, description) VALUES (?, ?, ?, ?, ?)");
    foreach ($permissions as $p) {
        $permInsert->execute($p);
    }
    echo "Permissions re-seeded successfully.\n";

    // 3. Clear and map role_permissions table
    $db->exec("TRUNCATE TABLE role_permissions;");
    $rolePermInsert = $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");

    // Super Admin (Role ID 1) gets all permissions (1-15)
    for ($i = 1; $i <= 15; $i++) {
        $rolePermInsert->execute([1, $i]);
    }

    // Admin (Role ID 2) gets CMS, SEO, Media, Tourism, Events, Gallery, Blogs, Marketplace, Directory, Social, Branding, Logs, Notifications
    $adminPerms = [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    foreach ($adminPerms as $ap) {
        $rolePermInsert->execute([2, $ap]);
    }

    // Editor (Role ID 3) gets CMS, Media, Tourism, Events, Gallery, Blogs, Marketplace, Directory, Social
    $editorPerms = [3, 5, 6, 7, 8, 9, 10, 11, 12];
    foreach ($editorPerms as $ep) {
        $rolePermInsert->execute([3, $ep]);
    }

    // Contributor (Role ID 4) gets Blogs & Gallery (draft level permissions)
    $contributorPerms = [5, 8, 9];
    foreach ($contributorPerms as $cp) {
        $rolePermInsert->execute([4, $cp]);
    }

    echo "Role permissions mapped successfully.\n";

    // 4. Clean up and unify existing user roles
    // Rajesh Kumar (Role 5 -> 3 Editor)
    $db->exec("UPDATE users SET role_id = 3 WHERE id = 2;");
    // Priya Singh (Role 2 -> 2 Admin)
    $db->exec("UPDATE users SET role_id = 2 WHERE id = 3;");
    // Amit Handicrafts (Role 4 -> 3 Editor)
    $db->exec("UPDATE users SET role_id = 3 WHERE id = 4;");
    // All user roles (Role 8 -> 8 User)
    $db->exec("UPDATE users SET role_id = 8 WHERE role_id NOT IN (1, 2, 3, 4, 8);");
    
    echo "User role foreign keys cleaned and normalized.\n";

    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    echo "=== RBAC INITIALIZATION COMPLETE ===\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
