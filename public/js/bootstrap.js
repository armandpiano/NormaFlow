/**
 * Bootstrap - NormaFlow
 */

import axios from 'axios';

/**
 * Setup axios defaults
 */
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Setup CSRF token for all requests
 */
const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

/**
 * Handle axios errors globally
 */
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 419) {
            // CSRF token mismatch - reload page
            window.location.reload();
        }
        return Promise.reject(error);
    }
);

export default axios;
