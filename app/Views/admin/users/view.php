<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/admin/users') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Users
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user"></i> User Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th style="width: 40%">ID</th>
                        <td><?= $user['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
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
                    <tr>
                        <th>Registered On</th>
                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-wallet"></i> Wallet Information</h5>
            </div>
            <div class="card-body">
                <?php
                // Get wallet information
                $db = \Config\Database::connect();
                $wallet = $db->table('wallets')->where('user_id', $user['id'])->get()->getRowArray();
                ?>
                
                <?php if ($wallet): ?>
                    <div class="text-center mb-3">
                        <h2>₹<?= number_format($wallet['balance'], 2) ?></h2>
                        <p class="text-muted">Current Balance</p>
                    </div>
                    
                    <h6>Recent Transactions</h6>
                    <?php
                    $transactions = $db->table('wallet_transactions')
                                      ->where('user_id', $user['id'])
                                      ->orderBy('created_at', 'DESC')
                                      ->limit(5)
                                      ->get()
                                      ->getResultArray();
                    ?>
                    
                    <?php if (empty($transactions)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No transactions found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($transaction['created_at'])) ?></td>
                                            <td>
                                                <?php if ($transaction['type'] == 'credit'): ?>
                                                    <span class="badge bg-success">Credit</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Debit</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>₹<?= number_format($transaction['amount'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> No wallet found for this user.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-alt"></i> Booking History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($bookings)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No bookings found for this user.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
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
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
