<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4">Dashboard</h1>

<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Users</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_users ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success me-2"><i class="fas fa-arrow-up"></i> 12%</span>
                            <span>Since last month</span>
                        </div>
                    </div>
                    <div class="stat-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/users') ?>" class="text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Dishes</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_dishes ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success me-2"><i class="fas fa-arrow-up"></i> 8%</span>
                            <span>Since last month</span>
                        </div>
                    </div>
                    <div class="stat-icon text-success">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/dishes') ?>" class="text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Total Bookings</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_bookings ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success me-2"><i class="fas fa-arrow-up"></i> 15%</span>
                            <span>Since last month</span>
                        </div>
                    </div>
                    <div class="stat-icon text-info">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/bookings') ?>" class="text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Bookings</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $pending_bookings ?></div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-danger me-2"><i class="fas fa-arrow-down"></i> 5%</span>
                            <span>Since yesterday</span>
                        </div>
                    </div>
                    <div class="stat-icon text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <a href="<?= base_url('/admin/bookings') ?>" class="text-decoration-none">
                    View Details <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Sales Overview</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="salesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Last 7 Days
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="salesDropdown">
                        <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                        <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Today's Bookings</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="text-center mb-4">
                    <h1 class="display-4"><?= $today_bookings ?></h1>
                    <p class="lead">Bookings for <?= date('F j, Y') ?></p>
                </div>
                <div class="d-grid gap-2 mt-auto">
                    <a href="<?= base_url('/admin/bookings') ?>" class="btn btn-primary">
                        <i class="fas fa-calendar-alt me-2"></i> View All Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Popular Dishes</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="popularDishesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="<?= base_url('/admin/dishes/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i> Add New Dish
                    </a>
                    <a href="<?= base_url('/admin/delivery-slots/create') ?>" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i> Add Delivery Slot
                    </a>
                    <a href="<?= base_url('/admin/reports') ?>" class="btn btn-info text-white">
                        <i class="fas fa-chart-bar me-2"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sales',
                    data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }, {
                    label: 'Orders',
                    data: [15, 25, 20, 30, 25, 35, 32],
                    backgroundColor: 'rgba(28, 200, 138, 0.05)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [<?= $pending_bookings ?>, 15, 35, 5],
                    backgroundColor: [
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(246, 194, 62, 1)',
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });

        // Popular Dishes Chart
        const popularDishesCtx = document.getElementById('popularDishesChart').getContext('2d');
        const popularDishesChart = new Chart(popularDishesCtx, {
            type: 'bar',
            data: {
                labels: ['Dish 1', 'Dish 2', 'Dish 3', 'Dish 4', 'Dish 5'],
                datasets: [{
                    label: 'Orders',
                    data: [45, 37, 30, 27, 22],
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>