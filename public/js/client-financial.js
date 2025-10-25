/**
 * النظام المالي للعملاء - JavaScript
 * ملف مخصص للتفاعلات وتحسين تجربة المستخدم
 */

(function() {
    'use strict';

    // تهيئة النظام عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        initClientFinancialSystem();
    });

    /**
     * تهيئة النظام المالي للعملاء
     */
    function initClientFinancialSystem() {
        setupCSRFToken();
        setupEventListeners();
        setupFormValidations();
        setupNotifications();
        setupAnimations();
        setupFilters();
        setupModals();
        setupCharts();
        setupUtilities();
        setupProgressTracking();
        setupSearch();
    }

    /**
     * إعداد CSRF Token
     */
    function setupCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.csrfToken = token.getAttribute('content');
            
            // إعداد AJAX بـ CSRF
            if (window.jQuery) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken
                    }
                });
            }
        }
    }

    /**
     * إعداد مستمعي الأحداث
     */
    function setupEventListeners() {
        // أحداث الأزرار والنماذج
        setupButtonEvents();
        
        // أحداث الفلترة والبحث
        setupFilterEvents();
        
        // أحداث لوحة المفاتيح
        setupKeyboardShortcuts();
        
        // أحداث التمرير والنقر
        setupScrollEvents();
        
        // أحداث تغيير حجم النافذة
        setupResizeEvents();
    }

    /**
     * إعداد أحداث الأزرار
     */
    function setupButtonEvents() {
        // أزرار الدفع
        const paymentButtons = document.querySelectorAll('.payment-btn, [data-bs-target="#paymentModal"]');
        paymentButtons.forEach(button => {
            button.addEventListener('click', function() {
                const contractId = this.dataset.contractId;
                const amount = this.dataset.amount;
                const contractNumber = this.dataset.contractNumber;
                
                if (contractId && amount) {
                    preparePaymentModal(contractId, amount, contractNumber);
                }
            });
        });

        // أزرار الطباعة
        const printButtons = document.querySelectorAll('.print-btn, [onclick*="print"]');
        printButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.href || this.dataset.url;
                if (url) {
                    printDocument(url);
                }
            });
        });

        // أزرار المشاركة
        const shareButtons = document.querySelectorAll('.share-btn');
        shareButtons.forEach(button => {
            button.addEventListener('click', function() {
                const url = this.dataset.url || window.location.href;
                const title = this.dataset.title || document.title;
                shareContent(url, title);
            });
        });

        // أزرار الإعجاب/المفضلة
        const favoriteButtons = document.querySelectorAll('.favorite-btn');
        favoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                toggleFavorite(this);
            });
        });
    }

    /**
     * إعداد أحداث الفلاتر
     */
    function setupFilterEvents() {
        const filterForms = document.querySelectorAll('#filterForm, .filter-form');
        
        filterForms.forEach(form => {
            const inputs = form.querySelectorAll('select, input[type="text"], input[type="number"]');
            
            inputs.forEach(input => {
                if (input.type === 'text') {
                    // تأخير للحقول النصية
                    let timeout;
                    input.addEventListener('input', function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            if (this.value.length >= 3 || this.value.length === 0) {
                                form.submit();
                            }
                        }, 800);
                    });
                } else {
                    // تطبيق فوري للقوائم المنسدلة والأرقام
                    input.addEventListener('change', function() {
                        form.submit();
                    });
                }
            });
        });
    }

    /**
     * إعداد اختصارات لوحة المفاتيح
     */
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + P للطباعة
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                const printBtn = document.querySelector('.print-btn');
                if (printBtn) {
                    e.preventDefault();
                    printBtn.click();
                }
            }
            
            // Ctrl/Cmd + F للبحث
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            }
            
            // ESC لإغلاق المودالات
            if (e.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal.show');
                openModals.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            }
        });
    }

    /**
     * إعداد أحداث التمرير
     */
    function setupScrollEvents() {
        let ticking = false;
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });
        
        function handleScroll() {
            const scrollTop = window.pageYOffset;
            
            // إظهار/إخفاء زر العودة للأعلى
            const backToTopBtn = document.querySelector('.back-to-top');
            if (backToTopBtn) {
                if (scrollTop > 300) {
                    backToTopBtn.style.display = 'block';
                } else {
                    backToTopBtn.style.display = 'none';
                }
            }
            
            // تحديث sticky sidebar
            updateStickySidebar(scrollTop);
            
            // lazy loading للصور
            lazyLoadImages();
        }
    }

    /**
     * إعداد تغيير حجم النافذة
     */
    function setupResizeEvents() {
        let resizeTimeout;
        
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                // إعادة تهيئة العناصر المتجاوبة
                reinitializeResponsiveElements();
                
                // تحديث الرسوم البيانية
                updateCharts();
            }, 250);
        });
    }

    /**
     * إعداد التحقق من صحة النماذج
     */
    function setupFormValidations() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                    return false;
                }
                
                // إظهار التحميل عند الإرسال
                showLoading();
                
                // تعطيل الزر لمنع الإرسال المتكرر
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';
                    
                    // إعادة تفعيل الزر بعد 30 ثانية كحد أقصى
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }, 30000);
                }
            });
            
            // التحقق في الوقت الفعلي
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                
                input.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });
        });
    }

    /**
     * التحقق من صحة النموذج
     */
    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });
        
        // التحقق من المبالغ المالية
        const amountFields = form.querySelectorAll('input[name*="amount"]');
        amountFields.forEach(field => {
            const value = parseFloat(field.value);
            const max = parseFloat(field.getAttribute('max'));
            
            if (max && value > max) {
                showFieldError(field, 'المبلغ أكبر من الحد المسموح');
                isValid = false;
            }
            
            if (value <= 0) {
                showFieldError(field, 'يجب أن يكون المبلغ أكبر من صفر');
                isValid = false;
            }
        });
        
        return isValid;
    }

    /**
     * التحقق من صحة حقل واحد
     */
    function validateField(field) {
        const value = field.value.trim();
        
        if (field.required && !value) {
            showFieldError(field, 'هذا الحقل مطلوب');
            return false;
        }
        
        if (field.type === 'email' && value && !isValidEmail(value)) {
            showFieldError(field, 'البريد الإلكتروني غير صحيح');
            return false;
        }
        
        if (field.type === 'tel' && value && !isValidPhone(value)) {
            showFieldError(field, 'رقم الهاتف غير صحيح');
            return false;
        }
        
        clearFieldError(field);
        return true;
    }

    /**
     * إظهار خطأ في الحقل
     */
    function showFieldError(field, message) {
        clearFieldError(field);
        
        field.classList.add('is-invalid');
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    /**
     * مسح خطأ الحقل
     */
    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    /**
     * إعداد الإشعارات
     */
    function setupNotifications() {
        // إخفاء تلقائي للتنبيهات
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            setTimeout(() => {
                fadeOut(alert);
            }, 5000);
        });

        // إعداد toast notifications
        const toastContainer = createToastContainer();
        document.body.appendChild(toastContainer);
    }

    /**
     * إنشاء حاوي الإشعارات
     */
    function createToastContainer() {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
        }
        return container;
    }

    /**
     * إعداد الرسوم المتحركة
     */
    function setupAnimations() {
        // تطبيق رسوم متحركة على العناصر عند ظهورها
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // مراقبة العناصر القابلة للتحريك
        const animatableElements = document.querySelectorAll('.card, .stats-card, .offer-card, .contract-card');
        animatableElements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.1}s`;
            observer.observe(element);
        });

        // تحريك الأرقام
        animateNumbers();
    }

    /**
     * تحريك الأرقام في بطاقات الإحصائيات
     */
    function animateNumbers() {
        const numberElements = document.querySelectorAll('.stats-card .number, .animated-number');
        
        numberElements.forEach(element => {
            const finalValue = parseInt(element.textContent.replace(/[^\d]/g, ''));
            if (finalValue && finalValue > 0) {
                animateNumber(element, 0, finalValue, 2000);
            }
        });
    }

    /**
     * تحريك رقم واحد
     */
    function animateNumber(element, start, end, duration) {
        const range = end - start;
        const startTime = performance.now();
        
        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // استخدام easing function للتحريك السلس
            const easeProgress = easeOutCubic(progress);
            const current = Math.floor(start + (range * easeProgress));
            
            element.textContent = formatNumber(current);
            
            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            } else {
                element.textContent = formatNumber(end);
            }
        }
        
        requestAnimationFrame(updateNumber);
    }

    /**
     * دالة easing
     */
    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    /**
     * إعداد المودالات
     */
    function setupModals() {
        const modals = document.querySelectorAll('.modal');
        
        modals.forEach(modal => {
            // تنظيف المودال عند الإغلاق
            modal.addEventListener('hidden.bs.modal', function() {
                const forms = this.querySelectorAll('form');
                forms.forEach(form => {
                    form.reset();
                    clearFormErrors(form);
                });
            });
            
            // تركيز على أول حقل عند الفتح
            modal.addEventListener('shown.bs.modal', function() {
                const firstInput = this.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstInput) {
                    firstInput.focus();
                }
            });
        });
    }

    /**
     * مسح أخطاء النموذج
     */
    function clearFormErrors(form) {
        const errorElements = form.querySelectorAll('.is-invalid');
        errorElements.forEach(element => {
            element.classList.remove('is-invalid');
        });
        
        const errorMessages = form.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(message => {
            message.remove();
        });
    }

    /**
     * إعداد الرسوم البيانية
     */
    function setupCharts() {
        const chartElements = document.querySelectorAll('canvas[id*="Chart"]');
        
        chartElements.forEach(canvas => {
            if (window.Chart && canvas.getContext) {
                // إعداد الرسم البياني سيتم في الصفحات المخصصة
                canvas.style.maxHeight = '400px';
            }
        });
    }

    /**
     * إعداد الأدوات المساعدة
     */
    function setupUtilities() {
        // تفعيل tooltips
        if (window.bootstrap) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // تفعيل popovers
        if (window.bootstrap) {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }

        // إضافة زر العودة للأعلى
        addBackToTopButton();
        
        // تحسين loading للصور
        setupImageLoading();
    }

    /**
     * إضافة زر العودة للأعلى
     */
    function addBackToTopButton() {
        const backToTopBtn = document.createElement('button');
        backToTopBtn.className = 'btn btn-primary back-to-top';
        backToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTopBtn.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            display: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        
        document.body.appendChild(backToTopBtn);
    }

    /**
     * إعداد تحميل الصور
     */
    function setupImageLoading() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
            
            img.addEventListener('error', function() {
                this.src = '/images/placeholder.jpg'; // صورة احتياطية
            });
        });
    }

    /**
     * إعداد تتبع التقدم
     */
    function setupProgressTracking() {
        const progressBars = document.querySelectorAll('.progress-bar');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = entry.target;
                    const width = progressBar.style.width || progressBar.getAttribute('aria-valuenow') + '%';
                    
                    progressBar.style.width = '0%';
                    setTimeout(() => {
                        progressBar.style.width = width;
                    }, 500);
                    
                    observer.unobserve(progressBar);
                }
            });
        });
        
        progressBars.forEach(bar => observer.observe(bar));
    }

    /**
     * إعداد البحث
     */
    function setupSearch() {
        const searchInputs = document.querySelectorAll('input[name="search"]');
        
        searchInputs.forEach(input => {
            // إضافة أيقونة البحث
            if (!input.parentElement.classList.contains('input-group')) {
                wrapInputWithIcon(input, 'fas fa-search');
            }
            
            // إضافة placeholder ديناميكي
            if (!input.placeholder) {
                input.placeholder = 'البحث...';
            }
            
            // إضافة اقتراحات البحث
            setupSearchSuggestions(input);
        });
    }

    /**
     * تغليف حقل الإدخال بأيقونة
     */
    function wrapInputWithIcon(input, iconClass) {
        const wrapper = document.createElement('div');
        wrapper.className = 'input-group';
        
        const icon = document.createElement('span');
        icon.className = 'input-group-text';
        icon.innerHTML = `<i class="${iconClass}"></i>`;
        
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(icon);
        wrapper.appendChild(input);
    }

    /**
     * إعداد اقتراحات البحث
     */
    function setupSearchSuggestions(input) {
        let suggestionsContainer;
        
        input.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length >= 2) {
                // يمكن إضافة AJAX call للحصول على الاقتراحات
                showSearchSuggestions(this, []);
            } else {
                hideSearchSuggestions();
            }
        });
        
        input.addEventListener('blur', function() {
            setTimeout(hideSearchSuggestions, 200);
        });
    }

    /**
     * إظهار اقتراحات البحث
     */
    function showSearchSuggestions(input, suggestions) {
        hideSearchSuggestions();
        
        if (suggestions.length === 0) return;
        
        const container = document.createElement('div');
        container.className = 'search-suggestions';
        container.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
        `;
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.style.cssText = 'padding: 8px 12px; cursor: pointer; border-bottom: 1px solid #eee;';
            item.textContent = suggestion;
            
            item.addEventListener('click', function() {
                input.value = suggestion;
                hideSearchSuggestions();
                input.form.submit();
            });
            
            container.appendChild(item);
        });
        
        input.parentElement.style.position = 'relative';
        input.parentElement.appendChild(container);
    }

    /**
     * إخفاء اقتراحات البحث
     */
    function hideSearchSuggestions() {
        const suggestions = document.querySelector('.search-suggestions');
        if (suggestions) {
            suggestions.remove();
        }
    }

    // =======================
    // وظائف مساعدة عامة
    // =======================

    /**
     * إظهار التحميل
     */
    window.showLoading = function() {
        const loadingOverlay = document.getElementById('loadingOverlay') || document.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
    };

    /**
     * إخفاء التحميل
     */
    window.hideLoading = function() {
        const loadingOverlay = document.getElementById('loadingOverlay') || document.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    };

    /**
     * عرض إشعار Toast
     */
    window.showToast = function(message, type = 'info', duration = 5000) {
        const container = document.querySelector('.toast-container') || createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type}`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${getToastIcon(type)} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        container.appendChild(toast);
        
        if (window.bootstrap) {
            const bsToast = new bootstrap.Toast(toast, {
                delay: duration
            });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        } else {
            // fallback
            setTimeout(() => {
                fadeOut(toast);
            }, duration);
        }
    };

    /**
     * الحصول على أيقونة Toast
     */
    function getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    /**
     * تأكيد الإجراء
     */
    window.confirmAction = function(message = 'هل أنت متأكد؟') {
        return confirm(message);
    };

    /**
     * تنسيق الأرقام
     */
    window.formatNumber = function(num) {
        return new Intl.NumberFormat('ar-SA').format(num);
    };

    /**
     * تنسيق العملة
     */
    window.formatCurrency = function(amount, currency = 'SAR') {
        return new Intl.NumberFormat('ar-SA', {
            style: 'currency',
            currency: currency,
            minimumFractionDigits: 2
        }).format(amount);
    };

    /**
     * تنسيق التاريخ
     */
    window.formatDate = function(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('ar-SA', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    };

    /**
     * مشاركة المحتوى
     */
    window.shareContent = function(url, title) {
        if (navigator.share) {
            navigator.share({
                title: title,
                url: url
            }).catch(console.error);
        } else {
            // نسخ الرابط للحافظة
            navigator.clipboard.writeText(url).then(() => {
                showToast('تم نسخ الرابط للحافظة', 'success');
            }).catch(() => {
                // fallback للمتصفحات القديمة
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showToast('تم نسخ الرابط للحافظة', 'success');
            });
        }
    };

    /**
     * طباعة المستند
     */
    window.printDocument = function(url) {
        if (url) {
            const printWindow = window.open(url, '_blank');
            printWindow.onload = function() {
                printWindow.print();
            };
        } else {
            window.print();
        }
    };

    /**
     * تبديل المفضلة
     */
    window.toggleFavorite = function(button) {
        const icon = button.querySelector('i');
        const isActive = button.classList.contains('active');
        
        if (isActive) {
            button.classList.remove('active');
            icon.className = 'far fa-heart';
            showToast('تم إزالة من المفضلة', 'info');
        } else {
            button.classList.add('active');
            icon.className = 'fas fa-heart';
            showToast('تم إضافة للمفضلة', 'success');
        }
    };

    /**
     * تحضير مودال الدفع
     */
    window.preparePaymentModal = function(contractId, amount, contractNumber) {
        const modal = document.getElementById('paymentModal');
        if (modal) {
            const contractIdInput = modal.querySelector('#paymentContractId, input[name="contract_id"]');
            const contractNumberSpan = modal.querySelector('#paymentContractNumber');
            const remainingAmountSpan = modal.querySelector('#paymentRemainingAmount');
            const amountInput = modal.querySelector('#paymentAmount, input[name="amount"]');
            
            if (contractIdInput) contractIdInput.value = contractId;
            if (contractNumberSpan) contractNumberSpan.textContent = contractNumber;
            if (remainingAmountSpan) remainingAmountSpan.textContent = formatNumber(amount);
            if (amountInput) amountInput.setAttribute('max', amount);
        }
    };

    /**
     * تحديث sticky sidebar
     */
    function updateStickySidebar(scrollTop) {
        const sidebars = document.querySelectorAll('.sticky-top');
        sidebars.forEach(sidebar => {
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const sidebarHeight = sidebar.offsetHeight;
            
            if (scrollTop + windowHeight + sidebarHeight >= documentHeight) {
                sidebar.style.top = documentHeight - scrollTop - sidebarHeight - 20 + 'px';
            } else {
                sidebar.style.top = '20px';
            }
        });
    }

    /**
     * lazy loading للصور
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[data-src]');
        images.forEach(img => {
            if (isElementInViewport(img)) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
            }
        });
    }

    /**
     * فحص ما إذا كان العنصر في منطقة العرض
     */
    function isElementInViewport(element) {
        const rect = element.getBoundingClientRect();
        return rect.top < window.innerHeight && rect.bottom > 0;
    }

    /**
     * إعادة تهيئة العناصر المتجاوبة
     */
    function reinitializeResponsiveElements() {
        // إعادة حساب sticky elements
        const stickyElements = document.querySelectorAll('.sticky-top');
        stickyElements.forEach(element => {
            element.style.top = '20px';
        });
    }

    /**
     * تحديث الرسوم البيانية
     */
    function updateCharts() {
        if (window.Chart) {
            Chart.helpers.each(Chart.instances, function(instance) {
                instance.resize();
            });
        }
    }

    /**
     * تأثير الاختفاء
     */
    function fadeOut(element) {
        element.style.opacity = '1';
        element.style.transition = 'opacity 0.5s ease';
        element.style.opacity = '0';
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 500);
    }

    /**
     * التحقق من صحة البريد الإلكتروني
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    /**
     * التحقق من صحة رقم الهاتف
     */
    function isValidPhone(phone) {
        const phoneRegex = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return phoneRegex.test(phone);
    }

    // تصدير الوظائف للاستخدام العام
    window.ClientFinancial = {
        showLoading,
        hideLoading,
        showToast,
        confirmAction,
        formatNumber,
        formatCurrency,
        formatDate,
        shareContent,
        printDocument,
        toggleFavorite,
        preparePaymentModal
    };

    // إخفاء التحميل عند اكتمال تحميل الصفحة
    window.addEventListener('load', function() {
        hideLoading();
    });

})();
