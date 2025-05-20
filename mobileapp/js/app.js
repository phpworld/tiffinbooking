/**
 * Main application script for the Tiffin Delight mobile app
 */

$(document).ready(function() {
    // Initialize the app
    initApp();

    // Event listeners
    setupEventListeners();
});

/**
 * Initialize the application
 */
function initApp() {
    // Show splash screen for 2 seconds
    setTimeout(function() {
        // Check if user is logged in
        if (isLoggedIn()) {
            // Load user data and show main app
            loadUserData();
            showMainApp();

            // Load app data
            loadAppData();

            // Initialize user data
            const user = getCurrentUser();
        } else {
            // Show login screen
            showLoginScreen();
        }

        // Hide splash screen
        $('#splash-screen').removeClass('active');
    }, 2000);
}

/**
 * Set up event listeners
 */
function setupEventListeners() {
    // Toggle password visibility
    $('.toggle-password').on('click', function() {
        const passwordInput = $(this).siblings('input');
        const icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Show login/register screens
    $('#show-login').on('click', function(e) {
        e.preventDefault();
        showLoginScreen();
    });

    $('#show-register').on('click', function(e) {
        e.preventDefault();
        showRegisterScreen();
    });

    // Login form submission
    $('#login-form').on('submit', function(e) {
        e.preventDefault();

        const email = $('#login-email').val();
        const password = $('#login-password').val();

        loginUser(email, password)
            .then(user => {
                // Clear form
                $('#login-form')[0].reset();
                $('#login-error').addClass('d-none');

                // Show main app
                showMainApp();

                // Load app data
                loadAppData();

                showToast('Login successful!', 'success');
            })
            .catch(error => {
                $('#login-error').removeClass('d-none').html(error.message);
            });
    });

    // Register form submission
    $('#register-form').on('submit', function(e) {
        e.preventDefault();

        const userData = {
            name: $('#register-name').val(),
            email: $('#register-email').val(),
            phone: $('#register-phone').val(),
            address: $('#register-address').val(),
            password: $('#register-password').val()
        };

        registerUser(userData)
            .then(user => {
                // Clear form
                $('#register-form')[0].reset();
                $('#register-error').addClass('d-none');

                // Show main app
                showMainApp();

                // Load app data
                loadAppData();

                showToast('Registration successful!', 'success');
            })
            .catch(error => {
                $('#register-error').removeClass('d-none').html(error.message);
            });
    });

    // Flyout menu toggle
    $('.menu-toggle').on('click', function() {
        $(this).toggleClass('active');
        $('.flyout-menu').toggleClass('active');
        $('.flyout-overlay').toggleClass('active');
    });

    // Flyout overlay click (close menu)
    $('.flyout-overlay').on('click', function() {
        $('.menu-toggle').removeClass('active');
        $('.flyout-menu').removeClass('active');
        $('.flyout-overlay').removeClass('active');
    });

    // Flyout menu items
    $('.flyout-menu-item').on('click', function(e) {
        e.preventDefault();

        const screenId = $(this).data('screen');

        // Update active menu item
        $('.flyout-menu-item').removeClass('active');
        $(this).addClass('active');

        // Close flyout menu
        $('.menu-toggle').removeClass('active');
        $('.flyout-menu').removeClass('active');
        $('.flyout-overlay').removeClass('active');

        // Show selected screen
        $('.content-screen').removeClass('active');
        $('#' + screenId).addClass('active');

        // Update bottom nav
        $('.nav-item').removeClass('active');
        $(`.nav-item[data-screen="${screenId}"]`).addClass('active');

        // Load screen data if needed
        if (screenId === 'menu-screen') {
            loadDishes();
        } else if (screenId === 'cart-screen') {
            loadCart();
        } else if (screenId === 'orders-screen') {
            loadOrders();
        } else if (screenId === 'profile-screen') {
            loadProfile();
        } else if (screenId === 'home-screen') {
            loadHomeScreen();
        } else if (screenId === 'wallet-screen') {
            loadWallet();
        }
    });

    // Flyout logout
    $('#flyout-logout').on('click', function(e) {
        e.preventDefault();

        if (confirm('Are you sure you want to logout?')) {
            logoutUser();
        }
    });

    // Bottom navigation
    $('.nav-item').on('click', function(e) {
        e.preventDefault();

        const screenId = $(this).data('screen');

        // Update active nav item
        $('.nav-item').removeClass('active');
        $(this).addClass('active');

        // Update flyout menu
        $('.flyout-menu-item').removeClass('active');
        $(`.flyout-menu-item[data-screen="${screenId}"]`).addClass('active');

        // Show selected screen
        $('.content-screen').removeClass('active');
        $('#' + screenId).addClass('active');

        // Load screen data if needed
        if (screenId === 'menu-screen') {
            loadDishes();
        } else if (screenId === 'cart-screen') {
            loadCart();
        } else if (screenId === 'orders-screen') {
            loadOrders();
        } else if (screenId === 'profile-screen') {
            loadProfile();
        } else if (screenId === 'home-screen') {
            loadHomeScreen();
        }
    });

    // Cart button in header
    $(document).on('click', '[id^="cart-btn"]', function() {
        $('.nav-item').removeClass('active');
        $('.nav-item[data-screen="cart-screen"]').addClass('active');

        $('.content-screen').removeClass('active');
        $('#cart-screen').addClass('active');

        loadCart();
    });

    // Category chips click event
    $(document).on('click', '.category-chip', function() {
        $('.category-chip').removeClass('active');
        $(this).addClass('active');

        // Filter dishes based on vegetarian status
        if ($(this).hasClass('veg-filter')) {
            // Show only vegetarian dishes
            $('.dish-card, .dish-card-horizontal').each(function() {
                const isVeg = $(this).find('.veg-badge').length > 0;
                $(this).toggle(isVeg);
            });
        } else if ($(this).hasClass('non-veg-filter')) {
            // Show only non-vegetarian dishes
            $('.dish-card, .dish-card-horizontal').each(function() {
                const isNonVeg = $(this).find('.non-veg-badge').length > 0;
                $(this).toggle(isNonVeg);
            });
        } else {
            // Show all dishes
            $('.dish-card, .dish-card-horizontal').show();
        }
    });

    // Dish card click events
    $(document).on('click', '.dish-card, .dish-card-horizontal', function() {
        const dishId = $(this).data('id');
        showDishDetails(dishId);
    });

    // Add to cart button click
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.stopPropagation(); // Prevent triggering the parent card click

        const dishId = $(this).data('id');
        addToCart(dishId, 1);
    });

    // Logout button
    $(document).on('click', '#logout-button', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to logout?')) {
            logoutUser();
        }
    });

    // Wallet quick recharge options
    $(document).on('click', '.recharge-amount-option', function() {
        $('.recharge-amount-option').removeClass('active');
        $(this).addClass('active');
    });
}

