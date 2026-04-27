<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facility\FacilityController;
use App\Http\Controllers\Facility\FacilityProductController;
use App\Http\Controllers\Facility\FacilityBookingController;
use App\Http\Controllers\Facility\FacilityOfferController;
use App\Http\Controllers\Facility\FacilityContractController;
use App\Http\Controllers\Facility\FacilityInvoiceController;
use App\Http\Controllers\Facility\FacilityPaymentController;
use App\Http\Controllers\Facility\FacilityFinancialReportController;
use App\Http\Controllers\Facility\FacilityFinancialController;
use App\Http\Controllers\Facility\AccountingController;
use App\Http\Controllers\Facility\ChartOfAccountController;
use App\Http\Controllers\Facility\AccountingPeriodController;
use App\Http\Controllers\Facility\TaxRateController;
use App\Http\Controllers\Facility\BudgetController;
use App\Http\Controllers\Facility\FinancialReportController;
use App\Http\Controllers\Facility\FacilityUserController;
use App\Http\Controllers\FacilityCustomizationController;
use App\Http\Controllers\Facility\FacilityTaskController;
use App\Http\Controllers\Facility\FacilityProjectController;
use App\Http\Controllers\Facility\FacilityAppointmentController;
use App\Http\Controllers\Facility\FacilityRentalsReportController;
use App\Http\Controllers\Facility\FacilitySmartBrokerController;
use App\Http\Controllers\Facility\HR\DepartmentController as FacilityHrDepartmentController;
use App\Http\Controllers\Facility\HR\PositionController as FacilityHrPositionController;
use App\Http\Controllers\Facility\ProductLifecycleController;
use App\Http\Controllers\Facility\FacilityExecutionRequestController;

