<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-star"></i> Customer Reviews</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">All Reviews</h6>
    </div>
    <div class="card-body">
        <?php if (empty($reviews)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No reviews found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="reviewsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?= $review['id'] ?></td>
                                <td><?= $review['user_name'] ?></td>
                                <td>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($review['comment'])): ?>
                                        <?= substr($review['comment'], 0, 50) . (strlen($review['comment']) > 50 ? '...' : '') ?>
                                    <?php else: ?>
                                        <span class="text-muted">No comment</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($review['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('/admin/reviews/view/' . $review['id']) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/reviews/delete/' . $review['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                                        <i class="fas fa-trash"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        $('#reviewsTable').DataTable({
            order: [[4, 'desc']], // Sort by date column (index 4) in descending order
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search reviews..."
            }
        });
    });
</script>
<?= $this->endSection() ?>
