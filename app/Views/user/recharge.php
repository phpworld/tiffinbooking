<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Recharge Wallet</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/user/wallet/recharge') ?>" method="post">
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
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">Recharge Now</button>
                        <a href="<?= base_url('/user/wallet') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> Note: This is a simulated wallet recharge. In a real application, this would be integrated with a payment gateway.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountBtns = document.querySelectorAll('.amount-btn');
        const amountInput = document.getElementById('amount');
        
        amountBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = this.getAttribute('data-amount');
                amountInput.value = amount;
                
                // Remove active class from all buttons
                amountBtns.forEach(b => b.classList.remove('active', 'btn-success', 'text-white'));
                b.classList.add('btn-outline-success');
                
                // Add active class to clicked button
                this.classList.add('active', 'btn-success', 'text-white');
                this.classList.remove('btn-outline-success');
            });
        });
    });
</script>
<?= $this->endSection() ?>
