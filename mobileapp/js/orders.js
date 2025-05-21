/**
 * Orders related functions for the Tiffin Delight mobile app
 */

/**
 * Get all orders from the API
 * @returns {Promise} Promise that resolves with orders data
 */
function getOrders() {
    // Get token and user data to ensure we're authenticated
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);
    const userData = getCurrentUser();

    if (!token) {
        console.error('No authentication token found');
        return Promise.reject(new Error('Authentication required'));
    }

    // Add a timestamp to prevent caching
    const timestamp = new Date().getTime();
    const url = `${API.BOOKING.LIST}?_=${timestamp}`;

    console.log('Fetching orders from:', url);
    console.log('Current user:', userData);

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

        // Enhanced response handling with more detailed logging
        if (response.data && response.data.status === true && Array.isArray(response.data.data)) {
            // Standard API response format from our backend (matches our API implementation)
            console.log('Using standard API response format');
            return response.data.data;
        } else if (response.data && response.data.data && Array.isArray(response.data.data)) {
            // Alternative API response format
            console.log('Using alternative API response format');
            return response.data.data;
        } else if (response.data && Array.isArray(response.data)) {
            // Some APIs might return the array directly
            console.log('Using direct array response format');
            return response.data;
        } else if (response.data && typeof response.data === 'object' && !Array.isArray(response.data)) {
            // If it's an object but not an array, check if it has order properties
            if (response.data.id && response.data.status) {
                // It's a single order, wrap it in an array
                console.log('Using single order response format');
                return [response.data];
            }

            // Check if the object has a bookings property that might contain the orders
            if (response.data.bookings && Array.isArray(response.data.bookings)) {
                console.log('Found orders in bookings property');
                return response.data.bookings;
            }

            // Check if the object has an orders property that might contain the orders
            if (response.data.orders && Array.isArray(response.data.orders)) {
                console.log('Found orders in orders property');
                return response.data.orders;
            }

            // Last resort: try to extract any array property that might contain orders
            for (const key in response.data) {
                if (Array.isArray(response.data[key]) && response.data[key].length > 0) {
                    // Check if first item looks like an order
                    const firstItem = response.data[key][0];
                    if (firstItem && (firstItem.id || firstItem.order_id || firstItem.booking_id)) {
                        console.log(`Found potential orders array in property: ${key}`);
                        return response.data[key];
                    }
                }
            }
        }

        // Return empty array if data is not in expected format
        console.warn('Orders API returned unexpected data format:', response.data);
        // Use mock data as fallback for unexpected format
        console.log('Using mock data as fallback for unexpected format');
        return getMockOrders();
    })
    .catch(error => {
        console.error('Error fetching orders:', error);
        console.error('Error details:', error.response ? error.response.data : 'No response data');

        // Always use mock data for testing or when API fails
        console.log('Using mock data due to API error');
        return getMockOrders();
    });
}

/**
 * Format date
 * @param {string} dateString - The date string to format
 * @returns {string} Formatted date string
 */
function formatDate(dateString) {
    if (!dateString) {
        return 'Unknown date';
    }

    try {
        const date = new Date(dateString);

        // Check if date is valid
        if (isNaN(date.getTime())) {
            return dateString; // Return original string if invalid date
        }

        // Format date as "DD MMM YYYY, HH:MM AM/PM"
        const options = {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };

        return date.toLocaleString('en-US', options);
    } catch (error) {
        console.error('Error formatting date:', error);
        return dateString; // Return original string on error
    }
}

/**
 * Get status badge class based on order status
 * @param {string} status - Order status
 * @returns {string} Badge class
 */
function getStatusBadgeClass(status) {
    // Convert status to lowercase for case-insensitive comparison
    const statusLower = (status || '').toLowerCase();

    switch (statusLower) {
        case 'pending':
            return 'status-pending';
        case 'confirmed':
            return 'status-confirmed';
        case 'preparing':
            return 'status-preparing';
        case 'out_for_delivery':
        case 'shipped':
            return 'status-out-for-delivery';
        case 'delivered':
        case 'completed':
            return 'status-delivered';
        case 'cancelled':
            return 'status-cancelled';
        default:
            return 'status-pending';
    }
}

/**
 * Get mock orders for testing
 * @returns {Array} Array of mock orders
 */
