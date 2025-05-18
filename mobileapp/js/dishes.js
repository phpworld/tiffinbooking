/**
 * Dishes related functions for the Tiffin Delight mobile app
 */

/**
 * Load all dishes from the API
 * @returns {Promise} Promise that resolves with dishes data
 */
function loadAllDishes() {
    return axios.get(API.DISHES.LIST)
        .then(response => {
            return response.data.data;
        });
}

/**
 * Load dish details from the API
 * @param {number} dishId - The ID of the dish to load
 * @returns {Promise} Promise that resolves with dish details
 */
function loadDishDetails(dishId) {
    return axios.get(API.DISHES.DETAILS(dishId))
        .then(response => {
            return response.data.data;
        });
}

/**
 * Render dish card for horizontal scroll
 * @param {Object} dish - The dish data
 * @returns {string} HTML for the dish card
 */
function renderDishCardHorizontal(dish) {
    const vegBadge = dish.is_vegetarian 
        ? '<span class="veg-badge"></span>' 
        : '<span class="non-veg-badge"></span>';
        
    return `
        <div class="dish-card-horizontal" data-id="${dish.id}">
            <img src="${dish.image || CONFIG.DEFAULT_DISH_IMAGE}" class="dish-card-img" alt="${dish.name}">
            <div class="dish-card-body">
                <div class="d-flex align-items-center">
                    ${vegBadge}
                    <h5 class="dish-card-title">${dish.name}</h5>
                </div>
                <p class="dish-card-price">₹${dish.price}</p>
                <div class="dish-card-rating">
                    <i class="fas fa-star"></i>
                    <span>${dish.rating > 0 ? dish.rating : 'New'}</span>
                </div>
            </div>
        </div>
    `;
}

/**
 * Render dish card for grid view
 * @param {Object} dish - The dish data
 * @returns {string} HTML for the dish card
 */
function renderDishCardGrid(dish) {
    const vegBadge = dish.is_vegetarian 
        ? '<span class="veg-badge"></span>' 
        : '<span class="non-veg-badge"></span>';
        
    return `
        <div class="col-6 mb-3">
            <div class="card dish-card" data-id="${dish.id}">
                <img src="${dish.image || CONFIG.DEFAULT_DISH_IMAGE}" class="card-img-top" alt="${dish.name}">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        ${vegBadge}
                        <h5 class="card-title mb-0">${dish.name}</h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-text text-success fw-bold mb-0">₹${dish.price}</p>
                        <div class="rating">
                            <i class="fas fa-star text-warning"></i>
                            <small>${dish.rating > 0 ? dish.rating : 'New'}</small>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-success w-100 mt-2 add-to-cart-btn" data-id="${dish.id}">
                        <i class="fas fa-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        </div>
    `;
}

/**
 * Render dish details modal
 * @param {Object} dish - The dish data
 * @returns {string} HTML for the dish details modal
 */
function renderDishDetailsModal(dish) {
    const vegBadge = dish.is_vegetarian 
        ? '<span class="badge bg-success">Vegetarian</span>' 
        : '<span class="badge bg-danger">Non-Vegetarian</span>';
        
    let reviewsHtml = '';
    if (dish.reviews && dish.reviews.length > 0) {
        reviewsHtml = dish.reviews.map(review => `
            <div class="review-item mb-3">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-primary text-white me-2">
                            ${review.user_name.charAt(0)}
                        </div>
                        <div>
                            <h6 class="mb-0">${review.user_name}</h6>
                            <small class="text-muted">${review.date}</small>
                        </div>
                    </div>
                    <div class="rating">
                        ${renderStarRating(review.rating)}
                    </div>
                </div>
                <p class="mt-2 mb-0">${review.comment || 'Great food!'}</p>
            </div>
        `).join('');
    } else {
        reviewsHtml = '<p class="text-muted">No reviews yet.</p>';
    }
    
    return `
        <div class="modal fade" id="dishDetailsModal" tabindex="-1" aria-labelledby="dishDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dishDetailsModalLabel">${dish.name}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="${dish.image || CONFIG.DEFAULT_DISH_IMAGE}" class="img-fluid rounded mb-3" alt="${dish.name}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                ${vegBadge}
                                <span class="badge bg-primary ms-2">₹${dish.price}</span>
                            </div>
                            <div class="rating">
                                ${renderStarRating(dish.rating)}
                                <small>(${dish.review_count} reviews)</small>
                            </div>
                        </div>
                        <h6>Description</h6>
                        <p>${dish.description || 'No description available.'}</p>
                        
                        <div class="quantity-control mb-3">
                            <button class="quantity-btn decrease-btn">-</button>
                            <input type="number" class="quantity-input" value="1" min="1" max="10">
                            <button class="quantity-btn increase-btn">+</button>
                        </div>
                        
                        <h6 class="mt-4">Reviews</h6>
                        <div class="reviews-container">
                            ${reviewsHtml}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success add-to-cart-modal-btn" data-id="${dish.id}">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Show dish details modal
 * @param {number} dishId - The ID of the dish to show
 */
function showDishDetails(dishId) {
    toggleLoading(true);
    
    loadDishDetails(dishId)
        .then(dish => {
            // Remove any existing modal
            $('#dishDetailsModal').remove();
            
            // Add the modal to the DOM
            $('body').append(renderDishDetailsModal(dish));
            
            // Initialize the modal
            const modal = new bootstrap.Modal(document.getElementById('dishDetailsModal'));
            modal.show();
            
            // Add event listeners
            $('.decrease-btn').on('click', function() {
                let quantity = parseInt($('.quantity-input').val());
                if (quantity > 1) {
                    $('.quantity-input').val(quantity - 1);
                }
            });
            
            $('.increase-btn').on('click', function() {
                let quantity = parseInt($('.quantity-input').val());
                if (quantity < 10) {
                    $('.quantity-input').val(quantity + 1);
                }
            });
            
            $('.add-to-cart-modal-btn').on('click', function() {
                const dishId = $(this).data('id');
                const quantity = parseInt($('.quantity-input').val());
                
                addToCart(dishId, quantity);
                modal.hide();
            });
            
            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            showToast('Failed to load dish details. Please try again.', 'error');
            console.error('Error loading dish details:', error);
        });
}
