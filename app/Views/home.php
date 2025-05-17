<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<div class="card bg-dark text-white mb-4">
    <img src="https://source.unsplash.com/random/1200x400/?food,indian" class="card-img" alt="Tiffin Service" style="height: 400px; object-fit: cover; filter: brightness(0.6);">
    <div class="card-img-overlay d-flex flex-column justify-content-center text-center">
        <h1 class="card-title display-4 fw-bold">Delicious Homemade Tiffin</h1>
        <p class="card-text fs-5">Healthy, fresh, and tasty meals delivered to your doorstep</p>
        <div>
            <a href="<?= base_url('/dishes') ?>" class="btn btn-success btn-lg me-2">
                <i class="fas fa-utensils"></i> View Menu
            </a>
            <?php if (!session()->get('logged_in')): ?>
                <a href="<?= base_url('/auth/register') ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-user-plus"></i> Register Now
                </a>
            <?php else: ?>
                <a href="<?= base_url('/booking/create') ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-shopping-cart"></i> Book Tiffin
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="row mb-5">
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-utensils fa-3x text-success"></i>
                </div>
                <h3 class="card-title">Fresh & Healthy</h3>
                <p class="card-text">Our meals are prepared fresh daily using high-quality ingredients to ensure you get the best nutrition.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-truck fa-3x text-success"></i>
                </div>
                <h3 class="card-title">Timely Delivery</h3>
                <p class="card-text">Choose your preferred delivery slot and we'll make sure your tiffin arrives on time, every time.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="mb-3">
                    <i class="fas fa-wallet fa-3x text-success"></i>
                </div>
                <h3 class="card-title">Easy Payments</h3>
                <p class="card-text">Recharge your wallet for hassle-free payments or choose cash on delivery for your convenience.</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Dishes Section -->
<div class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star"></i> Featured Dishes</h2>
        <a href="<?= base_url('/dishes') ?>" class="btn btn-outline-success">
            View All <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <?php if (empty($featured_dishes)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No featured dishes available at the moment.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($featured_dishes as $dish): ?>
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
</div>

<!-- How It Works Section -->
<div class="card mb-5">
    <div class="card-body">
        <h2 class="text-center mb-4"><i class="fas fa-question-circle"></i> How It Works</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                </div>
                <h4>Register</h4>
                <p>Create an account to get started</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                </div>
                <h4>Select Dishes</h4>
                <p>Choose from our delicious menu</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
                <h4>Schedule Delivery</h4>
                <p>Pick your preferred date and time</p>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <div class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
                <h4>Enjoy Your Meal</h4>
                <p>Receive fresh tiffin at your doorstep</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="card bg-success text-white mb-4">
    <div class="card-body text-center py-5">
        <h2 class="card-title">Ready to Order?</h2>
        <p class="card-text">Get delicious, homemade food delivered to your doorstep today!</p>
        <?php if (!session()->get('logged_in')): ?>
            <a href="<?= base_url('/auth/register') ?>" class="btn btn-light btn-lg me-2">
                <i class="fas fa-user-plus"></i> Register Now
            </a>
            <a href="<?= base_url('/auth/login') ?>" class="btn btn-outline-light btn-lg">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        <?php else: ?>
            <a href="<?= base_url('/booking/create') ?>" class="btn btn-light btn-lg">
                <i class="fas fa-shopping-cart"></i> Book Tiffin Now
            </a>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
