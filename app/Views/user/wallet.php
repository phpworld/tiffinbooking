<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> Your wallet has been recharged successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-wallet"></i> My Wallet</h5>
            </div>
            <div class="card-body text-center">
                <h1 class="display-4">₹<?= number_format($wallet['balance'] ?? 0, 2) ?></h1>
                <p class="lead">Current Balance</p>
                <div class="d-grid gap-2">
                    <a href="<?= base_url('/user/wallet/recharge') ?>" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Recharge Wallet
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> Transaction History</h5>
            </div>
            <div class="card-body">
                <?php if (empty($transactions)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No transactions found.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction): ?>
                                    <tr>
                                        <td><?= date('M d, Y H:i', strtotime($transaction['created_at'])) ?></td>
                                        <td>
                                            <?php if ($transaction['type'] == 'credit'): ?>
                                                <span class="badge bg-success">Credit</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Debit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($transaction['type'] == 'credit'): ?>
                                                <span class="text-success">+₹<?= number_format($transaction['amount'], 2) ?></span>
                                            <?php else: ?>
                                                <span class="text-danger">-₹<?= number_format($transaction['amount'], 2) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $transaction['description'] ?></td>
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
