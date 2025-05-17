<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Banner Slider Section -->
<div class="banner-slider swiper mb-5">
    <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide">
            <div class="banner-slide position-relative">
                <img src="https://images.unsplash.com/photo-1596797038530-2c107229654b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" class="w-100" alt="Tiffin Service" style="height: 450px; object-fit: cover; filter: brightness(0.6);">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center text-center text-white p-4">
                    <div class="container banner-content">
                        <h1 class="display-4 fw-bold mb-3">
                            Delicious <span class="text-accent">Homemade</span> <span class="text-primary">Tiffin</span>
                        </h1>
                        <p class="lead mx-auto mb-4" style="max-width: 600px;">
                            Fresh, healthy meals delivered to your doorstep
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= base_url('/dishes') ?>" class="btn btn-primary px-4">
                                <i class="fas fa-utensils me-2"></i> View Menu
                            </a>
                            <?php if (!session()->get('logged_in')): ?>
                                <a href="<?= base_url('/auth/register') ?>" class="btn btn-light px-4">
                                    <i class="fas fa-user-plus me-2"></i> Sign Up
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('/booking/create') ?>" class="btn btn-success px-4">
                                    <i class="fas fa-shopping-cart me-2"></i> Order Now
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="swiper-slide">
            <div class="banner-slide position-relative">
                <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" class="w-100" alt="Tiffin Service" style="height: 450px; object-fit: cover; filter: brightness(0.6);">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center text-center text-white p-4">
                    <div class="container banner-content">
                        <h1 class="display-4 fw-bold mb-3">
                            Healthy <span class="text-accent">Nutritious</span> <span class="text-primary">Meals</span>
                        </h1>
                        <p class="lead mx-auto mb-4" style="max-width: 600px;">
                            Prepared with love using fresh ingredients
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= base_url('/dishes') ?>" class="btn btn-primary px-4">
                                <i class="fas fa-utensils me-2"></i> Explore Menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="swiper-slide">
            <div class="banner-slide position-relative">
                <img src="https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80" class="w-100" alt="Tiffin Service" style="height: 450px; object-fit: cover; filter: brightness(0.6);">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center text-center text-white p-4">
                    <div class="container banner-content">
                        <h1 class="display-4 fw-bold mb-3">
                            Fast <span class="text-accent">Reliable</span> <span class="text-primary">Delivery</span>
                        </h1>
                        <p class="lead mx-auto mb-4" style="max-width: 600px;">
                            Hot meals delivered right to your doorstep
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <?php if (!session()->get('logged_in')): ?>
                                <a href="<?= base_url('/auth/register') ?>" class="btn btn-primary px-4">
                                    <i class="fas fa-user-plus me-2"></i> Join Now
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('/booking/create') ?>" class="btn btn-success px-4">
                                    <i class="fas fa-shopping-cart me-2"></i> Order Now
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="swiper-pagination"></div>

    <!-- Navigation buttons -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
</div>

<!-- Features Section -->
<div class="features-section py-4 bg-light-2">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-item d-flex align-items-center">
                    <div class="feature-icon-circle bg-primary-light text-primary me-3">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div>
                        <h5 class="feature-title">Fresh Ingredients</h5>
                        <p class="feature-text mb-0">Quality ingredients daily</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item d-flex align-items-center">
                    <div class="feature-icon-circle bg-success-light text-success me-3">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h5 class="feature-title">Fast Delivery</h5>
                        <p class="feature-text mb-0">Right to your doorstep</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item d-flex align-items-center">
                    <div class="feature-icon-circle bg-accent-light text-accent me-3">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h5 class="feature-title">Easy Payment</h5>
                        <p class="feature-text mb-0">Multiple payment options</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .features-section {
        margin-top: -20px;
        position: relative;
        z-index: 10;
    }

    .feature-icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .feature-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--dark-color);
    }

    .feature-text {
        font-size: 0.9rem;
        color: var(--gray-color);
    }

    .bg-success-light {
        background-color: rgba(76, 175, 80, 0.15);
    }
</style>

