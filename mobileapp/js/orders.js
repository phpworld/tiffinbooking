/**
 * Orders related functions for the Tiffin Delight mobile app
 */

/**
 * Get all orders from the API
 * @returns {Promise} Promise that resolves with orders data
 */
function getOrders() {
    // Get token to ensure we're authenticated
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);

    if (!token) {
        console.error('No authentication token found');
        return Promise.reject(new Error('Authentication required'));
    }

    // Add a timestamp to prevent caching
    const timestamp = new Date().getTime();
    const url = `${API.BOOKING.LIST}?_=${timestamp}`;

    console.log('Fetching orders from:', url);

    return axios.get(url, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        },
        timeout: 15000 // Increase timeout for this request
    })
    .then(response => {
        console.log('Orders API response:', response.data);

        // Check if response has the expected structure
        if (response.data && response.data.status === 'success' && Array.isArray(response.data.data)) {
            // Standard API response format
            return response.data.data;
        } else if (response.data && response.data.data && Array.isArray(response.data.data)) {
            // Alternative API response format
            return response.data.data;
        } else if (response.data && Array.isArray(response.data)) {
            // Some APIs might return the array directly
            return response.data;
        } else if (response.data && typeof response.data === 'object' && !Array.isArray(response.data)) {
            // If it's an object but not an array, check if it has order properties
            if (response.data.id && response.data.status) {
                // It's a single order, wrap it in an array
                return [response.data];
            }
        }

        // Return empty array if data is not in expected format
        console.warn('Orders API returned unexpected data format:', response.data);
        return [];
    })
    .catch(error => {
        console.error('Error fetching orders:', error);

        // Create mock data for testing
        if (CONFIG.API_BASE_URL.includes('localhost')) {
            console.log('Using mock data for localhost testing');
            return getMockOrders();
        }

        // Re-throw the error to be handled by the caller
        throw error;
    });
}

/**
 * Get mock orders for testing
 * @returns {Array} Array of mock orders
 */
function getMockOrders() {
    return [
        {
            id: 1001,
            status: 'pending',
            created_at: new Date().toISOString(),
            total_amount: 450,
            item_count: 2,
            can_cancel: true,
            items: [
                { quantity: 1, dish_name: 'Butter Chicken', price: 250 },
                { quantity: 2, dish_name: 'Naan', price: 100 }
            ],
            booking_date: new Date().toLocaleDateString(),
            delivery_slot: '12:00 PM - 2:00 PM',
            payment_method: 'cash'
        },
        {
            id: 1002,
            status: 'delivered',
            created_at: new Date(Date.now() - 86400000).toISOString(), // Yesterday
            total_amount: 350,
            item_count: 2,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Paneer Tikka', price: 200 },
                { quantity: 1, dish_name: 'Jeera Rice', price: 150 }
            ],
            booking_date: new Date(Date.now() - 86400000).toLocaleDateString(),
            delivery_slot: '7:00 PM - 9:00 PM',
            payment_method: 'wallet'
        },
        {
            id: 1003,
            status: 'cancelled',
            created_at: new Date(Date.now() - 172800000).toISOString(), // 2 days ago
            total_amount: 550,
            item_count: 3,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Chicken Biryani', price: 300 },
                { quantity: 1, dish_name: 'Raita', price: 50 },
                { quantity: 2, dish_name: 'Gulab Jamun', price: 100 }
            ],
            booking_date: new Date(Date.now() - 172800000).toLocaleDateString(),
            delivery_slot: '1:00 PM - 3:00 PM',
            payment_method: 'cash'
        }
    ];
}

/**
 * Get order details from the API
 * @param {number} orderId - The ID of the order to get
 * @returns {Promise} Promise that resolves with order details
 */
