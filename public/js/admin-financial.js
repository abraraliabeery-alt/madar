/**
 * النظام المالي للأدمن - JavaScript
 * ملف مخصص للتفاعلات والوظائف الديناميكية
 */

(function() {
    'use strict';

    // تهيئة النظام عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        initFinancialSystem();
    });

    /**
     * تهيئة النظام المالي
     */
    function initFinancialSystem() {
        setupCSRFToken();
        setupEventListeners();
        setupDataTables();
        setupCharts();
        setupDatePickers();
        setupAutoRefresh();
        setupKeyboardShortcuts();
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
        // أحداث الفلترة التلقائية
        setupAutoFiltering();
        
        // أحداث التحديد المتعدد
        setupBulkSelection();
        
        // أحداث التصدير
        setupExportHandlers();
        
        // أحداث المودالات
        setupModalHandlers();
        
        // أحداث الإشعارات
        setupNotificationHandlers();
    }

    /**
     * إعداد الفلترة التلقائية
     */
    function setupAutoFiltering() {
        const filterForms = document.querySelectorAll('#filterForm');
        
        filterForms.forEach(form => {
            const inputs = form.querySelectorAll('select, input[type="text"]');
            
            inputs.forEach(input => {
                if (input.type === 'text') {
                    // تأخير للحقول النصية
                    let timeout;
                    input.addEventListener('input', function() {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            form.submit();
                        }, 1000);
                    });
                } else {
                    // تطبيق فوري للقوائم المنسدلة
                    input.addEventListener('change', function() {
                        form.submit();
                    });
                }
            });
        });
    }

    /**
     * إعداد التحديد المتعدد
     */
    function setupBulkSelection() {
        // تحديد الكل
        const selectAllCheckboxes = document.querySelectorAll('#selectAll, #selectAllHeader');
        selectAllCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const targetCheckboxes = document.querySelectorAll('.contract-checkbox, .offer-checkbox, .payment-checkbox');
                targetCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkActionButtons();
            });
        });

        // تحديد فردي
        const itemCheckboxes = document.querySelectorAll('.contract-checkbox, .offer-checkbox, .payment-checkbox');
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActionButtons);
        });
    }

    /**
     * تحديث أزرار الإجراءات المتعددة
     */
    function updateBulkActionButtons() {
        const checkedBoxes = document.querySelectorAll('.contract-checkbox:checked, .offer-checkbox:checked, .payment-checkbox:checked');
        const bulkButtons = document.querySelectorAll('.bulk-action-btn');
        const selectedCountElements = document.querySelectorAll('.selected-count');

        const count = checkedBoxes.length;
        
        bulkButtons.forEach(btn => {
            btn.disabled = count === 0;
        });

        selectedCountElements.forEach(element => {
            element.textContent = count;
        });

        // تحديث نص العداد
        const countText = count > 0 ? `تم تحديد ${count} عنصر` : 'لم يتم تحديد أي عناصر';
        const countDisplays = document.querySelectorAll('#selectedCount, .selection-info');
        countDisplays.forEach(display => {
            display.innerHTML = count > 0 
                ? `<i class="fas fa-check-circle text-success ms-2"></i>${countText}`
                : `<i class="fas fa-info-circle text-muted ms-2"></i>${countText}`;
        });
    }

    /**
     * إعداد معالجات التصدير
     */
    function setupExportHandlers() {
        const exportButtons = document.querySelectorAll('.export-btn');
        
        exportButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type || 'excel';
                const scope = this.dataset.scope || 'current';
                
                handleExport(type, scope);
            });
        });
    }

    /**
     * معالجة التصدير
     */
    function handleExport(type, scope) {
        showLoading();
        
        const params = new URLSearchParams(window.location.search);
        params.set('format', type);
        params.set('scope', scope);
        
        // إضافة العناصر المحددة إذا كان النطاق محدد
        if (scope === 'selected') {
            const selected = getSelectedItems();
            if (selected.length === 0) {
                hideLoading();
                showNotification('يرجى تحديد عنصر واحد على الأقل', 'warning');
                return;
            }
            selected.forEach(id => params.append('selected[]', id));
        }

        // فتح رابط التحميل
        const currentPath = window.location.pathname;
        const exportUrl = `${currentPath}/export?${params.toString()}`;
        
        setTimeout(() => {
            window.open(exportUrl, '_blank');
            hideLoading();
            showNotification('تم بدء التصدير', 'success');
        }, 1000);
    }

    /**
     * الحصول على العناصر المحددة
     */
    function getSelectedItems() {
        const checkboxes = document.querySelectorAll('.contract-checkbox:checked, .offer-checkbox:checked, .payment-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }

    /**
     * إعداد معالجات المودالات
     */
    function setupModalHandlers() {
        // تنظيف المودالات عند الإغلاق
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                const forms = this.querySelectorAll('form');
                forms.forEach(form => form.reset());
                
                const loadingAreas = this.querySelectorAll('.loading-content');
                loadingAreas.forEach(area => {
                    area.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
                });
            });
        });

        // تحميل محتوى المودالات ديناميكياً
        const modalTriggers = document.querySelectorAll('[data-modal-url]');
        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const url = this.dataset.modalUrl;
                const targetModal = this.dataset.bsTarget;
                
                if (url && targetModal) {
                    loadModalContent(url, targetModal);
                }
            });
        });
    }

    /**
     * تحميل محتوى المودال
     */
    function loadModalContent(url, modalSelector) {
        const modal = document.querySelector(modalSelector);
        const contentArea = modal.querySelector('.modal-body');
        
        if (!contentArea) return;
        
        // إظهار التحميل
        contentArea.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">جاري التحميل...</p></div>';
        
        // تحميل المحتوى
        fetch(url)
            .then(response => response.text())
            .then(html => {
                contentArea.innerHTML = html;
                // إعادة تهيئة العناصر الجديدة
                reinitializeElements(contentArea);
            })
            .catch(error => {
                contentArea.innerHTML = '<div class="alert alert-danger">حدث خطأ في تحميل المحتوى</div>';
                console.error('خطأ في تحميل المودال:', error);
            });
    }

    /**
     * إعادة تهيئة العناصر الجديدة
     */
    function reinitializeElements(container) {
        // إعادة تهيئة tooltips
        const tooltips = container.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });

        // إعادة تهيئة date pickers
        const datePickers = container.querySelectorAll('input[type="date"]');
        datePickers.forEach(picker => {
            setupDatePicker(picker);
        });
    }

    /**
     * إعداد إشعارات النظام
     */
    function setupNotificationHandlers() {
        // إخفاء تلقائي للإشعارات
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            }, 5000);
        });

        // إشعارات الإجراءات
        const actionButtons = document.querySelectorAll('[data-confirm]');
        actionButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const message = this.dataset.confirm || 'هل أنت متأكد؟';
                if (!confirm(message)) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }

    /**
     * إعداد الجداول التفاعلية
     */
    function setupDataTables() {
        // إذا كان DataTables متوفر
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('.data-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/ar.json'
                },
                responsive: true,
                pageLength: 25,
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: [-1] } // آخر عمود (الإجراءات)
                ]
            });
        }
    }

    /**
     * إعداد الرسوم البيانية
     */
    function setupCharts() {
        // رسوم Chart.js
        const chartElements = document.querySelectorAll('.chart-canvas');
        chartElements.forEach(canvas => {
            const type = canvas.dataset.chartType || 'line';
            const data = JSON.parse(canvas.dataset.chartData || '{}');
            
            if (window.Chart && data) {
                createChart(canvas, type, data);
            }
        });
    }

    /**
     * إنشاء رسم بياني
     */
    function createChart(canvas, type, data) {
        const ctx = canvas.getContext('2d');
        
        new Chart(ctx, {
            type: type,
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: type !== 'line'
                    }
                },
                scales: type === 'pie' ? {} : {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return formatCurrency(value);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * إعداد منتقي التواريخ
     */
    function setupDatePickers() {
        const datePickers = document.querySelectorAll('input[type="date"]');
        datePickers.forEach(setupDatePicker);
    }

    /**
     * إعداد منتقي تاريخ واحد
     */
    function setupDatePicker(input) {
        // إضافة تنسيق أفضل للتواريخ
        input.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                const formatted = date.toLocaleDateString('ar-SA');
                this.title = formatted;
            }
        });
    }

    /**
     * إعداد التحديث التلقائي
     */
    function setupAutoRefresh() {
        const refreshInterval = 300000; // 5 دقائق
        const refreshablePages = ['/admin/financial/dashboard', '/admin/financial/payments'];
        
        if (refreshablePages.some(page => window.location.pathname.includes(page))) {
            setInterval(() => {
                // تحديث الإحصائيات فقط
                updateDashboardStats();
            }, refreshInterval);
        }
    }

    /**
     * تحديث إحصائيات لوحة المعلومات
     */
    function updateDashboardStats() {
        const statsCards = document.querySelectorAll('.stats-card [data-stat]');
        
        if (statsCards.length === 0) return;
        
        fetch(window.location.pathname + '?ajax=stats')
            .then(response => response.json())
            .then(data => {
                statsCards.forEach(card => {
                    const statKey = card.dataset.stat;
                    if (data[statKey] !== undefined) {
                        const numberElement = card.querySelector('.number');
                        if (numberElement) {
                            animateNumber(numberElement, data[statKey]);
                        }
                    }
                });
            })
            .catch(error => {
                console.log('فشل في تحديث الإحصائيات:', error);
            });
    }

    /**
     * تحريك الأرقام
     */
    function animateNumber(element, newValue) {
        const currentValue = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
        const difference = newValue - currentValue;
        const steps = 20;
        const stepValue = difference / steps;
        let step = 0;
        
        const animation = setInterval(() => {
            step++;
            const value = currentValue + (stepValue * step);
            element.textContent = formatNumber(Math.round(value));
            
            if (step >= steps) {
                clearInterval(animation);
                element.textContent = formatNumber(newValue);
            }
        }, 50);
    }

    /**
     * إعداد اختصارات لوحة المفاتيح
     */
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + E للتصدير
            if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                e.preventDefault();
                const exportBtn = document.querySelector('.export-btn');
                if (exportBtn) exportBtn.click();
            }
            
            // Ctrl/Cmd + R للتحديث
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                location.reload();
            }
            
            // Ctrl/Cmd + A لتحديد الكل
            if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                const selectAllBtn = document.querySelector('#selectAll');
                if (selectAllBtn && !e.target.matches('input, textarea')) {
                    e.preventDefault();
                    selectAllBtn.click();
                }
            }
        });
    }

    // =======================
    // وظائف مساعدة عامة
    // =======================

    /**
     * إظهار التحميل
     */
    window.showLoading = function() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'flex';
        }
    };

    /**
     * إخفاء التحميل
     */
    window.hideLoading = function() {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    };

    /**
     * عرض إشعار
     */
    window.showNotification = function(message, type = 'info') {
        const colors = {
            success: '#28a745',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };

        // إنشاء toast إذا كان Bootstrap متوفر
        if (window.bootstrap) {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type}" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // إزالة العنصر بعد الإخفاء
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        } else {
            // fallback للمتصفحات القديمة
            alert(message);
        }
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
     * تأكيد الحذف
     */
    window.confirmDelete = function(message = 'هل أنت متأكد من الحذف؟') {
        return confirm(message);
    };

    /**
     * نسخ نص للحافظة
     */
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('تم النسخ للحافظة', 'success');
        }).catch(() => {
            showNotification('فشل في النسخ', 'error');
        });
    };

    /**
     * التمرير السلس لعنصر
     */
    window.scrollToElement = function(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    };

    // تصدير الوظائف للاستخدام العام
    window.FinancialSystem = {
        showLoading,
        hideLoading,
        showNotification,
        formatNumber,
        formatCurrency,
        formatDate,
        confirmDelete,
        copyToClipboard,
        scrollToElement,
        updateBulkActionButtons,
        getSelectedItems
    };

})();
