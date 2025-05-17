<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-utensils"></i> Book Tiffin</h1>
    <a href="<?= base_url('/dishes') ?>" class="btn btn-outline-primary">
        <i class="fas fa-plus-circle"></i> Add More Dishes
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-list"></i> Available Dishes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($dishes)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No dishes available at the moment.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($dishes as $dish): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <?php if ($dish['image']): ?>
                                                <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" class="img-fluid rounded-start h-100" alt="<?= $dish['name'] ?>" style="object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-light text-center py-4 h-100">
                                                    <i class="fas fa-utensils fa-2x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?= $dish['name'] ?>
                                                    <?php if (isset($dish['is_vegetarian'])): ?>
                                                        <?php if ($dish['is_vegetarian']): ?>
                                                            <span class="badge bg-success rounded-pill ms-2">
                                                                <i class="fas fa-leaf"></i> Veg
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger rounded-pill ms-2">
                                                                <i class="fas fa-drumstick-bite"></i> Non-Veg
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </h5>
                                                <p class="card-text text-success fw-bold">₹<?= number_format($dish['price'], 2) ?></p>
                                                <a href="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus"></i> Add to Cart
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card sticky-top" style="top: 20px;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Your Cart</h5>
            </div>
            <div class="card-body">
                <?php if (empty($cart)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Your cart is empty. Add some dishes to proceed.
                    </div>
                <?php else: ?>
                    <div class="cart-items">
                        <?php
                        $total = 0;
                        foreach ($cart as $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-12 mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0 fw-bold">
                                                        <?= $item['name'] ?>
                                                        <?php if (isset($item['is_vegetarian'])): ?>
                                                            <?php if ($item['is_vegetarian']): ?>
                                                                <span class="badge bg-success rounded-pill ms-2">
                                                                    <i class="fas fa-leaf"></i> Veg
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger rounded-pill ms-2">
                                                                    <i class="fas fa-drumstick-bite"></i> Non-Veg
                                                                </span>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </h6>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-primary">₹<?= number_format($subtotal, 2) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="input-group input-group-sm quantity-input-group" style="width: 140px;">
                                                    <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease" data-id="<?= $item['id'] ?>">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control text-center quantity-input" value="<?= $item['quantity'] ?>" min="1" max="10" data-id="<?= $item['id'] ?>">
                                                    <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase" data-id="<?= $item['id'] ?>">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <a href="<?= base_url('/booking/remove-from-cart/' . $item['id']) ?>" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i> Remove
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="card mb-3 border-0 shadow-sm bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Total</h5>
                                    <h5 class="mb-0 text-primary">₹<?= number_format($total, 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="<?= base_url('/booking/clear-cart') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Clear Cart
                        </a>
                        <a href="<?= base_url('/booking/checkout') ?>" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Proceed to Checkout
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity buttons
        const quantityBtns = document.querySelectorAll('.quantity-btn');
        const quantityInputs = document.querySelectorAll('.quantity-input');

        // Add event listeners to quantity buttons
        quantityBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const id = this.getAttribute('data-id');
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                let value = parseInt(input.value);

                if (action === 'increase') {
                    if (value < 10) {
                        value++;
                    }
                } else if (action === 'decrease') {
                    if (value > 1) {
                        value--;
                    }
                }

                input.value = value;
                updateCartQuantity(id, value);
            });
        });

        // Add event listeners to quantity inputs
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const id = this.getAttribute('data-id');
                let value = parseInt(this.value);

                // Validate input
                if (isNaN(value) || value < 1) {
                    value = 1;
                } else if (value > 10) {
                    value = 10;
                }

                this.value = value;
                updateCartQuantity(id, value);
            });
        });

        // Function to update cart quantity
        function updateCartQuantity(id, quantity) {
            // Show loading indicator
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay';
            loadingOverlay.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
            document.body.appendChild(loadingOverlay);

            // Send AJAX request to update cart
            fetch(`<?= base_url('/booking/update-quantity/') ?>/${id}/${quantity}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to update totals
                        window.location.reload();
                    } else {
                        alert('Failed to update quantity: ' + data.message);
                        // Remove loading overlay
                        document.body.removeChild(loadingOverlay);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the quantity.');
                    // Remove loading overlay
                    document.body.removeChild(loadingOverlay);
                });
        }
    });
</script>

<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .cart-items .card {
        transition: all 0.3s ease;
    }

    .cart-items .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .quantity-input-group {
        border-radius: 30px;
        overflow: hidden;
    }

    .quantity-input-group .btn {
        border-radius: 0;
        z-index: 1;
    }

    .quantity-input-group .form-control {
        border-left: none;
        border-right: none;
        box-shadow: none;
    }

    .quantity-input-group .form-control:focus {
        box-shadow: none;
    }

    .sticky-top {
        z-index: 100;
    }

    @media (max-width: 767.98px) {
        .sticky-top {
            position: relative;
            top: 0 !important;
        }
    }
</style>
<?= $this->endSection() ?>
