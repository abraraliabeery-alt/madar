/**
 * Facility Financial System JavaScript
 * Advanced interactive features for facility management
 */

// Global configuration
window.FacilityFinancial = window.FacilityFinancial || {
    config: {
        ajaxTimeout: 30000,
        toastDuration: 5000,
        loadingDelay: 300,
        chartColors: {
            primary: '#0d6efd',
            success: '#198754',
            warning: '#ffc107',
            danger: '#dc3545',
            info: '#0dcaf0',
            secondary: '#6c757d'
        },
        animations: {
            fast: 150,
            normal: 300,
            slow: 500
        }
    },
    
    // State management
    state: {
        activeFilters: {},
        selectedItems: new Set(),
        currentPage: 1,
        isLoading: false,
        charts: {}
    }
};

/**
 * Utility Functions
 */
const Utils = {
    /**
     * Format number with locale support
     */
    formatNumber: function(number, locale = 'en') {
        if (typeof number !== 'number') {
            number = parseFloat(number) || 0;
        }
        return new Intl.NumberFormat(locale).format(number);
    },

    /**
     * Format currency
     */
    formatCurrency: function(amount, currency = 'SAR', locale = 'ar-SA') {
        if (typeof amount !== 'number') {
            amount = parseFloat(amount) || 0;
        }
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(amount);
    },

    /**
     * Debounce function
     */
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Throttle function
     */
    throttle: function(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    /**
     * Get CSRF token
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    },

    /**
     * Generate unique ID
     */
    generateId: function() {
        return 'id_' + Math.random().toString(36).substr(2, 9);
    },

    /**
     * Deep clone object
     */
    deepClone: function(obj) {
        return JSON.parse(JSON.stringify(obj));
    },

    /**
     * Check if element is in viewport
     */
    isInViewport: function(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
};

/**
 * Loading Management
 */
const LoadingManager = {
    /**
     * Show loading overlay
     */
    show: function(message = 'Loading...') {
        let overlay = document.getElementById('loadingOverlay');
        if (!overlay) {
            overlay = this.createOverlay();
        }
        
        const text = overlay.querySelector('.loading-text');
        if (text) {
            text.textContent = message;
        }
        
        overlay.classList.remove('d-none');
        FacilityFinancial.state.isLoading = true;
    },

    /**
     * Hide loading overlay
     */
    hide: function() {
        setTimeout(() => {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.add('d-none');
            }
            FacilityFinancial.state.isLoading = false;
        }, FacilityFinancial.config.loadingDelay);
    },

    /**
     * Create loading overlay
     */
    createOverlay: function() {
        const overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay d-none';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="loading-text">Loading...</div>
            </div>
        `;
        document.body.appendChild(overlay);
        return overlay;
    }
};

/**
 * Toast Notifications
 */
const ToastManager = {
    /**
     * Show toast notification
     */
    show: function(type, message, title = null, duration = null) {
        const toastId = Utils.generateId();
        const toastContainer = this.getContainer();
        
        const toast = this.createToast(toastId, type, message, title);
        toastContainer.appendChild(toast);
        
        // Initialize Bootstrap toast
        const bsToast = new bootstrap.Toast(toast, {
            delay: duration || FacilityFinancial.config.toastDuration
        });
        
        bsToast.show();
        
        // Auto-remove after hide
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
        
        return toastId;
    },

    /**
     * Get or create toast container
     */
    getContainer: function() {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
        }
        return container;
    },

    /**
     * Create toast element
     */
    createToast: function(id, type, message, title) {
        const toast = document.createElement('div');
        toast.id = id;
        toast.className = `toast border-0`;
        toast.setAttribute('role', 'alert');
        
        const typeConfig = this.getTypeConfig(type);
        
        toast.innerHTML = `
            <div class="toast-header bg-${typeConfig.color} text-white">
                <i class="bi ${typeConfig.icon} me-2"></i>
                <strong class="me-auto">${title || typeConfig.title}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        return toast;
    },

    /**
     * Get type configuration
     */
    getTypeConfig: function(type) {
        const configs = {
            success: { color: 'success', icon: 'bi-check-circle', title: 'نجح' },
            error: { color: 'danger', icon: 'bi-exclamation-triangle', title: 'خطأ' },
            warning: { color: 'warning', icon: 'bi-exclamation-triangle', title: 'تحذير' },
            info: { color: 'info', icon: 'bi-info-circle', title: 'معلومات' }
        };
        return configs[type] || configs.info;
    }
};

/**
 * AJAX Helper
 */
const AjaxHelper = {
    /**
     * Make AJAX request
     */
    request: function(options) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': Utils.getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: FacilityFinancial.config.ajaxTimeout
        };
        
        const config = { ...defaults, ...options };
        
        // Show loading if specified
        if (config.showLoading !== false) {
            LoadingManager.show(config.loadingMessage);
        }
        
        return fetch(config.url, config)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (config.showLoading !== false) {
                    LoadingManager.hide();
                }
                return data;
            })
            .catch(error => {
                if (config.showLoading !== false) {
                    LoadingManager.hide();
                }
                console.error('AJAX Error:', error);
                ToastManager.show('error', 'حدث خطأ في الاتصال بالخادم');
                throw error;
            });
    },

    /**
     * GET request
     */
    get: function(url, params = {}, options = {}) {
        const urlObj = new URL(url, window.location.origin);
        Object.keys(params).forEach(key => {
            if (params[key] !== null && params[key] !== undefined) {
                urlObj.searchParams.append(key, params[key]);
            }
        });
        
        return this.request({
            url: urlObj.toString(),
            method: 'GET',
            ...options
        });
    },

    /**
     * POST request
     */
    post: function(url, data = {}, options = {}) {
        return this.request({
            url: url,
            method: 'POST',
            body: JSON.stringify(data),
            ...options
        });
    },

    /**
     * PUT request
     */
    put: function(url, data = {}, options = {}) {
        return this.request({
            url: url,
            method: 'PUT',
            body: JSON.stringify(data),
            ...options
        });
    },

    /**
     * DELETE request
     */
    delete: function(url, options = {}) {
        return this.request({
            url: url,
            method: 'DELETE',
            ...options
        });
    }
};

