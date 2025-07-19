<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Facility\FacilityController;
use App\Http\Controllers\Facility\FacilityProductController;
use App\Http\Controllers\Facility\FacilityBookingController;

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
});
