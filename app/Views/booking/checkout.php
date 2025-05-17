<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/booking/create') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Cart
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Dish</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): 
                                $subtotal = $item['price'] * $item['quantity'];
                            ?>
                                <tr>
                                    <td><?= $item['name'] ?></td>
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
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payment Information</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/booking/place-order') ?>" method="post">
                    <div class="mb-3">
                        <label for="booking_date" class="form-label">Delivery Date</label>
                        <input type="date" class="form-control" id="booking_date" name="booking_date" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="delivery_slot_id" class="form-label">Delivery Time Slot</label>
                        <select class="form-select" id="delivery_slot_id" name="delivery_slot_id" required>
                            <option value="">Select a time slot</option>
                            <?php foreach ($slots as $slot): ?>
                                <option value="<?= $slot['id'] ?>"><?= $slot['slot_time'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="wallet" value="wallet" checked>
                            <label class="form-check-label" for="wallet">
                                Wallet (Balance: ₹<?= number_format($wallet['balance'] ?? 0, 2) ?>)
                                <?php if (($wallet['balance'] ?? 0) < $total): ?>
                                    <span class="text-danger">Insufficient balance</span>
                                <?php endif; ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash">
                            <label class="form-check-label" for="cash">
                                Cash on Delivery
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" <?= ($wallet['balance'] ?? 0) < $total ? 'disabled' : '' ?>>
                            <i class="fas fa-check-circle"></i> Place Order
                        </button>
                        <?php if (($wallet['balance'] ?? 0) < $total): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> Your wallet balance is insufficient. Please select Cash on Delivery or <a href="<?= base_url('/user/wallet/recharge') ?>" class="alert-link">recharge your wallet</a>.
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Order Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Total Items:</strong> <?= array_sum(array_column($cart, 'quantity')) ?></p>
                <p><strong>Total Amount:</strong> ₹<?= number_format($total, 2) ?></p>
                
                <hr>
                
                <div class="alert alert-info">
                    <h6><i class="fas fa-truck"></i> Delivery Information</h6>
                    <p class="mb-0">Your tiffin will be delivered on the selected date and time slot.</p>
                </div>
                
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Cancellation Policy</h6>
                    <p class="mb-0">Orders can be cancelled up to 2 hours before the delivery time. Cancelled orders paid through wallet will be refunded.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const walletRadio = document.getElementById('wallet');
        const cashRadio = document.getElementById('cash');
        const submitButton = document.querySelector('button[type="submit"]');
        const walletBalance = <?= ($wallet['balance'] ?? 0) ?>;
        const orderTotal = <?= $total ?>;
        
        function updateSubmitButton() {
            if (walletRadio.checked && walletBalance < orderTotal) {
                submitButton.disabled = true;
            } else {
                submitButton.disabled = false;
            }
        }
        
        walletRadio.addEventListener('change', updateSubmitButton);
        cashRadio.addEventListener('change', updateSubmitButton);
    });
</script>
<?= $this->endSection() ?>