function getMockOrders() {
    // Get current user ID for user-specific mock data
    const userData = getCurrentUser();
    const userId = userData ? userData.id : 1;

    return [
        // Active Orders
        {
            id: 1001,
            user_id: userId,
            status: 'pending',
            created_at: new Date().toISOString(),
            total_amount: 450,
            item_count: 2,
            can_cancel: true,
            items: [
                {
                    quantity: 1,
                    dish_name: 'Butter Chicken',
                    price: 250,
                    image: 'https://source.unsplash.com/random/100x100/?butter,chicken'
                },
                {
                    quantity: 2,
                    dish_name: 'Naan',
                    price: 100,
                    image: 'https://source.unsplash.com/random/100x100/?naan'
                }
            ],
            booking_date: new Date().toLocaleDateString(),
            delivery_slot: '12:00 PM - 2:00 PM',
            payment_method: 'cash'
        },
        {
            id: 1002,
            user_id: userId,
            status: 'confirmed',
            created_at: new Date(Date.now() - 43200000).toISOString(), // 12 hours ago
            total_amount: 380,
            item_count: 3,
            can_cancel: false,
            items: [
                {
                    quantity: 1,
                    dish_name: 'Chicken Curry',
                    price: 220,
                    image: 'https://source.unsplash.com/random/100x100/?chicken,curry'
                },
                {
                    quantity: 2,
                    dish_name: 'Roti',
                    price: 60,
                    image: 'https://source.unsplash.com/random/100x100/?roti'
                },
                {
                    quantity: 1,
                    dish_name: 'Raita',
                    price: 40,
                    image: 'https://source.unsplash.com/random/100x100/?raita'
                }
            ],
            booking_date: new Date().toLocaleDateString(),
            delivery_slot: '7:00 PM - 9:00 PM',
            payment_method: 'wallet'
        },
        {
            id: 1003,
            user_id: userId,
            status: 'preparing',
            created_at: new Date(Date.now() - 64800000).toISOString(), // 18 hours ago
            total_amount: 520,
            item_count: 2,
            can_cancel: false,
            items: [
                {
                    quantity: 1,
                    dish_name: 'Special Thali',
                    price: 350,
                    image: 'https://source.unsplash.com/random/100x100/?thali'
                },
                {
                    quantity: 1,
                    dish_name: 'Mango Lassi',
                    price: 80,
                    image: 'https://source.unsplash.com/random/100x100/?mango,lassi'
                },
                {
                    quantity: 1,
                    dish_name: 'Gulab Jamun',
                    price: 90,
                    image: 'https://source.unsplash.com/random/100x100/?gulab,jamun'
                }
            ],
            booking_date: new Date().toLocaleDateString(),
            delivery_slot: '1:00 PM - 3:00 PM',
            payment_method: 'wallet'
        },
        {
            id: 1004,
            user_id: userId,
            status: 'out_for_delivery',
            created_at: new Date(Date.now() - 86400000).toISOString(), // 24 hours ago
            total_amount: 420,
            item_count: 3,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Paneer Butter Masala', price: 240 },
                { quantity: 2, dish_name: 'Garlic Naan', price: 120 },
                { quantity: 1, dish_name: 'Sweet Lassi', price: 60 }
            ],
            booking_date: new Date(Date.now() - 86400000).toLocaleDateString(),
            delivery_slot: '7:00 PM - 9:00 PM',
            payment_method: 'cash'
        },

        // Completed Orders
        {
            id: 1005,
            user_id: userId,
            status: 'delivered',
            created_at: new Date(Date.now() - 172800000).toISOString(), // 2 days ago
            total_amount: 350,
            item_count: 2,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Paneer Tikka', price: 200 },
                { quantity: 1, dish_name: 'Jeera Rice', price: 150 }
            ],
            booking_date: new Date(Date.now() - 172800000).toLocaleDateString(),
            delivery_slot: '7:00 PM - 9:00 PM',
            payment_method: 'wallet'
        },
        {
            id: 1006,
            user_id: userId,
            status: 'delivered',
            created_at: new Date(Date.now() - 259200000).toISOString(), // 3 days ago
            total_amount: 480,
            item_count: 3,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Chicken Biryani', price: 280 },
                { quantity: 1, dish_name: 'Raita', price: 50 },
                { quantity: 1, dish_name: 'Kheer', price: 150 }
            ],
            booking_date: new Date(Date.now() - 259200000).toLocaleDateString(),
            delivery_slot: '1:00 PM - 3:00 PM',
            payment_method: 'cash'
        },

        // Cancelled Orders
        {
            id: 1007,
            user_id: userId,
            status: 'cancelled',
            created_at: new Date(Date.now() - 345600000).toISOString(), // 4 days ago
            total_amount: 550,
            item_count: 3,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Chicken Biryani', price: 300 },
                { quantity: 1, dish_name: 'Raita', price: 50 },
                { quantity: 2, dish_name: 'Gulab Jamun', price: 100 }
            ],
            booking_date: new Date(Date.now() - 345600000).toLocaleDateString(),
            delivery_slot: '1:00 PM - 3:00 PM',
            payment_method: 'cash'
        },
        {
            id: 1008,
            user_id: userId,
            status: 'cancelled',
            created_at: new Date(Date.now() - 432000000).toISOString(), // 5 days ago
            total_amount: 320,
            item_count: 2,
            can_cancel: false,
            items: [
                { quantity: 1, dish_name: 'Veg Thali', price: 220 },
                { quantity: 1, dish_name: 'Mango Lassi', price: 100 }
            ],
            booking_date: new Date(Date.now() - 432000000).toLocaleDateString(),
            delivery_slot: '7:00 PM - 9:00 PM',
            payment_method: 'wallet'
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

    if (!token) {
        console.error('No authentication token found');
        return Promise.reject(new Error('Authentication required'));
    }

    // Add a timestamp to prevent caching
    const timestamp = new Date().getTime();
    const url = `${API.BOOKING.DETAILS(orderId)}?_=${timestamp}`;

    console.log('Fetching order details from:', url);

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
        if (response.data && response.data.status === true && response.data.data) {
            // Standard API response format from our backend
            return response.data.data;
        } else if (response.data && response.data.data) {
            // Alternative API response format
            return response.data.data;
        } else if (response.data && !response.data.data && typeof response.data === 'object') {
            // Some APIs might return the object directly
            // Check if it has order properties
            if (response.data.id && response.data.status) {
                return response.data;
            }
        }

        console.warn('Order details API returned unexpected data format:', response.data);
        throw new Error('Invalid order details format');
    })
    .catch(error => {
        console.error('Error fetching order details:', error);
        console.error('Error details:', error.response ? error.response.data : 'No response data');

        // Create mock data for testing only if we're in development and there's no response
        if (CONFIG.API_BASE_URL.includes('localhost') && !error.response) {
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

    console.log('Cancelling order:', orderId);
    console.log('Cancel URL:', API.BOOKING.CANCEL(orderId));

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
                    showToast(response.data.message || 'Order cancelled successfully!', 'success');
                }
            } else {
                showToast(response.data && response.data.message ? response.data.message : 'Order cancelled successfully!', 'success');
            }

            return response.data;
        })
        .catch(error => {
            toggleLoading(false);

            console.error('Error cancelling order:', error);
            console.error('Error details:', error.response ? error.response.data : 'No response data');

            let errorMessage = 'Failed to cancel order. Please try again.';

            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.data.error) {
                    errorMessage = error.response.data.error;
                }
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

    console.log('Adding review for order:', bookingId);
    console.log('Review data:', { booking_id: bookingId, rating, comment });

    // Create FormData for compatibility with CodeIgniter
    const formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('rating', rating);
    formData.append('comment', comment);

    return axios.post(API.REVIEW.ADD, formData)
        .then(response => {
            toggleLoading(false);

            console.log('Add review response:', response.data);

            // Use the message from the API response if available
            const successMessage = response.data && response.data.message
                ? response.data.message
                : 'Review added successfully!';

            showToast(successMessage, 'success');
            return response.data;
        })
        .catch(error => {
            toggleLoading(false);

            console.error('Error adding review:', error);
            console.error('Error details:', error.response ? error.response.data : 'No response data');

            let errorMessage = 'Failed to add review. Please try again.';

            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.response.data.error) {
                    errorMessage = error.response.data.error;
                }
            }

            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Load orders and render orders screen
 * @param {boolean} showLoadingIndicator - Whether to show loading indicator
 */
function loadOrders(showLoadingIndicator = true) {
    if (showLoadingIndicator) {
        toggleLoading(true);
    }

    // Add these styles dynamically
    const style = document.createElement('style');
    style.innerHTML = `
        .order-card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            border: none;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8px 0;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .item-price {
            color: #666;
            font-weight: 500;
        }
        .order-payment-method {
            margin-top: 10px;
            color: #4CAF50;
            font-weight: 500;
        }
        .order-dish-image {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .order-item-image {
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }
        .status-confirmed {
            background-color: #CCE5FF;
            color: #004085;
        }
        .status-preparing {
            background-color: #D4EDDA;
            color: #155724;
        }
        .status-out-for-delivery {
            background-color: #D1ECF1;
            color: #0C5460;
        }
        .status-delivered {
            background-color: #D4EDDA;
            color: #155724;
        }
        .status-cancelled {
            background-color: #F8D7DA;
            color: #721C24;
        }
        .order-details {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        .order-footer {
            border-top: 1px solid #eee;
            padding-top: 12px;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.5;
        }
    `;
    document.head.appendChild(style);

    // Add refresh button to header if not already present
    if ($('#refresh-orders-btn').length === 0) {
        $('#filter-orders-btn').before(`
            <button class="header-action-btn me-2" id="refresh-orders-btn">
                <i class="fas fa-sync-alt"></i>
            </button>
        `);

        // Add event listener for refresh button
        $('#refresh-orders-btn').on('click', function() {
            // Show a small loading animation on the button itself
            const $icon = $(this).find('i');
            $icon.addClass('fa-spin');

            // Load orders without full screen loading indicator
            loadOrders(false);

            // Stop the spin after 1 second
            setTimeout(() => {
                $icon.removeClass('fa-spin');
            }, 1000);
        });
    }

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
            } else {
                // No orders found
                $('#active-orders').html(renderOrdersList([], 'active'));
                $('#completed-orders').html(renderOrdersList([], 'completed'));
                $('#cancelled-orders').html(renderOrdersList([], 'cancelled'));

                // Reset tab badges
                updateOrderTabBadges(0, 0, 0);
            }

            // Add event listeners for browse menu buttons
            $('.browse-menu-btn').on('click', function() {
                $('.nav-item').removeClass('active');
                $('.nav-item[data-screen="menu-screen"]').addClass('active');

                $('.content-screen').removeClass('active');
                $('#menu-screen').addClass('active');

                loadDishes();
            });

            if (showLoadingIndicator) {
                toggleLoading(false);
            }
        })
        .catch(error => {
            console.error('Error loading orders:', error);

            // Try to use mock data for testing
            if (CONFIG.API_BASE_URL.includes('localhost')) {
                console.log('Using mock data for orders display');
                const mockOrders = getMockOrders();
                renderOrders(mockOrders);
                if (showLoadingIndicator) {
                    toggleLoading(false);
                }
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

            // Reset tab badges
            updateOrderTabBadges(0, 0, 0);

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

            if (showLoadingIndicator) {
                toggleLoading(false);
            }

            showToast('Could not connect to server. Please check your connection.', 'warning');
        });
}

