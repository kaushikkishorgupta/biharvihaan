<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-shield-halved text-primary me-2"></i> Role Permission Matrix (RBAC)</h4>
            <p class="text-secondary small mb-0 font-primary">Configure custom boundary permissions for each team role. Super Admins bypass checks dynamically.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/users" class="btn btn-outline-light rounded-pill px-4"><i class="fa-solid fa-arrow-left me-1"></i> Back to Users</a>
    </div>

    <form action="<?= BASE_URL ?>/admin/roles/update" method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary border-bottom border-secondary">
                        <th>Module / Permission</th>
                        <?php foreach($roles as $r): ?>
                            <th class="text-center font-primary" style="min-width: 110px;"><?= htmlspecialchars($r['name']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $currentModule = '';
                    if(!empty($permissions)): foreach($permissions as $p): 
                        $permId = (int)$p['id'];
                        $module = htmlspecialchars($p['module'] ?: 'Global');
                        
                        // Output module header row
                        if ($module !== $currentModule) {
                            $currentModule = $module;
                            echo '<tr class="bg-dark bg-opacity-40"><td colspan="'.(count($roles) + 1).'" class="fw-bold text-primary text-uppercase py-2" style="font-size:0.75rem; letter-spacing:1px;"><i class="fa-solid fa-cubes me-1"></i> '.$currentModule.' Module</td></tr>';
                        }
                    ?>
                        <tr>
                            <td class="py-3 px-3">
                                <div class="fw-bold text-white"><?= htmlspecialchars($p['name']) ?></div>
                                <small class="text-secondary font-monospace" style="font-size:0.72rem;"><?= htmlspecialchars($p['slug']) ?></small>
                            </td>
                            <?php foreach($roles as $r): 
                                $roleId = (int)$r['id'];
                                $isSuper = ($r['slug'] === 'super_admin');
                                $checked = ($isSuper || (isset($mappings[$roleId]) && in_array($permId, $mappings[$roleId]))) ? 'checked' : '';
                                $disabled = $isSuper ? 'disabled onclick="return false;"' : '';
                            ?>
                                <td class="text-center py-3">
                                    <input type="checkbox" 
                                           name="perms[<?= $roleId ?>][]" 
                                           value="<?= $permId ?>" 
                                           class="form-check-input cursor-pointer" 
                                           <?= $checked ?> 
                                           <?= $disabled ?>>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 border-top border-secondary pt-3">
            <button type="submit" class="btn btn-primary px-5 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save Permission Matrix</button>
        </div>
    </form>
</div>
