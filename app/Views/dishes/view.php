<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="mb-4" data-aos="fade-up">
        <a href="<?= base_url('/dishes') ?>" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Back to Menu
        </a>
    </div>

    <div class="card border-0 shadow-lg overflow-hidden" data-aos="fade-up">
        <div class="row g-0">
            <div class="col-lg-5">
                <?php if ($dish['image']): ?>
                    <img src="<?= base_url('/uploads/dishes/' . $dish['image']) ?>" class="img-fluid h-100" alt="<?= $dish['name'] ?>" style="object-fit: cover;">
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
                    <img src="<?= $placeholder ?>" class="img-fluid h-100" alt="<?= $dish['name'] ?>" style="object-fit: cover;">
                <?php endif; ?>
            </div>
            <div class="col-lg-7">
                <div class="card-body p-4 p-lg-5">
                    <div class="mb-3 d-flex gap-2">
                        <?php if ($dish['available']): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2 mb-3">Available</span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">Sold Out</span>
                        <?php endif; ?>

                        <?php if ($dish['is_vegetarian'] ?? true): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2 mb-3">
                                <i class="fas fa-leaf me-1"></i> Vegetarian
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger rounded-pill px-3 py-2 mb-3">
                                <i class="fas fa-drumstick-bite me-1"></i> Non-Vegetarian
                            </span>
                        <?php endif; ?>
                    </div>

                    <h1 class="card-title display-5 mb-3"><?= $dish['name'] ?></h1>

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
                        <span class="text-muted ms-2">(<?= $reviewCount > 0 ? $avgRating : 'No reviews' ?>)</span>
                    </div>

                    <p class="card-text text-primary fw-bold fs-3 mb-4">₹<?= number_format($dish['price'], 2) ?></p>

                    <div class="mb-4">
                        <h5 class="mb-3">Description</h5>
                        <p class="card-text fs-5"><?= nl2br($dish['description']) ?></p>
                    </div>

                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-leaf fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Fresh Ingredients</h6>
                                        <p class="mb-0 small text-muted">Locally sourced</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-fire fa-2x text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Freshly Prepared</h6>
                                        <p class="mb-0 small text-muted">Cooked on order</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (session()->get('logged_in') && !session()->get('is_admin') && $dish['available']): ?>
                        <form action="<?= base_url('/booking/add-to-cart/' . $dish['id']) ?>" method="get">
                            <div class="row align-items-end">
                                <div class="col-md-4 mb-3">
                                    <label for="quantity" class="form-label fw-bold">Quantity</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary" onclick="decrementQuantity()">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="number" class="form-control text-center" id="quantity" name="quantity" min="1" value="1">
                                        <button type="button" class="btn btn-outline-secondary" onclick="incrementQuantity()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                    </button>
                                    <a href="<?= base_url('/booking/create') ?>" class="btn btn-primary btn-lg ms-2 px-4">
                                        <i class="fas fa-shopping-cart me-2"></i> View Cart
                                    </a>
                                </div>
                            </div>
                        </form>

                        <script>
                            function incrementQuantity() {
                                const input = document.getElementById('quantity');
                                input.value = parseInt(input.value) + 1;
                            }

                            function decrementQuantity() {
                                const input = document.getElementById('quantity');
                                if (parseInt(input.value) > 1) {
                                    input.value = parseInt(input.value) - 1;
                                }
                            }
                        </script>
                    <?php elseif (session()->get('logged_in') && !session()->get('is_admin') && !$dish['available']): ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i> Sorry, this dish is currently sold out.
                        </div>
                    <?php elseif (!session()->get('logged_in')): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Please <a href="<?= base_url('/auth/login') ?>" class="alert-link">login</a> to order this dish.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Reviews Section -->
    <div class="reviews-section mt-5" data-aos="fade-up">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Customer Reviews</h3>
            <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                    <i class="fas fa-star me-2"></i> Write a Review
                </button>
            <?php endif; ?>
        </div>

        <?php
        // Use the same review data that we already calculated above
        if (!isset($reviews)) {
            // Get reviews for this dish
            $db = \Config\Database::connect();
            $builder = $db->table('reviews');
            $builder->select('reviews.*, users.name as user_name');
            $builder->join('users', 'users.id = reviews.user_id');
            $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
            $builder->where('booking_items.dish_id', $dish['id']);
            $builder->orderBy('reviews.created_at', 'DESC');
            $reviews = $builder->get()->getResultArray();

            // Calculate average rating
            $totalRating = 0;
            $reviewCount = count($reviews);
            foreach ($reviews as $review) {
                $totalRating += $review['rating'];
            }
            $avgRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0;
        }
        ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center border-end">
                        <h1 class="display-4 fw-bold text-primary mb-0"><?= number_format($avgRating, 1) ?></h1>
                        <div class="rating-stars mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= floor($avgRating)): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php elseif ($i - 0.5 <= $avgRating): ?>
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <p class="text-muted mb-0"><?= $reviewCount ?> reviews</p>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <?php
                                $ratingCount = 0;
                                foreach ($reviews as $review) {
                                    if ($review['rating'] == $i) {
                                        $ratingCount++;
                                    }
                                }
                                $percentage = $reviewCount > 0 ? ($ratingCount / $reviewCount) * 100 : 0;
                                ?>
                                <div class="col-12 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2" style="width: 60px;">
                                            <?= $i ?> <i class="fas fa-star text-warning"></i>
                                        </div>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="ms-2" style="width: 40px;">
                                            <?= $ratingCount ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if (empty($reviews)): ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> No reviews yet. Be the first to review this dish!
            </div>
        <?php else: ?>
            <div class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="review-avatar me-3">
                                        <div class="avatar-circle bg-primary text-white">
                                            <?= substr($review['user_name'], 0, 1) ?>
                                        </div>
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
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Review Modal -->
    <?php if (session()->get('logged_in') && !session()->get('is_admin')): ?>
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    // Check if user has ordered this dish
                    $userId = session()->get('user_id');
                    $builder = $db->table('bookings');
                    $builder->select('bookings.id, bookings.status');
                    $builder->join('booking_items', 'booking_items.booking_id = bookings.id');
                    $builder->where('bookings.user_id', $userId);
                    $builder->where('booking_items.dish_id', $dish['id']);
                    $builder->where('bookings.status', 'delivered');
                    $eligibleBookings = $builder->get()->getResultArray();

                    if (empty($eligibleBookings)):
                    ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle me-2"></i> You can only review dishes that you have ordered and received.
                        </div>
                    <?php else: ?>
                        <form id="reviewForm" action="<?= base_url('/review/store-dish-review') ?>" method="post">
                            <input type="hidden" name="dish_id" value="<?= $dish['id'] ?>">
                            <input type="hidden" name="booking_id" value="<?= $eligibleBookings[0]['id'] ?>">

                            <div class="mb-4 text-center">
                                <label class="form-label">Your Rating</label>
                                <div class="rating-input">
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" title="5 stars"><i class="far fa-star"></i></label>

                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" title="4 stars"><i class="far fa-star"></i></label>

                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" title="3 stars"><i class="far fa-star"></i></label>

                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" title="2 stars"><i class="far fa-star"></i></label>

                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" title="1 star"><i class="far fa-star"></i></label>
                                </div>
                                <div class="selected-rating mt-2">Select a rating</div>
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Your Review (Optional, max 80 words)</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" maxlength="400" placeholder="Share your experience with this dish..."></textarea>
                                <div class="form-text">
                                    <span id="wordCount">0</span>/80 words
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <?php if (!empty($eligibleBookings)): ?>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('reviewForm').submit()">Submit Review</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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

        .rating-input {
            display: inline-flex;
            flex-direction: row-reverse;
            font-size: 1.8rem;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            cursor: pointer;
            color: #ccc;
            padding: 0 0.2rem;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffb700;
        }

        .rating-input label:hover i,
        .rating-input label:hover ~ label i,
        .rating-input input:checked ~ label i {
            font-weight: 900;
            content: "\f005";
            font-family: "Font Awesome 5 Free";
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentField = document.getElementById('comment');
            const wordCountDisplay = document.getElementById('wordCount');
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const selectedRating = document.querySelector('.selected-rating');

            if (commentField && wordCountDisplay) {
                // Word count functionality
                commentField.addEventListener('input', function() {
                    const words = this.value.match(/\S+/g) || [];
                    const wordCount = words.length;

                    wordCountDisplay.textContent = wordCount;

                    if (wordCount > 80) {
                        // Trim to 80 words
                        this.value = words.slice(0, 80).join(' ');
                        wordCountDisplay.textContent = 80;
                    }
                });
            }

            if (ratingInputs.length > 0 && selectedRating) {
                // Rating text update
                ratingInputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const ratingValue = this.value;
                        const ratingTexts = {
                            '1': 'Poor',
                            '2': 'Fair',
                            '3': 'Good',
                            '4': 'Very Good',
                            '5': 'Excellent'
                        };

                        selectedRating.textContent = ratingTexts[ratingValue] || 'Select a rating';
                    });
                });
            }
        });
    </script>
    <?php endif; ?>

    <!-- Related Dishes Section -->
    <div class="related-dishes mt-5" data-aos="fade-up">
        <h3 class="mb-4">You May Also Like</h3>
        <div class="row">
            <?php for ($i = 1; $i <= 3; $i++): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card dish-card h-100 border-0 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1631452180519-c014fe946bc7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8OHx8aW5kaWFuJTIwZm9vZHxlbnwwfHwwfHx8MA%3D%3D&auto=format&fit=crop&w=500&q=60" class="card-img-top" alt="Related Dish" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title">Related Dish <?= $i ?></h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                                <span class="price-tag fw-bold">₹150.00</span>
                            </div>
                            <p class="card-text text-muted">Another delicious dish you might enjoy.</p>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>