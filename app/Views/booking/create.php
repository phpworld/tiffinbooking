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
                                                <h5 class="card-title"><?= $dish['name'] ?></h5>
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Dish</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($cart as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₹<?= number_format($subtotal, 2) ?></td>
                                        <td>
                                            <a href="<?= base_url('/booking/remove-from-cart/' . $item['id']) ?>" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total:</th>
                                    <th colspan="2">₹<?= number_format($total, 2) ?></th>
                                </tr>
                            </tfoot>
                        </table>
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
<?= $this->endSection() ?>
