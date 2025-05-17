<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-utensils"></i> Manage Dishes</h1>
    <a href="<?= base_url('/admin/dishes/create') ?>" class="btn btn-success">
        <i class="fas fa-plus-circle"></i> Add New Dish
    </a>
</div>

<?php if (empty($dishes)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No dishes found. Add your first dish to get started.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dishes as $dish): ?>
                            <tr>
                                <td><?= $dish['id'] ?></td>
                                <td>
                                    <?php if ($dish['image']): ?>
                                        <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" alt="<?= $dish['name'] ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light text-center" style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?= $dish['name'] ?></td>
                                <td>₹<?= number_format($dish['price'], 2) ?></td>
                                <td>
                                    <?php if ($dish['is_vegetarian'] ?? true): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-leaf me-1"></i> Vegetarian
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-drumstick-bite me-1"></i> Non-Vegetarian
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($dish['available']): ?>
                                        <span class="badge bg-success">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Unavailable</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('/admin/dishes/edit/' . $dish['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/admin/dishes/delete/' . $dish['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this dish?')">
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
