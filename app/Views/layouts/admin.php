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
    <style>
        body {
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .wrapper {
            width: 100%;
            display: flex;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
            height: 100vh;
            position: fixed;
            z-index: 1000;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #212529;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul p {
            color: #fff;
            padding: 10px;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            color: #fff;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            color: #343a40;
            background: #fff;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: #28a745;
        }

        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
        }

        #content.active {
            margin-left: 0;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            flex: 1;
            padding: 20px;
            width: 100%;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
            }

            #content.active {
                margin-left: 250px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="<?= base_url('/admin/dashboard') ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/dishes') ?>">
                        <i class="fas fa-utensils me-2"></i> Manage Dishes
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/bookings') ?>">
                        <i class="fas fa-calendar-alt me-2"></i> Manage Bookings
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/users') ?>">
                        <i class="fas fa-users me-2"></i> Manage Users
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/delivery-slots') ?>">
                        <i class="fas fa-clock me-2"></i> Delivery Slots
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/reports') ?>">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('/admin/logout') ?>">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
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
                    <div class="ms-auto">
                        <span class="me-3">Welcome, <?= session()->get('name') ?></span>
                        <a href="<?= base_url('/admin/logout') ?>" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </nav>

            <div class="main-content">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });
        });
    </script>
</body>

</html>