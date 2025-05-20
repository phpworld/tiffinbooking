<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Recharge Wallet</h5>
            </div>
            <div class="card-body">
                <form id="rechargeForm">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₹)</label>
                        <input type="number" class="form-control form-control-lg" id="amount" name="amount" min="1" step="1" required>
                        <div class="form-text">Enter the amount you want to add to your wallet.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Quick Select</label>
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-outline-success amount-btn" data-amount="100">₹100</button>
                            <button type="button" class="btn btn-outline-success amount-btn" data-amount="200">₹200</button>
                            <button type="button" class="btn btn-outline-success amount-btn" data-amount="500">₹500</button>
                            <button type="button" class="btn btn-outline-success amount-btn" data-amount="1000">₹1000</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" checked>
                            <label class="form-check-label" for="razorpay">
                                <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay" height="20"> Razorpay
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" id="rechargeBtn" class="btn btn-success btn-lg">Recharge Now</button>
                        <a href="<?= base_url('/user/wallet') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Secure payments powered by Razorpay. Your payment information is never stored on our servers.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Razorpay JS SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountBtns = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount');
        const rechargeBtn = document.getElementById('rechargeBtn');

        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;

                // Remove active class from all buttons
                amountBtns.forEach(b => {
                    b.classList.remove('active', 'btn-success', 'text-white');
                    b.classList.add('btn-outline-success');
                });

                // Add active class to clicked button
                this.classList.add('active', 'btn-success', 'text-white');
                this.classList.remove('btn-outline-success');
            });
        });

        // Handle recharge button click
        rechargeBtn.addEventListener('click', function() {
            const amount = amountInput.value;

            if (!amount || amount <= 0) {
                alert('Please enter a valid amount');
                return;
            }

            // Create a form to submit to the server to create an order
            const formData = new FormData();
            formData.append('amount', amount);
            formData.append('payment_method', 'razorpay');

            // Disable the button and show loading
            rechargeBtn.disabled = true;
            rechargeBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';

            // Send request to create Razorpay order
            fetch('<?= base_url('/user/wallet/create-order') ?>', {
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
                    description: 'Wallet Recharge',
                    order_id: data.order_id,
                    handler: function(response) {
                        // On successful payment, verify the payment
                        verifyPayment(response, data.order_id, amount);
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
                            rechargeBtn.disabled = false;
                            rechargeBtn.innerHTML = 'Recharge Now';
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
                rechargeBtn.disabled = false;
                rechargeBtn.innerHTML = 'Recharge Now';
            });
        });

        // Function to verify payment
        function verifyPayment(payment, orderId, amount) {
            const formData = new FormData();
            formData.append('razorpay_payment_id', payment.razorpay_payment_id);
            formData.append('razorpay_order_id', payment.razorpay_order_id);
            formData.append('razorpay_signature', payment.razorpay_signature);
            formData.append('amount', amount);

            fetch('<?= base_url('/user/wallet/verify-payment') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= base_url('/user/wallet') ?>?success=1';
                } else {
                    alert('Payment verification failed: ' + (data.error || 'Unknown error'));
                    rechargeBtn.disabled = false;
                    rechargeBtn.innerHTML = 'Recharge Now';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment verification failed. Please contact support.');
                rechargeBtn.disabled = false;
                rechargeBtn.innerHTML = 'Recharge Now';
            });
        }
    });
</script>
<?= $this->endSection() ?>
