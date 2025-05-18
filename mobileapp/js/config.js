/**
 * Configuration file for the Tiffin Delight mobile app
 */

const CONFIG = {
    // API Base URL - Change this to your server URL
    API_BASE_URL: 'http://localhost/tiffine/public/index.php/api',

    // Local Storage Keys
    STORAGE_TOKEN_KEY: 'tiffin_auth_token',
    STORAGE_USER_KEY: 'tiffin_user_data',

    // Default Images
    DEFAULT_DISH_IMAGE: 'img/default-dish.jpg',

    // Timeout for API requests (in milliseconds)
    API_TIMEOUT: 10000,

    // Toast notification duration (in milliseconds)
    TOAST_DURATION: 3000
};

/**
 * API Endpoints
 */
const API = {
    // Auth Endpoints
    AUTH: {
        LOGIN: `${CONFIG.API_BASE_URL}/auth/login`,
        REGISTER: `${CONFIG.API_BASE_URL}/auth/register`
    },

    // User Endpoints
    USER: {
        PROFILE: `${CONFIG.API_BASE_URL}/user/profile`,
        UPDATE_PROFILE: `${CONFIG.API_BASE_URL}/user/update-profile`
    },

    // Dish Endpoints
    DISHES: {
        LIST: `${CONFIG.API_BASE_URL}/dishes`,
        DETAILS: (id) => `${CONFIG.API_BASE_URL}/dishes/${id}`
    },

    // Cart Endpoints
    CART: {
        GET: `${CONFIG.API_BASE_URL}/cart`,
        ADD: `${CONFIG.API_BASE_URL}/cart/add`,
        UPDATE: `${CONFIG.API_BASE_URL}/cart/update`,
        REMOVE: `${CONFIG.API_BASE_URL}/cart/remove`,
        CLEAR: `${CONFIG.API_BASE_URL}/cart/clear`
    },

    // Booking Endpoints
    BOOKING: {
        PLACE_ORDER: `${CONFIG.API_BASE_URL}/booking/place-order`,
        LIST: `${CONFIG.API_BASE_URL}/bookings`,
        DETAILS: (id) => `${CONFIG.API_BASE_URL}/bookings/${id}`,
        CANCEL: (id) => `${CONFIG.API_BASE_URL}/bookings/cancel/${id}`
    },

    // Review Endpoints
    REVIEW: {
        ADD: `${CONFIG.API_BASE_URL}/reviews/add`,
        DISH_REVIEWS: (dishId) => `${CONFIG.API_BASE_URL}/reviews/dish/${dishId}`
    },

    // Wallet Endpoints
    WALLET: {
        GET: `${CONFIG.API_BASE_URL}/wallet`,
        RECHARGE: `${CONFIG.API_BASE_URL}/wallet/recharge`
    }
};

/**
 * Configure Axios defaults
 */
axios.defaults.timeout = CONFIG.API_TIMEOUT;

// Add request interceptor to include auth token
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem(CONFIG.STORAGE_TOKEN_KEY);
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Add response interceptor to handle common errors
axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // Handle unauthorized errors (token expired or invalid)
        if (error.response && error.response.status === 401) {
            // Clear local storage and redirect to login
            localStorage.removeItem(CONFIG.STORAGE_TOKEN_KEY);
            localStorage.removeItem(CONFIG.STORAGE_USER_KEY);

            // Only redirect if not already on login screen
            if (!window.location.hash.includes('login')) {
                showToast('Your session has expired. Please login again.');
                showLoginScreen();
            }
        }

        return Promise.reject(error);
    }
);

/**
 * Helper function to show toast notifications
 * @param {string} message - The message to display
 * @param {string} type - The type of toast (success, error, info)
 */
function showToast(message, type = 'info') {
    const toast = $('#toast-notification');
    const toastBody = toast.find('.toast-body');

    // Set toast color based on type
    toast.removeClass('bg-success bg-danger bg-info');
    switch (type) {
        case 'success':
            toast.addClass('bg-success text-white');
            break;
        case 'error':
            toast.addClass('bg-danger text-white');
            break;
        default:
            toast.addClass('bg-info text-white');
    }

    // Set message and show toast
    toastBody.text(message);

    const bsToast = new bootstrap.Toast(toast, {
        delay: CONFIG.TOAST_DURATION
    });
    bsToast.show();
}

/**
 * Helper function to show/hide loading overlay
 * @param {boolean} show - Whether to show or hide the overlay
 */
function toggleLoading(show) {
    const loadingOverlay = $('#loading-overlay');
    if (show) {
        loadingOverlay.addClass('active');
    } else {
        loadingOverlay.removeClass('active');
    }
}
