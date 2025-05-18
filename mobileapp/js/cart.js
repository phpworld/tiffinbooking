/**
 * Cart related functions for the Tiffin Delight mobile app
 */

/**
 * Get cart contents from the API
 * @returns {Promise} Promise that resolves with cart data
 */
function getCart() {
    return axios.get(API.CART.GET)
        .then(response => {
            const cartData = response.data.data;

            // Update cart badge with the item count
            if (cartData && cartData.items) {
                updateCartBadge(cartData.items.length);
            }

            return cartData;
        })
        .catch(error => {
            console.error('Error getting cart:', error);
            // Return empty cart on error
            return { items: [], total: 0 };
        });
}

/**
 * Add item to cart
 * @param {number} dishId - The ID of the dish to add
 * @param {number} quantity - The quantity to add
 * @returns {Promise} Promise that resolves with updated cart data
 */
function addToCart(dishId, quantity = 1) {
    toggleLoading(true);

    // Create FormData for compatibility with CodeIgniter
    const formData = new FormData();
    formData.append('dish_id', dishId);
    formData.append('quantity', quantity);

    console.log('Adding to cart:', dishId, quantity);

    return axios.post(API.CART.ADD, formData)
        .then(response => {
            toggleLoading(false);
            console.log('Add to cart response:', response.data);

            // Get the item count from the response or calculate it
            let itemCount = 0;

            if (response.data && response.data.data) {
                if (response.data.data.item_count) {
                    itemCount = response.data.data.item_count;
                } else if (response.data.data.items) {
                    // Calculate item count from items array
                    itemCount = response.data.data.items.length;
                }
            }

            // Update cart badge with the new count
            updateCartBadge(itemCount);

            // Reload cart to reflect changes
            loadCart();

            showToast('Item added to cart!', 'success');
            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            console.error('Add to cart error:', error);

            let errorMessage = 'Failed to add item to cart. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            } else if (error.response && error.response.data && error.response.data.messages) {
                errorMessage = error.response.data.messages.error || errorMessage;
            }

            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Update cart item quantity
 * @param {number} dishId - The ID of the dish to update
 * @param {number} quantity - The new quantity
 * @returns {Promise} Promise that resolves with updated cart data
 */
function updateCartItem(dishId, quantity) {
    toggleLoading(true);

    // Create FormData for compatibility with CodeIgniter
    const formData = new FormData();
    formData.append('dish_id', dishId);
    formData.append('quantity', quantity);

    console.log('Updating cart item:', dishId, quantity);

    return axios.post(API.CART.UPDATE, formData)
        .then(response => {
            toggleLoading(false);
            console.log('Update cart response:', response.data);

            showToast('Cart updated!', 'success');

            // Get the item count from the response
            let itemCount = 0;
            if (response.data && response.data.data && response.data.data.item_count) {
                itemCount = response.data.data.item_count;
            }

            updateCartBadge(itemCount);

            // Reload cart to reflect changes
            loadCart();

            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            console.error('Update cart error:', error);

            let errorMessage = 'Failed to update cart. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            } else if (error.response && error.response.data && error.response.data.messages) {
                errorMessage = error.response.data.messages.error || errorMessage;
            }

            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Remove item from cart
 * @param {number} dishId - The ID of the dish to remove
 * @returns {Promise} Promise that resolves with updated cart data
 */
function removeFromCart(dishId) {
    toggleLoading(true);

    // Create FormData for compatibility with CodeIgniter
    const formData = new FormData();
    formData.append('dish_id', dishId);

    console.log('Removing from cart:', dishId);

    return axios.post(API.CART.REMOVE, formData)
        .then(response => {
            toggleLoading(false);
            console.log('Remove from cart response:', response.data);

            showToast('Item removed from cart!', 'success');

            // Get the item count from the response
            let itemCount = 0;
            if (response.data && response.data.data && response.data.data.item_count) {
                itemCount = response.data.data.item_count;
            }

            updateCartBadge(itemCount);

            // Reload cart to reflect changes
            loadCart();

            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            console.error('Remove from cart error:', error);

            let errorMessage = 'Failed to remove item from cart. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            } else if (error.response && error.response.data && error.response.data.messages) {
                errorMessage = error.response.data.messages.error || errorMessage;
            }

            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Clear cart
 * @returns {Promise} Promise that resolves with empty cart data
 */
function clearCart() {
    toggleLoading(true);

    return axios.post(API.CART.CLEAR)
        .then(response => {
            toggleLoading(false);
            showToast('Cart cleared!', 'success');
            updateCartBadge(0);
            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            let errorMessage = 'Failed to clear cart. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }
            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Place order
 * @param {string} paymentMethod - The payment method (cash or wallet)
 * @returns {Promise} Promise that resolves with order data
 */
function placeOrder(paymentMethod) {
    toggleLoading(true);

    // Make sure payment method is not null
    if (!paymentMethod) {
        paymentMethod = 'cash'; // Default to cash if not specified
    }

    console.log('Placing order with payment method:', paymentMethod);

    // Get cart data to calculate total
    return getCart()
        .then(cartData => {
            // Calculate total
            let subtotal = 0;
            cartData.items.forEach(item => {
                subtotal += (parseFloat(item.price) * parseInt(item.quantity));
            });

            const deliveryFee = 0;
            const taxes = subtotal * 0.05; // 5% tax
            const total = subtotal + deliveryFee + taxes;

            // If payment method is wallet, check balance
            if (paymentMethod === 'wallet') {
                return getWalletData()
                    .then(walletData => {
                        if (walletData.balance < total) {
                            toggleLoading(false);
                            showToast('Insufficient wallet balance. Please add money to your wallet or choose a different payment method.', 'error');
                            throw new Error('Insufficient wallet balance');
                        }

                        // Create FormData for compatibility with CodeIgniter
                        const formData = new FormData();
                        formData.append('payment_method', paymentMethod);

                        // Proceed with order placement
                        return axios.post(API.BOOKING.PLACE_ORDER, formData);
                    });
            } else {
                // Create FormData for compatibility with CodeIgniter
                const formData = new FormData();
                formData.append('payment_method', paymentMethod);

                // Proceed with order placement
                return axios.post(API.BOOKING.PLACE_ORDER, formData);
            }
        })
        .then(response => {
            toggleLoading(false);
            console.log('Place order response:', response.data);

            // Get order data
            const orderData = response.data.data;

            // If payment method was wallet, update wallet balance in localStorage
            if (paymentMethod === 'wallet' && orderData) {
                // Get current user
                const user = getCurrentUser();
                const userId = user ? user.id : '';

                // Create a user-specific wallet key
                const walletKey = `wallet_balance_${userId}`;

                // Get current balance from API to ensure it's up-to-date
                getWalletData()
                    .then(walletData => {
                        // Update wallet balance in localStorage
                        if (walletData && walletData.balance !== undefined) {
                            localStorage.setItem(walletKey, walletData.balance.toString());

                            // Also update the wallet balance display if it exists
                            if ($('#wallet-balance').length) {
                                $('#wallet-balance').text(`₹${walletData.balance.toFixed(2)}`);
                            }

                            // Also update the wallet balance in checkout if it exists
                            if ($('#wallet-balance-checkout').length) {
                                $('#wallet-balance-checkout').text(`Balance: ₹${walletData.balance.toFixed(2)}`);
                            }
                        }
                    });
            }

            showToast('Order placed successfully!', 'success');
            updateCartBadge(0);

            // Show order success screen
            showOrderSuccess(orderData);

            return orderData;
        })
        .catch(error => {
            toggleLoading(false);
            console.error('Place order error:', error);

            if (error.message === 'Insufficient wallet balance') {
                // Already showed toast
                return;
            }

            let errorMessage = 'Failed to place order. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            } else if (error.response && error.response.data && error.response.data.messages) {
                errorMessage = error.response.data.messages.error || errorMessage;
            }

            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Update cart badge count
 * @param {number} count - The new count
 */
function updateCartBadge(count) {
    // Make sure count is a number
    count = parseInt(count) || 0;

    // Update all cart badges
    $('.cart-badge').text(count);

    // Show/hide badges based on count
    if (count > 0) {
        $('.cart-badge').removeClass('d-none');
    } else {
        $('.cart-badge').addClass('d-none');
    }

    // Log for debugging
    console.log('Cart badge updated:', count);
}

/**
 * Load cart data and render cart screen
 */
function loadCart() {
    toggleLoading(true);

    getCart()
        .then(cartData => {
            renderCart(cartData);
            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            showToast('Failed to load cart. Please try again.', 'error');
            console.error('Error loading cart:', error);
        });
}

/**
 * Render cart screen
 * @param {Object} cartData - The cart data
 */
function renderCart(cartData) {
    // Ensure cartData has the required properties
    const items = cartData.items || [];
    const item_count = items.length;

    // Calculate total manually to ensure it's correct
    let calculatedTotal = 0;
    items.forEach(item => {
        calculatedTotal += (parseFloat(item.price) * parseInt(item.quantity));
    });

    const total = calculatedTotal;

    // Update cart badge
    updateCartBadge(item_count);

    let html = '';

    if (items.length === 0) {
        html = `
            <div class="cart-empty">
                <div class="cart-empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h4>Your cart is empty</h4>
                <p class="text-muted">Add some delicious dishes to your cart!</p>
                <button class="btn btn-success mt-3" id="browse-menu-btn">
                    <i class="fas fa-utensils"></i> Browse Menu
                </button>
            </div>
        `;
    } else {
        html = `
            <div class="cart-items mb-4">
                ${items.map(item => renderCartItem(item)).join('')}
            </div>

            <div class="cart-summary mb-4">
                <div class="cart-summary-row">
                    <span>Subtotal</span>
                    <span>₹${total.toFixed(2)}</span>
                </div>
                <div class="cart-summary-row">
                    <span>Delivery Fee</span>
                    <span>₹0.00</span>
                </div>
                <div class="cart-summary-row cart-summary-total">
                    <span>Total</span>
                    <span>₹${total.toFixed(2)}</span>
                </div>
            </div>

            <div class="d-grid gap-2 mb-4">
                <button class="btn btn-success btn-lg" id="proceed-to-checkout-btn">
                    <i class="fas fa-shopping-bag"></i> Proceed to Checkout
                </button>
                <button class="btn btn-outline-primary" id="continue-shopping-btn">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </button>
            </div>
        `;
    }

    $('#cart-container').html(html);

    // Add event listeners
    $('#browse-menu-btn, #continue-shopping-btn').on('click', function() {
        $('.nav-item').removeClass('active');
        $('.nav-item[data-screen="menu-screen"]').addClass('active');

        $('.content-screen').removeClass('active');
        $('#menu-screen').addClass('active');

        loadDishes();
    });

    $('.cart-quantity-btn').on('click', function() {
        const dishId = $(this).data('id');
        const action = $(this).data('action');
        const quantityInput = $(this).closest('.cart-quantity-control').find('.cart-quantity-input');
        let quantity = parseInt(quantityInput.val());

        if (action === 'decrease' && quantity > 1) {
            quantity--;
        } else if (action === 'increase' && quantity < 10) {
            quantity++;
        }

        quantityInput.val(quantity);
        updateCartItem(dishId, quantity);
    });

    $('.cart-remove-btn').on('click', function() {
        const dishId = $(this).data('id');
        removeFromCart(dishId);
    });

    $('#proceed-to-checkout-btn').on('click', function() {
        // Show checkout screen
        $('.content-screen').removeClass('active');
        $('#checkout-screen').addClass('active');

        // Load checkout data
        loadCheckout(cartData);
    });
}

/**
 * Render cart item
 * @param {Object} item - The cart item data
 * @returns {string} HTML for the cart item
 */
function renderCartItem(item) {
    const vegBadge = item.is_vegetarian
        ? '<span class="veg-badge"></span>'
        : '<span class="non-veg-badge"></span>';

    return `
        <div class="cart-item-card">
            <div class="cart-item-content">
                <img src="${item.image || 'img/default-dish.svg'}" class="cart-item-image" alt="${item.name}">
                <div class="cart-item-details">
                    <div class="cart-item-title">
                        ${vegBadge} ${item.name}
                    </div>
                    <div class="cart-item-price">₹${item.price}</div>
                    <div class="cart-item-actions">
                        <div class="cart-quantity-control">
                            <button class="cart-quantity-btn" data-id="${item.id}" data-action="decrease">-</button>
                            <input type="number" class="cart-quantity-input" value="${item.quantity}" min="1" max="10" readonly>
                            <button class="cart-quantity-btn" data-id="${item.id}" data-action="increase">+</button>
                        </div>
                        <button class="cart-remove-btn" data-id="${item.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Load checkout data
 * @param {Object} cartData - The cart data
 */
function loadCheckout(cartData) {
    // Get user data for delivery address
    const user = getCurrentUser();

    // Set delivery address
    $('#delivery-address').html(`
        <p class="mb-1"><strong>${user.name}</strong></p>
        <p class="mb-1">${user.phone}</p>
        <p class="mb-0">${user.address}</p>
    `);

    // Render checkout items
    let checkoutItemsHtml = '';
    cartData.items.forEach(item => {
        const vegBadge = item.is_vegetarian
            ? '<span class="veg-badge"></span>'
            : '<span class="non-veg-badge"></span>';

        checkoutItemsHtml += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <span class="fw-bold">${item.quantity}x</span> ${vegBadge} ${item.name}
                </div>
                <span>₹${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</span>
            </div>
        `;
    });

    $('#checkout-items').html(checkoutItemsHtml);

    // Calculate totals manually to ensure they're correct
    let subtotal = 0;
    cartData.items.forEach(item => {
        subtotal += (parseFloat(item.price) * parseInt(item.quantity));
    });

    const deliveryFee = 0;
    const taxes = subtotal * 0.05; // 5% tax
    const total = subtotal + deliveryFee + taxes;

    $('#checkout-subtotal').text(`₹${subtotal.toFixed(2)}`);
    $('#checkout-delivery-fee').text(`₹${deliveryFee.toFixed(2)}`);
    $('#checkout-taxes').text(`₹${taxes.toFixed(2)}`);
    $('#checkout-total').text(`₹${total.toFixed(2)}`);

    // Get wallet balance and update the checkout page
    getWalletData()
        .then(walletData => {
            if (walletData && walletData.balance !== undefined) {
                $('#wallet-balance-checkout').text(`Balance: ₹${walletData.balance.toFixed(2)}`);

                // If wallet balance is less than total, disable wallet option and show warning
                if (walletData.balance < total) {
                    $('.payment-option[data-payment="wallet"]').addClass('disabled');
                    $('.payment-option[data-payment="wallet"] .payment-option-balance').addClass('text-danger');
                    $('.payment-option[data-payment="wallet"] .payment-option-balance').text(`Insufficient balance: ₹${walletData.balance.toFixed(2)}`);

                    // Select cash option by default
                    $('.payment-option').removeClass('active');
                    $('.payment-option[data-payment="cash"]').addClass('active');
                }
            }
        })
        .catch(error => {
            console.error('Error getting wallet data for checkout:', error);
            // If we can't get wallet data, disable wallet option
            $('.payment-option[data-payment="wallet"]').addClass('disabled');
            $('.payment-option[data-payment="wallet"] .payment-option-balance').addClass('text-danger');
            $('.payment-option[data-payment="wallet"] .payment-option-balance').text('Unable to verify balance');

            // Select cash option by default
            $('.payment-option').removeClass('active');
            $('.payment-option[data-payment="cash"]').addClass('active');
        });

    // Add event listeners
    $('.payment-option').on('click', function() {
        // Don't allow selecting disabled payment options
        if ($(this).hasClass('disabled')) {
            showToast('Insufficient wallet balance. Please add money to your wallet or choose a different payment method.', 'error');
            return;
        }

        $('.payment-option').removeClass('active');
        $(this).addClass('active');
    });

    $('#back-to-cart-btn').on('click', function() {
        $('.content-screen').removeClass('active');
        $('#cart-screen').addClass('active');
    });

    $('#place-order-btn').on('click', function() {
        const paymentMethod = $('.payment-option.active').data('payment');

        // Check if a payment method is selected
        if (!paymentMethod) {
            showToast('Please select a payment method.', 'error');
            return;
        }

        // Place order
        placeOrder(paymentMethod);
    });

    $('#change-address-btn').on('click', function() {
        // Show address change modal
        showAddressChangeModal(user);
    });
}

/**
 * Show address change modal
 * @param {Object} user - The user data
 */
function showAddressChangeModal(user) {
    // Remove any existing modal
    $('#addressChangeModal').remove();

    // Add the modal to the DOM
    $('body').append(`
        <div class="modal fade" id="addressChangeModal" tabindex="-1" aria-labelledby="addressChangeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addressChangeModalLabel">Change Delivery Address</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="address-change-form">
                            <div class="mb-3">
                                <label for="address-name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="address-name" value="${user.name}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address-phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="address-phone" value="${user.phone}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address-full" class="form-label">Full Address</label>
                                <textarea class="form-control" id="address-full" rows="3" required>${user.address}</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" id="save-address-btn">Save Address</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    // Initialize the modal
    const modal = new bootstrap.Modal(document.getElementById('addressChangeModal'));
    modal.show();

    // Add event listener for save button
    $('#save-address-btn').on('click', function() {
        const name = $('#address-name').val();
        const phone = $('#address-phone').val();
        const address = $('#address-full').val();

        if (!name || !phone || !address) {
            showToast('Please fill all fields', 'error');
            return;
        }

        // Update delivery address
        $('#delivery-address').html(`
            <p class="mb-1"><strong>${name}</strong></p>
            <p class="mb-1">${phone}</p>
            <p class="mb-0">${address}</p>
        `);

        modal.hide();
    });
}

/**
 * Show order success screen
 * @param {Object} orderData - The order data
 */
function showOrderSuccess(orderData) {
    console.log('Showing order success with data:', orderData);

    // Ensure orderData is valid
    if (!orderData) {
        orderData = {
            id: 'N/A',
            total_amount: 0,
            payment_method: 'cash'
        };
    }

    // Get cart data to show correct amount if orderData doesn't have it
    getCart()
        .then(cartData => {
            // Calculate total if not available in orderData
            let total = orderData.total_amount;

            if (!total && cartData && cartData.items) {
                let subtotal = 0;
                cartData.items.forEach(item => {
                    subtotal += (parseFloat(item.price) * parseInt(item.quantity));
                });

                const deliveryFee = 0;
                const taxes = subtotal * 0.05; // 5% tax
                total = subtotal + deliveryFee + taxes;
            }

            // Set order details
            $('#success-order-id').text(orderData.id ? `#${orderData.id}` : 'Processing');
            $('#success-order-date').text(new Date().toLocaleDateString());
            $('#success-order-amount').text(`₹${total.toFixed(2)}`);
            $('#success-payment-method').text(orderData.payment_method === 'wallet' ? 'Wallet' : 'Cash on Delivery');
        })
        .catch(error => {
            console.error('Error getting cart data for order success:', error);

            // Set order details with available data
            $('#success-order-id').text(orderData.id ? `#${orderData.id}` : 'Processing');
            $('#success-order-date').text(new Date().toLocaleDateString());
            $('#success-order-amount').text(orderData.total_amount ? `₹${orderData.total_amount.toFixed(2)}` : 'Processing');
            $('#success-payment-method').text(orderData.payment_method === 'wallet' ? 'Wallet' : 'Cash on Delivery');
        });

    // Show order success screen
    $('.content-screen').removeClass('active');
    $('#order-success-screen').addClass('active');

    // Add event listeners
    $('#track-order-btn').off('click').on('click', function() {
        // Go to orders screen
        $('.nav-item').removeClass('active');
        $('.nav-item[data-screen="orders-screen"]').addClass('active');

        $('.content-screen').removeClass('active');
        $('#orders-screen').addClass('active');

        loadOrders();
    });

    $('#continue-shopping-btn').off('click').on('click', function() {
        // Go to menu screen
        $('.nav-item').removeClass('active');
        $('.nav-item[data-screen="menu-screen"]').addClass('active');

        $('.content-screen').removeClass('active');
        $('#menu-screen').addClass('active');

        loadDishes();
    });
}
