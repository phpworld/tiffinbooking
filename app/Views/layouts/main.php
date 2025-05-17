<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiffin Delight | Premium Homemade Food Delivery</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <style>
        :root {
            /* Primary Brand Colors - Green Theme */
            --primary-color: #4CAF50;
            --primary-dark: #388E3C;
            --primary-light: #81C784;

            /* Secondary Colors */
            --secondary-color: #2E7D32;
            --secondary-dark: #1B5E20;
            --secondary-light: #66BB6A;

            /* Accent Colors */
            --accent-color: #FFC107;
            --accent-dark: #FFA000;
            --accent-light: #FFE082;

            /* Neutral Colors */
            --dark-color: #263238;
            --dark-color-2: #37474F;
            --light-color: #FFFFFF;
            --light-color-2: #F5F5F5;
            --gray-color: #757575;

            /* Feedback Colors */
            --success-color: #4CAF50;
            --info-color: #2196F3;
            --warning-color: #FFC107;
            --danger-color: #F44336;

            /* Text Colors */
            --text-color: #2F3E46;
            --text-muted: #6C757D;
            --text-light: #F8F9FA;

            /* UI Elements */
            --border-radius-sm: 6px;
            --border-radius: 10px;
            --border-radius-lg: 16px;
            --border-radius-xl: 24px;
            --border-radius-pill: 50px;

            /* Shadows */
            --box-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            --box-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.12);
            --box-shadow-xl: 0 15px 35px rgba(0, 0, 0, 0.15);

            /* Transitions */
            --transition-fast: all 0.2s ease;
            --transition: all 0.3s ease;
            --transition-slow: all 0.5s ease;

            /* Spacing */
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2.5rem;
            --spacing-xxl: 4rem;
        }

        body {
            padding-top: 80px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            background-color: var(--light-color-2);
            line-height: 1.6;
            font-size: 16px;
            font-weight: 400;
        }

        /* Typography */
        h1, .h1 { font-size: 2.5rem; margin-bottom: var(--spacing-lg); }
        h2, .h2 { font-size: 2rem; margin-bottom: var(--spacing-md); }
        h3, .h3 { font-size: 1.75rem; margin-bottom: var(--spacing-md); }
        h4, .h4 { font-size: 1.5rem; margin-bottom: var(--spacing-sm); }
        h5, .h5 { font-size: 1.25rem; margin-bottom: var(--spacing-sm); }
        h6, .h6 { font-size: 1rem; margin-bottom: var(--spacing-sm); }

        h1, h2, h3, h4, h5, h6,
        .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            line-height: 1.3;
            color: var(--dark-color);
        }

        p {
            margin-bottom: var(--spacing-md);
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition-fast);
        }

        a:hover {
            color: var(--primary-dark);
        }

        .text-primary { color: var(--primary-color) !important; }
        .text-secondary { color: var(--secondary-color) !important; }
        .text-accent { color: var(--accent-color) !important; }
        .text-muted { color: var(--text-muted) !important; }
        .text-dark { color: var(--dark-color) !important; }
        .text-light { color: var(--light-color) !important; }

        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-secondary { background-color: var(--secondary-color) !important; }
        .bg-accent { background-color: var(--accent-color) !important; }
        .bg-dark { background-color: var(--dark-color) !important; }
        .bg-light { background-color: var(--light-color) !important; }
        .bg-light-2 { background-color: var(--light-color-2) !important; }

        .content {
            flex: 1;
        }

        .section-padding {
            padding: var(--spacing-xxl) 0;
        }

        /* Navbar Styles */
        .navbar {
            background-color: white !important;
            box-shadow: var(--box-shadow);
            padding: 15px 0;
            transition: var(--transition);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .navbar.scrolled {
            padding: 10px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            color: var(--primary-color) !important;
            font-size: 1.6rem;
            letter-spacing: -0.5px;
        }

        .navbar-brand i {
            color: var(--secondary-color);
            font-size: 1.4rem;
        }

        .navbar-nav {
            align-items: center;
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--dark-color) !important;
            margin: 0 8px;
            padding: 8px 4px !important;
            position: relative;
            transition: var(--transition-fast);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary-color);
            transition: var(--transition-fast);
            opacity: 0;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
            opacity: 1;
        }

        .navbar .dropdown-menu {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow-lg);
            padding: 1rem 0;
            min-width: 230px;
            margin-top: 15px;
        }

        .navbar .dropdown-item {
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            color: var(--text-color);
            transition: var(--transition-fast);
        }

        .navbar .dropdown-item:hover, .navbar .dropdown-item:focus {
            background-color: rgba(0,0,0,0.03);
            color: var(--primary-color);
        }

        .navbar .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
            color: var(--primary-color);
        }

        /* Mobile Navigation Styles */
        .mobile-cart-icon {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-right: 10px;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-color);
            color: var(--dark-color);
            font-size: 0.7rem;
            font-weight: bold;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .mobile-user-menu {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        .mobile-user-header {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            margin-bottom: 15px;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 8px;
        }

        .mobile-menu-links {
            display: flex;
            flex-direction: column;
        }

        .mobile-menu-links a {
            padding: 10px 15px;
            color: var(--dark-color);
            text-decoration: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .mobile-menu-links a:hover {
            background-color: rgba(0,0,0,0.03);
            padding-left: 20px;
        }

        .mobile-menu-links a.text-danger {
            color: var(--danger-color);
        }

        .mobile-auth-buttons {
            margin-top: 20px;
            padding: 15px;
            border-top: 1px solid rgba(0,0,0,0.1);
        }

        /* Card Styles */
        .card {
            border-radius: var(--border-radius);
            border: none;
            overflow: hidden;
            margin-bottom: var(--spacing-lg);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            background-color: white;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-lg);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
        }

        .card-footer {
            background-color: white;
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem 1.5rem;
        }

        .card-img-top {
            height: 220px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--dark-color);
        }

        .card-text {
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Dish Card Specific */
        .dish-card {
            border-radius: var(--border-radius-lg);
            transition: var(--transition);
        }

        .dish-card .card-img-top {
            border-top-left-radius: var(--border-radius-lg);
            border-top-right-radius: var(--border-radius-lg);
        }

        .dish-card .dish-price {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-pill);
            font-weight: 700;
            box-shadow: var(--box-shadow);
        }

        .dish-card .dish-category {
            position: absolute;
            bottom: 15px;
            left: 15px;
            background-color: rgba(0,0,0,0.6);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: var(--border-radius-pill);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Button Styles - Square corners */
        .btn {
            border-radius: var(--border-radius);
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: var(--transition);
            text-transform: capitalize;
            letter-spacing: 0.3px;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .btn i {
            margin-right: 6px;
        }

        .btn-sm {
            padding: 0.4rem 1.2rem;
            font-size: 0.875rem;
        }

        .btn-lg {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover, .btn-secondary:focus {
            background-color: var(--secondary-dark);
            border-color: var(--secondary-dark);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .btn-success:hover, .btn-success:focus {
            background-color: var(--success-color);
            border-color: var(--success-color);
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            background-color: transparent;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        .btn-outline-secondary {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
            background-color: transparent;
        }

        .btn-outline-secondary:hover, .btn-outline-secondary:focus {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-3px);
            box-shadow: var(--box-shadow);
        }

        /* Section Styles */
        .section-title {
            position: relative;
            margin-bottom: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -12px;
            width: 60px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .section-title.text-center::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .section-subtitle {
            color: var(--primary-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 2px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            display: block;
        }

        .section-description {
            font-size: 1.1rem;
            color: var(--text-muted);
            max-width: 800px;
            margin: 0 auto var(--spacing-xl);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: var(--spacing-lg);
        }

        .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-xl);
            color: rgba(255,255,255,0.9);
        }

        /* Features Section */
        .feature-box {
            padding: var(--spacing-lg);
            border-radius: var(--border-radius-lg);
            background-color: white;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 100%;
            text-align: center;
        }

        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: var(--box-shadow-lg);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--spacing-md);
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--light-color-2);
            color: var(--primary-color);
            border-radius: 50%;
            font-size: 2rem;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: var(--spacing-xxl) 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiB2aWV3Qm94PSIwIDAgMTI4MCAxNDAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI2ZmZmZmZiI+PHBhdGggZD0iTTEyODAgMy40QzEwNTAuNTkgMTggMTAxOS40IDg0Ljg5IDczNC40MiA4NC44OWMtMzIwIDAtMzIwLTg0LjMtNjQwLTg0LjNDNTkuNC41OSAyOC4yIDEuNiAwIDMuNFYxNDBoMTI4MHoiIGZpbGwtb3BhY2l0eT0iLjMiLz48cGF0aCBkPSJNMCAyNC4zMWM0My40Ni01LjY5IDk0LjU2LTkuMjUgMTU4LjQyLTkuMjUgMzIwIDAgMzIwIDg5LjI0IDY0MCA4OS4yNCAyNTYuMTMgMCAzMDcuMjgtNTcuMTYgNDgxLjU4LTgwVjE0MEgweiIgZmlsbC1vcGFjaXR5PSIuNSIvPjxwYXRoIGQ9Ik0xMjgwIDUxLjc2Yy0yMDEgMTIuNDktMjQyLjQzIDUzLjQtNTEzLjU4IDUzLjQtMzIwIDAtMzIwLTU3LTY0MC01Ny00OC44NS4wMS05MC4yMSAxLjM1LTEyNi40MiAzLjZWMTQwaDEyODB6Ii8+PC9nPjwvc3ZnPg==');
            background-size: 100% 100%;
            z-index: 1;
            opacity: 0.1;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
        }

        .cta-text {
            font-size: 1.2rem;
            margin-bottom: var(--spacing-lg);
            opacity: 0.9;
        }

        /* Footer Styles */
        footer {
            margin-top: auto;
            padding: 80px 0 20px;
            background-color: var(--dark-color);
            color: rgba(255, 255, 255, 0.8);
            position: relative;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        footer h5 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            position: relative;
            display: inline-block;
        }

        footer h5::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
        }

        footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: var(--transition-fast);
            display: inline-block;
        }

        footer a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        footer .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            margin-right: 10px;
            transition: var(--transition);
        }

        footer .social-links a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }

        footer .contact-info li {
            margin-bottom: 15px;
        }

        footer .contact-info i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }

        footer .footer-bottom {
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Animation Classes */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .fade-up.active {
            opacity: 1;
            transform: translateY(0);
        }

        .fade-in {
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .fade-in.active {
            opacity: 1;
        }

        .slide-in-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .slide-in-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-in-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .slide-in-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        /* Form Styles */
        .form-control {
            padding: 0.75rem 1.25rem;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0,0,0,0.1);
            font-size: 1rem;
            transition: var(--transition-fast);
            box-shadow: none;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .form-text {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .input-group-text {
            background-color: var(--light-color-2);
            border: 1px solid rgba(0,0,0,0.1);
            color: var(--text-muted);
        }

        /* Table Styles */
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .table th {
            font-weight: 600;
            color: var(--dark-color);
            background-color: var(--light-color-2);
            border-bottom: 2px solid rgba(0,0,0,0.05);
        }

        .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0,0,0,0.03);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(67, 170, 139, 0.1);
            color: var(--success-color);
        }

        .alert-info {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--info-color);
        }

        .alert-warning {
            background-color: rgba(249, 199, 79, 0.1);
            color: var(--warning-color);
        }

        .alert-danger {
            background-color: rgba(230, 57, 70, 0.1);
            color: var(--danger-color);
        }

        /* Badge Styles */
        .badge {
            padding: 0.4em 0.8em;
            font-weight: 600;
            border-radius: var(--border-radius-pill);
        }

        /* Fixed Cart Button */
        .fixed-cart {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .fixed-cart .btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            position: relative;
        }

        .fixed-cart .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--accent-color);
            color: var(--dark-color);
            font-size: 0.75rem;
            font-weight: bold;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Utility Classes */
        .shadow-sm { box-shadow: var(--box-shadow-sm) !important; }
        .shadow { box-shadow: var(--box-shadow) !important; }
        .shadow-lg { box-shadow: var(--box-shadow-lg) !important; }
        .shadow-xl { box-shadow: var(--box-shadow-xl) !important; }

        .rounded-sm { border-radius: var(--border-radius-sm) !important; }
        .rounded { border-radius: var(--border-radius) !important; }
        .rounded-lg { border-radius: var(--border-radius-lg) !important; }
        .rounded-xl { border-radius: var(--border-radius-xl) !important; }

        .border-primary { border-color: var(--primary-color) !important; }
        .border-secondary { border-color: var(--secondary-color) !important; }
        .border-accent { border-color: var(--accent-color) !important; }

        .font-weight-medium { font-weight: 500 !important; }
        .font-weight-semibold { font-weight: 600 !important; }
        .font-weight-bold { font-weight: 700 !important; }
        .font-weight-extrabold { font-weight: 800 !important; }

        /* Responsive Styles */
        @media (max-width: 1199.98px) {
            .hero-title {
                font-size: 3rem;
            }

            .cta-title {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 991.98px) {
            h1, .h1 { font-size: 2.25rem; }
            h2, .h2 { font-size: 1.75rem; }
            h3, .h3 { font-size: 1.5rem; }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .section-padding {
                padding: var(--spacing-xl) 0;
            }

            .cta-title {
                font-size: 2rem;
            }

            .feature-icon {
                width: 70px;
                height: 70px;
                font-size: 1.75rem;
            }
        }

        @media (max-width: 767.98px) {
            body {
                padding-top: 70px;
            }

            .navbar {
                padding: 10px 0;
            }

            .navbar-brand {
                font-size: 1.3rem;
            }

            .navbar-collapse {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                padding: 15px;
                margin-top: 15px;
                max-height: 80vh;
                overflow-y: auto;
            }

            .navbar-toggler {
                border: none;
                padding: 0;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: rgba(76, 175, 80, 0.1);
                border-radius: 8px;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            .nav-link {
                padding: 12px 15px !important;
                margin: 0;
                border-bottom: 1px solid rgba(0,0,0,0.05);
                border-radius: 8px;
            }

            .nav-link:hover {
                background-color: rgba(76, 175, 80, 0.05);
            }

            .nav-link::after {
                display: none;
            }

            .nav-item {
                margin: 5px 0;
            }

            .nav-link i {
                width: 24px;
                text-align: center;
                margin-right: 10px;
                color: var(--primary-color);
            }

            .card-img-top {
                height: 180px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .hero-title {
                font-size: 2rem;
            }

            .fixed-cart {
                bottom: 20px;
                right: 20px;
            }

            .fixed-cart .btn {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .cta-title {
                font-size: 1.75rem;
            }

            .cta-text {
                font-size: 1rem;
            }

            footer {
                padding: 50px 0 20px;
            }
        }

        @media (max-width: 575.98px) {
            h1, .h1 { font-size: 2rem; }
            h2, .h2 { font-size: 1.6rem; }
            h3, .h3 { font-size: 1.4rem; }
            h4, .h4 { font-size: 1.3rem; }

            .btn-lg {
                padding: 0.6rem 1.5rem;
                font-size: 1rem;
            }

            .section-description {
                font-size: 1rem;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .mobile-cart-icon {
                margin-right: 5px;
            }

            .navbar-collapse {
                margin-top: 10px;
            }

            .menu-card .row {
                flex-direction: column;
            }

            .menu-card .col-4 {
                width: 100%;
            }

            .menu-card .col-8 {
                width: 100%;
            }

            .menu-img-container {
                height: 180px;
                border-radius: 8px 8px 0 0;
            }

            .menu-content {
                padding-top: 10px;
            }

            .fixed-cart {
                bottom: 15px;
                right: 15px;
            }

            .fixed-cart .btn {
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-utensils me-2"></i> Tiffin Delight
            </a>

            <!-- Mobile Cart Icon (Only visible on mobile) -->
            <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                <?php
                $cart = session()->get('cart');
                $totalItems = 0;
                if (!empty($cart)) {
                    $totalItems = array_sum(array_column($cart, 'quantity'));
                }
                ?>
                <a class="mobile-cart-icon d-lg-none" href="<?= base_url('/booking/create') ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($totalItems > 0): ?>
                        <span class="cart-badge"><?= $totalItems ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() == base_url('/') ? 'active' : '' ?>" href="<?= base_url('/') ?>">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), '/dishes') !== false ? 'active' : '' ?>" href="<?= base_url('/dishes') ?>">
                            <i class="fas fa-utensils me-1"></i> Menu
                        </a>
                    </li>
                    <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= strpos(current_url(), '/booking/create') !== false ? 'active' : '' ?>" href="<?= base_url('/booking/create') ?>">
                                <i class="fas fa-shopping-cart me-1"></i> Book Tiffin
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="fas fa-info-circle me-1"></i> About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                </ul>

                <!-- Mobile User Menu (Only visible on mobile) -->
                <?php if (session()->get('logged_in')): ?>
                    <div class="mobile-user-menu d-lg-none">
                        <?php if (session()->get('is_admin')): ?>
                            <div class="mobile-user-header">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                <div class="ms-2">
                                    <h6 class="mb-0"><?= session()->get('name') ?></h6>
                                    <small class="text-muted">Administrator</small>
                                </div>
                            </div>
                            <div class="mobile-menu-links">
                                <a href="<?= base_url('/admin/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                                </a>
                                <a href="<?= base_url('/admin/logout') ?>" class="text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="mobile-user-header">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                                <div class="ms-2">
                                    <h6 class="mb-0"><?= session()->get('name') ?></h6>
                                    <small class="text-muted"><?= session()->get('email') ?></small>
                                </div>
                            </div>
                            <div class="mobile-menu-links">
                                <a href="<?= base_url('/user/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                                <a href="<?= base_url('/user/profile') ?>">
                                    <i class="fas fa-user-edit me-2"></i> Profile
                                </a>
                                <a href="<?= base_url('/user/wallet') ?>">
                                    <i class="fas fa-wallet me-2"></i> Wallet
                                </a>
                                <a href="<?= base_url('/user/bookings') ?>">
                                    <i class="fas fa-list-alt me-2"></i> My Bookings
                                </a>
                                <a href="<?= base_url('/auth/logout') ?>" class="text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="mobile-auth-buttons d-lg-none">
                        <a class="btn btn-success w-100 mb-2" href="<?= base_url('/auth/login') ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a class="btn btn-primary w-100" href="<?= base_url('/auth/register') ?>">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Desktop User Menu (Only visible on desktop) -->
                <div class="d-none d-lg-flex align-items-center">
                    <?php if (session()->get('logged_in')): ?>
                        <?php if (session()->get('is_admin')): ?>
                            <a class="btn btn-sm btn-outline-primary me-2" href="<?= base_url('/admin/dashboard') ?>">
                                <i class="fas fa-tachometer-alt me-1"></i> Admin Dashboard
                            </a>
                            <a class="btn btn-sm btn-outline-danger" href="<?= base_url('/admin/logout') ?>">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </a>
                        <?php else: ?>
                            <?php if ($totalItems > 0): ?>
                                <a class="btn btn-sm btn-primary position-relative me-3" href="<?= base_url('/booking/create') ?>">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-accent text-dark">
                                        <?= $totalItems ?>
                                        <span class="visually-hidden">items in cart</span>
                                    </span>
                                </a>
                            <?php endif; ?>
                            <div class="dropdown">
                                <a class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-2"></i>
                                    <span class="d-none d-md-inline"><?= session()->get('name') ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                    <li>
                                        <div class="dropdown-item text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-user-circle fa-3x text-primary"></i>
                                            </div>
                                            <h6 class="mb-0"><?= session()->get('name') ?></h6>
                                            <small class="text-muted"><?= session()->get('email') ?></small>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/dashboard') ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/profile') ?>"><i class="fas fa-user-edit me-2"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/wallet') ?>"><i class="fas fa-wallet me-2"></i> Wallet</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('/user/bookings') ?>"><i class="fas fa-list-alt me-2"></i> My Bookings</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?= base_url('/auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="btn btn-sm btn-outline-primary me-2" href="<?= base_url('/auth/login') ?>">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a class="btn btn-sm btn-primary" href="<?= base_url('/auth/register') ?>">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="content">
        <div class="container py-4">
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
    </div>

    <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
    <!-- Fixed Cart Button -->
    <div class="fixed-cart">
        <a href="<?= base_url('/booking/create') ?>" class="btn btn-success">
            <i class="fas fa-shopping-cart"></i>
            <?php
            $cart = session()->get('cart');
            if (!empty($cart)):
                $totalItems = array_sum(array_column($cart, 'quantity'));
            ?>
                <span class="cart-count"><?= $totalItems ?></span>
            <?php endif; ?>
        </a>
    </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer id="contact">
        <div class="container">
            <div class="row py-5">
                <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <a href="<?= base_url('/') ?>" class="d-inline-block">
                            <h5><i class="fas fa-utensils me-2"></i> Tiffin Delight</h5>
                        </a>
                    </div>
                    <p class="mb-4">Delicious homemade food delivered to your doorstep. We use fresh ingredients and authentic recipes to bring you the best tiffin experience.</p>
                    <div class="social-links mb-4">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="newsletter">
                        <h6 class="mb-3">Subscribe to our newsletter</h6>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your email address" aria-label="Your email address">
                            <button class="btn btn-primary" type="button">Subscribe</button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/') ?>"><i class="fas fa-angle-right me-2"></i>Home</a></li>
                        <li><a href="<?= base_url('/dishes') ?>"><i class="fas fa-angle-right me-2"></i>Menu</a></li>
                        <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                            <li><a href="<?= base_url('/booking/create') ?>"><i class="fas fa-angle-right me-2"></i>Book Tiffin</a></li>
                        <?php endif; ?>
                        <li><a href="#about"><i class="fas fa-angle-right me-2"></i>About Us</a></li>
                        <li><a href="#contact"><i class="fas fa-angle-right me-2"></i>Contact</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>FAQ</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-angle-right me-2"></i>Terms of Service</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                123 Main Street<br>
                                New Delhi, 110001
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <div>
                                <a href="tel:+919876543210">+91 98765 43210</a>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <div>
                                <a href="mailto:info@tiffindelight.com">info@tiffindelight.com</a>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <div>
                                Mon-Fri: 8:00 AM - 8:00 PM<br>
                                Sat: 9:00 AM - 7:00 PM<br>
                                Sun: 10:00 AM - 6:00 PM
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5>Download Our App</h5>
                    <p class="mb-3">Order on the go with our mobile app</p>
                    <div class="app-buttons mb-4">
                        <a href="#" class="btn btn-outline-light mb-2 d-flex align-items-center">
                            <i class="fab fa-apple fs-4 me-2"></i>
                            <div class="text-start">
                                <small class="d-block" style="font-size: 0.7rem;">Download on the</small>
                                <span>App Store</span>
                            </div>
                        </a>
                        <a href="#" class="btn btn-outline-light d-flex align-items-center">
                            <i class="fab fa-google-play fs-4 me-2"></i>
                            <div class="text-start">
                                <small class="d-block" style="font-size: 0.7rem;">GET IT ON</small>
                                <span>Google Play</span>
                            </div>
                        </a>
                    </div>
                    <div>
                        <h6 class="mb-3">Payment Methods</h6>
                        <div class="payment-methods">
                            <i class="fab fa-cc-visa me-2 fs-4"></i>
                            <i class="fab fa-cc-mastercard me-2 fs-4"></i>
                            <i class="fab fa-cc-paypal me-2 fs-4"></i>
                            <i class="fab fa-cc-apple-pay fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom py-4 mt-4 border-top border-secondary">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0">&copy; <?= date('Y') ?> Tiffin Delight. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i> for good food</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Initialize AOS animation library
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
            offset: 50
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Add animation classes to elements
            const animationElements = document.querySelectorAll('.fade-up, .fade-in, .slide-in-left, .slide-in-right');
            animationElements.forEach(function(element) {
                element.classList.add('active');
            });

            // Navbar scroll effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        const navbarHeight = document.querySelector('.navbar').offsetHeight;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Initialize banner slider
            if (document.querySelector('.banner-slider')) {
                new Swiper('.banner-slider', {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    }
                });
            }

            // Initialize testimonial swiper
            if (document.querySelector('.testimonial-swiper')) {
                new Swiper('.testimonial-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                        },
                        1024: {
                            slidesPerView: 3,
                        },
                    }
                });
            }

            // Quantity input handlers
            const quantityInputs = document.querySelectorAll('.quantity-input');
            if (quantityInputs.length > 0) {
                quantityInputs.forEach(input => {
                    const decrementBtn = input.parentElement.querySelector('.decrement-btn');
                    const incrementBtn = input.parentElement.querySelector('.increment-btn');

                    decrementBtn.addEventListener('click', () => {
                        const currentValue = parseInt(input.value);
                        if (currentValue > parseInt(input.min)) {
                            input.value = currentValue - 1;
                            // Trigger change event
                            input.dispatchEvent(new Event('change'));
                        }
                    });

                    incrementBtn.addEventListener('click', () => {
                        const currentValue = parseInt(input.value);
                        if (currentValue < parseInt(input.max)) {
                            input.value = currentValue + 1;
                            // Trigger change event
                            input.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>