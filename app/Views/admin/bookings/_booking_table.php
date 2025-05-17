<?php if (empty($bookings)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No bookings found in this category.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?= $booking['id'] ?></td>
                        <td>
                            <?php 
                            // Get user name from database
                            $db = \Config\Database::connect();
                            $user = $db->table('users')->where('id', $booking['user_id'])->get()->getRowArray();
                            echo $user ? $user['name'] : 'Unknown User';
                            ?>
                        </td>
                        <td><?= date('M d, Y', strtotime($booking['booking_date'])) ?></td>
                        <td>₹<?= number_format($booking['total_amount'], 2) ?></td>
                        <td>
                            <?php if ($booking['payment_method'] == 'wallet'): ?>
                                <span class="badge bg-success">Wallet</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Cash</span>
                            <?php endif; ?>
                        </td>
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
                            <a href="<?= base_url('/admin/bookings/view/' . $booking['id']) ?>" class="btn btn-sm btn-info text-white">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
