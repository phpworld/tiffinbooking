/**
 * Authentication related functions for the Tiffin Delight mobile app
 */

/**
 * Check if user is logged in
 * @returns {boolean} True if user is logged in, false otherwise
 */
function isLoggedIn() {
    const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);
    return !!token;
}

/**
 * Get current user data
 * @returns {Object|null} User data object or null if not logged in
 */
function getCurrentUser() {
    const userData = localStorage.getItem(CONFIG.STORAGE_USER_KEY);
    return userData ? JSON.parse(userData) : null;
}

/**
 * Login user
 * @param {string} email - User email
 * @param {string} password - User password
 * @returns {Promise} Promise that resolves with user data or rejects with error
 */
function loginUser(email, password) {
    toggleLoading(true);

    return axios.post(API.AUTH.LOGIN, {
        email: email,
        password: password
    })
    .then(response => {
        const { data } = response.data;

        // Save token and user data to local storage
        localStorage.setItem(CONFIG.STORAGE_TOKEN_KEY, data.token);
        localStorage.setItem(CONFIG.STORAGE_USER_KEY, JSON.stringify(data.user));

        

        toggleLoading(false);
        return data.user;
    })
    .catch(error => {
        toggleLoading(false);

        let errorMessage = 'Login failed. Please try again.';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        }

        throw new Error(errorMessage);
    });
}

/**
 * Register new user
 * @param {Object} userData - User registration data
 * @returns {Promise} Promise that resolves with user data or rejects with error
 */
function registerUser(userData) {
    toggleLoading(true);

    return axios.post(API.AUTH.REGISTER, userData)
    .then(response => {
        const { data } = response.data;

        // Save token and user data to local storage
        localStorage.setItem(CONFIG.STORAGE_TOKEN_KEY, data.token);
        localStorage.setItem(CONFIG.STORAGE_USER_KEY, JSON.stringify(data.user));

        toggleLoading(false);
        return data.user;
    })
    .catch(error => {
        toggleLoading(false);

        let errorMessage = 'Registration failed. Please try again.';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        } else if (error.response && error.response.data && error.response.data.errors) {
            // Format validation errors
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors).join('<br>');
        }

        throw new Error(errorMessage);
    });
}

/**
 * Logout user
 */
function logoutUser() {
    // Clear local storage
    localStorage.removeItem(CONFIG.STORAGE_TOKEN_KEY);
    localStorage.removeItem(CONFIG.STORAGE_USER_KEY);

    // Redirect to login screen
    showLoginScreen();
    showToast('You have been logged out successfully.', 'success');
}

/**
 * Update user profile
 * @param {Object} userData - Updated user data
 * @returns {Promise} Promise that resolves with updated user data or rejects with error
 */
function updateUserProfile(userData) {
    toggleLoading(true);

    return axios.post(API.USER.UPDATE_PROFILE, userData)
    .then(response => {
        const { data } = response.data;

        // Update user data in local storage
        localStorage.setItem(CONFIG.STORAGE_USER_KEY, JSON.stringify(data));

        toggleLoading(false);
        return data;
    })
    .catch(error => {
        toggleLoading(false);

        let errorMessage = 'Profile update failed. Please try again.';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        } else if (error.response && error.response.data && error.response.data.errors) {
            // Format validation errors
            const errors = error.response.data.errors;
            errorMessage = Object.values(errors).join('<br>');
        }

        throw new Error(errorMessage);
    });
}

/**
 * Get user profile data from API
 * @returns {Promise} Promise that resolves with user profile data or rejects with error
 */
function getUserProfile() {
    toggleLoading(true);

    return axios.get(API.USER.PROFILE)
    .then(response => {
        const { data } = response.data;

        // Update user data in local storage
        localStorage.setItem(CONFIG.STORAGE_USER_KEY, JSON.stringify(data));

        toggleLoading(false);
        return data;
    })
    .catch(error => {
        toggleLoading(false);

        let errorMessage = 'Failed to get profile data. Please try again.';
        if (error.response && error.response.data && error.response.data.message) {
            errorMessage = error.response.data.message;
        }

        throw new Error(errorMessage);
    });
}

// Show login screen
function showLoginScreen() {
    $('.screen').removeClass('active');
    $('#login-screen').addClass('active');
}

// Show register screen
function showRegisterScreen() {
    $('.screen').removeClass('active');
    $('#register-screen').addClass('active');
}

// Show main app screen
function showMainApp() {
    $('.screen').removeClass('active');
    $('#main-app').addClass('active');

    // Set home screen as active by default
    $('.content-screen').removeClass('active');
    $('#home-screen').addClass('active');

    // Update bottom nav
    $('.nav-item').removeClass('active');
    $('.nav-item[data-screen="home-screen"]').addClass('active');
}
