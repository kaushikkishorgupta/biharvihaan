<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-receipt text-primary me-2"></i> System Activity Logs</h4>
            <p class="text-secondary small mb-0 font-primary">Real-time security audit trails tracking administrative operations, logins, and uploads.</p>
        </div>
        <button class="btn btn-outline-light rounded-pill px-3 btn-sm" onclick="window.location.reload()"><i class="fa-solid fa-sync me-1"></i> Refresh Logs</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary border-bottom border-secondary">
                    <th>Operator</th>
                    <th>Action</th>
                    <th>IP Address</th>
                    <th>Browser / OS</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($logs)): ?>
                    <tr><td colspan="5" class="text-center py-4 text-secondary">No audit logs found in system database.</td></tr>
                <?php else: foreach($logs as $log): 
                    $agent = htmlspecialchars($log['user_agent'] ?? '');
                    
                    // Simple friendly user agent parser
                    $os = 'Unknown OS';
                    if (strpos($agent, 'Windows') !== false) $os = 'Windows';
                    elseif (strpos($agent, 'Macintosh') !== false) $os = 'Mac OS';
                    elseif (strpos($agent, 'Linux') !== false) $os = 'Linux';
                    elseif (strpos($agent, 'iPhone') !== false) $os = 'iOS';
                    elseif (strpos($agent, 'Android') !== false) $os = 'Android';

                    $browser = 'Browser';
                    if (strpos($agent, 'Chrome') !== false) $browser = 'Chrome';
                    elseif (strpos($agent, 'Safari') !== false) $browser = 'Safari';
                    elseif (strpos($agent, 'Firefox') !== false) $browser = 'Firefox';
                    elseif (strpos($agent, 'Edge') !== false) $browser = 'Edge';
                ?>
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3" style="width:36px; height:36px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fa-solid fa-user-shield text-primary"></i>
                                </div>
                                <span class="text-white fw-bold"><?= htmlspecialchars($log['user_name'] ?? 'Guest / Visitor') ?></span>
                            </div>
                        </td>
                        <td class="py-3 text-light">
                            <strong><?= htmlspecialchars($log['action']) ?></strong>
                            <?php if(!empty($log['details'])): ?>
                                <div class="small text-secondary mt-1"><?= htmlspecialchars($log['details']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 text-secondary font-monospace" style="font-size:0.85rem;"><?= htmlspecialchars($log['ip_address'] ?? '127.0.0.1') ?></td>
                        <td class="py-3 text-secondary small">
                            <i class="fa-solid fa-laptop-code me-1 text-info"></i> <?= $browser ?> on <?= $os ?>
                        </td>
                        <td class="py-3 text-secondary small"><?= date('M d, Y, h:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