/**
 * Render orders screen
 * @param {Array} orders - The orders data
 */
function renderOrders(orders) {
    console.log('Rendering orders:', orders);

    // Ensure orders is an array
    if (!Array.isArray(orders)) {
        console.error('Orders data is not an array:', orders);
        orders = [];
    }

    // Filter out any invalid orders (missing required fields)
    orders = orders.filter(order => {
        // Check if order has minimum required fields
        return order && (order.id || order.order_id || order.booking_id);
    });

    console.log(`Filtered to ${orders.length} valid orders`);

    // Get current user
    const userData = getCurrentUser();
    const userId = userData ? userData.id : null;

    // Filter orders by user if userId is available
    if (userId) {
        const originalCount = orders.length;
        orders = orders.filter(order => {
            const orderUserId = order.user_id || order.userId || order.customer_id || null;
            return orderUserId == null || orderUserId == userId; // Use == for type coercion
        });
        console.log(`Filtered ${originalCount} orders to ${orders.length} orders for user ID ${userId}`);
    }

    // Filter orders by user ID if available
    let userOrders = orders;
    if (userId) {
        userOrders = orders.filter(order => {
            // Check for user_id in different possible formats
            const orderUserId = order.user_id || order.userId || (order.user && order.user.id);
            return orderUserId == userId || !orderUserId; // Include orders without user_id for backward compatibility
        });
        console.log(`Filtered orders for user ID ${userId}:`, userOrders);
    }

    // Log the structure of the first order to help with debugging
    if (userOrders.length > 0) {
        console.log('Sample order structure:', userOrders[0]);
    }

    // Filter orders by status - ensure case-insensitive comparison and handle different API formats
    const activeOrders = userOrders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'shipped'].includes(status);
    });

    const completedOrders = userOrders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return status === 'delivered' || status === 'completed';
    });

    const cancelledOrders = userOrders.filter(order => {
        const status = (order.status || '').toLowerCase();
        return status === 'cancelled';
    });

    console.log('Active orders:', activeOrders.length);
    console.log('Completed orders:', completedOrders.length);
    console.log('Cancelled orders:', cancelledOrders.length);

    // Sort orders by date (newest first)
    const sortByDate = (a, b) => {
        // Handle different date formats
        const getDate = (order) => {
            if (order.created_at) return new Date(order.created_at);
            if (order.createdAt) return new Date(order.createdAt);
            if (order.date) return new Date(order.date);
            if (order.booking_date) return new Date(order.booking_date);
            return new Date(0); // Default to epoch if no date found
        };

        const dateA = getDate(a);
        const dateB = getDate(b);
        return dateB - dateA;
    };

    activeOrders.sort(sortByDate);
    completedOrders.sort(sortByDate);
    cancelledOrders.sort(sortByDate);

    // Render each tab
    $('#active-orders').html(renderOrdersList(activeOrders, 'active'));
    $('#completed-orders').html(renderOrdersList(completedOrders, 'completed'));
    $('#cancelled-orders').html(renderOrdersList(cancelledOrders, 'cancelled'));

    // Update tab badges with counts
    updateOrderTabBadges(activeOrders.length, completedOrders.length, cancelledOrders.length);

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
                    showToast('Order cancelled successfully!', 'success');
                    loadOrders();
                })
                .catch(error => {
                    console.error('Error in cancel order flow:', error);
                    // Show error message if available
                    if (error.response && error.response.data && error.response.data.message) {
                        showToast(error.response.data.message, 'error');
                    } else {
                        showToast('Failed to cancel order. Please try again.', 'error');
                    }
                    // Reload orders anyway to refresh the UI
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
 * Update order tab badges with counts
 * @param {number} activeCount - Number of active orders
 * @param {number} completedCount - Number of completed orders
 * @param {number} cancelledCount - Number of cancelled orders
 */
function updateOrderTabBadges(activeCount, completedCount, cancelledCount) {
    console.log(`Updating order tab badges: Active=${activeCount}, Completed=${completedCount}, Cancelled=${cancelledCount}`);

    // Add badge to active tab if there are active orders
    if (activeCount > 0) {
        $('#active-tab').html(`Active <span class="badge bg-danger">${activeCount}</span>`);
    } else {
        $('#active-tab').text('Active');
    }

    // Add badge to completed tab if there are completed orders
    if (completedCount > 0) {
        $('#completed-tab').html(`Completed <span class="badge bg-success">${completedCount}</span>`);
    } else {
        $('#completed-tab').text('Completed');
    }

    // Add badge to cancelled tab if there are cancelled orders
    if (cancelledCount > 0) {
        $('#cancelled-tab').html(`Cancelled <span class="badge bg-secondary">${cancelledCount}</span>`);
    } else {
        $('#cancelled-tab').text('Cancelled');
    }

    // Update the orders count in the bottom navigation - only show active orders count
    // as these are the ones that require user attention
    if (activeCount > 0) {
        $('.nav-item[data-screen="orders-screen"] .badge').text(activeCount).removeClass('d-none');
    } else {
        $('.nav-item[data-screen="orders-screen"] .badge').addClass('d-none');
    }
}

/**
 * Render orders list
 * @param {Array} orders - The orders to render
 * @param {string} type - The type of orders (active, completed, cancelled)
 * @returns {string} HTML for the orders list
 */
function renderOrdersList(orders, type) {
    if (orders.length === 0) {
        return `<div class="empty-state">
            <i class="fas fa-history"></i>
            <p>No ${type} orders found</p>
        </div>`;
    }

    return orders.map(order => {
        // Ensure order has items array
        if (!order.items || !Array.isArray(order.items) || order.items.length === 0) {
            console.error('Order has no items:', order);
            order.items = [{ dish_name: 'Unknown Item', quantity: 1, price: 0, image: '' }];
        }

        // Ensure each item has the required properties
        order.items = order.items.map(item => {
            return {
                dish_name: item.dish_name || 'Unknown Item',
                quantity: item.quantity || 1,
                price: item.price || 0,
                image: item.image || '',
                dish_id: item.dish_id || null,
                is_vegetarian: item.is_vegetarian !== undefined ? item.is_vegetarian : null
            };
        });

        // Get status badge class
        const statusClass = getStatusBadgeClass(order.status);

        // Format status text with proper capitalization
        const statusText = order.status.charAt(0).toUpperCase() + order.status.slice(1).replace('_', ' ');

        // Format date
        const orderDate = formatDate(order.created_at);

        return `
        <div class="order-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center">
                        ${order.items[0].image ?
                            `<img src="${order.items[0].image}" alt="${order.items[0].dish_name}"
                                  class="order-dish-image me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">` :
                            `<div class="placeholder-image me-2" style="width:50px;height:50px;background:#eee;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-utensils text-muted"></i>
                             </div>`}
                        <div>
                            <h6 class="mb-0">${order.items[0].dish_name}</h6>
                            <small class="text-muted">Order #${order.id}</small>
                        </div>
                    </div>
                    <span class="badge ${statusClass}">${statusText}</span>
                </div>

                <div class="order-details">
                    ${order.items.map(item => `
                        <div class="order-item">
                            <div class="d-flex align-items-center">
                                ${item.image ?
                                    `<img src="${item.image}" alt="${item.dish_name}"
                                          class="order-item-image me-2" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">` :
                                    ''}
                                <span>${item.quantity}x ${item.dish_name}</span>
                            </div>
                            <span class="item-price">₹${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                    `).join('')}
                </div>

                <div class="order-footer mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">${orderDate}</small>
                            ${order.payment_method === 'wallet' ?
                                '<i class="fas fa-wallet text-success ms-2"></i>' :
                                '<i class="fas fa-money-bill-wave text-primary ms-2"></i>'}
                            <small class="ms-1">${order.payment_method.charAt(0).toUpperCase() + order.payment_method.slice(1)}</small>
                        </div>
                        <div class="text-end">
                            <strong>Total: ₹${parseFloat(order.total_amount).toFixed(2)}</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        ${order.can_cancel ? `
                            <button class="btn btn-sm btn-outline-danger cancel-order-btn" data-id="${order.id}">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        ` :
                        type === 'completed' ? `
                            <button class="btn btn-sm btn-outline-success add-review-btn" data-id="${order.id}">
                                <i class="fas fa-star"></i> Review
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-primary view-order-btn" data-id="${order.id}">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;
    }).join('');
}

/**
 * Render order card
 * @param {Object} order - The order data
 * @param {string} type - The type of order (active, completed, cancelled)
 * @returns {string} HTML for the order card
 */
function renderOrderCard(order, type) {
    // Log the order data for debugging
    console.log(`Rendering order card for order ID ${order.id}, type: ${type}`, order);

    // Handle potential missing data with defaults
    const status = (order.status || '').toLowerCase();

    // Handle different ID field names
    const orderId = order.id || order.order_id || order.booking_id || 'Unknown';

    // Handle different date field names and formats
    let createdAt = 'Unknown date';
    let createdTime = '';

    if (order.created_at) {
        try {
            createdAt = new Date(order.created_at).toLocaleDateString();
            createdTime = new Date(order.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } catch (e) {
            console.error('Error parsing created_at date:', e);
        }
    } else if (order.createdAt) {
        try {
            createdAt = new Date(order.createdAt).toLocaleDateString();
            createdTime = new Date(order.createdAt).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } catch (e) {
            console.error('Error parsing createdAt date:', e);
        }
    } else if (order.date) {
        try {
            createdAt = new Date(order.date).toLocaleDateString();
            createdTime = new Date(order.date).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        } catch (e) {
            console.error('Error parsing date:', e);
        }
    } else if (order.booking_date) {
        try {
            createdAt = new Date(order.booking_date).toLocaleDateString();
            // No time available in booking_date typically
        } catch (e) {
            console.error('Error parsing booking_date:', e);
        }
    }

    // Handle different amount field names
    const totalAmount = order.total_amount || order.totalAmount || order.amount || order.price || 0;

    // Calculate item count from items array if not provided
    let itemCount = order.item_count || order.itemCount || 0;

    // Ensure items array exists - check different possible field names
    let items = [];
    if (Array.isArray(order.items)) {
        items = order.items;
    } else if (Array.isArray(order.orderItems)) {
        items = order.orderItems;
    } else if (Array.isArray(order.booking_items)) {
        items = order.booking_items;
    } else if (order.items && typeof order.items === 'string') {
        // Handle case where items might be a JSON string
        try {
            const parsedItems = JSON.parse(order.items);
            if (Array.isArray(parsedItems)) {
                items = parsedItems;
            }
        } catch (e) {
            console.error('Failed to parse items JSON string:', e);
        }
    }

    // If item_count is not provided but items array exists, use its length
    if (itemCount === 0 && items.length > 0) {
        itemCount = items.length;
    }

    // Default can_cancel to false if not specified
    // Only pending orders can be cancelled unless explicitly allowed
    const canCancel = order.can_cancel === true || status === 'pending';

    // Status classes with improved colors
    const statusClass =
        status === 'pending' ? 'status-pending' :
        status === 'confirmed' ? 'status-confirmed' :
        status === 'preparing' ? 'status-preparing' :
        status === 'out_for_delivery' ? 'status-out-for-delivery' :
        status === 'delivered' ? 'status-delivered' :
        'status-cancelled';

    // Status text with proper capitalization
    const statusText =
        status === 'pending' ? 'Pending' :
        status === 'confirmed' ? 'Confirmed' :
        status === 'preparing' ? 'Preparing' :
        status === 'out_for_delivery' ? 'Out for Delivery' :
        status === 'delivered' ? 'Delivered' :
        'Cancelled';

    // Create a formatted item list (limit to 2 items + "and more")
    let itemsText = '';
    if (items.length > 0) {
        // Get first 2 items
        const displayItems = items.slice(0, 2);

        itemsText = displayItems.map(item => {
            // Handle different field names for quantity and dish name
            const quantity = item.quantity || item.qty || 1;
            const dishName = item.dish_name || item.dishName || item.name || item.product_name || item.productName || 'Unknown dish';
            return `${quantity}x ${dishName}`;
        }).join(', ');

        // Add "and more" if there are more items
        if (items.length > 2) {
            itemsText += `, and ${items.length - 2} more`;
        }
    } else {
        itemsText = 'No items available';
    }

    // Format payment method - handle different field names
    let paymentMethodText = 'Not specified';
    const paymentMethod = order.payment_method || order.paymentMethod || order.payment_type || '';

    if (paymentMethod === 'cash' || paymentMethod === 'cod') {
        paymentMethodText = 'Cash on Delivery';
    } else if (paymentMethod === 'wallet') {
        paymentMethodText = 'Wallet';
    } else if (paymentMethod === 'razorpay') {
        paymentMethodText = 'Razorpay';
    } else if (paymentMethod) {
        // Capitalize first letter
        paymentMethodText = paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1);
    }

    // Get delivery slot if available
    let deliverySlotText = '';
    if (order.delivery_slot) {
        deliverySlotText = `
            <div class="mb-2">
                <span class="text-muted small">
                    <i class="fas fa-clock"></i> ${order.delivery_slot}
                </span>
            </div>
        `;
    }

    // Get booking date if available
    let bookingDateText = '';
    if (order.booking_date && order.booking_date !== createdAt) {
        try {
            const formattedDate = new Date(order.booking_date).toLocaleDateString();
            bookingDateText = `
                <div class="mb-2">
                    <span class="text-muted small">
                        <i class="fas fa-calendar-check"></i> Delivery on ${formattedDate}
                    </span>
                </div>
            `;
        } catch (e) {
            console.error('Error formatting booking_date:', e);
        }
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
                        <i class="far fa-calendar-alt"></i> ${createdAt} ${createdTime}
                    </span>
                    <span class="fw-bold">₹${parseFloat(totalAmount).toFixed(2)}</span>
                </div>

                <div class="order-items mb-2">
                    <p class="mb-1"><strong>${itemCount} item${itemCount !== 1 ? 's' : ''}</strong></p>
                    <p class="text-muted small mb-0">
                        ${itemsText}
                    </p>
                </div>

                <div class="mb-2">
                    <span class="text-muted small">
                        <i class="fas fa-money-bill-wave"></i> ${paymentMethodText}
                    </span>
                </div>

                ${bookingDateText}
                ${deliverySlotText}

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

    console.log('Showing details for order ID:', orderId);

    getOrderDetails(orderId)
        .then(order => {
            console.log('Order details received:', order);

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
                            showToast('Order cancelled successfully!', 'success');
                            loadOrders();
                        })
                        .catch(error => {
                            console.error('Error in cancel order flow:', error);
                            // Show error message if available
                            if (error.response && error.response.data && error.response.data.message) {
                                showToast(error.response.data.message, 'error');
                            } else {
                                showToast('Failed to cancel order. Please try again.', 'error');
                            }
                            // Reload orders anyway to refresh the UI
                            loadOrders();
                        });
                }
            });

            // Add event listener for add review button in modal
            $('.add-review-modal-btn').on('click', function() {
                const reviewOrderId = $(this).data('id');
                modal.hide();
                showAddReviewModal(reviewOrderId);
            });

            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            console.error('Error loading order details:', error);
            console.error('Error details:', error.response ? error.response.data : 'No response data');

            let errorMessage = 'Failed to load order details. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }

            showToast(errorMessage, 'error');
        });
}

