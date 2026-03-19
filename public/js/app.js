/**
 * NormaFlow - JavaScript Application
 */

import './bootstrap';

/**
 * Initialize application
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initTooltips();
    
    // Initialize confirmation dialogs
    initConfirmDialogs();
    
    // Initialize auto-hide alerts
    initAutoHideAlerts();
});

/**
 * Initialize tooltips
 */
function initTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(el => {
        el.addEventListener('mouseenter', function() {
            const text = this.getAttribute('data-tooltip');
            // Tooltip implementation
        });
    });
}

/**
 * Initialize confirmation dialogs
 */
function initConfirmDialogs() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || '¿Estás seguro?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Auto-hide alerts after 5 seconds
 */
function initAutoHideAlerts() {
    const alerts = document.querySelectorAll('.alert-auto-hide');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Show loading state on button
 */
function setButtonLoading(button, loading = true) {
    if (loading) {
        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner"></span> Cargando...';
    } else {
        button.disabled = false;
        button.innerHTML = button.dataset.originalText;
    }
}

// Export functions for global use
window.NormaFlow = {
    formatFileSize,
    setButtonLoading,
};
