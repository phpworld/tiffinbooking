/**
 * Profile related functions for the Tiffin Delight mobile app
 */

/**
 * Get user profile data from API
 * @returns {Promise} Promise that resolves with user profile data
 */
function getUserProfileData() {
    return axios.get(API.USER.PROFILE)
        .then(response => {
            return response.data.data;
        });
}

/**
 * Update user profile
 * @param {Object} userData - Updated user data
 * @returns {Promise} Promise that resolves with updated user data
 */
function updateUserProfileData(userData) {
    toggleLoading(true);

    return axios.post(API.USER.UPDATE_PROFILE, userData)
        .then(response => {
            toggleLoading(false);
            showToast('Profile updated successfully!', 'success');

            // Update user data in local storage
            const currentUser = getCurrentUser();
            const updatedUser = { ...currentUser, ...response.data.data };
            localStorage.setItem(CONFIG.STORAGE_USER_KEY, JSON.stringify(updatedUser));

            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            let errorMessage = 'Failed to update profile. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }
            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Get wallet data from API
 * @returns {Promise} Promise that resolves with wallet data
 */
function getWalletData() {
    // Force refresh the token to ensure we have the latest authentication
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);

    // Add a timestamp to prevent caching
    const timestamp = new Date().getTime();
    const url = `${API.WALLET.GET}?_=${timestamp}`;

    // Get current user
    const user = getCurrentUser();
    const userId = user ? user.id : '';

    // Create a user-specific wallet key
    const walletKey = `wallet_balance_${userId}`;

    // Make API call to get wallet data
    return axios.get(url, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        }
    })
    .then(response => {
        console.log('Wallet data response:', response.data);

        // Check if response has the expected structure
        if (response.data && response.data.data) {
            // Parse the balance as a number to ensure it's treated correctly
            const walletData = response.data.data;
            walletData.balance = parseFloat(walletData.balance);

            // Store the wallet balance in localStorage for quick access with user-specific key
            localStorage.setItem(walletKey, walletData.balance);

            return walletData;
        } else {
            console.warn('Wallet API returned unexpected data format');

            // Try to get the balance from localStorage as a fallback
            const storedBalance = localStorage.getItem(walletKey);
            const balance = storedBalance ? parseFloat(storedBalance) : 0;

            // Return default wallet data
            return {
                balance: balance,
                transactions: []
            };
        }
    })
    .catch(error => {
        console.error('Error fetching wallet data:', error);

        // Try to get the balance from localStorage as a fallback
        const storedBalance = localStorage.getItem(walletKey);
        const balance = storedBalance ? parseFloat(storedBalance) : 0;

        // Return default wallet data on error
        return {
            balance: balance,
            transactions: []
        };
    });
}

/**
 * Recharge wallet
 * @param {number} amount - Amount to recharge
 * @returns {Promise} Promise that resolves with updated wallet data
 */
function rechargeWallet(amount) {
    toggleLoading(true);

    return axios.post(API.WALLET.RECHARGE, {
        amount: amount
    })
        .then(response => {
            toggleLoading(false);
            showToast('Wallet recharged successfully!', 'success');
            return response.data.data;
        })
        .catch(error => {
            toggleLoading(false);
            let errorMessage = 'Failed to recharge wallet. Please try again.';
            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }
            showToast(errorMessage, 'error');
            throw error;
        });
}

/**
 * Load profile data and render profile screen
 */
function loadProfile() {
    toggleLoading(true);

    Promise.all([
        getUserProfileData(),
        getWalletData()
    ])
        .then(([profileData, walletData]) => {
            renderProfile(profileData, walletData);
            toggleLoading(false);
        })
        .catch(error => {
            toggleLoading(false);
            showToast('Failed to load profile data. Please try again.', 'error');
            console.error('Error loading profile data:', error);
        });
}

/**
 * Render profile screen
 * @param {Object} profileData - User profile data
 * @param {Object} walletData - Wallet data
 */
function renderProfile(profileData, walletData) {
    const html = `
        <div class="profile-header-section mb-4">
            <div class="text-center py-4">
                <div class="avatar-circle mx-auto mb-3">
                    ${profileData.name.charAt(0).toUpperCase()}
                </div>
                <h4>${profileData.name}</h4>
                <p class="text-muted mb-0">${profileData.email}</p>
            </div>
        </div>

        <div class="wallet-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Wallet Balance</h5>
                <button class="btn btn-sm btn-light" id="recharge-wallet-btn">
                    <i class="fas fa-plus-circle"></i> Recharge
                </button>
            </div>
            <div class="wallet-balance">₹${walletData.balance.toFixed(2)}</div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Personal Information</h5>

                <div class="mb-3">
                    <label class="form-label text-muted">Phone</label>
                    <p class="mb-0">${profileData.phone}</p>
                </div>

                <div class="mb-0">
                    <label class="form-label text-muted">Address</label>
                    <p class="mb-0">${profileData.address}</p>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Recent Transactions</h5>

                ${walletData.transactions && walletData.transactions.length > 0 ? `
                    <div class="transactions-list">
                        ${walletData.transactions.map(transaction => `
                            <div class="transaction-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <p class="mb-0 fw-bold">${transaction.description}</p>
                                    <small class="text-muted">${new Date(transaction.date).toLocaleDateString()}</small>
                                </div>
                                <span class="${transaction.type === 'credit' ? 'text-success' : 'text-danger'}">
                                    ${transaction.type === 'credit' ? '+' : '-'}₹${transaction.amount.toFixed(2)}
                                </span>
                            </div>
                        `).join('')}
                    </div>
                ` : `
                    <p class="text-muted">No recent transactions.</p>
                `}
            </div>
        </div>

        <div class="d-grid gap-2 mb-4">
            <button class="btn btn-outline-primary" id="edit-profile-button">
                <i class="fas fa-user-edit"></i> Edit Profile
            </button>
            <button class="btn btn-outline-danger" id="logout-button">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>
    `;

    $('#profile-container').html(html);

    // Add event listeners
    $('#edit-profile-button').on('click', function() {
        showEditProfileModal(profileData);
    });

    $('#recharge-wallet-btn').on('click', function() {
        showRechargeWalletModal();
    });

    $('#logout-button').on('click', function() {
        if (confirm('Are you sure you want to logout?')) {
            logoutUser();
        }
    });
}

