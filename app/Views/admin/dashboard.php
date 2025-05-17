<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Users</h6>
                        <h2 class="mb-0"><?= $total_users ?></h2>
                    </div>
                    <i class="fas fa-users fa-3x"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/users') ?>" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Dishes</h6>
                        <h2 class="mb-0"><?= $total_dishes ?></h2>
                    </div>
                    <i class="fas fa-utensils fa-3x"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/dishes') ?>" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Bookings</h6>
                        <h2 class="mb-0"><?= $total_bookings ?></h2>
                    </div>
                    <i class="fas fa-calendar-alt fa-3x"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/bookings') ?>" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Pending Bookings</h6>
                        <h2 class="mb-0"><?= $pending_bookings ?></h2>
                    </div>
                    <i class="fas fa-clock fa-3x"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/bookings') ?>" class="text-white text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Today's Bookings</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <div class="text-center">
                        <h1 class="display-4"><?= $today_bookings ?></h1>
                        <p class="lead">Bookings for <?= date('F j, Y') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?= base_url('/admin/dishes/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add New Dish
                    </a>
                    <a href="<?= base_url('/admin/delivery-slots/create') ?>" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Add Delivery Slot
                    </a>
                    <a href="<?= base_url('/admin/reports') ?>" class="btn btn-info text-white">
                        <i class="fas fa-chart-bar"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>