/**
 * Render order details modal
 * @param {Object} order - The order data
 * @returns {string} HTML for the order details modal
 */
function renderOrderDetailsModal(order) {
    console.log('Rendering order details modal for order:', order);

    // Handle potential missing data with defaults
    const status = (order.status || '').toLowerCase();
    const orderId = order.id || order.order_id || order.booking_id || 'Unknown';

    // Format the created date
    const formattedDate = formatDate(order.created_at);

    // Handle different amount formats
    const totalAmount = order.total_amount || order.totalAmount || order.amount || 0;

    // Format booking date
    let bookingDate = 'Not specified';
    if (order.booking_date) {
        bookingDate = formatDate(order.booking_date);
    }

    const deliverySlot = order.delivery_slot || 'Not specified';
    const paymentMethod = order.payment_method || order.paymentMethod || 'Not specified';

    // Ensure items array exists - check different possible field names
    let items = [];
    if (Array.isArray(order.items)) {
        items = order.items;
    } else if (Array.isArray(order.orderItems)) {
        items = order.orderItems;
    } else if (Array.isArray(order.booking_items)) {
        items = order.booking_items;
    } else if (order.items && typeof order.items === 'string') {
        // Handle case where items might be a JSON string
        try {
            const parsedItems = JSON.parse(order.items);
            if (Array.isArray(parsedItems)) {
                items = parsedItems;
            }
        } catch (e) {
            console.error('Failed to parse items JSON string:', e);
        }
    }

    // Default can_cancel to false if not specified
    // Only pending orders can be cancelled unless explicitly allowed
    const canCancel = order.can_cancel === true || status === 'pending';

    // Status classes with improved colors
    const statusClass =
        status === 'pending' ? 'status-pending' :
        status === 'confirmed' ? 'status-confirmed' :
        status === 'preparing' ? 'status-preparing' :
        status === 'out_for_delivery' ? 'status-out-for-delivery' :
        status === 'delivered' ? 'status-delivered' :
        'status-cancelled';

    // Status text with proper capitalization
    const statusText =
        status === 'pending' ? 'Pending' :
        status === 'confirmed' ? 'Confirmed' :
        status === 'preparing' ? 'Preparing' :
        status === 'out_for_delivery' ? 'Out for Delivery' :
        status === 'delivered' ? 'Delivered' :
        'Cancelled';

    // Generate items HTML
    let itemsHtml = '';
    if (items && items.length > 0) {
        // Ensure each item has the required properties
        items = items.map(item => {
            return {
                dish_name: item.dish_name || item.dishName || item.name || item.product_name || 'Unknown dish',
                quantity: item.quantity || item.qty || 1,
                price: item.price || item.unit_price || 0,
                image: item.image || '',
                dish_id: item.dish_id || null,
                is_vegetarian: item.is_vegetarian !== undefined ? item.is_vegetarian : null
            };
        });

        itemsHtml = items.map(item => {
            // Handle different field names for quantity and dish name
            const quantity = item.quantity;
            const dishName = item.dish_name;
            const price = item.price;
            const subtotal = (price * quantity).toFixed(2);
            const imageUrl = item.image;

            // Check if the item has veg/non-veg information
            const isVeg = item.is_veg === true || item.isVeg === true || item.veg === true;
            const isNonVeg = item.is_veg === false || item.isVeg === false || item.veg === false;

            let vegBadge = '';
            if (isVeg) {
                vegBadge = '<span class="veg-badge">🟢</span>';
            } else if (isNonVeg) {
                vegBadge = '<span class="non-veg-badge">🔴</span>';
            }

            return `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        ${imageUrl ?
                            `<img src="${imageUrl}" alt="${dishName}"
                                  class="order-item-image me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px;">` :
                            `<div class="placeholder-image me-2" style="width:40px;height:40px;background:#eee;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-utensils text-muted"></i>
                             </div>`}
                        <div>
                            <div>${vegBadge} <span class="fw-bold">${quantity}x</span> ${dishName}</div>
                            <div class="text-muted small">₹${price} per item</div>
                        </div>
                    </div>
                    <span class="fw-bold">₹${subtotal}</span>
                </div>
            `;
        }).join('');
    } else {
        itemsHtml = '<p class="text-muted">No items available</p>';
    }

    // Format payment method text
    let paymentMethodText = 'Not specified';
    if (paymentMethod === 'cash' || paymentMethod === 'cod') {
        paymentMethodText = 'Cash on Delivery';
    } else if (paymentMethod === 'wallet') {
        paymentMethodText = 'Wallet';
    } else if (paymentMethod === 'razorpay') {
        paymentMethodText = 'Razorpay';
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
                                <i class="far fa-calendar-alt"></i> ${formattedDate}
                            </span>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>

                        <h6>Order Items</h6>
                        <div class="order-items-list mb-4">
                            ${itemsHtml}

                            <hr>

                            <div class="d-flex justify-content-between align-items-center fw-bold">
                                <span>Total</span>
                                <span>₹${parseFloat(totalAmount).toFixed(2)}</span>
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
    console.log('Showing add review modal for order ID:', orderId);

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

        // Validate comment length (max 80 characters as per requirements)
        if (comment && comment.length > 80) {
            showToast('Comment must be 80 characters or less.', 'error');
            return;
        }

        toggleLoading(true);
        addReview(orderId, rating, comment)
            .then(() => {
                modal.hide();
                loadOrders();
                toggleLoading(false);
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                toggleLoading(false);
                // Error message is already shown in addReview function
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
                        <h5 class="modal-title" id="addReviewModalLabel">Add Review for Order #${orderId}</h5>
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
                            <input type="hidden" id="review-order-id" value="${orderId}">
                        </div>

                        <div class="mb-3">
                            <label for="review-comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="review-comment" rows="3" maxlength="80" placeholder="Write your review here..."></textarea>
                            <div class="form-text">Maximum 80 characters</div>
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