/**
 * Show edit profile modal
 * @param {Object} profileData - User profile data
 */
function showEditProfileModal(profileData) {
    // Remove any existing modal
    $('#editProfileModal').remove();

    // Add the modal to the DOM
    $('body').append(renderEditProfileModal(profileData));

    // Initialize the modal
    const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    modal.show();

    // Add event listeners
    $('#update-profile-form').on('submit', function(e) {
        e.preventDefault();

        const userData = {
            name: $('#edit-name').val(),
            phone: $('#edit-phone').val(),
            address: $('#edit-address').val()
        };

        // Add email if changed
        const email = $('#edit-email').val();
        if (email !== profileData.email) {
            userData.email = email;
        }

        // Add password if provided
        const password = $('#edit-password').val();
        if (password) {
            userData.password = password;
        }

        updateUserProfileData(userData)
            .then(() => {
                modal.hide();
                loadProfile();
            });
    });
}

/**
 * Render edit profile modal
 * @param {Object} profileData - User profile data
 * @returns {string} HTML for the edit profile modal
 */
function renderEditProfileModal(profileData) {
    return `
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="update-profile-form">
                            <div class="mb-3">
                                <label for="edit-name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-name" value="${profileData.name}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit-email" value="${profileData.email}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="edit-phone" value="${profileData.phone}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit-address" class="form-label">Address</label>
                                <textarea class="form-control" id="edit-address" rows="3" required>${profileData.address}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit-password" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="edit-password">
                                <div class="form-text">Minimum 6 characters</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Show recharge wallet modal
 */
function showRechargeWalletModal() {
    // Remove any existing modal
    $('#rechargeWalletModal').remove();

    // Add the modal to the DOM
    $('body').append(renderRechargeWalletModal());

    // Initialize the modal
    const modal = new bootstrap.Modal(document.getElementById('rechargeWalletModal'));
    modal.show();

    // Add event listeners
    $('.amount-option').on('click', function() {
        $('.amount-option').removeClass('active');
        $(this).addClass('active');

        const amount = $(this).data('amount');
        $('#recharge-amount').val(amount);
    });

    $('#recharge-form').on('submit', function(e) {
        e.preventDefault();

        const amount = parseFloat($('#recharge-amount').val());

        if (!amount || amount <= 0) {
            showToast('Please enter a valid amount.', 'error');
            return;
        }

        rechargeWallet(amount)
            .then(() => {
                modal.hide();
                loadProfile();
            });
    });
}

/**
 * Render recharge wallet modal
 * @returns {string} HTML for the recharge wallet modal
 */
function renderRechargeWalletModal() {
    return `
        <div class="modal fade" id="rechargeWalletModal" tabindex="-1" aria-labelledby="rechargeWalletModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rechargeWalletModalLabel">Recharge Wallet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="recharge-form">
                            <div class="mb-3">
                                <label class="form-label">Select Amount</label>
                                <div class="row">
                                    <div class="col-4 mb-2">
                                        <div class="amount-option card text-center p-2" data-amount="100">
                                            <h5 class="mb-0">₹100</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-2">
                                        <div class="amount-option card text-center p-2" data-amount="200">
                                            <h5 class="mb-0">₹200</h5>
                                        </div>
                                    </div>
                                    <div class="col-4 mb-2">
                                        <div class="amount-option card text-center p-2" data-amount="500">
                                            <h5 class="mb-0">₹500</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="recharge-amount" class="form-label">Or Enter Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control" id="recharge-amount" min="1" step="1" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_upi" value="upi" checked>
                                    <label class="form-check-label" for="payment_upi">
                                        <i class="fas fa-mobile-alt text-primary me-2"></i> UPI
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card">
                                    <label class="form-check-label" for="payment_card">
                                        <i class="fas fa-credit-card text-success me-2"></i> Credit/Debit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_netbanking" value="netbanking">
                                    <label class="form-check-label" for="payment_netbanking">
                                        <i class="fas fa-university text-info me-2"></i> Net Banking
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Proceed to Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
}
