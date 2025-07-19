<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFacilityController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminContractController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminFeatureController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
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
    Route::post('bookings/{booking}/update-payment', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment');
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
    Route::get('contracts/statistics', [AdminContractController::class, 'statistics'])->name('contracts.statistics');
    Route::get('contracts/export', [AdminContractController::class, 'export'])->name('contracts.export');

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

    // Additional Admin Routes
    Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::post('notifications/mark-read', [AdminController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::get('profile', [AdminController::class, 'profile'])->name('profile');
    Route::post('profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('change-password', [AdminController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [AdminController::class, 'updatePassword'])->name('change-password.update');
});