/**
 * Load user data
 */
function loadUserData() {
    // Get user data from local storage
    const user = getCurrentUser();

    if (user) {
        // Update UI with user data
        $('.user-name').text(user.name);
        $('.user-email').text(user.email);

        // Set user avatar initial
        $('.profile-avatar').text(user.name.charAt(0).toUpperCase());

        // Update flyout menu
        $('#flyout-avatar').text(user.name.charAt(0).toUpperCase());
        $('#flyout-user-name').text(user.name);
        $('#flyout-user-email').text(user.email);
    }
}

/**
 * Load all app data
 */
function loadAppData() {
    loadUserData();
    loadHomeScreen();

    // Initialize cart badge
    updateCartBadge(0);

    // Load cart data to update badge
    getCart()
        .then(cartData => {
            updateCartBadge(cartData.item_count);
        })
        .catch(error => {
            console.error('Error loading cart data:', error);
        });

    // Force update wallet display if on wallet screen
    if ($('#wallet-screen').hasClass('active')) {
        loadWallet();
    }
}

/**
 * Load home screen data
 */
function loadHomeScreen() {
    toggleLoading(true);

    // Initialize banner slider
    initBannerSlider();

    // Get all dishes - with error handling for each step
    loadAllDishes()
        .then(dishes => {
            // Render popular dishes
            renderPopularDishes(dishes);

            // Render recommended dishes
            renderRecommendedDishes(dishes);

            // No longer loading orders on home screen
            toggleLoading(false);
        })
        .catch(error => {
            console.error('Error loading dishes:', error);

            // Show empty states for dishes sections
            $('#popular-dishes, #recommended-dishes').html(`
                <div class="text-center w-100 py-3">
                    <p class="text-muted mb-0">Failed to load dishes. Please try again later.</p>
                </div>
            `);

            toggleLoading(false);
            showToast('Some content could not be loaded. Please try again later.', 'error');
        });
}

/**
 * Initialize banner slider
 */
