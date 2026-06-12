<div class="row g-4">
    <!-- General Settings Form -->
    <div class="col-lg-6">
        <div class="glass-card">
            <h4 class="mb-2 fw-bold text-white"><i class="fa-solid fa-robot text-primary me-2"></i> AI Trip Planner Settings</h4>
            <p class="text-secondary small mb-4">Edit active system prompts, recommendation criteria, seasonal tags, and hidden gem directories.</p>
            
            <form action="<?= BASE_URL ?>/admin/trip-planner/store" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Active System Prompt Template</label>
                    <textarea name="prompt_template" rows="4" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size:0.85rem;" required><?= htmlspecialchars($settings['prompt_template'] ?? '') ?></textarea>
                    <small class="text-secondary">Variables: <code>{duration}</code>, <code>{start_city}</code>, <code>{group_type}</code>, <code>{budget}</code>, <code>{style}</code>, <code>{interests}</code>.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Default Budget Allocation breakdown (JSON)</label>
                    <textarea name="default_budget_breakdown" rows="2" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size:0.85rem;" required><?= htmlspecialchars($settings['default_budget_breakdown'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Seasonal Destination Recommendations (JSON)</label>
                    <textarea name="seasonal_recommendations" rows="3" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size:0.85rem;" required><?= htmlspecialchars($settings['seasonal_recommendations'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Hidden Gems Directory (JSON List)</label>
                    <textarea name="hidden_gems" rows="2" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size:0.85rem;" required><?= htmlspecialchars($settings['hidden_gems'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary px-5 rounded-pill"><i class="fa-solid fa-save me-2"></i> Save AI Parameters</button>
            </form>
        </div>
    </div>

    <!-- Algorithmic Rules CRUD -->
    <div class="col-lg-6">
        <div class="glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-network-wired text-info me-2"></i> Algorithmic Router Rules</h4>
                <button class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#ruleModal" onclick="clearRuleForm()"><i class="fa-solid fa-plus me-1"></i> Add Rule</button>
            </div>
            
            <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-secondary border-bottom border-secondary">
                            <th>Criteria</th>
                            <th>Recommendations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($rules)): ?>
                            <tr><td colspan="3" class="text-center py-4 text-secondary">No algorithmic rules configured.</td></tr>
                        <?php else: foreach($rules as $r): ?>
                            <tr>
                                <td class="py-3">
                                    <div class="fw-bold text-white"><?= htmlspecialchars($r['criteria']) ?></div>
                                    <small class="badge bg-secondary"><?= htmlspecialchars($r['rule_type']) ?></small>
                                </td>
                                <td class="py-3 text-secondary small text-truncate" style="max-width:200px;">
                                    <?= htmlspecialchars($r['recommendations_json']) ?>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-light" onclick="editRule(<?= htmlspecialchars(json_encode($r)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                        <form action="<?= BASE_URL ?>/admin/trip-planner/delete" method="POST" class="d-inline" onsubmit="return confirm('Remove this algorithmic routing rule?');">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Rule Modal -->
<div class="modal fade" id="ruleModal" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content bg-dark border border-secondary text-white" id="ruleForm" action="<?= BASE_URL ?>/admin/trip-planner/update" method="POST">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="modalTitle">Configure Route Rule</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="rule_id">

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Rule Mapping Type</label>
                    <select name="rule_type" id="rule_type" class="form-select bg-dark border-secondary text-white">
                        <option value="interest">User Interest Match</option>
                        <option value="style">Travel Style Match</option>
                        <option value="budget">Budget Level Match</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Criteria Value</label>
                    <input type="text" name="criteria" id="rule_criteria" class="form-control bg-dark border-secondary text-white" placeholder="e.g. heritage, spiritual, low, family" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Recommendations List (JSON Array)</label>
                    <textarea name="recommendations_json" id="rule_json" rows="4" class="form-control bg-dark border-secondary text-white font-monospace" style="font-size:0.85rem;" placeholder='["Mahabodhi Temple", "Nalanda Ruins"]' required></textarea>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Routing Rule</button>
            </div>
        </form>
    </div>
</div>

<script>
    function clearRuleForm() {
        document.getElementById('ruleForm').reset();
        document.getElementById('rule_id').value = '';
        document.getElementById('ruleForm').action = '<?= BASE_URL ?>/admin/trip-planner/update';
        document.getElementById('modalTitle').textContent = 'Configure Route Rule';
    }

    function editRule(data) {
        clearRuleForm();
        document.getElementById('rule_id').value = data.id;
        document.getElementById('rule_type').value = data.rule_type;
        document.getElementById('rule_criteria').value = data.criteria;
        document.getElementById('rule_json').value = data.recommendations_json;

        document.getElementById('ruleForm').action = '<?= BASE_URL ?>/admin/trip-planner/update';
        document.getElementById('modalTitle').textContent = 'Update Route Rule';

        const modal = new bootstrap.Modal(document.getElementById('ruleModal'));
        modal.show();
    }
</script>
