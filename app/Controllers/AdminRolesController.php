<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Session;
use PDO;

class AdminRolesController extends Controller {

    public function __construct() {
        parent::__construct();
        Auth::requirePermission('manage_settings');
    }

    public function index() {
        $db = Database::getInstance()->getConnection();
        
        $roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        $permissions = $db->query("SELECT * FROM permissions ORDER BY module, slug ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch existing mappings
        $stmt = $db->query("SELECT role_id, permission_id FROM role_permissions");
        $mappingsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $mappings = [];
        foreach ($mappingsRaw as $m) {
            $mappings[$m['role_id']][] = $m['permission_id'];
        }

        $this->renderAdmin('admin/roles', [
            'title' => 'Configure RBAC Permissions | Bihar Vihaan',
            'roles' => $roles,
            'permissions' => $permissions,
            'mappings' => $mappings
        ]);
    }

    public function update() {
        $db = Database::getInstance()->getConnection();
        $perms = $_POST['perms'] ?? []; // Array mapping [role_id][permission_id]

        $db->beginTransaction();
        try {
            // Retrieve all role ids (excluding super_admin to protect lockouts)
            $stmt = $db->query("SELECT id, slug FROM roles");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($roles as $r) {
                // Skip modifying super_admin permissions (always has all)
                if ($r['slug'] === 'super_admin') {
                    continue;
                }
                
                // Delete existing mappings for this role
                $del = $db->prepare("DELETE FROM role_permissions WHERE role_id = ?");
                $del->execute([$r['id']]);

                // Insert new checked mappings
                if (isset($perms[$r['id']])) {
                    $ins = $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
                    foreach ($perms[$r['id']] as $permId) {
                        $ins->execute([$r['id'], (int)$permId]);
                    }
                }
            }
            $db->commit();
            Session::setFlash('success', 'RBAC permission matrix updated.');
        } catch (\Exception $e) {
            $db->rollBack();
            Session::setFlash('error', 'Failed to update matrix: ' . $e->getMessage());
        }

        $this->redirect('/admin/roles');
    }
}