function initBannerSlider() {
    // Fetch banners from API
    fetch(CONFIG.API_BASE_URL + '/banners')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data.length > 0) {
                // Render banners
                const bannerSlider = document.querySelector('.banner-slider .swiper-wrapper');
                bannerSlider.innerHTML = '';

                data.data.forEach(banner => {
                    const slide = document.createElement('div');
                    slide.className = 'swiper-slide';

                    let buttonHtml = '';
                    if (banner.button_text && banner.button_link) {
                        buttonHtml = `
                            <div class="banner-button">
                                <a href="${banner.button_link}" class="btn btn-primary">${banner.button_text}</a>
                            </div>
                        `;
                    }

                    slide.innerHTML = `
                        <div class="banner-slide">
                            <img src="${banner.image}" alt="${banner.title}">
                            <div class="banner-content">
                                <h3>${banner.title}</h3>
                                ${banner.subtitle ? `<p>${banner.subtitle}</p>` : ''}
                                ${buttonHtml}
                            </div>
                        </div>
                    `;

                    bannerSlider.appendChild(slide);
                });
            }

            // Initialize Swiper
            new Swiper('.banner-slider', {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        })
        .catch(error => {
            console.error('Error loading banners:', error);

            // Initialize Swiper with default banners
            new Swiper('.banner-slider', {
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
}

/**
 * Render popular dishes section
 * @param {Array} dishes - All dishes
 */
function renderPopularDishes(dishes) {
    // Sort dishes by rating (descending)
    const popularDishes = [...dishes].sort((a, b) => b.rating - a.rating).slice(0, 6);

    let html = `
        <div class="swiper dish-swiper popular-dishes-swiper">
            <div class="swiper-wrapper">
                ${popularDishes.map(dish => `
                    <div class="swiper-slide">
                        ${renderDishCardHorizontal(dish)}
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    $('#popular-dishes').html(html);

    // Initialize Swiper
    new Swiper('.popular-dishes-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 15,
        freeMode: true,
        grabCursor: true
    });
}

/**
 * Render recommended dishes section
 * @param {Array} dishes - All dishes
 */
function renderRecommendedDishes(dishes) {
    // For demo, just shuffle the dishes and take the first 6
    const shuffled = [...dishes].sort(() => 0.5 - Math.random());
    const recommendedDishes = shuffled.slice(0, 6);

    let html = `
        <div class="swiper dish-swiper recommended-dishes-swiper">
            <div class="swiper-wrapper">
                ${recommendedDishes.map(dish => `
                    <div class="swiper-slide">
                        ${renderDishCardHorizontal(dish)}
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    $('#recommended-dishes').html(html);

    // Initialize Swiper
    new Swiper('.recommended-dishes-swiper', {
        slidesPerView: 'auto',
        spaceBetween: 15,
        freeMode: true,
        grabCursor: true
    });
}



/**
 * Load dishes for menu screen
 */
function loadDishes() {
    toggleLoading(true);

    loadAllDishes()
        .then(dishes => {
            renderDishesGrid(dishes);
            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            showToast('Failed to load dishes. Please try again.', 'error');
            console.error('Error loading dishes:', error);
        });
}

/**
 * Render dishes grid for menu screen
 * @param {Array} dishes - All dishes
 */
function renderDishesGrid(dishes) {
    let html = '';
    dishes.forEach(dish => {
        html += renderDishCardGrid(dish);
    });

    $('#menu-items-grid').html(html);
}

/**
 * Get status badge class based on order status
 * @param {string} status - Order status
 * @returns {string} Badge class
 */
function getStatusBadgeClass(status) {
    // Convert status to lowercase for case-insensitive comparison
    const statusLower = status.toLowerCase();

    switch (statusLower) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'confirmed':
        case 'preparing':
        case 'out_for_delivery':
            return 'bg-info text-white';
        case 'delivered':
            return 'bg-success text-white';
        case 'cancelled':
            return 'bg-danger text-white';
        default:
            return 'bg-secondary text-white';
    }
}

/**
 * Get status text based on order status
 * @param {string} status - Order status
 * @returns {string} Status text
 */
function getStatusText(status) {
    // Convert status to lowercase for case-insensitive comparison
    const statusLower = status.toLowerCase();

    switch (statusLower) {
        case 'pending':
            return 'Pending';
        case 'confirmed':
            return 'Confirmed';
        case 'preparing':
            return 'Preparing';
        case 'out_for_delivery':
            return 'Out for Delivery';
        case 'delivered':
            return 'Delivered';
        case 'cancelled':
            return 'Cancelled';
        default:
            return status.charAt(0).toUpperCase() + status.slice(1);
    }
}

/**
 * Load wallet screen
 */
function loadWallet() {
    toggleLoading(true);

    // Get wallet data - the improved getWalletData function handles errors internally
    getWalletData()
        .then(walletData => {
            // Render wallet with the data (which may be default data if there was an error)
            renderWallet(walletData);
            toggleLoading(false);
        });
}

/**
 * Render wallet screen
 * @param {Object} walletData - Wallet data
 */
function renderWallet(walletData) {
    console.log('Rendering wallet with data:', walletData);

    // Get current user
    const user = getCurrentUser();
    const userId = user ? user.id : '';

    // Create a user-specific wallet key
    const walletKey = `wallet_balance_${userId}`;

    // Ensure we have a valid balance
    let balance = 0;

    if (walletData && typeof walletData.balance !== 'undefined') {
        // Parse the balance as a number to ensure it's treated correctly
        balance = parseFloat(walletData.balance);

        // Update the localStorage with the latest balance from API
        localStorage.setItem(walletKey, balance.toString());
    } else {
        // Try to get the balance from localStorage as a fallback
        const storedBalance = localStorage.getItem(walletKey);
        if (storedBalance) {
            balance = parseFloat(storedBalance);
        }
    }

    // Update wallet balance display
    $('#wallet-balance').text(`₹${balance.toFixed(2)}`);

    // Also update the wallet balance in checkout if it exists
    if ($('#wallet-balance-checkout').length) {
        $('#wallet-balance-checkout').text(`Balance: ₹${balance.toFixed(2)}`);
    }

    // Render recent transactions
    let transactionsHtml = '';

    if (walletData && walletData.transactions && walletData.transactions.length > 0) {
        const recentTransactions = walletData.transactions.slice(0, 5);

        recentTransactions.forEach(transaction => {
            const isCredit = transaction.type === 'credit';

            transactionsHtml += `
                <div class="transaction-item">
                    <div class="transaction-icon ${isCredit ? 'credit' : 'debit'}">
                        <i class="fas ${isCredit ? 'fa-arrow-down' : 'fa-arrow-up'}"></i>
                    </div>
                    <div class="transaction-details">
                        <div class="transaction-title">${transaction.description}</div>
                        <div class="transaction-date">${new Date(transaction.date).toLocaleDateString()}</div>
                    </div>
                    <div class="transaction-amount ${isCredit ? 'credit' : 'debit'}">
                        ${isCredit ? '+' : '-'}₹${parseFloat(transaction.amount).toFixed(2)}
                    </div>
                </div>
            `;
        });
    } else {
        transactionsHtml = `
            <div class="text-center py-4">
                <p class="text-muted mb-0">No recent transactions.</p>
            </div>
        `;
    }

    $('#recent-transactions').html(transactionsHtml);

    // Add event listeners
    $('#add-money-btn').off('click').on('click', function() {
        // Show recharge modal directly
        const amount = 100; // Default amount
        showRechargeModal(amount);
    });

    $('.recharge-amount-option').off('click').on('click', function() {
        $('.recharge-amount-option').removeClass('active');
        $(this).addClass('active');

        const amount = $(this).data('amount');
        showRechargeModal(amount);
    });

    $('#view-all-transactions').off('click').on('click', function() {
        showAllTransactionsModal(walletData.transactions || []);
    });
}

/**
 * Show recharge modal
 * @param {number} amount - Recharge amount
 */
function showRechargeModal(amount) {
    // Get user data
    const user = getCurrentUser();

    // Create Razorpay options
    const options = {
        key: 'rzp_test_XaZ89XsD6ejHqt', // Razorpay key provided by the user
        amount: amount * 100, // Amount in paise
        currency: 'INR',
        name: 'Tiffin Delight',
        description: 'Wallet Recharge',
        image: 'img/logo.svg',
        prefill: {
            name: user.name,
            email: user.email,
            contact: user.phone
        },
        theme: {
            color: '#4CAF50'
        },
        handler: function(response) {
            // On successful payment
            console.log('Razorpay payment successful:', response);

            // Process the recharge with payment ID
            processWalletRecharge(amount, response.razorpay_payment_id);
        },
        modal: {
            ondismiss: function() {
                console.log('Payment modal dismissed');
            }
        }
    };

    // Open Razorpay checkout
    try {
        const rzp = new Razorpay(options);
        rzp.open();
    } catch (error) {
        console.error('Error opening Razorpay:', error);
        showToast('Failed to open payment gateway. Please try again.', 'error');
    }
}

/**
 * Process wallet recharge after successful payment
 * @param {number} amount - Recharge amount
 * @param {string} paymentId - Razorpay payment ID
 */
function processWalletRecharge(amount, paymentId) {
    toggleLoading(true);

    console.log('Processing wallet recharge:', amount, paymentId);

    // Create FormData for compatibility with CodeIgniter
    const formData = new FormData();
    formData.append('amount', amount);
    formData.append('payment_id', paymentId);
    formData.append('payment_method', 'razorpay');

    // Force refresh the token to ensure we have the latest authentication
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);

    axios.post(API.WALLET.RECHARGE, formData, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        }
    })
    .then(response => {
        console.log('Wallet recharge response:', response.data);
        toggleLoading(false);

        if (response.data && response.data.data) {
            // Get current user
            const user = getCurrentUser();
            const userId = user ? user.id : '';

            // Create a user-specific wallet key
            const walletKey = `wallet_balance_${userId}`;

            // Update the wallet balance in localStorage with user-specific key
            const newBalance = parseFloat(response.data.data.balance);
            localStorage.setItem(walletKey, newBalance);

            // Show success message with the amount added
            showToast(`₹${amount} added to your wallet successfully!`, 'success');

            // Update user profile to get latest data
            getUserProfile()
                .then(() => {
                    // Reload wallet to show updated balance
                    loadWallet();
                })
                .catch(err => {
                    console.error('Error updating user profile after recharge:', err);
                    // Still reload wallet
                    loadWallet();
                });
        } else {
            showToast('Wallet recharged successfully!', 'success');
            loadWallet();
        }
    })
    .catch(error => {
        toggleLoading(false);
        console.error('Error processing wallet recharge:', error);

        let errorMessage = 'Failed to process recharge. Please contact support.';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        } else if (error.response && error.response.data && error.response.data.messages) {
            errorMessage = error.response.data.messages.error || errorMessage;
        }

        showToast(errorMessage, 'error');

        // Still reload wallet to ensure we have the latest balance
        loadWallet();
    });
}

