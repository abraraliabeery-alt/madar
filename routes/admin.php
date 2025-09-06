<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFacilityController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminContractController;
use App\Http\Controllers\Admin\AdminOfferController;
use App\Http\Controllers\Admin\AdminFinancialReportController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminAttributeController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminUploadController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/index', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

    // Users Management
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('users/{user}/toggle-verification', [AdminUserController::class, 'toggleVerification'])->name('users.toggle-verification');
    Route::post('users/{user}/assign-role', [AdminUserController::class, 'assignRole'])->name('users.assign-role');
    Route::post('users/{user}/assign-facility', [AdminUserController::class, 'assignFacility'])->name('users.assign-facility');
    Route::get('users/{user}/activity', [AdminUserController::class, 'activity'])->name('users.activity');

    // Facilities Management
    Route::resource('facilities', AdminFacilityController::class);
    Route::post('facilities/{facility}/toggle-status', [AdminFacilityController::class, 'toggleStatus'])->name('facilities.toggle-status');
    Route::post('facilities/{facility}/toggle-verification', [AdminFacilityController::class, 'toggleVerification'])->name('facilities.toggle-verification');
    Route::post('facilities/{facility}/toggle-featured', [AdminFacilityController::class, 'toggleFeatured'])->name('facilities.toggle-featured');
    Route::get('facilities/{facility}/products', [AdminFacilityController::class, 'products'])->name('facilities.products');
    Route::get('facilities/{facility}/bookings', [AdminFacilityController::class, 'bookings'])->name('facilities.bookings');

    // Products Management
    Route::resource('products', AdminProductController::class);
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('products/{product}/toggle-verification', [AdminProductController::class, 'toggleVerification'])->name('products.toggle-verification');
    Route::post('products/{product}/toggle-featured', [AdminProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::get('products/{product}/gallery', [AdminProductController::class, 'gallery'])->name('products.gallery');
    Route::get('products/{product}/comments', [AdminProductController::class, 'comments'])->name('products.comments');

    // Bookings Management
    Route::resource('bookings', AdminBookingController::class);
    Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('bookings/{booking}/unconfirm', [AdminBookingController::class, 'unconfirm'])->name('bookings.unconfirm');
    Route::post('bookings/{booking}/update-payment-status', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment-status');
    Route::get('bookings/statistics', [AdminBookingController::class, 'statistics'])->name('bookings.statistics');
    Route::get('bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');

    // Roles Management
    Route::resource('roles', AdminRoleController::class);
    Route::get('roles/{role}/permissions', [AdminRoleController::class, 'permissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [AdminRoleController::class, 'updatePermissions'])->name('roles.update-permissions');

    // Contracts Management
    Route::resource('contracts', AdminContractController::class);
    Route::post('contracts/{contract}/toggle-status', [AdminContractController::class, 'toggleStatus'])->name('contracts.toggle-status');
    Route::post('contracts/{contract}/toggle-verification', [AdminContractController::class, 'toggleVerification'])->name('contracts.toggle-verification');
    Route::post('contracts/{contract}/update-status', [AdminContractController::class, 'updateStatus'])->name('contracts.update-status');
    Route::post('contracts/{contract}/cancel', [AdminContractController::class, 'cancel'])->name('contracts.cancel');
    Route::post('contracts/{contract}/record-payment', [AdminContractController::class, 'recordPayment'])->name('contracts.record-payment');
    Route::get('contracts/{contract}/invoices', [AdminContractController::class, 'invoices'])->name('contracts.invoices');
    Route::get('contracts/{contract}/payments', [AdminContractController::class, 'payments'])->name('contracts.payments');
    Route::get('contracts/{contract}/financial-report', [AdminContractController::class, 'financialReport'])->name('contracts.financial-report');
    Route::get('contracts/statistics', [AdminContractController::class, 'statistics'])->name('contracts.statistics');
    Route::get('contracts/export', [AdminContractController::class, 'export'])->name('contracts.export');

    // Offers Management
    Route::resource('offers', AdminOfferController::class);
    Route::post('offers/{offer}/toggle-status', [AdminOfferController::class, 'toggleStatus'])->name('offers.toggle-status');
    Route::post('offers/{offer}/copy', [AdminOfferController::class, 'copy'])->name('offers.copy');
    Route::get('offers/statistics', [AdminOfferController::class, 'statistics'])->name('offers.statistics');
    Route::get('offers/export', [AdminOfferController::class, 'export'])->name('offers.export');

    // Financial Reports
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/', [AdminFinancialReportController::class, 'index'])->name('index');
        Route::get('/facility-summary', [AdminFinancialReportController::class, 'facilitySummary'])->name('facility-summary');
        Route::get('/revenue', [AdminFinancialReportController::class, 'revenue'])->name('revenue');
        Route::get('/receivables', [AdminFinancialReportController::class, 'receivables'])->name('receivables');
        Route::get('/commissions', [AdminFinancialReportController::class, 'commissions'])->name('commissions');
        Route::get('/payments', [AdminFinancialReportController::class, 'payments'])->name('payments');
        Route::get('/invoices', [AdminFinancialReportController::class, 'invoices'])->name('invoices');
        Route::get('/contracts', [AdminFinancialReportController::class, 'contracts'])->name('contracts');
        Route::get('/customer', [AdminFinancialReportController::class, 'customer'])->name('customer');
        Route::get('/owner', [AdminFinancialReportController::class, 'owner'])->name('owner');
        Route::get('/monthly', [AdminFinancialReportController::class, 'monthly'])->name('monthly');
        Route::get('/yearly', [AdminFinancialReportController::class, 'yearly'])->name('yearly');
        Route::get('/export', [AdminFinancialReportController::class, 'export'])->name('export');
    });

    // Categories Management
    Route::resource('categories', AdminCategoryController::class);
    Route::post('categories/{category}/toggle-status', [AdminCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::post('categories/{category}/toggle-featured', [AdminCategoryController::class, 'toggleFeatured'])->name('categories.toggle-featured');
    Route::post('categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');
    Route::get('categories/statistics', [AdminCategoryController::class, 'statistics'])->name('categories.statistics');

    // Features Management
    Route::resource('features', AdminFeatureController::class);
    Route::post('features/{feature}/toggle-status', [AdminFeatureController::class, 'toggleStatus'])->name('features.toggle-status');
    Route::post('features/reorder', [AdminFeatureController::class, 'reorder'])->name('features.reorder');
    Route::get('features/statistics', [AdminFeatureController::class, 'statistics'])->name('features.statistics');

    // Attributes Management
    Route::resource('attributes', AdminAttributeController::class);
    Route::post('attributes/{attribute}/toggle-required', [AdminAttributeController::class, 'toggleRequired'])->name('attributes.toggle-required');
    Route::get('attributes/statistics', [AdminAttributeController::class, 'statistics'])->name('attributes.statistics');

    // FAQ Management
    Route::resource('faqs', AdminFaqController::class);
    Route::post('faqs/{faq}/toggle-status', [AdminFaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
    Route::post('faqs/update-order', [AdminFaqController::class, 'updateOrder'])->name('faqs.update-order');

    // Additional Admin Routes
    Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('notifications/mark-read', [AdminController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [AdminController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::get('notifications/settings', [AdminController::class, 'notificationSettings'])->name('notifications.settings');
    Route::post('notifications/settings', [AdminController::class, 'updateNotificationSettings'])->name('notifications.settings.update');
    Route::get('notifications/count', [AdminController::class, 'getUnreadNotificationsCount'])->name('notifications.count');
    Route::get('notifications/latest', [AdminController::class, 'getLatestNotifications'])->name('notifications.latest');

    // Search Routes
    Route::get('search/global', [AdminController::class, 'globalSearch'])->name('search.global');
    Route::get('search/results', [AdminController::class, 'searchResults'])->name('search.results');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('change-password', [AdminController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [AdminController::class, 'updatePassword'])->name('change-password.update');

    // Profile Routes
    Route::get('profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [AdminProfileController::class, 'update'])->name('profile.update');

    // Upload Routes
    Route::post('upload/image', [AdminUploadController::class, 'uploadImage'])->name('upload.image');
});