/**
 * Chart Manager
 */
const ChartManager = {
    /**
     * Create line chart
     */
    createLineChart: function(canvas, data, options = {}) {
        const ctx = canvas.getContext('2d');
        
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return Utils.formatNumber(value, window.facilityFinancial?.locale || 'en');
                        }
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        };
        
        const chartOptions = { ...defaultOptions, ...options };
        
        const chart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: chartOptions
        });
        
        return chart;
    },

    /**
     * Create doughnut chart
     */
    createDoughnutChart: function(canvas, data, options = {}) {
        const ctx = canvas.getContext('2d');
        
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        };
        
        const chartOptions = { ...defaultOptions, ...options };
        
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: chartOptions
        });
        
        return chart;
    },

    /**
     * Create bar chart
     */
    createBarChart: function(canvas, data, options = {}) {
        const ctx = canvas.getContext('2d');
        
        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return Utils.formatNumber(value, window.facilityFinancial?.locale || 'en');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };
        
        const chartOptions = { ...defaultOptions, ...options };
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: chartOptions
        });
        
        return chart;
    }
};

/**
 * Form Helper
 */
const FormHelper = {
    /**
     * Serialize form data
     */
    serialize: function(form) {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (data[key]) {
                // Handle multiple values (checkboxes, etc.)
                if (Array.isArray(data[key])) {
                    data[key].push(value);
                } else {
                    data[key] = [data[key], value];
                }
            } else {
                data[key] = value;
            }
        }
        
        return data;
    },

    /**
     * Reset form with animation
     */
    reset: function(form) {
        form.reset();
        
        // Clear any validation states
        form.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        form.querySelectorAll('.invalid-feedback').forEach(element => {
            element.remove();
        });
    },

    /**
     * Show form validation errors
     */
    showErrors: function(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        form.querySelectorAll('.invalid-feedback').forEach(element => {
            element.remove();
        });
        
        // Show new errors
        Object.keys(errors).forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');
                
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                
                input.parentNode.appendChild(feedback);
            }
        });
    },

    /**
     * Auto-save form data
     */
    autoSave: function(form, storageKey, interval = 30000) {
        const saveData = () => {
            const data = this.serialize(form);
            localStorage.setItem(storageKey, JSON.stringify(data));
        };
        
        // Save on input change
        form.addEventListener('input', Utils.debounce(saveData, 1000));
        
        // Save periodically
        setInterval(saveData, interval);
        
        // Load saved data
        const savedData = localStorage.getItem(storageKey);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                this.populate(form, data);
            } catch (e) {
                console.warn('Could not load auto-saved data:', e);
            }
        }
    },

    /**
     * Populate form with data
     */
    populate: function(form, data) {
        Object.keys(data).forEach(key => {
            const element = form.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'checkbox' || element.type === 'radio') {
                    element.checked = element.value === data[key];
                } else {
                    element.value = data[key];
                }
            }
        });
    }
};