/**
 * Show all transactions modal
 * @param {Array} transactions - All transactions
 */
function showAllTransactionsModal(transactions) {
    // Remove any existing modal
    $('#allTransactionsModal').remove();

    // Prepare transactions HTML
    let transactionsHtml = '';

    if (transactions && transactions.length > 0) {
        transactions.forEach(transaction => {
            const isCredit = transaction.type === 'credit';

            transactionsHtml += `
                <div class="transaction-item">
                    <div class="transaction-icon ${isCredit ? 'credit' : 'debit'}">
                        <i class="fas ${isCredit ? 'fa-arrow-down' : 'fa-arrow-up'}"></i>
                    </div>
                    <div class="transaction-details">
                        <div class="transaction-title">${transaction.description}</div>
                        <div class="transaction-date">${new Date(transaction.date).toLocaleDateString()}</div>
                    </div>
                    <div class="transaction-amount ${isCredit ? 'credit' : 'debit'}">
                        ${isCredit ? '+' : '-'}₹${transaction.amount.toFixed(2)}
                    </div>
                </div>
            `;
        });
    } else {
        transactionsHtml = `
            <div class="text-center py-4">
                <p class="text-muted mb-0">No transactions found.</p>
            </div>
        `;
    }

    // Add the modal to the DOM
    $('body').append(`
        <div class="modal fade" id="allTransactionsModal" tabindex="-1" aria-labelledby="allTransactionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allTransactionsModalLabel">Transaction History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="transactions-list">
                            ${transactionsHtml}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    `);

    // Initialize the modal
    const modal = new bootstrap.Modal(document.getElementById('allTransactionsModal'));
    modal.show();
}

/**
 * Helper function to render star rating
 * @param {number} rating - Rating value (0-5)
 * @returns {string} HTML for star rating
 */
function renderStarRating(rating) {
    let html = '';
    const fullStars = Math.floor(rating);
    const halfStar = rating % 1 >= 0.5;

    // Add full stars
    for (let i = 0; i < fullStars; i++) {
        html += '<i class="fas fa-star"></i>';
    }

    // Add half star if needed
    if (halfStar) {
        html += '<i class="fas fa-star-half-alt"></i>';
    }

    // Add empty stars
    const emptyStars = 5 - fullStars - (halfStar ? 1 : 0);
    for (let i = 0; i < emptyStars; i++) {
        html += '<i class="far fa-star"></i>';
    }

    return html;
}

/**
 * Helper function to truncate text
 * @param {string} text - Text to truncate
 * @param {number} maxLength - Maximum length
 * @returns {string} Truncated text
 */
function truncateText(text, maxLength) {
    if (!text) return '';
    return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
}
