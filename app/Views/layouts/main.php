<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiffin Booking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        footer {
            margin-top: auto;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
        }
        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-utensils"></i> Tiffin Booking
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/dishes') ?>">Menu</a>
                    </li>
                    <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/booking/create') ?>">Book Tiffin</a>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('is_admin')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('/admin/dashboard') ?>">Admin Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('/admin/logout') ?>">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> <?= session()->get('name') ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?= base_url('/user/dashboard') ?>">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/profile') ?>">Profile</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/wallet') ?>">Wallet</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/bookings') ?>">My Bookings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/auth/logout') ?>">Logout</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/auth/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/auth/register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content container py-4">
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

    <!-- Footer -->
    <footer class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Tiffin Booking System</h5>
                    <p>Delicious homemade food delivered to your doorstep.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/') ?>">Home</a></li>
                        <li><a href="<?= base_url('/dishes') ?>">Menu</a></li>
                        <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                            <li><a href="<?= base_url('/booking/create') ?>">Book Tiffin</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact Us</h5>
                    <address>
                        <i class="fas fa-map-marker-alt"></i> 123 Main Street<br>
                        <i class="fas fa-phone"></i> +1 234 567 8900<br>
                        <i class="fas fa-envelope"></i> info@tiffinbooking.com
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> Tiffin Booking System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
