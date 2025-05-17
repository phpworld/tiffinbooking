<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-clock"></i> Delivery Slots</h1>
    <a href="<?= base_url('/admin/delivery-slots/create') ?>" class="btn btn-success">
        <i class="fas fa-plus-circle"></i> Add New Slot
    </a>
</div>

<?php if (empty($slots)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No delivery slots found. Add your first slot to get started.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slot Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($slots as $slot): ?>
                            <tr>
                                <td><?= $slot['id'] ?></td>
                                <td><?= $slot['slot_time'] ?></td>
                                <td>
                                    <?php if ($slot['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin/delivery-slots/edit/' . $slot['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/admin/delivery-slots/delete/' . $slot['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slot?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