function getOrderDetails(orderId) {
    // Get token to ensure we're authenticated
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);

    // Add a timestamp to prevent caching
    const timestamp = new Date().getTime();
    const url = `${API.BOOKING.DETAILS(orderId)}?_=${timestamp}`;

    return axios.get(url, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        },
        timeout: 15000 // Increase timeout for this request
    })
    .then(response => {
        console.log('Order details API response:', response.data);

        // Check if response has the expected structure
        if (response.data && response.data.data) {
            return response.data.data;
        } else if (response.data && !response.data.data) {
            // Some APIs might return the object directly
            return response.data;
        } else {
            console.warn('Order details API returned unexpected data format');
            throw new Error('Invalid order details format');
        }
    })
    .catch(error => {
        console.error('Error fetching order details:', error);

        // Create mock data for testing
        if (CONFIG.API_BASE_URL.includes('localhost')) {
            console.log('Using mock data for order details');
            // Find the order in mock orders
            const mockOrders = getMockOrders();
            const mockOrder = mockOrders.find(order => order.id == orderId);

            if (mockOrder) {
                return mockOrder;
            }
        }

        // Re-throw the error to be handled by the caller
        throw error;
    });
}

/**
 * Cancel an order
 * @param {number} orderId - The ID of the order to cancel
 * @returns {Promise} Promise that resolves with cancellation result
 */