/**
 * Modal Manager
 */
const ModalManager = {
    /**
     * Show dynamic modal
     */
    show: function(title, content, options = {}) {
        const modal = this.create(title, content, options);
        const bsModal = new bootstrap.Modal(modal);
        
        bsModal.show();
        
        // Auto-remove after hide
        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
        
        return { modal, bsModal };
    },

    /**
     * Create modal element
     */
    create: function(title, content, options = {}) {
        const modalId = Utils.generateId();
        const size = options.size || '';
        const centered = options.centered ? 'modal-dialog-centered' : '';
        
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = modalId;
        modal.tabIndex = -1;
        
        modal.innerHTML = `
            <div class="modal-dialog ${size} ${centered}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${content}
                    </div>
                    ${options.footer ? `<div class="modal-footer">${options.footer}</div>` : ''}
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        return modal;
    },

    /**
     * Show confirmation modal
     */
    confirm: function(message, title = 'تأكيد', onConfirm = null, onCancel = null) {
        const footer = `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="button" class="btn btn-primary" id="confirmBtn">تأكيد</button>
        `;
        
        const { modal, bsModal } = this.show(title, message, { footer });
        
        const confirmBtn = modal.querySelector('#confirmBtn');
        confirmBtn.addEventListener('click', () => {
            if (onConfirm) onConfirm();
            bsModal.hide();
        });
        
        modal.addEventListener('hidden.bs.modal', () => {
            if (onCancel) onCancel();
        });
        
        return bsModal;
    }
};

/**
 * Filter Manager
 */
const FilterManager = {
    /**
     * Initialize filters
     */
    init: function(formSelector, options = {}) {
        const form = document.querySelector(formSelector);
        if (!form) return;
        
        const config = {
            autoSubmit: true,
            debounceDelay: 500,
            ...options
        };
        
        if (config.autoSubmit) {
            this.setupAutoSubmit(form, config.debounceDelay);
        }
        
        this.setupClearFilters(form);
        this.loadFiltersFromUrl(form);
    },

    /**
     * Setup auto-submit on filter change
     */
    setupAutoSubmit: function(form, delay) {
        const inputs = form.querySelectorAll('input, select');
        const debouncedSubmit = Utils.debounce(() => {
            this.submitFilters(form);
        }, delay);
        
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'search') {
                input.addEventListener('input', debouncedSubmit);
            } else {
                input.addEventListener('change', debouncedSubmit);
            }
        });
    },

    /**
     * Setup clear filters functionality
     */
    setupClearFilters: function(form) {
        const clearBtn = form.querySelector('[data-action="clear-filters"]');
        if (clearBtn) {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.clearFilters(form);
            });
        }
    },

    /**
     * Submit filters
     */
    submitFilters: function(form) {
        const data = FormHelper.serialize(form);
        FacilityFinancial.state.activeFilters = data;
        
        // Update URL without page reload
        const url = new URL(window.location);
        Object.keys(data).forEach(key => {
            if (data[key]) {
                url.searchParams.set(key, data[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        
        history.pushState({}, '', url);
        
        // Reload content with filters
        this.loadFilteredContent(data);
    },

    /**
     * Clear all filters
     */
    clearFilters: function(form) {
        FormHelper.reset(form);
        FacilityFinancial.state.activeFilters = {};
        
        // Clear URL parameters
        const url = new URL(window.location);
        url.search = '';
        history.pushState({}, '', url);
        
        // Reload content without filters
        this.loadFilteredContent({});
    },

    /**
     * Load filters from URL
     */
    loadFiltersFromUrl: function(form) {
        const params = new URLSearchParams(window.location.search);
        const data = {};
        
        for (let [key, value] of params) {
            data[key] = value;
        }
        
        FormHelper.populate(form, data);
        FacilityFinancial.state.activeFilters = data;
    },

    /**
     * Load filtered content
     */
    loadFilteredContent: function(filters) {
        // This should be overridden for specific pages
        window.location.reload();
    }
};

/**
 * Global Functions (for backward compatibility)
 */
window.showLoading = function(message) {
    LoadingManager.show(message);
};

window.hideLoading = function() {
    LoadingManager.hide();
};

window.showToast = function(type, message, title, duration) {
    ToastManager.show(type, message, title, duration);
};

/**
 * Keyboard Shortcuts
 */
const KeyboardShortcuts = {
    shortcuts: {
        'ctrl+n': () => window.location.href = window.facilityFinancial?.routes?.createOffer || '#',
        'ctrl+r': () => window.location.reload(),
        'ctrl+f': () => document.querySelector('input[name="search"]')?.focus(),
        'escape': () => {
            // Close any open modals
            const openModals = document.querySelectorAll('.modal.show');
            openModals.forEach(modal => {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            });
        }
    },

    init: function() {
        document.addEventListener('keydown', (e) => {
            const key = this.getKeyString(e);
            if (this.shortcuts[key]) {
                e.preventDefault();
                this.shortcuts[key]();
            }
        });
    },

    getKeyString: function(event) {
        const parts = [];
        if (event.ctrlKey) parts.push('ctrl');
        if (event.altKey) parts.push('alt');
        if (event.shiftKey) parts.push('shift');
        parts.push(event.key.toLowerCase());
        return parts.join('+');
    }
};

/**
 * Real-time Updates
 */
const RealTimeUpdater = {
    intervals: new Map(),

    start: function(key, callback, interval = 30000) {
        this.stop(key);
        const intervalId = setInterval(callback, interval);
        this.intervals.set(key, intervalId);
    },

    stop: function(key) {
        if (this.intervals.has(key)) {
            clearInterval(this.intervals.get(key));
            this.intervals.delete(key);
        }
    },

    stopAll: function() {
        this.intervals.forEach(intervalId => clearInterval(intervalId));
        this.intervals.clear();
    }
};

/**
 * Animation Helper
 */
const AnimationHelper = {
    fadeIn: function(element, duration = 300) {
        element.style.opacity = '0';
        element.style.display = 'block';
        
        const start = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = progress;
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    },

    fadeOut: function(element, duration = 300) {
        const start = performance.now();
        const startOpacity = parseFloat(getComputedStyle(element).opacity);
        
        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);
            
            element.style.opacity = startOpacity * (1 - progress);
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            } else {
                element.style.display = 'none';
            }
        };
        
        requestAnimationFrame(animate);
    },

    slideUp: function(element, duration = 300) {
        const startHeight = element.offsetHeight;
        element.style.overflow = 'hidden';
        element.style.transition = `height ${duration}ms ease`;
        
        requestAnimationFrame(() => {
            element.style.height = '0px';
            setTimeout(() => {
                element.style.display = 'none';
                element.style.height = '';
                element.style.transition = '';
                element.style.overflow = '';
            }, duration);
        });
    },

    slideDown: function(element, duration = 300) {
        element.style.display = 'block';
        const targetHeight = element.scrollHeight;
        element.style.height = '0px';
        element.style.overflow = 'hidden';
        element.style.transition = `height ${duration}ms ease`;
        
        requestAnimationFrame(() => {
            element.style.height = targetHeight + 'px';
            setTimeout(() => {
                element.style.height = '';
                element.style.transition = '';
                element.style.overflow = '';
            }, duration);
        });
    }
};

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize keyboard shortcuts
    KeyboardShortcuts.init();
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            AnimationHelper.fadeOut(alert);
        });
    }, FacilityFinancial.config.toastDuration);

    // Initialize filters if filter form exists
    const filterForm = document.querySelector('#filtersForm');
    if (filterForm) {
        FilterManager.init('#filtersForm');
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Handle page visibility change (pause/resume real-time updates)
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            RealTimeUpdater.stopAll();
        } else {
            // Resume updates based on current page
            // This should be customized per page
        }
    });

    // Handle online/offline status
    window.addEventListener('online', function() {
        ToastManager.show('success', 'تم استعادة الاتصال بالإنترنت');
    });

    window.addEventListener('offline', function() {
        ToastManager.show('warning', 'تم فقدان الاتصال بالإنترنت');
    });
});

/**
 * Handle page unload (cleanup)
 */
window.addEventListener('beforeunload', function() {
    RealTimeUpdater.stopAll();
    
    // Destroy charts to prevent memory leaks
    Object.values(FacilityFinancial.state.charts).forEach(chart => {
        if (chart && typeof chart.destroy === 'function') {
            chart.destroy();
        }
    });
});

/**
 * Export to global scope
 */
window.FacilityFinancial = {
    ...FacilityFinancial,
    Utils,
    LoadingManager,
    ToastManager,
    AjaxHelper,
    ChartManager,
    FormHelper,
    ModalManager,
    FilterManager,
    KeyboardShortcuts,
    RealTimeUpdater,
    AnimationHelper
};
