<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('/dishes') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Menu
    </a>
</div>

<div class="card">
    <div class="row g-0">
        <div class="col-md-4">
            <?php if ($dish['image']): ?>
                <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" class="img-fluid rounded-start h-100" alt="<?= $dish['name'] ?>" style="object-fit: cover;">
            <?php else: ?>
                <div class="bg-light text-center py-5 h-100">
                    <i class="fas fa-utensils fa-5x text-muted"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h2 class="card-title"><?= $dish['name'] ?></h2>
                <p class="card-text text-success fw-bold fs-4">₹<?= number_format($dish['price'], 2) ?></p>
                <p class="card-text"><?= nl2br($dish['description']) ?></p>
                
                <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                    <form action="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" method="get">
                        <div class="row align-items-end">
                            <div class="col-md-4 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1">
                            </div>
                            <div class="col-md-8 mb-3">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                                <a href="<?= base_url('/booking/create') ?>" class="btn btn-primary btn-lg ms-2">
                                    <i class="fas fa-shopping-cart"></i> View Cart
                                </a>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <?php if (!session()->get('logged_in')): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Please <a href="<?= base_url('/auth/login') ?>" class="alert-link">login</a> to order this dish.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
