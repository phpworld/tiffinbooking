<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-star me-2"></i> Rate Your Order</h5>
                </div>
                <div class="card-body p-4">
                    <h6 class="mb-4">Order #<?= $booking['id'] ?> - <?= date('F j, Y', strtotime($booking['booking_date'])) ?></h6>
                    
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('/review/store') ?>" method="post">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        
                        <div class="mb-4 text-center">
                            <p class="mb-3">How would you rate your experience?</p>
                            <div class="rating-stars mb-3">
                                <div class="d-flex justify-content-center gap-2 fs-2">
                                    <div class="rating-input">
                                        <input type="radio" id="star5" name="rating" value="5" <?= old('rating') == 5 ? 'checked' : '' ?>>
                                        <label for="star5" class="text-warning"><i class="far fa-star star-icon"></i></label>
                                    </div>
                                    <div class="rating-input">
                                        <input type="radio" id="star4" name="rating" value="4" <?= old('rating') == 4 ? 'checked' : '' ?>>
                                        <label for="star4" class="text-warning"><i class="far fa-star star-icon"></i></label>
                                    </div>
                                    <div class="rating-input">
                                        <input type="radio" id="star3" name="rating" value="3" <?= old('rating') == 3 ? 'checked' : '' ?>>
                                        <label for="star3" class="text-warning"><i class="far fa-star star-icon"></i></label>
                                    </div>
                                    <div class="rating-input">
                                        <input type="radio" id="star2" name="rating" value="2" <?= old('rating') == 2 ? 'checked' : '' ?>>
                                        <label for="star2" class="text-warning"><i class="far fa-star star-icon"></i></label>
                                    </div>
                                    <div class="rating-input">
                                        <input type="radio" id="star1" name="rating" value="1" <?= old('rating') == 1 ? 'checked' : '' ?>>
                                        <label for="star1" class="text-warning"><i class="far fa-star star-icon"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="comment" class="form-label">Your Comments (Optional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" maxlength="200" placeholder="Tell us about your experience (max 200 characters)"><?= old('comment') ?></textarea>
                            <div class="form-text text-end"><span id="char-count">0</span>/200 characters</div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit Review
                            </button>
                            <a href="<?= base_url('/user/bookings') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Bookings
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Star rating functionality
        const stars = document.querySelectorAll('.star-icon');
        const ratingInputs = document.querySelectorAll('.rating-input input');
        
        // Initialize stars based on selected rating
        updateStars();
        
        // Add event listeners to rating inputs
        ratingInputs.forEach(input => {
            input.addEventListener('change', updateStars);
        });
        
        // Add event listeners to stars for better UX
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                // Fill in stars up to the hovered one
                stars.forEach((s, i) => {
                    if (i <= index) {
                        s.classList.remove('far');
                        s.classList.add('fas');
                    } else {
                        s.classList.remove('fas');
                        s.classList.add('far');
                    }
                });
            });
            
            star.addEventListener('mouseout', () => {
                // Reset to selected rating
                updateStars();
            });
            
            star.addEventListener('click', () => {
                // Set the corresponding radio input as checked
                ratingInputs[index].checked = true;
                updateStars();
            });
        });
        
        function updateStars() {
            // Find the selected rating
            let selectedRating = 0;
            ratingInputs.forEach((input, index) => {
                if (input.checked) {
                    selectedRating = 5 - index;
                }
            });
            
            // Update star icons based on selected rating
            stars.forEach((star, index) => {
                if (5 - index <= selectedRating) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
        }
        
        // Character counter for comment
        const commentTextarea = document.getElementById('comment');
        const charCount = document.getElementById('char-count');
        
        commentTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Initialize character count
        charCount.textContent = commentTextarea.value.length;
    });
</script>
<?= $this->endSection() ?>
