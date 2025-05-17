<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Tiffin Booking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #1cc88a;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --sidebar-width: 250px;
        }

        body {
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fc;
        }

        .wrapper {
            width: 100%;
            display: flex;
        }

        /* Sidebar Styles */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            color: #fff;
            transition: all 0.3s;
            height: 100vh;
            position: fixed;
            z-index: 1000;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        #sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        #sidebar .sidebar-header {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        #sidebar .sidebar-header h3 {
            margin: 0;
            font-weight: 800;
            font-size: 1.2rem;
        }

        #sidebar ul.components {
            padding: 1rem 0;
        }

        #sidebar ul li {
            position: relative;
        }

        #sidebar ul li a {
            padding: 0.8rem 1.5rem;
            font-size: 0.85rem;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.2s;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        #sidebar ul li a i {
            margin-right: 0.5rem;
            font-size: 0.9rem;
            width: 1.5rem;
            text-align: center;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: #fff;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            border-left-color: var(--secondary-color);
        }

        /* Content Styles */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            margin-left: var(--sidebar-width);
            background-color: #f8f9fc;
        }

        #content.active {
            margin-left: 0;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.75rem 1.25rem;
            border: none;
        }

        .main-content {
            flex: 1;
            padding: 1.5rem;
            width: 100%;
        }

        /* Card Styles */
        .card {
            margin-bottom: 1.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 0.35rem;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #e3e6f0;
            background-color: #f8f9fc;
        }

        .card-header h5,
        .card-header h6 {
            margin-bottom: 0;
            font-weight: 700;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #17a673;
            border-color: #169b6b;
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-info:hover {
            background-color: #2c9faf;
            border-color: #2a96a5;
        }

        /* Footer Styles */
        footer {
            background-color: #fff;
            padding: 1rem 0;
            text-align: center;
            font-size: 0.85rem;
            color: #858796;
            border-top: 1px solid #e3e6f0;
        }

        /* Stats Cards */
        .stat-card {
            border-left: 0.25rem solid;
            border-radius: 0.35rem;
        }

        .stat-card.primary {
            border-left-color: var(--primary-color);
        }

        .stat-card.success {
            border-left-color: var(--success-color);
        }

        .stat-card.info {
            border-left-color: var(--info-color);
        }

        .stat-card.warning {
            border-left-color: var(--warning-color);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.3;
        }

        /* Chart Containers */
        .chart-container {
            position: relative;
            height: 20rem;
            width: 100%;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
            }

            #content.active {
                margin-left: var(--sidebar-width);
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-utensils me-2"></i>Admin Panel</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="<?= current_url() == base_url('/admin/dashboard') ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/dashboard') ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/dishes') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/dishes') ?>">
                        <i class="fas fa-utensils"></i> Manage Dishes
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/bookings') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/bookings') ?>">
                        <i class="fas fa-calendar-alt"></i> Manage Bookings
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/users') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/users') ?>">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/delivery-slots') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/delivery-slots') ?>">
                        <i class="fas fa-clock"></i> Delivery Slots
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/reviews') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/reviews') ?>">
                        <i class="fas fa-star"></i> Customer Reviews
                    </a>
                </li>
                <li class="<?= strpos(current_url(), '/admin/reports') !== false ? 'active' : '' ?>">
                    <a href="<?= base_url('/admin/reports') ?>">
                        <i class="fas fa-chart-bar"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/logout') ?>">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown me-3">
                            <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    3
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">New booking received</a></li>
                                <li><a class="dropdown-item" href="#">Delivery scheduled for today</a></li>
                                <li><a class="dropdown-item" href="#">Low stock alert</a></li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3 text-end">
                                <div class="fw-bold"><?= session()->get('name') ?? 'Admin' ?></div>
                                <div class="small text-muted">Administrator</div>
                            </div>
                            <a href="<?= base_url('/admin/logout') ?>" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors) && $errors): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>

            <footer>
                <div class="container-fluid">
                    <p>&copy; <?= date('Y') ?> Tiffin Booking System. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Admin JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
</body>

</html>