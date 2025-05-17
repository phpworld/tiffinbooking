<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/bookings') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Booking #<?= $booking['id'] ?> Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Booking Information</h6>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%">Booking ID</th>
                        <td>#<?= $booking['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Booking Date</th>
                        <td><?= date('F j, Y', strtotime($booking['booking_date'])) ?></td>
                    </tr>
                    <tr>
                        <th>Delivery Time</th>
                        <td><?= $slot['slot_time'] ?></td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td>₹<?= number_format($booking['total_amount'], 2) ?></td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>
                            <?php if ($booking['payment_method'] == 'wallet'): ?>
                                <span class="badge bg-success">Wallet</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Cash on Delivery</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
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
                        </td>
                    </tr>
                    <tr>
                        <th>Created On</th>
                        <td><?= date('F j, Y g:i A', strtotime($booking['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Customer Information</h6>
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 40%">Name</th>
                        <td><?= $user['name'] ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= $user['email'] ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?= $user['phone'] ?: 'Not provided' ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?= $user['address'] ?: 'Not provided' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-utensils"></i> Ordered Items</h5>
    </div>
    <div class="card-body">
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
                        
                        // Get dish name from database
                        $db = \Config\Database::connect();
                        $dish = $db->table('dishes')->where('id', $item['dish_id'])->get()->getRowArray();
                    ?>
                        <tr>
                            <td><?= $dish ? $dish['name'] : 'Unknown Dish' ?></td>
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
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-edit"></i> Update Status</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/bookings/update-status/' . $booking['id']) ?>" method="post">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label for="status" class="form-label">Change Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= $booking['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="delivered" <?= $booking['status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option value="cancelled" <?= $booking['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
