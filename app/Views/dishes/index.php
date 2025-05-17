<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Menu Header -->
<div class="menu-header py-5 mb-5" style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1596797038530-2c107229654b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1600&q=80') center/cover no-repeat; color: white;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="section-title mb-3"><i class="fas fa-utensils me-2"></i> Our Menu</h1>
                <p class="lead">Explore our delicious range of homemade tiffin dishes</p>
                <div class="mt-4">
                    <span class="badge bg-primary me-2 p-2">Breakfast</span>
                    <span class="badge bg-success me-2 p-2">Lunch</span>
                    <span class="badge bg-info me-2 p-2">Dinner</span>
                    <span class="badge bg-warning me-2 p-2">Snacks</span>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end" data-aos="fade-left">
                <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                    <a href="<?= base_url('/booking/create') ?>" class="btn btn-primary btn-lg px-4">
                        <i class="fas fa-shopping-cart me-2"></i>
                        View Cart
                        <?php
                        $cart = session()->get('cart');
                        if (!empty($cart)):
                            $totalItems = array_sum(array_column($cart, 'quantity'));
                        ?>
                            <span class="badge bg-light text-dark ms-1"><?= $totalItems ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Menu Categories -->
<div class="container mb-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="menu-categories d-flex justify-content-center flex-wrap gap-3 mb-4" data-aos="fade-up">
                <button class="btn btn-outline-primary active px-4 rounded-pill category-filter" data-filter="all">All Items</button>
                <button class="btn btn-outline-primary px-4 rounded-pill category-filter" data-filter="breakfast">Breakfast</button>
                <button class="btn btn-outline-primary px-4 rounded-pill category-filter" data-filter="lunch">Lunch</button>
                <button class="btn btn-outline-primary px-4 rounded-pill category-filter" data-filter="dinner">Dinner</button>
                <button class="btn btn-outline-primary px-4 rounded-pill category-filter" data-filter="snacks">Snacks</button>
            </div>

            <div class="diet-categories d-flex justify-content-center flex-wrap gap-3 mb-4" data-aos="fade-up">
                <button class="btn btn-outline-secondary active px-4 rounded-pill diet-filter" data-filter="all">
                    <i class="fas fa-utensils me-1"></i> All Diets
                </button>
                <button class="btn btn-outline-success px-4 rounded-pill diet-filter" data-filter="veg">
                    <i class="fas fa-leaf me-1"></i> Vegetarian
                </button>
                <button class="btn btn-outline-danger px-4 rounded-pill diet-filter" data-filter="non-veg">
                    <i class="fas fa-drumstick-bite me-1"></i> Non-Vegetarian
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Menu Items -->
<div class="container">
    <?php if (empty($dishes)): ?>
        <div class="alert alert-info" data-aos="fade-up">
            <i class="fas fa-info-circle me-2"></i> No dishes available at the moment.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($dishes as $index => $dish): ?>
                <div class="col-lg-4 col-md-6 mb-4 dish-card-container"
                    data-aos="fade-up"
                    data-aos-delay="<?= ($index % 3) * 100 ?>"
                    data-diet="<?= ($dish['is_vegetarian'] ?? true) ? 'veg' : 'non-veg' ?>">
                    <div class="card dish-card h-100 border-0 shadow-sm">
                        <div class="position-relative">
                            <?php if ($dish['image']): ?>
                                <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" class="card-img-top" alt="<?= $dish['name'] ?>" style="height: 220px; object-fit: cover;">
                            <?php else: ?>
                                <?php
                                // Array of food placeholder images
                                $placeholders = [
                                    'https://images.unsplash.com/photo-1567337710282-00832b415979?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                    'https://images.unsplash.com/photo-1589302168068-964664d93dc0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                    'https://images.unsplash.com/photo-1505253758473-96b7015fcd40?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                    'https://images.unsplash.com/photo-1585937421612-70a008356c36?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Nnx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60',
                                    'https://images.unsplash.com/photo-1631452180519-c014fe946bc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60'
                                ];
                                // Use dish ID to select a consistent placeholder
                                $placeholderIndex = $dish['id'] % count($placeholders);
                                $placeholder = $placeholders[$placeholderIndex];
                                ?>
                                <img src="<?= $placeholder ?>" class="card-img-top" alt="<?= $dish['name'] ?>" style="height: 220px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="position-absolute top-0 start-0 p-3">
                                <?php if ($dish['available']): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2">Available</span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">Sold Out</span>
                                <?php endif; ?>
                            </div>

                            <div class="position-absolute top-0 end-0 p-3">
                                <?php if ($dish['is_vegetarian'] ?? true): ?>
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-leaf me-1"></i> Veg
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill px-3 py-2">
                                        <i class="fas fa-drumstick-bite me-1"></i> Non-Veg
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0"><?= $dish['name'] ?></h5>
                                <span class="price-tag fw-bold">₹<?= number_format($dish['price'], 2) ?></span>
                            </div>
                            <div class="mb-3">
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
                                <small class="text-muted ms-1">(<?= $reviewCount > 0 ? $avgRating : 'No reviews' ?>)</small>
                            </div>
                            <p class="card-text text-muted"><?= substr($dish['description'], 0, 100) . (strlen($dish['description']) > 100 ? '...' : '') ?></p>
                        </div>
                        <div class="card-footer bg-white border-top-0 p-4">
                            <div class="d-flex justify-content-between">
                                <a href="<?= base_url('/dishes/view/' . $dish['id']) ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> Details
                                </a>
                                <?php if (session()->get('logged_in') && !session()->get('is_admin') && $dish['available']): ?>
                                    <a href="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" class="btn btn-success">
                                        <i class="fas fa-plus me-1"></i> Add to Cart
                                    </a>
                                <?php elseif (session()->get('logged_in') && !session()->get('is_admin') && !$dish['available']): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-times me-1"></i> Sold Out
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Diet filter functionality
        const dietFilters = document.querySelectorAll('.diet-filter');
        const dishCards = document.querySelectorAll('.dish-card-container');

        dietFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                dietFilters.forEach(f => f.classList.remove('active'));

                // Add active class to clicked filter
                this.classList.add('active');

                const filterValue = this.getAttribute('data-filter');

                // Show/hide dishes based on filter
                dishCards.forEach(card => {
                    if (filterValue === 'all') {
                        card.style.display = 'block';
                    } else {
                        const dietType = card.getAttribute('data-diet');
                        if (dietType === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    }
                });
            });
        });

        // Category filter functionality
        const categoryFilters = document.querySelectorAll('.category-filter');

        categoryFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                // Remove active class from all filters
                categoryFilters.forEach(f => f.classList.remove('active'));

                // Add active class to clicked filter
                this.classList.add('active');

                // Here you would add category filtering logic
                // This would require adding data-category attributes to the dish cards
            });
        });

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                dishCards.forEach(card => {
                    const dishName = card.querySelector('.card-title').textContent.toLowerCase();
                    const dishDescription = card.querySelector('.card-text').textContent.toLowerCase();

                    if (dishName.includes(searchTerm) || dishDescription.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
<?= $this->endSection() ?>