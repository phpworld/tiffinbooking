<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Banner Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('/admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Banners</li>
    </ol>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-images me-1"></i>
            Banner List
            <a href="<?= base_url('/admin/banners/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Add New Banner
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($banners)): ?>
                <div class="alert alert-info">
                    No banners found. Click the "Add New Banner" button to create one.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="bannersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Subtitle</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($banners as $banner): ?>
                                <tr>
                                    <td><?= $banner['id'] ?></td>
                                    <td>
                                        <?php if ($banner['image']): ?>
                                            <img src="<?= base_url('/uploads/banners/' . $banner['image']) ?>" alt="<?= $banner['title'] ?>" class="img-thumbnail" style="max-width: 100px;">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $banner['title'] ?></td>
                                    <td><?= $banner['subtitle'] ?? '-' ?></td>
                                    <td><?= $banner['order'] ?></td>
                                    <td>
                                        <?php if ($banner['is_active']): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('/admin/banners/edit/' . $banner['id']) ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?= base_url('/admin/banners/delete/' . $banner['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this banner?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#bannersTable').DataTable({
            order: [[4, 'asc']] // Sort by order column by default
        });
    });
</script>
<?= $this->endSection() ?>
