<?php
$controllers = [
    'AdminDirectoryController' => ['table' => 'businesses', 'view' => 'directory', 'title' => 'Directory Manager'],
    'AdminMarketplaceController' => ['table' => 'products', 'view' => 'marketplace', 'title' => 'Marketplace Manager'],
    'AdminGalleryController' => ['table' => 'gallery_images', 'view' => 'gallery', 'title' => 'Gallery Manager'],
    'AdminMediaController' => ['table' => null, 'view' => 'media', 'title' => 'Media Manager'],
    'AdminSettingsController' => ['table' => 'seo_settings', 'view' => 'settings', 'title' => 'Settings & SEO Manager']
];

foreach ($controllers as $class => $config) {
    // Generate Controller
    $cCode = "<?php\n\nnamespace App\\Controllers;\n\nuse App\\Core\\Controller;\nuse App\\Core\\Database;\nuse PDO;\n\nclass {$class} extends Controller {\n\n    public function index() {\n";
    if ($config['table']) {
        $cCode .= "        \$db = Database::getInstance()->getConnection();\n        \$stmt = \$db->query('SELECT * FROM {$config['table']} ORDER BY id DESC');\n        \$data = \$stmt->fetchAll(PDO::FETCH_ASSOC);\n\n        \$this->renderAdmin('admin/{$config['view']}', ['items' => \$data]);\n";
    } else {
        $cCode .= "        \$this->renderAdmin('admin/{$config['view']}');\n";
    }
    $cCode .= "    }\n}\n";
    
    file_put_contents(__DIR__ . '/app/Controllers/' . $class . '.php', $cCode);

    // Generate View
    $vCode = '<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold">' . $config['title'] . '</h4>
            <p class="text-muted mb-0 small">Manage your ' . strtolower($config['title']) . ' content.</p>
        </div>
        <button class="btn btn-primary" onclick="alert(\'Create modal to be implemented.\')"><i class="fa-solid fa-plus"></i> Add New</button>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover datatable align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name/Title</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($items)): ?>
                        <tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>
                    <?php endif; ?>
                    <?php if(isset($items)) { foreach($items as $d): ?>
                    <tr>
                        <td><?= $d[\'id\'] ?? \'\' ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($d[\'title\'] ?? $d[\'name\'] ?? $d[\'route\'] ?? \'N/A\') ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($d[\'status\'] ?? \'Active\') ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>';

    file_put_contents(__DIR__ . '/app/Views/admin/' . $config['view'] . '.php', $vCode);
}
echo 'SCAFFOLDING_SUCCESSFUL';
