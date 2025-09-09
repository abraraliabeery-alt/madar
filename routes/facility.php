<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facility\FacilityController;
use App\Http\Controllers\Facility\FacilityProductController;
use App\Http\Controllers\Facility\FacilityBookingController;
use App\Http\Controllers\Facility\FacilityOfferController;
use App\Http\Controllers\Facility\FacilityContractController;
use App\Http\Controllers\Facility\FacilityFinancialReportController;
use App\Http\Controllers\Facility\FacilityFinancialController;
use App\Http\Controllers\FacilityCustomizationController;

Route::middleware(['auth', 'role:facility'])->prefix('facility')->name('facility.')->group(function () {

    // Dashboard
    Route::get('/', [FacilityController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [FacilityController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [FacilityController::class, 'settings'])->name('settings');
    Route::post('/settings', [FacilityController::class, 'updateSettings'])->name('settings.update');

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
    Route::resource('bookings', FacilityBookingController::class);
    Route::post('bookings/{booking}/confirm', [FacilityBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/unconfirm', [FacilityBookingController::class, 'unconfirm'])->name('bookings.unconfirm');
    Route::post('bookings/{booking}/update-payment', [FacilityBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment');
    Route::get('bookings/statistics', [FacilityBookingController::class, 'statistics'])->name('bookings.statistics');
    Route::get('bookings/calendar', [FacilityBookingController::class, 'calendar'])->name('bookings.calendar');

    // Offers Management
    Route::resource('offers', FacilityOfferController::class);
    Route::post('offers/{offer}/toggle-status', [FacilityOfferController::class, 'toggleStatus'])->name('offers.toggle-status');
    Route::post('offers/{offer}/copy', [FacilityOfferController::class, 'copy'])->name('offers.copy');
    Route::get('offers/statistics', [FacilityOfferController::class, 'statistics'])->name('offers.statistics');
    Route::get('offers/export', [FacilityOfferController::class, 'export'])->name('offers.export');

    // Contracts Management
    Route::resource('contracts', FacilityContractController::class);
    Route::post('contracts/{contract}/update-status', [FacilityContractController::class, 'updateStatus'])->name('contracts.update-status');
    Route::post('contracts/{contract}/cancel', [FacilityContractController::class, 'cancel'])->name('contracts.cancel');
    Route::post('contracts/{contract}/record-payment', [FacilityContractController::class, 'recordPayment'])->name('contracts.record-payment');
    Route::get('contracts/{contract}/invoices', [FacilityContractController::class, 'invoices'])->name('contracts.invoices');
    Route::get('contracts/{contract}/payments', [FacilityContractController::class, 'payments'])->name('contracts.payments');
    Route::get('contracts/{contract}/financial-report', [FacilityContractController::class, 'financialReport'])->name('contracts.financial-report');

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
        
        // Reports and Analytics
        Route::get('/reports', [FacilityFinancialController::class, 'reports'])->name('reports');
        Route::get('/reports/export', [FacilityFinancialController::class, 'exportReports'])->name('export-reports');
        
        // Accounting Entries
        Route::get('/accounting-entries', [FacilityFinancialController::class, 'accountingEntries'])->name('accounting-entries');
    });
});
