<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">My Dashboard</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> Profile Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> <?= $user['name'] ?></p>
                <p><strong>Email:</strong> <?= $user['email'] ?></p>
                <p><strong>Phone:</strong> <?= $user['phone'] ?: 'Not provided' ?></p>
                <p><strong>Address:</strong> <?= $user['address'] ?: 'Not provided' ?></p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/user/profile') ?>" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-wallet"></i> Wallet</h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h2>₹<?= number_format($wallet['balance'] ?? 0, 2) ?></h2>
                    <p class="text-muted">Current Balance</p>
                </div>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/user/wallet/recharge') ?>" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Recharge Wallet
                    </a>
                    <a href="<?= base_url('/user/wallet') ?>" class="btn btn-outline-success">
                        <i class="fas fa-history"></i> Transaction History
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-utensils"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/dishes') ?>" class="btn btn-info text-white">
                        <i class="fas fa-list"></i> Browse Menu
                    </a>
                    <a href="<?= base_url('/booking/create') ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i> Book Tiffin
                    </a>
                    <a href="<?= base_url('/user/bookings') ?>" class="btn btn-secondary">
                        <i class="fas fa-history"></i> My Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Recent Bookings</h5>
    </div>
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> You don't have any bookings yet.
                <a href="<?= base_url('/booking/create') ?>" class="alert-link">Book your first tiffin now!</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                            <tr>
                                <td>#<?= $booking['id'] ?></td>
                                <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                                <td>₹<?= number_format($booking['total_amount'], 2) ?></td>
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
                                <td>
                                    <a href="<?= base_url('/user/bookings/view/' . $booking['id']) ?>" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <?php if ($booking['status'] == 'pending' || $booking['status'] == 'confirmed'): ?>
                                        <a href="<?= base_url('/user/bookings/cancel/' . $booking['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($bookings) > 5): ?>
                <div class="text-center mt-3">
                    <a href="<?= base_url('/user/bookings') ?>" class="btn btn-outline-primary">
                        View All Bookings
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
