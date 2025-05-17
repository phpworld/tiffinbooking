<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4"><i class="fas fa-users"></i> Manage Users</h1>

<?php if (empty($users)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No users found.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['phone'] ?: 'Not provided' ?></td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('/admin/users/view/' . $user['id']) ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> View
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