function cancelOrder(orderId) {
    toggleLoading(true);

    return axios.post(API.BOOKING.CANCEL(orderId))
        .then(response => {
            toggleLoading(false);

            console.log('Cancel order response:', response.data);

            // Check if there's refund data in the response
            if (response.data && response.data.data) {
                const refundAmount = response.data.data.refund_amount;
                const newWalletBalance = response.data.data.new_wallet_balance;

                if (refundAmount) {
                    // Get current user
                    const user = getCurrentUser();
                    const userId = user ? user.id : '';

                    // Create a user-specific wallet key
                    const walletKey = `wallet_balance_${userId}`;

                    // Update wallet balance in localStorage with user-specific key
                    localStorage.setItem(walletKey, newWalletBalance);

                    // Show success message with refund information
                    showToast(response.data.message || `Order cancelled successfully! ₹${refundAmount} refunded to your wallet.`, 'success');

                    // Refresh wallet data if wallet screen is visible
                    if ($('#wallet-screen').hasClass('active')) {
                        loadWallet();
                    }
                } else {
                    showToast('Order cancelled successfully!', 'success');
                }
            } else {
                showToast('Order cancelled successfully!', 'success');
            }

            return response.data;
        })
        .catch(error => {
            toggleLoading(false);
            let errorMessage = 'Failed to cancel order. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }
            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Add a review for an order
 * @param {number} bookingId - The ID of the booking to review
 * @param {number} rating - The rating (1-5)
 * @param {string} comment - The review comment
 * @returns {Promise} Promise that resolves with review result
 */
function addReview(bookingId, rating, comment) {
    toggleLoading(true);

    return axios.post(API.REVIEW.ADD, {
        booking_id: bookingId,
        rating: rating,
        comment: comment
    })
        .then(response => {
            toggleLoading(false);
            showToast('Review added successfully!', 'success');
            return response.data;
        })
        .catch(error => {
            toggleLoading(false);
            let errorMessage = 'Failed to add review. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }
            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Load orders and render orders screen
 */
function loadOrders() {
    toggleLoading(true);

    // Clear any previous event listeners to prevent duplicates
    $(document).off('click', '#browse-menu-btn-active');
    $(document).off('click', '#browse-menu-btn-completed');
    $(document).off('click', '#browse-menu-btn-cancelled');
    $(document).off('click', '#retry-orders-btn');

    getOrders()
        .then(orders => {
            console.log('Orders loaded:', orders);

            if (orders && orders.length > 0) {
                renderOrders(orders);
                toggleLoading(false);
            } else {
                // No orders found
                $('#active-orders').html(renderOrdersList([], 'active'));
                $('#completed-orders').html(renderOrdersList([], 'completed'));
                $('#cancelled-orders').html(renderOrdersList([], 'cancelled'));

                toggleLoading(false);
                // No need for a toast here as empty state is normal for new users
            }

            // Add event listeners for browse menu buttons
            $('.browse-menu-btn').on('click', function() {
                $('.nav-item').removeClass('active');
                $('.nav-item[data-screen="menu-screen"]').addClass('active');

                $('.content-screen').removeClass('active');
                $('#menu-screen').addClass('active');

                loadDishes();
            });
        })
        .catch(error => {
            console.error('Error loading orders:', error);

            // Try to use mock data for testing
            if (CONFIG.API_BASE_URL.includes('localhost')) {
                console.log('Using mock data for orders display');
                const mockOrders = getMockOrders();
                renderOrders(mockOrders);
                toggleLoading(false);
                return;
            }

            // Show error state for each tab
            const errorHtml = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-circle fa-4x text-danger mb-3"></i>
                    <h4>Connection Error</h4>
                    <p class="text-muted">
                        Could not connect to server. Please check your connection.
                    </p>
                    <button class="btn btn-primary mt-3" id="retry-orders-btn">
                        <i class="fas fa-sync-alt"></i> Retry
                    </button>
                    <button class="btn btn-success mt-3 ms-2 browse-menu-btn">
                        <i class="fas fa-utensils"></i> Browse Menu
                    </button>
                </div>
            `;

            $('#active-orders').html(errorHtml);
            $('#completed-orders').html(errorHtml);
            $('#cancelled-orders').html(errorHtml);

            // Add event listener for retry button
            $('#retry-orders-btn').on('click', function() {
                loadOrders();
            });

            // Add event listener for browse menu button
            $('.browse-menu-btn').on('click', function() {
                $('.nav-item').removeClass('active');
                $('.nav-item[data-screen="menu-screen"]').addClass('active');

                $('.content-screen').removeClass('active');
                $('#menu-screen').addClass('active');

                loadDishes();
            });

            toggleLoading(false);
            showToast('Could not connect to server. Please check your connection.', 'warning');
        });
}

/**
 * Render orders screen
 * @param {Array} orders - The orders data
 */
function renderOrders(orders) {
    console.log('Rendering orders:', orders);

    // Filter orders by status - ensure case-insensitive comparison and handle different API formats
    const activeOrders = orders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return ['pending', 'confirmed', 'preparing', 'out_for_delivery'].includes(status);
    });

    const completedOrders = orders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return status === 'delivered';
    });

    const cancelledOrders = orders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return status === 'cancelled';
    });

    console.log('Active orders:', activeOrders);
    console.log('Completed orders:', completedOrders);
    console.log('Cancelled orders:', cancelledOrders);

    // Render each tab
    $('#active-orders').html(renderOrdersList(activeOrders, 'active'));
    $('#completed-orders').html(renderOrdersList(completedOrders, 'completed'));
    $('#cancelled-orders').html(renderOrdersList(cancelledOrders, 'cancelled'));

    // Add event listeners
    $('.view-order-btn').on('click', function() {
        const orderId = $(this).data('id');
        showOrderDetails(orderId);
    });

    $('.cancel-order-btn').on('click', function() {
        const orderId = $(this).data('id');

        if (confirm('Are you sure you want to cancel this order?')) {
            cancelOrder(orderId)
                .then(() => {
                    loadOrders();
                });
        }
    });

    $('.add-review-btn').on('click', function() {
        const orderId = $(this).data('id');
        showAddReviewModal(orderId);
    });
}

/**
 * Render orders list
 * @param {Array} orders - The orders to render
 * @param {string} type - The type of orders (active, completed, cancelled)
 * @returns {string} HTML for the orders list
 */
function renderOrdersList(orders, type) {
    if (orders.length === 0) {
        return `
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                <h4>No ${type} orders</h4>
                <p class="text-muted">
                    ${type === 'active' ? 'Place an order to see it here!' :
                      type === 'completed' ? 'Your completed orders will appear here.' :
                      'Your cancelled orders will appear here.'}
                </p>
                ${type === 'active' ? `
                    <button class="btn btn-success mt-3" id="browse-menu-btn-${type}">
                        <i class="fas fa-utensils"></i> Browse Menu
                    </button>
                ` : ''}
            </div>
        `;
    }

    return `
        <div class="orders-list p-2">
            ${orders.map(order => renderOrderCard(order, type)).join('')}
        </div>
    `;
}

/**
 * Render order card
 * @param {Object} order - The order data
 * @param {string} type - The type of order (active, completed, cancelled)
 * @returns {string} HTML for the order card
 */
function renderOrderCard(order, type) {
    // Handle potential missing data with defaults
    const status = (order.status || '').toLowerCase();
    const orderId = order.id || 'Unknown';
    const createdAt = order.created_at ? new Date(order.created_at).toLocaleDateString() : 'Unknown date';
    const totalAmount = order.total_amount || 0;
    const itemCount = order.item_count || 0;

    // Ensure items array exists
    const items = Array.isArray(order.items) ? order.items : [];

    // Default can_cancel to false if not specified
    const canCancel = order.can_cancel === true;

    const statusClass =
        status === 'pending' ? 'status-pending' :
        status === 'confirmed' ? 'status-confirmed' :
        status === 'preparing' ? 'status-confirmed' :
        status === 'out_for_delivery' ? 'status-confirmed' :
        status === 'delivered' ? 'status-delivered' :
        'status-cancelled';

    const statusText =
        status === 'pending' ? 'Pending' :
        status === 'confirmed' ? 'Confirmed' :
        status === 'preparing' ? 'Preparing' :
        status === 'out_for_delivery' ? 'Out for Delivery' :
        status === 'delivered' ? 'Delivered' :
        'Cancelled';

    // Create a safe item list string
    let itemsText = '';
    if (items.length > 0) {
        itemsText = items.map(item => {
            const quantity = item.quantity || 1;
            const dishName = item.dish_name || 'Unknown dish';
            return `${quantity}x ${dishName}`;
        }).join(', ');
    } else {
        itemsText = 'No items available';
    }

    return `
        <div class="card mb-3 order-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title">Order #${orderId}</h5>
                    <span class="status-badge ${statusClass}">${statusText}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">
                        <i class="far fa-calendar-alt"></i> ${createdAt}
                    </span>
                    <span class="fw-bold">₹${totalAmount}</span>
                </div>

                <div class="order-items mb-3">
                    <p class="mb-1">${itemCount} items</p>
                    <p class="text-muted small mb-0">
                        ${itemsText}
                    </p>
                </div>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-sm btn-outline-primary view-order-btn" data-id="${orderId}">
                        <i class="fas fa-eye"></i> View Details
                    </button>

                    ${type === 'active' && canCancel ? `
                        <button class="btn btn-sm btn-outline-danger cancel-order-btn" data-id="${orderId}">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    ` : ''}

                    ${type === 'completed' ? `
                        <button class="btn btn-sm btn-outline-success add-review-btn" data-id="${orderId}">
                            <i class="fas fa-star"></i> Add Review
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

/**
 * Show order details modal
 * @param {number} orderId - The ID of the order to show
 */
function showOrderDetails(orderId) {
    toggleLoading(true);

    getOrderDetails(orderId)
        .then(order => {
            // Remove any existing modal
            $('#orderDetailsModal').remove();

            // Add the modal to the DOM
            $('body').append(renderOrderDetailsModal(order));

            // Initialize the modal
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();

            // Add event listener for cancel button in modal
            $('.cancel-order-modal-btn').on('click', function() {
                const orderId = $(this).data('id');

                if (confirm('Are you sure you want to cancel this order?')) {
                    modal.hide();
                    cancelOrder(orderId)
                        .then(() => {
                            loadOrders();
                        });
                }
            });

            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            showToast('Failed to load order details. Please try again.', 'error');
            console.error('Error loading order details:', error);
        });
}

/**
 * Render order details modal
 * @param {Object} order - The order data
 * @returns {string} HTML for the order details modal
 */
function renderOrderDetailsModal(order) {
    // Handle potential missing data with defaults
    const status = (order.status || '').toLowerCase();
    const orderId = order.id || 'Unknown';
    const createdAt = order.created_at ? new Date(order.created_at).toLocaleDateString() : 'Unknown date';
    const totalAmount = order.total_amount || 0;
    const bookingDate = order.booking_date || 'Not specified';
    const deliverySlot = order.delivery_slot || 'Not specified';
    const paymentMethod = order.payment_method || 'Not specified';

    // Ensure items array exists
    const items = Array.isArray(order.items) ? order.items : [];

    // Default can_cancel to false if not specified
    const canCancel = order.can_cancel === true;

    const statusClass =
        status === 'pending' ? 'status-pending' :
        status === 'confirmed' ? 'status-confirmed' :
        status === 'preparing' ? 'status-confirmed' :
        status === 'out_for_delivery' ? 'status-confirmed' :
        status === 'delivered' ? 'status-delivered' :
        'status-cancelled';

    const statusText =
        status === 'pending' ? 'Pending' :
        status === 'confirmed' ? 'Confirmed' :
        status === 'preparing' ? 'Preparing' :
        status === 'out_for_delivery' ? 'Out for Delivery' :
        status === 'delivered' ? 'Delivered' :
        'Cancelled';

    // Generate items HTML
    let itemsHtml = '';
    if (items.length > 0) {
        itemsHtml = items.map(item => {
            const quantity = item.quantity || 1;
            const dishName = item.dish_name || 'Unknown dish';
            const price = item.price || 0;
            return `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <span class="fw-bold">${quantity}x</span> ${dishName}
                    </div>
                    <span>₹${(price * quantity).toFixed(2)}</span>
                </div>
            `;
        }).join('');
    } else {
        itemsHtml = '<p class="text-muted">No items available</p>';
    }

    // Format payment method text
    let paymentMethodText = 'Not specified';
    if (paymentMethod === 'cash') {
        paymentMethodText = 'Cash on Delivery';
    } else if (paymentMethod === 'wallet') {
        paymentMethodText = 'Wallet';
    } else if (paymentMethod) {
        // Capitalize first letter
        paymentMethodText = paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1);
    }

    return `
        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Order #${orderId}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">
                                <i class="far fa-calendar-alt"></i> ${createdAt}
                            </span>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>

                        <h6>Order Items</h6>
                        <div class="order-items-list mb-4">
                            ${itemsHtml}

                            <hr>

                            <div class="d-flex justify-content-between align-items-center fw-bold">
                                <span>Total</span>
                                <span>₹${totalAmount}</span>
                            </div>
                        </div>

                        <h6>Delivery Information</h6>
                        <div class="delivery-info mb-4">
                            <p class="mb-1"><strong>Date:</strong> ${bookingDate}</p>
                            <p class="mb-1"><strong>Time Slot:</strong> ${deliverySlot}</p>
                            <p class="mb-0"><strong>Payment Method:</strong> ${paymentMethodText}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        ${canCancel ? `
                            <button type="button" class="btn btn-danger cancel-order-modal-btn" data-id="${orderId}">
                                <i class="fas fa-times"></i> Cancel Order
                            </button>
                        ` : ''}

                        ${status === 'delivered' ? `
                            <button type="button" class="btn btn-success add-review-modal-btn" data-id="${orderId}">
                                <i class="fas fa-star"></i> Add Review
                            </button>
                        ` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Show add review modal
 * @param {number} orderId - The ID of the order to review
 */
function showAddReviewModal(orderId) {
    // Remove any existing modal
    $('#addReviewModal').remove();

    // Add the modal to the DOM
    $('body').append(renderAddReviewModal(orderId));

    // Initialize the modal
    const modal = new bootstrap.Modal(document.getElementById('addReviewModal'));
    modal.show();

    // Add event listeners
    $('.rating-star').on('click', function() {
        const rating = $(this).data('rating');
        $('.rating-star').removeClass('active');

        for (let i = 1; i <= rating; i++) {
            $(`.rating-star[data-rating="${i}"]`).addClass('active');
        }

        $('#review-rating').val(rating);
    });

    $('#submit-review-btn').on('click', function() {
        const rating = $('#review-rating').val();
        const comment = $('#review-comment').val();

        if (!rating) {
            showToast('Please select a rating.', 'error');
            return;
        }

        addReview(orderId, rating, comment)
            .then(() => {
                modal.hide();
                loadOrders();
            });
    });
}

/**
 * Render add review modal
 * @param {number} orderId - The ID of the order to review
 * @returns {string} HTML for the add review modal
 */
function renderAddReviewModal(orderId) {
    return `
        <div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReviewModalLabel">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating-stars mb-2">
                                <i class="fas fa-star rating-star" data-rating="1"></i>
                                <i class="fas fa-star rating-star" data-rating="2"></i>
                                <i class="fas fa-star rating-star" data-rating="3"></i>
                                <i class="fas fa-star rating-star" data-rating="4"></i>
                                <i class="fas fa-star rating-star" data-rating="5"></i>
                            </div>
                            <input type="hidden" id="review-rating" value="">
                        </div>

                        <div class="mb-3">
                            <label for="review-comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="review-comment" rows="3" maxlength="400" placeholder="Write your review here..."></textarea>
                            <div class="form-text">Maximum 400 characters</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="submit-review-btn">Submit Review</button>
                    </div>
                </div>
            </div>
        </div>
    `;
}
