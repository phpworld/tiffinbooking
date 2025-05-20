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
                    <!-- Delivery date and time slot removed as requested -->

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
                            <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay">
                            <label class="form-check-label" for="razorpay">
                                <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay" height="20"> Pay Online (Credit/Debit Card, UPI, etc.)
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
                        <button type="button" id="placeOrderBtn" class="btn btn-success btn-lg" <?= ($wallet['balance'] ?? 0) < $total && !isset($_POST['payment_method']) ? 'disabled' : '' ?>>
                            <i class="fas fa-check-circle"></i> Place Order
                        </button>
                        <?php if (($wallet['balance'] ?? 0) < $total): ?>
                            <div class="alert alert-warning wallet-warning">
                                <i class="fas fa-exclamation-triangle"></i> Your wallet balance is insufficient. Please select another payment method or <a href="<?= base_url('/user/wallet/recharge') ?>" class="alert-link">recharge your wallet</a>.
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
                    <p class="mb-0">Your tiffin will be delivered as per our standard delivery schedule.</p>
                </div>

                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Cancellation Policy</h6>
                    <p class="mb-0">Only pending orders can be cancelled. Once an order is confirmed by the admin, it cannot be cancelled. Cancelled orders paid through wallet will be refunded.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Razorpay JS SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const walletRadio = document.getElementById('wallet');
        const razorpayRadio = document.getElementById('razorpay');
        const cashRadio = document.getElementById('cash');
        const placeOrderBtn = document.getElementById('placeOrderBtn');
        const orderForm = document.querySelector('form');
        const walletBalance = <?= ($wallet['balance'] ?? 0) ?>;
        const orderTotal = <?= $total ?>;
        const walletWarning = document.querySelector('.wallet-warning');

        function updateSubmitButton() {
            if (walletRadio.checked && walletBalance < orderTotal) {
                placeOrderBtn.disabled = true;
                if (walletWarning) walletWarning.style.display = 'block';
            } else {
                placeOrderBtn.disabled = false;
                if (walletWarning) walletWarning.style.display = 'none';
            }
        }

        walletRadio.addEventListener('change', updateSubmitButton);
        razorpayRadio.addEventListener('change', updateSubmitButton);
        cashRadio.addEventListener('change', updateSubmitButton);

        // Handle place order button click
        placeOrderBtn.addEventListener('click', function() {
            // If Razorpay is selected, initiate Razorpay payment
            if (razorpayRadio.checked) {
                initiateRazorpayPayment();
            } else {
                // For wallet or cash, submit the form directly
                orderForm.submit();
            }
        });

        // Function to initiate Razorpay payment
        function initiateRazorpayPayment() {
            // Disable the button and show loading
            placeOrderBtn.disabled = true;
            placeOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

            // Create a form to submit to the server to create an order
            const formData = new FormData();
            formData.append('amount', orderTotal);
            formData.append('payment_method', 'razorpay');

            // Send request to create Razorpay order
            fetch('<?= base_url('/booking/create-order') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    throw new Error(data.error);
                }

                // Initialize Razorpay payment
                const options = {
                    key: '<?= getenv('razorpay.key_id') ?: 'rzp_test_XaZ89XsD6ejHqt' ?>',
                    amount: data.amount,
                    currency: 'INR',
                    name: 'Tiffin Delight',
                    description: 'Order Payment',
                    order_id: data.order_id,
                    handler: function(response) {
                        // On successful payment, add payment details to form and submit
                        const razorpayPaymentId = document.createElement('input');
                        razorpayPaymentId.type = 'hidden';
                        razorpayPaymentId.name = 'razorpay_payment_id';
                        razorpayPaymentId.value = response.razorpay_payment_id;
                        orderForm.appendChild(razorpayPaymentId);

                        const razorpayOrderId = document.createElement('input');
                        razorpayOrderId.type = 'hidden';
                        razorpayOrderId.name = 'razorpay_order_id';
                        razorpayOrderId.value = response.razorpay_order_id;
                        orderForm.appendChild(razorpayOrderId);

                        const razorpaySignature = document.createElement('input');
                        razorpaySignature.type = 'hidden';
                        razorpaySignature.name = 'razorpay_signature';
                        razorpaySignature.value = response.razorpay_signature;
                        orderForm.appendChild(razorpaySignature);

                        // Submit the form
                        orderForm.submit();
                    },
                    prefill: {
                        name: '<?= session()->get('name') ?>',
                        email: '<?= session()->get('email') ?>',
                        contact: ''
                    },
                    theme: {
                        color: '#4CAF50'
                    },
                    modal: {
                        ondismiss: function() {
                            // Re-enable the button
                            placeOrderBtn.disabled = false;
                            placeOrderBtn.innerHTML = '<i class="fas fa-check-circle"></i> Place Order';
                        }
                    }
                };

                const rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to process payment: ' + error.message);

                // Re-enable the button
                placeOrderBtn.disabled = false;
                placeOrderBtn.innerHTML = '<i class="fas fa-check-circle"></i> Place Order';
            });
        }

        // Initialize the form
        updateSubmitButton();
    });
</script>
<?= $this->endSection() ?>