Route::group([], function () {

    // Dashboard
    Route::get('/', [FacilityController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [FacilityController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [FacilityController::class, 'settings'])->name('settings');
    Route::post('/settings', [FacilityController::class, 'updateSettings'])->name('settings.update');

    // Optional new Home v2 (behind feature flag)
    if (config('features.facility_home_v2')) {
        Route::get('/home-v2', [FacilityController::class, 'homeV2'])->name('home-v2');
    }

    // Facility Management
    Route::get('/create', [FacilityController::class, 'create'])->name('create');
    Route::post('/create', [FacilityController::class, 'store'])->name('store');
    Route::get('/edit', [FacilityController::class, 'edit'])->name('edit');
    Route::post('/edit', [FacilityController::class, 'update'])->name('update');
    Route::post('/toggle-status', [FacilityController::class, 'toggleStatus'])->name('toggle-status');
    Route::post('/toggle-verification', [FacilityController::class, 'toggleVerification'])->name('toggle-verification');
    Route::post('/toggle-featured', [FacilityController::class, 'toggleFeatured'])->name('toggle-featured');

    // Products Management
    Route::resource('products', FacilityProductController::class);
    Route::get('categories/{category}/products', [FacilityProductController::class, 'categoryProducts'])->name('categories.products');
    Route::get('products/{product}/lifecycle', [FacilityProductController::class, 'lifecycle'])->name('products.lifecycle');
    Route::post('products/{product}/toggle-status', [FacilityProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('products/{product}/toggle-verification', [FacilityProductController::class, 'toggleVerification'])->name('products.toggle-verification');
    Route::post('products/{product}/toggle-featured', [FacilityProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::get('products/{product}/gallery', [FacilityProductController::class, 'gallery'])->name('products.gallery');
    Route::post('products/{product}/gallery', [FacilityProductController::class, 'storeGallery'])->name('products.gallery.store');
    Route::delete('products/{product}/gallery/{image}', [FacilityProductController::class, 'deleteGallery'])->name('products.gallery.delete');
    Route::get('products/{product}/comments', [FacilityProductController::class, 'comments'])->name('products.comments');
    Route::post('products/{product}/comments/{comment}/reply', [FacilityProductController::class, 'replyComment'])->name('products.comments.reply');
    Route::delete('products/{product}/comments/{comment}', [FacilityProductController::class, 'deleteComment'])->name('products.comments.delete');

    // Bookings Management
    Route::get('bookings/statistics', [FacilityBookingController::class, 'statistics'])->name('bookings.statistics');
    Route::get('bookings/calendar', [FacilityBookingController::class, 'calendar'])->name('bookings.calendar');
    Route::resource('bookings', FacilityBookingController::class);
    Route::post('bookings/{booking}/confirm', [FacilityBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/unconfirm', [FacilityBookingController::class, 'unconfirm'])->name('bookings.unconfirm');
    Route::post('bookings/{booking}/update-payment', [FacilityBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment');

    // Tasks Management (uses existing tasks tables only)
    Route::post('tasks/quick', [FacilityTaskController::class, 'quickStore'])->name('tasks.quick');
    Route::post('tasks/generate-reminders', [FacilityTaskController::class, 'generateReminders'])->name('tasks.generate-reminders');
    Route::resource('tasks', FacilityTaskController::class);

    // Projects Management (basic lifecycle view for facility projects)
    Route::get('projects', [FacilityProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/{project}', [FacilityProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/lifecycle', [FacilityProjectController::class, 'lifecycle'])->name('projects.lifecycle');
    Route::post('projects/{project}/lifecycle/ai', [FacilityProjectController::class, 'aiStage'])->name('projects.lifecycle.ai');

    // Smart Real Estate Broker (AI matching)
    Route::get('smart-broker', [FacilitySmartBrokerController::class, 'index'])->name('smart-broker.index');
    Route::post('smart-broker/match', [FacilitySmartBrokerController::class, 'match'])->middleware('throttle:10,1')->name('smart-broker.match');

    // Execution Requests (generic executors / offers system)
    Route::get('execution-requests/workspace', [FacilityExecutionRequestController::class, 'workspace'])->name('execution-requests.workspace');
    Route::get('execution-requests', [FacilityExecutionRequestController::class, 'index'])->name('execution-requests.index');
    Route::get('execution-requests/create', [FacilityExecutionRequestController::class, 'create'])->name('execution-requests.create');
    Route::post('execution-requests', [FacilityExecutionRequestController::class, 'store'])->name('execution-requests.store');
    Route::get('execution-requests/{executionRequest}', [FacilityExecutionRequestController::class, 'show'])->name('execution-requests.show');
    Route::post('execution-requests/{executionRequest}/bids', [FacilityExecutionRequestController::class, 'storeBid'])->name('execution-requests.bids.store');

    // Appointments Management
    Route::get('appointments', [FacilityAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('appointments/{appointment}', [FacilityAppointmentController::class, 'show'])->name('appointments.show');
    Route::post('appointments/{appointment}/status', [FacilityAppointmentController::class, 'updateStatus'])->name('appointments.update-status');

    // Offers Management
    Route::get('offers/statistics', [FacilityOfferController::class, 'statistics'])->name('offers.statistics');
    Route::get('offers/export', [FacilityOfferController::class, 'export'])->name('offers.export');
    Route::resource('offers', FacilityOfferController::class);
    Route::post('offers/{offer}/toggle-status', [FacilityOfferController::class, 'toggleStatus'])->name('offers.toggle-status');
    Route::post('offers/{offer}/copy', [FacilityOfferController::class, 'copy'])->name('offers.copy');

    // Rentals Reports
    Route::get('reports/rentals/occupancy', [FacilityRentalsReportController::class, 'occupancy'])->name('reports.rentals.occupancy');
    Route::get('reports/rentals/collections', [FacilityRentalsReportController::class, 'collections'])->name('reports.rentals.collections');
    
    // Facility Loan Requests Dashboard
    Route::get('loans/requests', [FacilityController::class, 'loanRequests'])->name('loans.requests');
    Route::get('loans/requests/{loanRequest}', [FacilityController::class, 'showLoanRequest'])->name('loans.requests.show');

    // Contracts Management
    Route::get('contracts/statistics', [FacilityContractController::class, 'statistics'])->name('contracts.statistics');
    Route::get('contracts/export', [FacilityContractController::class, 'export'])->name('contracts.export');
    Route::resource('contracts', FacilityContractController::class);
    Route::post('contracts/{contract}/update-status', [FacilityContractController::class, 'updateStatus'])->name('contracts.update-status');
    Route::post('contracts/{contract}/cancel', [FacilityContractController::class, 'cancel'])->name('contracts.cancel');
    Route::post('contracts/{contract}/record-payment', [FacilityContractController::class, 'recordPayment'])->name('contracts.record-payment');
    Route::post('contracts/{contract}/renew', [FacilityContractController::class, 'renew'])->name('contracts.renew');
    Route::get('contracts/{contract}/invoices', [FacilityContractController::class, 'invoices'])->name('contracts.invoices');
    Route::get('contracts/{contract}/payments', [FacilityContractController::class, 'payments'])->name('contracts.payments');
    Route::get('contracts/{contract}/financial-report', [FacilityContractController::class, 'financialReport'])->name('contracts.financial-report');
    Route::post('contracts/{contract}/commissions', [FacilityContractController::class, 'storeCommission'])->name('contracts.commissions.store');

    // Invoices Management
    Route::get('invoices/statistics', [FacilityInvoiceController::class, 'statistics'])->name('invoices.statistics');
    Route::get('invoices/export', [FacilityInvoiceController::class, 'export'])->name('invoices.export');
    Route::get('invoices/generate', [FacilityInvoiceController::class, 'generateInvoices'])->name('invoices.generate');
    Route::resource('invoices', FacilityInvoiceController::class);
    Route::post('invoices/{invoice}/reminder', [FacilityInvoiceController::class, 'sendReminder'])->name('invoices.reminder');

    // Payments Management
    Route::get('payments/statistics', [FacilityPaymentController::class, 'statistics'])->name('payments.statistics');
    Route::get('payments/export', [FacilityPaymentController::class, 'export'])->name('payments.export');
    Route::resource('payments', FacilityPaymentController::class);
    Route::post('payments/{payment}/confirm', [FacilityPaymentController::class, 'confirm'])->name('payments.confirm');
    Route::post('payments/{payment}/fail', [FacilityPaymentController::class, 'fail'])->name('payments.fail');
    Route::post('payments/{payment}/refund', [FacilityPaymentController::class, 'refund'])->name('payments.refund');

    // Users Management
    Route::get('users/me/dashboard', [FacilityUserController::class, 'employeeDashboard'])->name('users.employee-dashboard');
    Route::get('users/statistics', [FacilityUserController::class, 'statistics'])->name('users.statistics');
    Route::get('users/export', [FacilityUserController::class, 'export'])->name('users.export');
    Route::resource('users', FacilityUserController::class);
    Route::post('users/add-existing', [FacilityUserController::class, 'addExistingUser'])->name('users.add-existing');
    Route::delete('users/{user}/remove', [FacilityUserController::class, 'removeFromFacility'])->name('users.remove');
    Route::post('users/{user}/assign-role', [FacilityUserController::class, 'assignRole'])->name('users.assign-role');
    Route::delete('users/{user}/roles/{role}', [FacilityUserController::class, 'removeRole'])->name('users.remove-role');

    // HR Management (behind feature flag)
    if (config('features.hr_core')) {
        Route::prefix('hr')->name('hr.')->group(function () {
            Route::resource('departments', FacilityHrDepartmentController::class)->only(['index','store','show','update','destroy']);
            Route::resource('positions', FacilityHrPositionController::class)->only(['index','store','show','update','destroy']);
        });
    }

    // Product Lifecycle metrics (behind feature flag)
    if (config('features.facility_lifecycle_widgets')) {
        Route::get('lifecycle/metrics', [ProductLifecycleController::class, 'metrics'])->name('lifecycle.metrics');
    }

    // Financial Reports
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/', [FacilityFinancialReportController::class, 'index'])->name('index');
        Route::get('/revenue', [FacilityFinancialReportController::class, 'revenue'])->name('revenue');
        Route::get('/receivables', [FacilityFinancialReportController::class, 'receivables'])->name('receivables');
        Route::get('/commissions', [FacilityFinancialReportController::class, 'commissions'])->name('commissions');
        Route::get('/payments', [FacilityFinancialReportController::class, 'payments'])->name('payments');
        Route::get('/invoices', [FacilityFinancialReportController::class, 'invoices'])->name('invoices');
        Route::get('/contracts', [FacilityFinancialReportController::class, 'contracts'])->name('contracts');
        Route::get('/customer', [FacilityFinancialReportController::class, 'customer'])->name('customer');
        Route::get('/owner', [FacilityFinancialReportController::class, 'owner'])->name('owner');
        Route::get('/monthly', [FacilityFinancialReportController::class, 'monthly'])->name('monthly');
        Route::get('/yearly', [FacilityFinancialReportController::class, 'yearly'])->name('yearly');
        Route::get('/export', [FacilityFinancialReportController::class, 'export'])->name('export');
    });

    // Additional Facility Routes
    Route::get('notifications', [FacilityController::class, 'notifications'])->name('notifications');
    Route::post('notifications/mark-read', [FacilityController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::get('profile', [FacilityController::class, 'profile'])->name('profile');
    Route::post('profile', [FacilityController::class, 'updateProfile'])->name('profile.update');
    Route::get('change-password', [FacilityController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [FacilityController::class, 'updatePassword'])->name('change-password.update');
    Route::get('reports', [FacilityController::class, 'reports'])->name('reports');
    Route::get('reports/bookings', [FacilityController::class, 'bookingReports'])->name('reports.bookings');
    Route::get('reports/products', [FacilityController::class, 'productReports'])->name('reports.products');
    Route::get('reports/revenue', [FacilityController::class, 'revenueReports'])->name('reports.revenue');
    Route::get('banks', [FacilityController::class, 'banks'])->name('banks.index');
    
    // Facility Customization Routes
    Route::get('customization/{facility}/edit', [FacilityCustomizationController::class, 'edit'])->name('customization.edit');
    Route::put('customization/{facility}', [FacilityCustomizationController::class, 'update'])->name('customization.update');
    Route::get('customization/{facility}/preview', [FacilityCustomizationController::class, 'preview'])->name('customization.preview');
    Route::delete('customization/{facility}/reset', [FacilityCustomizationController::class, 'reset'])->name('customization.reset');
    Route::post('customization/{facility}/preset', [FacilityCustomizationController::class, 'applyPreset'])->name('customization.preset');
    Route::post('customization/test-upload', [FacilityCustomizationController::class, 'testUpload'])->name('customization.test-upload');

    // Enhanced Financial Management System for Facilities
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/dashboard', [FacilityFinancialController::class, 'dashboard'])->name('dashboard');
        
        // Offers Management
        Route::get('/offers', [FacilityFinancialController::class, 'offers'])->name('offers');
        Route::get('/offers/create', [FacilityFinancialController::class, 'createOffer'])->name('create-offer');
        Route::post('/offers/create', [FacilityFinancialController::class, 'createOffer'])->name('store-offer');
        Route::get('/offers/{id}/edit', [FacilityFinancialController::class, 'updateOffer'])->name('edit-offer');
        Route::post('/offers/{id}/edit', [FacilityFinancialController::class, 'updateOffer'])->name('update-offer');
        Route::post('/offers/{id}/toggle-status', [FacilityFinancialController::class, 'toggleOfferStatus'])->name('toggle-offer-status');
        
        // Contracts Management
        Route::get('/contracts', [FacilityFinancialController::class, 'contracts'])->name('contracts');
        Route::get('/contracts/{id}', [FacilityFinancialController::class, 'contractDetails'])->name('contract-details');
        Route::put('/contracts/{id}/status', [FacilityFinancialController::class, 'updateContractStatus'])->name('update-contract-status');
        
        // Payments Management
        Route::get('/payments', [FacilityFinancialController::class, 'payments'])->name('payments');
        Route::post('/payments/{id}/confirm', [FacilityFinancialController::class, 'confirmPayment'])->name('confirm-payment');
        Route::post('/payments/{id}/reject', [FacilityFinancialController::class, 'rejectPayment'])->name('reject-payment');
    });

    // Accounting System Routes
    Route::prefix('accounting')->name('accounting.')->group(function () {
        // Main Accounting Dashboard
        Route::get('/', [AccountingController::class, 'dashboard'])->name('dashboard');
        Route::get('/setup', [AccountingController::class, 'setup'])->name('setup');
        Route::post('/setup', [AccountingController::class, 'createDefaultSetup'])->name('setup.create');
        
        // Accounting Entries
        Route::get('/entries', [AccountingController::class, 'entriesIndex'])->name('entries.index');
        Route::get('/entries/create', [AccountingController::class, 'createEntry'])->name('entries.create');
        Route::post('/entries', [AccountingController::class, 'storeEntry'])->name('entries.store');
        Route::get('/entries/{entry}', [AccountingController::class, 'showEntry'])->name('entries.show');
        Route::post('/entries/{entry}/reverse', [AccountingController::class, 'reverseEntry'])->name('entries.reverse');
        Route::get('/entries/export', [AccountingController::class, 'exportEntries'])->name('entries.export');
        
        // Chart of Accounts
        Route::resource('chart-of-accounts', ChartOfAccountController::class);
        Route::post('chart-of-accounts/{account}/opening-balance', [ChartOfAccountController::class, 'updateOpeningBalance'])->name('chart-of-accounts.opening-balance');
        Route::get('chart-of-accounts/export', [ChartOfAccountController::class, 'export'])->name('chart-of-accounts.export');
        Route::post('chart-of-accounts/create-default', [ChartOfAccountController::class, 'createDefault'])->name('chart-of-accounts.create-default');
        
        // Accounting Periods
        Route::resource('periods', AccountingPeriodController::class);
        Route::post('periods/{period}/close', [AccountingPeriodController::class, 'close'])->name('periods.close');
        Route::post('periods/{period}/lock', [AccountingPeriodController::class, 'lock'])->name('periods.lock');
        Route::post('periods/{period}/unlock', [AccountingPeriodController::class, 'unlock'])->name('periods.unlock');
        
        // Tax Rates
        Route::resource('tax-rates', TaxRateController::class);
        
        // Budgets
        Route::resource('budgets', BudgetController::class);
        Route::post('budgets/{budget}/approve', [BudgetController::class, 'approve'])->name('budgets.approve');
        Route::post('budgets/{budget}/activate', [BudgetController::class, 'activate'])->name('budgets.activate');
        Route::post('budgets/{budget}/complete', [BudgetController::class, 'complete'])->name('budgets.complete');
        Route::post('budgets/{budget}/cancel', [BudgetController::class, 'cancel'])->name('budgets.cancel');
        
        // Financial Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index'])->name('index');
            Route::get('/income-statement', [FinancialReportController::class, 'incomeStatement'])->name('income-statement');
            Route::get('/balance-sheet', [FinancialReportController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/cash-flow', [FinancialReportController::class, 'cashFlow'])->name('cash-flow');
            Route::get('/budget-report', [FinancialReportController::class, 'budgetReport'])->name('budget-report');
            Route::get('/trial-balance', [FinancialReportController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/account-details', [FinancialReportController::class, 'accountDetails'])->name('account-details');
            Route::get('/custom', [FinancialReportController::class, 'customReport'])->name('custom');
            Route::get('/export-all', [FinancialReportController::class, 'exportAll'])->name('export-all');
            Route::get('/export-income-statement', [FinancialReportController::class, 'exportIncomeStatement'])->name('export-income-statement');
            Route::get('/export-balance-sheet', [FinancialReportController::class, 'exportBalanceSheet'])->name('export-balance-sheet');
            Route::get('/export-cash-flow', [FinancialReportController::class, 'exportCashFlow'])->name('export-cash-flow');
            Route::get('/export-budget-report', [FinancialReportController::class, 'exportBudgetReport'])->name('export-budget-report');
            Route::get('/export-trial-balance', [FinancialReportController::class, 'exportTrialBalance'])->name('export-trial-balance');
        });
        
        // Reports and Analytics (moved to nested reports group above)
        // Route::get('/reports', [FacilityFinancialController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [FacilityFinancialController::class, 'exportReports'])->name('export-reports');
        
        // Accounting Entries
        Route::get('/accounting-entries', [FacilityFinancialController::class, 'accountingEntries'])->name('accounting-entries');
    });
});
