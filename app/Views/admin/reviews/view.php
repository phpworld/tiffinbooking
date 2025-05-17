<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/reviews') ?>" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i> Back to Reviews
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Review Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary text-white me-3">
                                <?= substr($review['user_name'], 0, 1) ?>
                            </div>
                            <div>
                                <h5 class="mb-1"><?= $review['user_name'] ?></h5>
                                <div class="text-muted small">
                                    <i class="far fa-envelope me-1"></i> <?= $review['user_email'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="rating-stars mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review['rating']): ?>
                                        <i class="fas fa-star text-warning"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-warning"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="text-muted small">
                                <i class="far fa-calendar-alt me-1"></i> <?= date('M d, Y h:i A', strtotime($review['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="review-content p-3 bg-light rounded">
                        <?php if (!empty($review['comment'])): ?>
                            <p class="mb-0"><?= nl2br($review['comment']) ?></p>
                        <?php else: ?>
                            <p class="text-muted mb-0">No comment provided</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('/admin/reviews/delete/' . $review['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                        <i class="fas fa-trash me-2"></i> Delete Review
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Order ID:</strong> #<?= $booking['id'] ?></p>
                        <p class="mb-1"><strong>Order Date:</strong> <?= date('M d, Y', strtotime($booking['booking_date'])) ?></p>
                        <p class="mb-1"><strong>Delivery Slot:</strong> <?= $booking['slot_name'] ?? 'N/A' ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Status:</strong> 
                            <span class="badge bg-<?= getStatusColor($booking['status']) ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </p>
                        <p class="mb-1"><strong>Payment Method:</strong> <?= ucfirst($booking['payment_method']) ?></p>
                        <p class="mb-1"><strong>Total Amount:</strong> ₹<?= number_format($booking['total_amount'], 2) ?></p>
                    </div>
                </div>
                
                <h6 class="font-weight-bold">Ordered Items</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item['dish_image']): ?>
                                                <img src="<?= base_url('/uploads/dishes/' . $item['dish_image']) ?>" class="me-2" alt="<?= $item['dish_name'] ?>" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div class="bg-light text-center me-2" style="width: 40px; height: 40px; border-radius: 4px;">
                                                    <i class="fas fa-utensils text-muted" style="line-height: 40px;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <?= $item['dish_name'] ?>
                                        </div>
                                    </td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>₹<?= number_format($item['price'], 2) ?></td>
                                    <td>₹<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/admin/bookings/view/' . $booking['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i> View Full Order
                    </a>
                    <a href="<?= base_url('/admin/reviews/delete/' . $review['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this review?')">
                        <i class="fas fa-trash me-2"></i> Delete Review
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.5rem;
    }
</style>

<?php
function getStatusColor($status) {
    switch ($status) {
        case 'pending':
            return 'warning';
        case 'confirmed':
            return 'primary';
        case 'delivered':
            return 'success';
        case 'cancelled':
            return 'danger';
        default:
            return 'secondary';
    }
}
?>
<?= $this->endSection() ?>
