<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-calendar-check"></i> Booking Details</h1>
    <a href="<?= base_url('/user/bookings') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Booking Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Booking ID:</strong> #<?= $booking['id'] ?></p>
                <p><strong>Date:</strong> <?= date('F j, Y', strtotime($booking['booking_date'])) ?></p>
                <p>
                    <strong>Status:</strong>
                    <?php
                    $statusClass = '';
                    switch ($booking['status']) {
                        case 'pending':
                            $statusClass = 'warning';
                            break;
                        case 'confirmed':
                            $statusClass = 'primary';
                            break;
                        case 'delivered':
                            $statusClass = 'success';
                            break;
                        case 'cancelled':
                            $statusClass = 'danger';
                            break;
                    }
                    ?>
                    <span class="badge bg-<?= $statusClass ?>">
                        <?= ucfirst($booking['status']) ?>
                    </span>
                </p>
                <p>
                    <strong>Payment Method:</strong>
                    <?php if ($booking['payment_method'] == 'wallet'): ?>
                        <span class="badge bg-success">Wallet</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Cash</span>
                    <?php endif; ?>
                </p>
                <p><strong>Total Amount:</strong> ₹<?= number_format($booking['total_amount'], 2) ?></p>
                <p><strong>Booked On:</strong> <?= date('F j, Y g:i A', strtotime($booking['created_at'])) ?></p>

                <?php if ($booking['status'] == 'pending'): ?>
                    <div class="d-grid gap-2 mt-3">
                        <a href="<?= base_url('/user/bookings/cancel/' . $booking['id']) ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                            <i class="fas fa-times"></i> Cancel Booking
                        </a>
                    </div>
                <?php elseif ($booking['status'] == 'confirmed'): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> This booking has been confirmed and cannot be cancelled. Please contact customer support if you need assistance.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-utensils"></i> Ordered Items</h5>
            </div>
            <div class="card-body">
                <?php if (empty($items)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No items found for this booking.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total = 0;
                                foreach ($items as $item):
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td>
                                            <?php
                                            // Get dish name from database
                                            $db = \Config\Database::connect();
                                            $dish = $db->table('dishes')->where('id', $item['dish_id'])->get()->getRowArray();
                                            echo $dish ? $dish['name'] : 'Unknown Dish';
                                            ?>
                                        </td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₹<?= number_format($subtotal, 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th>₹<?= number_format($total, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-truck"></i> Delivery Information</h5>
            </div>
            <div class="card-body">
                <?php
                // Get delivery slot from database
                $db = \Config\Database::connect();
                $slot = $db->table('delivery_slots')->where('id', $booking['delivery_slot_id'])->get()->getRowArray();
                ?>
                <p><strong>Delivery Date:</strong> <?= date('F j, Y', strtotime($booking['booking_date'])) ?></p>
                <p><strong>Delivery Time:</strong> <?= $slot ? $slot['slot_time'] : 'Unknown Slot' ?></p>

                <?php if ($booking['status'] == 'confirmed'): ?>
                    <div class="alert alert-primary">
                        <i class="fas fa-info-circle"></i> Your order is confirmed and will be delivered at the scheduled time. <strong>Note:</strong> Confirmed orders cannot be cancelled.
                    </div>
                <?php elseif ($booking['status'] == 'pending'): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-clock"></i> Your order is pending confirmation. You can cancel this order if needed.
                    </div>
                <?php elseif ($booking['status'] == 'delivered'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Your order has been delivered. Enjoy your meal!
                    </div>

                    <?php
                    // Check if user has already reviewed this booking
                    $db = \Config\Database::connect();
                    $review = $db->table('reviews')->where('booking_id', $booking['id'])->get()->getRowArray();

                    if ($review): ?>
                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0">Your Review</h6>
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?= $i <= $review['rating'] ? 'fas' : 'far' ?> fa-star text-warning"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php if (!empty($review['comment'])): ?>
                                <p class="mb-0 small"><?= $review['comment'] ?></p>
                            <?php else: ?>
                                <p class="mb-0 small text-muted">No comment provided</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mt-3">
                            <a href="<?= base_url('/review/create/' . $booking['id']) ?>" class="btn btn-primary">
                                <i class="fas fa-star me-2"></i> Rate Your Experience
                            </a>
                            <p class="small text-muted mt-2">Please share your feedback to help us improve our service.</p>
                        </div>
                    <?php endif; ?>
                <?php elseif ($booking['status'] == 'cancelled'): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i> This order has been cancelled.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