<!-- About Us Section -->
<section id="about" class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">About Us</h2>
                <div class="d-flex justify-content-center">
                    <div class="divider-green"></div>
                </div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-md-5 mb-4 mb-md-0">
                <div class="about-img-container">
                    <img src="https://images.unsplash.com/photo-1605197161470-5d2a9af0ac7e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" class="img-fluid" alt="About Us">
                </div>
            </div>
            <div class="col-md-7">
                <h4 class="mb-3 text-primary">Serving Delicious Meals Since 2012</h4>
                <p class="mb-3">At Tiffin Delight, we believe that good food is the foundation of a healthy life. Our chefs prepare each meal with fresh ingredients, authentic recipes, and a passion for culinary excellence.</p>

                <div class="row mt-4">
                    <div class="col-md-6 mb-2">
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Quality Ingredients</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Hygienic Preparation</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Authentic Recipes</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="about-feature">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Customizable Options</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .about-img-container {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
    }

    .about-img-container:before {
        content: '';
        position: absolute;
        top: 20px;
        left: 20px;
        right: 20px;
        bottom: 20px;
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        z-index: -1;
    }

    .about-img-container img {
        border-radius: 8px;
        transform: translateY(-10px) translateX(-10px);
        transition: all 0.3s ease;
    }

    .about-img-container:hover img {
        transform: translateY(0) translateX(0);
    }

    .about-feature {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .about-feature i {
        margin-right: 10px;
        font-size: 1.1rem;
    }
</style>

<!-- Menu Items Section -->
<section class="menu-items py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Popular Menu Items</h2>
                <div class="d-flex justify-content-center">
                    <div class="divider-green"></div>
                </div>
            </div>
        </div>

        <?php if (empty($featured_dishes)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No dishes available at the moment.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($featured_dishes as $index => $dish): ?>
                    <div class="col-md-6 mb-4">
                        <div class="menu-card">
                            <div class="row g-0">
                                <div class="col-4">
                                    <div class="menu-img-container">
                                        <?php if ($dish['image']): ?>
                                            <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" alt="<?= $dish['name'] ?>">
                                        <?php else: ?>
                                            <?php
                                            // Array of food placeholder images
                                            $placeholders = [
                                                'https://images.unsplash.com/photo-1567337710282-00832b415979?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                                'https://images.unsplash.com/photo-1589302168068-964664d93dc0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                                'https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60'
                                            ];
                                            // Use dish ID to select a consistent placeholder
                                            $placeholderIndex = $dish['id'] % count($placeholders);
                                            $placeholder = $placeholders[$placeholderIndex];
                                            ?>
                                            <img src="<?= $placeholder ?>" alt="<?= $dish['name'] ?>">
                                        <?php endif; ?>
                                        <?php if ($dish['available']): ?>
                                            <div class="menu-badge">Available</div>
                                        <?php else: ?>
                                            <div class="menu-badge sold-out">Sold Out</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="menu-content">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="menu-title">
                                                <?= $dish['name'] ?>
                                                <?php if (isset($dish['is_vegetarian'])): ?>
                                                    <?php if ($dish['is_vegetarian']): ?>
                                                        <span class="badge bg-success rounded-pill ms-2" style="font-size: 0.7rem;">
                                                            <i class="fas fa-leaf"></i> Veg
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.7rem;">
                                                            <i class="fas fa-drumstick-bite"></i> Non-Veg
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </h5>
                                            <span class="menu-price">₹<?= number_format($dish['price'], 2) ?></span>
                                        </div>
                                        <div class="menu-rating mb-2">
                                            <?php
                                            // Get reviews for this dish
                                            $db = \Config\Database::connect();
                                            $builder = $db->table('reviews');
                                            $builder->select('AVG(rating) as avg_rating, COUNT(*) as review_count');
                                            $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
                                            $builder->where('booking_items.dish_id', $dish['id']);
                                            $result = $builder->get()->getRowArray();

                                            $avgRating = round($result['avg_rating'] ?? 0, 1);
                                            $reviewCount = $result['review_count'] ?? 0;

                                            // Display stars based on average rating
                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= floor($avgRating)): ?>
                                                    <i class="fas fa-star text-warning"></i>
                                                <?php elseif ($i - 0.5 <= $avgRating): ?>
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star text-warning"></i>
                                                <?php endif;
                                            endfor; ?>
                                            <small>(<?= $reviewCount > 0 ? $avgRating : 'No reviews' ?>)</small>
                                        </div>
                                        <p class="menu-desc"><?= substr($dish['description'], 0, 60) . (strlen($dish['description']) > 60 ? '...' : '') ?></p>
                                        <div class="menu-actions">
                                            <?php if (session()->get('logged_in') && !session()->get('is_admin') && $dish['available']): ?>
                                                <a href="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" class="btn btn-success btn-sm">
                                                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                                </a>
                                            <?php elseif (!$dish['available']): ?>
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-times me-1"></i> Sold Out
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?= base_url('/dishes/view/' . $dish['id']) ?>" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-eye"></i> Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a href="<?= base_url('/dishes') ?>" class="btn btn-primary">
                    View All Menu Items <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .divider-green {
        height: 3px;
        width: 80px;
        background-color: var(--primary-color);
        margin-bottom: 20px;
    }

    .menu-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
    }

    .menu-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }

    .menu-img-container {
        position: relative;
        height: 150px;
        overflow: hidden;
    }

    .menu-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .menu-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: var(--success-color);
        color: white;
        font-size: 0.7rem;
        font-weight: bold;
        padding: 3px 8px;
        border-radius: 4px;
    }

    .menu-badge.sold-out {
        background-color: var(--danger-color);
    }

    .menu-content {
        padding: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .menu-title {
        font-weight: 600;
        margin-bottom: 0;
        color: var(--dark-color);
    }

    .menu-price {
        font-weight: bold;
        color: var(--primary-color);
        background-color: rgba(76, 175, 80, 0.1);
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .menu-rating {
        color: var(--warning-color);
        font-size: 0.9rem;
    }

    .menu-rating small {
        color: var(--gray-color);
        margin-left: 5px;
    }

    .menu-desc {
        color: var(--gray-color);
        font-size: 0.85rem;
        margin-bottom: 15px;
    }

    .menu-actions {
        margin-top: auto;
        display: flex;
        gap: 8px;
    }
</style>



<!-- Customer Reviews Section -->
<section class="customer-reviews py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Customer Reviews</h2>
                <p class="text-muted">What our customers say about us</p>
            </div>
        </div>

        <div class="row">
            <?php if (empty($reviews)): ?>
                <!-- Default reviews if no reviews in database -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-3">
                                        P
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Priya Sharma</h6>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> Jan 15, 2023
                                        </div>
                                    </div>
                                </div>
                                <div class="rating-stars">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                            <p class="mb-0">"The food is absolutely delicious and reminds me of home-cooked meals. The delivery is always on time and the staff is very friendly."</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-success text-white me-3">
                                        R
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Rahul Verma</h6>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> Feb 20, 2023
                                        </div>
                                    </div>
                                </div>
                                <div class="rating-stars">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                </div>
                            </div>
                            <p class="mb-0">"I've been ordering from Tiffin Delight for the past 3 months and I'm extremely satisfied with their service. The food is fresh, tasty, and reasonably priced."</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-danger text-white me-3">
                                        A
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Ananya Patel</h6>
                                        <div class="text-muted small">
                                            <i class="far fa-calendar-alt me-1"></i> Mar 5, 2023
                                        </div>
                                    </div>
                                </div>
                                <div class="rating-stars">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                            <p class="mb-0">"As a busy professional, Tiffin Delight has been a lifesaver. The food is nutritious and delicious, and the wallet system makes payments so convenient!"</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Display actual reviews from database -->
                <?php foreach ($reviews as $review): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-primary text-white me-3">
                                            <?= substr($review['user_name'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-1"><?= $review['user_name'] ?></h6>
                                            <div class="text-muted small">
                                                <i class="far fa-calendar-alt me-1"></i> <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review['rating']): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php if (!empty($review['comment'])): ?>
                                    <p class="mb-0"><?= nl2br($review['comment']) ?></p>
                                <?php else: ?>
                                    <p class="text-muted mb-0">Great food and service!</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <style>
            .avatar-circle {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 1.2rem;
            }
        </style>

        <div class="text-center mt-3">
            <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                <a href="<?= base_url('/user/bookings') ?>" class="btn btn-primary">
                    <i class="fas fa-star me-2"></i> Leave Your Review
                </a>
            <?php else: ?>
                <a href="<?= base_url('/auth/register') ?>" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Join Our Happy Customers
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="cta-section py-5">
    <div class="container">
        <div class="cta-box">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="cta-title">Ready to Order Your Tiffin?</h2>
                    <p class="cta-text mb-0">Get delicious, homemade food delivered to your doorstep today!</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <?php if (!session()->get('logged_in')): ?>
                        <a href="<?= base_url('/auth/register') ?>" class="btn btn-light me-2">
                            <i class="fas fa-user-plus me-2"></i> Register Now
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/booking/create') ?>" class="btn btn-light">
                            <i class="fas fa-shopping-cart me-2"></i> Order Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .cta-section {
        background-color: var(--primary-color);
    }

    .cta-box {
        padding: 20px;
        border-radius: 8px;
        position: relative;
        overflow: hidden;
    }

    .cta-box:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60') center/cover;
        opacity: 0.1;
        z-index: 0;
    }

    .cta-title {
        color: white;
        font-weight: 600;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .cta-text {
        color: rgba(255, 255, 255, 0.9);
        position: relative;
        z-index: 1;
    }

    .cta-section .btn {
        position: relative;
        z-index: 1;
    }
</style>
<?= $this->endSection() ?>