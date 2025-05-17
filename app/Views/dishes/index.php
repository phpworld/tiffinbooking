<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="fas fa-utensils"></i> Our Menu</h1>
    <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
        <a href="<?= base_url('/booking/create') ?>" class="btn btn-primary">
            <i class="fas fa-shopping-cart"></i> 
            Book Tiffin
            <?php 
            $cart = session()->get('cart');
            if (!empty($cart)): 
                $totalItems = array_sum(array_column($cart, 'quantity'));
            ?>
                <span class="badge bg-light text-dark ms-1"><?= $totalItems ?></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
</div>

<?php if (empty($dishes)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No dishes available at the moment.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($dishes as $dish): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($dish['image']): ?>
                        <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" class="card-img-top" alt="<?= $dish['name'] ?>" style="height: 200px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light text-center py-5">
                            <i class="fas fa-utensils fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= $dish['name'] ?></h5>
                        <p class="card-text text-muted"><?= substr($dish['description'], 0, 100) . (strlen($dish['description']) > 100 ? '...' : '') ?></p>
                        <p class="card-text fw-bold text-success">₹<?= number_format($dish['price'], 2) ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('/dishes/view/' . $dish['id']) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                                <a href="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add to Cart
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
