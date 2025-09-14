<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\ClientBookingController;
use App\Http\Controllers\Client\ClientOfferController;
use App\Http\Controllers\Client\ClientContractController;
use App\Http\Controllers\Client\ClientFinancialController;

// Client Routes - جميع routes تحتاج middleware client
Route::group([], function () {

    // Dashboard
    Route::get('/', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
    Route::post('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [ClientController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [ClientController::class, 'updatePassword'])->name('change-password.update');

    // Favorites
    Route::get('/favorites', [ClientController::class, 'favorites'])->name('favorites');
    Route::get('/favorites/products', [ClientController::class, 'favoriteProducts'])->name('favorites.products');
    Route::get('/favorites/facilities', [ClientController::class, 'favoriteFacilities'])->name('favorites.facilities');
    Route::post('/favorites/products/{product}', [ClientController::class, 'addToFavorites'])->name('favorites.add-product');
    Route::delete('/favorites/products/{product}', [ClientController::class, 'removeFromFavorites'])->name('favorites.remove-product');
    Route::post('/favorites/facilities/{facility}', [ClientController::class, 'addFacilityToFavorites'])->name('favorites.add-facility');
    Route::delete('/favorites/facilities/{facility}', [ClientController::class, 'removeFacilityFromFavorites'])->name('favorites.remove-facility');

    // Activity Log
    Route::get('/activity', [ClientController::class, 'activity'])->name('activity');
    Route::get('/activity/bookings', [ClientController::class, 'bookingActivity'])->name('activity.bookings');
    Route::get('/activity/views', [ClientController::class, 'viewActivity'])->name('activity.views');
    Route::get('/activity/searches', [ClientController::class, 'searchActivity'])->name('activity.searches');

    // Notifications
    Route::get('/notifications', [ClientController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-read', [ClientController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [ClientController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/settings', [ClientController::class, 'notificationSettings'])->name('notifications.settings');
    Route::post('/notifications/settings', [ClientController::class, 'updateNotificationSettings'])->name('notifications.settings.update');

    // Bookings Management
    Route::resource('bookings', ClientBookingController::class);
    Route::post('bookings/{booking}/cancel', [ClientBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('bookings/{booking}/reschedule', [ClientBookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::post('bookings/{booking}/review', [ClientBookingController::class, 'review'])->name('bookings.review');
    Route::get('bookings/statistics', [ClientBookingController::class, 'statistics'])->name('bookings.statistics');

    // Offers
    Route::get('/offers', [ClientOfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/{offer}', [ClientOfferController::class, 'show'])->name('offers.show');
    Route::get('/offers/type/{type}', [ClientOfferController::class, 'byType'])->name('offers.by-type');
    Route::get('/offers/product/{product}', [ClientOfferController::class, 'byProduct'])->name('offers.by-product');
    Route::get('/offers/search', [ClientOfferController::class, 'search'])->name('offers.search');
    Route::post('/offers/{offer}/add-to-favorites', [ClientOfferController::class, 'addToFavorites'])->name('offers.add-to-favorites');
    Route::delete('/offers/{offer}/remove-from-favorites', [ClientOfferController::class, 'removeFromFavorites'])->name('offers.remove-from-favorites');
    Route::post('/offers/{offer}/request-info', [ClientOfferController::class, 'requestInfo'])->name('offers.request-info');
    Route::post('/offers/{offer}/book-visit', [ClientOfferController::class, 'bookVisit'])->name('offers.book-visit');
    Route::get('/offers/compare', [ClientOfferController::class, 'compare'])->name('offers.compare');
    Route::get('/offers/statistics', [ClientOfferController::class, 'statistics'])->name('offers.statistics');

    // Contracts
    Route::resource('contracts', ClientContractController::class);
    Route::get('contracts/{contract}/invoices', [ClientContractController::class, 'invoices'])->name('contracts.invoices');
    Route::get('contracts/{contract}/payments', [ClientContractController::class, 'payments'])->name('contracts.payments');
    Route::get('contracts/{contract}/financial-report', [ClientContractController::class, 'financialReport'])->name('contracts.financial-report');
    Route::post('contracts/request/{offer}', [ClientContractController::class, 'requestContract'])->name('contracts.request');
    Route::post('contracts/{contract}/confirm', [ClientContractController::class, 'confirmContract'])->name('contracts.confirm');
    Route::post('contracts/{contract}/cancel', [ClientContractController::class, 'cancelContract'])->name('contracts.cancel');
    Route::get('contracts/{contract}/download', [ClientContractController::class, 'download'])->name('contracts.download');
    Route::get('contracts/{contract}/payment', [ClientContractController::class, 'paymentPage'])->name('contracts.payment-page');
    Route::post('contracts/{contract}/pay-invoice', [ClientContractController::class, 'payInvoice'])->name('contracts.pay-invoice');
    Route::get('contracts/statistics', [ClientContractController::class, 'statistics'])->name('contracts.statistics');

    // Appointments
    Route::get('/appointments', [ClientController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/create', [ClientController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [ClientController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{appointment}', [ClientController::class, 'showAppointment'])->name('appointments.show');
    Route::post('/appointments/{appointment}/cancel', [ClientController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::post('/appointments/{appointment}/reschedule', [ClientController::class, 'rescheduleAppointment'])->name('appointments.reschedule');

    // Comments & Reviews
    Route::get('/comments', [ClientController::class, 'comments'])->name('comments');
    Route::get('/comments/products', [ClientController::class, 'productComments'])->name('comments.products');
    Route::get('/comments/facilities', [ClientController::class, 'facilityComments'])->name('comments.facilities');
    Route::post('/comments/products/{product}', [ClientController::class, 'addProductComment'])->name('comments.add-product');
    Route::post('/comments/facilities/{facility}', [ClientController::class, 'addFacilityComment'])->name('comments.add-facility');
    Route::put('/comments/{comment}', [ClientController::class, 'updateComment'])->name('comments.update');
    Route::delete('/comments/{comment}', [ClientController::class, 'deleteComment'])->name('comments.delete');

    // Gallery
    Route::get('/gallery', [ClientController::class, 'gallery'])->name('gallery');
    Route::get('/gallery/products', [ClientController::class, 'productGallery'])->name('gallery.products');
    Route::get('/gallery/facilities', [ClientController::class, 'facilityGallery'])->name('gallery.facilities');

    // Reports & Analytics
    Route::get('/reports', [ClientController::class, 'reports'])->name('reports');
    Route::get('/reports/bookings', [ClientController::class, 'bookingReports'])->name('reports.bookings');
    Route::get('/reports/spending', [ClientController::class, 'spendingReports'])->name('reports.spending');
    Route::get('/reports/activity', [ClientController::class, 'activityReports'])->name('reports.activity');

    // Settings
    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');
    Route::post('/settings', [ClientController::class, 'updateSettings'])->name('settings.update');
    Route::get('/settings/privacy', [ClientController::class, 'privacySettings'])->name('settings.privacy');
    Route::post('/settings/privacy', [ClientController::class, 'updatePrivacySettings'])->name('settings.privacy.update');
    Route::get('/settings/security', [ClientController::class, 'securitySettings'])->name('settings.security');
    Route::post('/settings/security', [ClientController::class, 'updateSecuritySettings'])->name('settings.security.update');

    // Help & Support
    Route::get('/help', [ClientController::class, 'help'])->name('help');
    Route::get('/help/faq', [ClientController::class, 'faq'])->name('help.faq');
    Route::get('/help/contact', [ClientController::class, 'contact'])->name('help.contact');
    Route::post('/help/contact', [ClientController::class, 'sendContactMessage'])->name('help.contact.send');
    Route::get('/help/tickets', [ClientController::class, 'tickets'])->name('help.tickets');
    Route::get('/help/tickets/create', [ClientController::class, 'createTicket'])->name('help.tickets.create');
    Route::post('/help/tickets', [ClientController::class, 'storeTicket'])->name('help.tickets.store');
    Route::get('/help/tickets/{ticket}', [ClientController::class, 'showTicket'])->name('help.tickets.show');
    Route::post('/help/tickets/{ticket}/reply', [ClientController::class, 'replyTicket'])->name('help.tickets.reply');

    // Enhanced Financial Management System for Clients
    Route::prefix('financial')->name('financial.')->group(function () {
        // Main Dashboard
        Route::get('/dashboard', [ClientFinancialController::class, 'dashboard'])->name('dashboard');
        
        // Available Offers
        Route::get('/offers', [ClientFinancialController::class, 'offers'])->name('offers');
        Route::get('/offers/{id}', [ClientFinancialController::class, 'offerDetails'])->name('offer-details');
        
        // Contract Management
        Route::post('/request-contract', [ClientFinancialController::class, 'requestContract'])->name('request-contract');
        Route::get('/contracts', [ClientFinancialController::class, 'contracts'])->name('contracts');
        Route::get('/contracts/{id}', [ClientFinancialController::class, 'contractDetails'])->name('contract-details');
        Route::delete('/contracts/{id}/cancel', [ClientFinancialController::class, 'cancelContract'])->name('cancel-contract');
        
        // Contract Printing
        Route::get('/contracts/{id}/print', [ClientFinancialController::class, 'printContract'])->name('print-contract');
        
        // Invoices Management
        Route::get('/invoices', [ClientFinancialController::class, 'invoices'])->name('invoices');
        Route::get('/invoices/{id}/download', [ClientFinancialController::class, 'downloadInvoice'])->name('download-invoice');
        
        // Payments Management
        Route::post('/make-payment', [ClientFinancialController::class, 'makePayment'])->name('make-payment');
        Route::get('/payments', [ClientFinancialController::class, 'payments'])->name('payments');
        
        // Financial Summary and Reports
        Route::get('/summary', [ClientFinancialController::class, 'financialSummary'])->name('summary');
    });
});
