<div class="glass-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold text-white"><i class="fa-solid fa-calendar-days text-primary me-2"></i> Events & Festivals Manager</h4>
            <p class="text-secondary small mb-0">Publish cultural festivals, events schedules, ticket registrations, and banners.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addEventModal"><i class="fa-solid fa-plus me-1"></i> Add Event</button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-secondary border-bottom border-secondary">
                    <th>Banner</th>
                    <th>Title & Category</th>
                    <th>Schedule</th>
                    <th>Location</th>
                    <th>Tickets & Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($events)): ?>
                    <tr><td colspan="7" class="text-center py-4 text-secondary">No events scheduled.</td></tr>
                <?php else: foreach($events as $event): ?>
                    <tr>
                        <td class="py-3">
                            <img src="<?= !empty($event['image']) ? (strpos($event['image'], 'http') === 0 ? htmlspecialchars($event['image']) : BASE_URL . $event['image']) : 'https://images.unsplash.com/photo-1574513693246-17b2b73bc102?w=150' ?>" width="60" height="50" class="rounded object-fit-cover shadow-sm border border-secondary">
                        </td>
                        <td class="py-3">
                            <div class="fw-bold text-white"><?= htmlspecialchars($event['title']) ?></div>
                            <span class="badge bg-primary bg-opacity-10 text-primary mt-1"><?= htmlspecialchars($event['category'] ?? 'Festival') ?></span>
                        </td>
                        <td class="py-3 text-secondary small">
                            <div><i class="fa-solid fa-calendar text-info me-1"></i> <?= date('M d, Y', strtotime($event['start_date'])) ?></div>
                            <div>to <?= date('M d, Y', strtotime($event['end_date'])) ?></div>
                        </td>
                        <td class="py-3 text-secondary small">
                            <i class="fa-solid fa-location-dot text-danger me-1"></i> <?= htmlspecialchars($event['location']) ?>
                        </td>
                        <td class="py-3 text-light small">
                            <div>Price: <strong class="text-success"><?= $event['price'] > 0 ? '₹' . number_format($event['price'], 2) : 'Free' ?></strong></div>
                            <div>Avail: <strong><?= $event['available_tickets'] ?? $event['max_tickets'] ?? 'Unlimited' ?></strong></div>
                        </td>
                        <td class="py-3">
                            <?php if(($event['status'] ?? 'active') === 'active'): ?>
                                <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light" onclick="editEvent(<?= htmlspecialchars(json_encode($event)) ?>)"><i class="fa-solid fa-edit"></i></button>
                                <form action="<?= BASE_URL ?>/admin/events/delete" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?');">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="id" value="<?= $event['id'] ?>">
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

<!-- Modal Add Event -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" action="<?= BASE_URL ?>/admin/events/store" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Create Event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Title</label>
                        <input type="text" name="title" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sonepur Cattle Fair" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <input type="text" name="category" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Festivals, Arts & Culture" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control bg-dark border-secondary text-white" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location (City)</label>
                        <input type="text" name="location" class="form-control bg-dark border-secondary text-white" placeholder="e.g. Sonepur, Saran" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Banner Image (Upload)</label>
                        <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Price (INR)</label>
                        <input type="number" name="price" step="0.01" class="form-control bg-dark border-secondary text-white" placeholder="0.00 for Free">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Max Tickets</label>
                        <input type="number" name="max_tickets" class="form-control bg-dark border-secondary text-white" placeholder="e.g. 500">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Status</label>
                        <select name="status" class="form-select bg-dark border-secondary text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Description</label>
                        <textarea name="description" rows="4" class="form-control bg-dark border-secondary text-white" placeholder="Write details about the festival/event..." required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Event</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Event -->
<div class="modal fade" id="editEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content bg-dark border border-secondary text-white" id="editEventForm" action="<?= BASE_URL ?>/admin/events/update" method="POST" enctype="multipart/form-data">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold">Edit Event</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="edit_event_id">
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Title</label>
                        <input type="text" name="title" id="edit_event_title" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Category</label>
                        <input type="text" name="category" id="edit_event_category" class="form-control bg-dark border-secondary text-white" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Start Date</label>
                        <input type="date" name="start_date" id="edit_event_start" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">End Date</label>
                        <input type="date" name="end_date" id="edit_event_end" class="form-control bg-dark border-secondary text-white" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Location (City)</label>
                        <input type="text" name="location" id="edit_event_location" class="form-control bg-dark border-secondary text-white" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Banner Image (Upload to change)</label>
                        <input type="file" name="image" class="form-control bg-dark border-secondary text-white" accept="image/*">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Price (INR)</label>
                        <input type="number" name="price" id="edit_event_price" step="0.01" class="form-control bg-dark border-secondary text-white">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Max Tickets</label>
                        <input type="number" name="max_tickets" id="edit_event_max_tickets" class="form-control bg-dark border-secondary text-white">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-secondary small fw-bold">Status</label>
                        <select name="status" id="edit_event_status" class="form-select bg-dark border-secondary text-white">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-secondary small fw-bold">Description</label>
                        <textarea name="description" id="edit_event_desc" rows="4" class="form-control bg-dark border-secondary text-white" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editEvent(data) {
        document.getElementById('edit_event_id').value = data.id;
        document.getElementById('edit_event_title').value = data.title;
        document.getElementById('edit_event_category').value = data.category;
        document.getElementById('edit_event_start').value = data.start_date;
        document.getElementById('edit_event_end').value = data.end_date;
        document.getElementById('edit_event_location').value = data.location;
        document.getElementById('edit_event_price').value = data.price;
        document.getElementById('edit_event_max_tickets').value = data.max_tickets;
        document.getElementById('edit_event_status').value = data.status || 'active';
        document.getElementById('edit_event_desc').value = data.description;

        const modal = new bootstrap.Modal(document.getElementById('editEventModal'));
        modal.show();
    }
</script>
