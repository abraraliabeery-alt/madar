<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiFacilityController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiFeatureController;
use App\Http\Controllers\Api\ApiSearchController;
use App\Http\Controllers\Api\ApiContactController;
use App\Http\Controllers\Api\ApiStaticController;
use App\Http\Controllers\Api\ApiReportController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiBookingController;
use App\Http\Controllers\Api\ApiContractController;
use App\Http\Controllers\Api\ApiAppointmentController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiNotificationController;
use App\Http\Controllers\Api\ApiAdminController;
use App\Http\Controllers\Api\ApiAdminUserController;
use App\Http\Controllers\Api\ApiAdminFacilityController;
use App\Http\Controllers\Api\ApiAdminProductController;
use App\Http\Controllers\Api\ApiAdminBookingController;
use App\Http\Controllers\Api\ApiAdminContractController;
use App\Http\Controllers\Api\ApiAdminCategoryController;
use App\Http\Controllers\Api\ApiAdminFeatureController;
use App\Http\Controllers\Api\ApiFacilityDashboardController;
use App\Http\Controllers\Api\ApiFacilityProductController;
use App\Http\Controllers\Api\ApiFacilityBookingController;
use App\Http\Controllers\Api\ApiFacilityProfileController;
use App\Http\Controllers\Api\ApiTicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API Routes
Route::prefix('v1')->group(function () {

    // Authentication Routes
    Route::post('/register', [ApiAuthController::class, 'register']);
    Route::post('/login', [ApiAuthController::class, 'login']);
    Route::post('/forgot-password', [ApiAuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);
    Route::post('/verify-email', [ApiAuthController::class, 'verifyEmail']);
    Route::post('/resend-verification', [ApiAuthController::class, 'resendVerification']);

    // Public Product Routes
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::get('/products/featured', [ApiProductController::class, 'featured']);
    Route::get('/products/latest', [ApiProductController::class, 'latest']);
    Route::get('/products/search', [ApiProductController::class, 'search']);
    Route::get('/products/statistics', [ApiProductController::class, 'statistics']);
    Route::get('/products/{product}', [ApiProductController::class, 'show']);
    Route::get('/categories/{category}/products', [ApiProductController::class, 'byCategory']);
    Route::get('/facilities/{facility}/products', [ApiProductController::class, 'byFacility']);

    // Public Facility Routes
    Route::get('/facilities', [ApiFacilityController::class, 'index']);
    Route::get('/facilities/featured', [ApiFacilityController::class, 'featured']);
    Route::get('/facilities/search', [ApiFacilityController::class, 'search']);
    Route::get('/facilities/statistics', [ApiFacilityController::class, 'statistics']);
    Route::get('/facilities/{facility}', [ApiFacilityController::class, 'show']);
    Route::get('/categories/{category}/facilities', [ApiFacilityController::class, 'byCategory']);

    // Public Category Routes
    Route::get('/categories', [ApiCategoryController::class, 'index']);
    Route::get('/categories/{category}', [ApiCategoryController::class, 'show']);
    Route::get('/categories/{category}/products', [ApiCategoryController::class, 'products']);
    Route::get('/categories/{category}/facilities', [ApiCategoryController::class, 'facilities']);

    // Public Feature Routes
    Route::get('/features', [ApiFeatureController::class, 'index']);
    Route::get('/features/{feature}', [ApiFeatureController::class, 'show']);

    // Public Search Routes
    Route::get('/search', [ApiSearchController::class, 'globalSearch']);
    Route::get('/search/products', [ApiSearchController::class, 'searchProducts']);
    Route::get('/search/facilities', [ApiSearchController::class, 'searchFacilities']);
    Route::get('/search/advanced', [ApiSearchController::class, 'advancedSearch']);

    // Public Contact Routes
    Route::post('/contact', [ApiContactController::class, 'sendMessage']);
    Route::post('/contact/quote', [ApiContactController::class, 'requestQuote']);
    Route::post('/contact/feedback', [ApiContactController::class, 'sendFeedback']);

    // Public Static Routes
    Route::get('/about', [ApiStaticController::class, 'about']);
    Route::get('/services', [ApiStaticController::class, 'services']);
    Route::get('/team', [ApiStaticController::class, 'team']);
    Route::get('/terms', [ApiStaticController::class, 'terms']);
    Route::get('/privacy', [ApiStaticController::class, 'privacy']);
    Route::get('/faq', [ApiStaticController::class, 'faq']);
    Route::get('/sitemap', [ApiStaticController::class, 'sitemap']);
});

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // User Profile Routes
    Route::get('/user', [ApiAuthController::class, 'user']);
    Route::post('/user', [ApiAuthController::class, 'updateProfile']);
    Route::post('/user/avatar', [ApiAuthController::class, 'updateAvatar']);
    Route::post('/user/password', [ApiAuthController::class, 'updatePassword']);
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // User Favorites Routes
    Route::get('/favorites', [ApiUserController::class, 'favorites']);
    Route::get('/favorites/products', [ApiUserController::class, 'favoriteProducts']);
    Route::get('/favorites/facilities', [ApiUserController::class, 'favoriteFacilities']);
    Route::post('/favorites/products/{product}', [ApiProductController::class, 'addToFavorites']);
    Route::delete('/favorites/products/{product}', [ApiProductController::class, 'removeFromFavorites']);
    Route::post('/favorites/facilities/{facility}', [ApiFacilityController::class, 'addToFavorites']);
    Route::delete('/favorites/facilities/{facility}', [ApiFacilityController::class, 'removeFromFavorites']);

    // User Bookings Routes
    Route::get('/bookings', [ApiBookingController::class, 'index']);
    Route::post('/bookings', [ApiBookingController::class, 'store']);
    Route::get('/bookings/{booking}', [ApiBookingController::class, 'show']);
    Route::put('/bookings/{booking}', [ApiBookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [ApiBookingController::class, 'destroy']);
    Route::post('/bookings/{booking}/cancel', [ApiBookingController::class, 'cancel']);
    Route::post('/bookings/{booking}/reschedule', [ApiBookingController::class, 'reschedule']);
    Route::post('/bookings/{booking}/review', [ApiBookingController::class, 'review']);

    // User Contracts Routes
    Route::get('/contracts', [ApiContractController::class, 'index']);
    Route::get('/contracts/{contract}', [ApiContractController::class, 'show']);
    Route::get('/contracts/{contract}/download', [ApiContractController::class, 'download']);

    // User Appointments Routes
    Route::get('/appointments', [ApiAppointmentController::class, 'index']);
    Route::post('/appointments', [ApiAppointmentController::class, 'store']);
    Route::get('/appointments/{appointment}', [ApiAppointmentController::class, 'show']);
    Route::put('/appointments/{appointment}', [ApiAppointmentController::class, 'update']);
    Route::delete('/appointments/{appointment}', [ApiAppointmentController::class, 'destroy']);
    Route::post('/appointments/{appointment}/cancel', [ApiAppointmentController::class, 'cancel']);
    Route::post('/appointments/{appointment}/reschedule', [ApiAppointmentController::class, 'reschedule']);

    // User Comments Routes
    Route::get('/comments', [ApiCommentController::class, 'index']);
    Route::post('/comments/products/{product}', [ApiCommentController::class, 'storeProductComment']);
    Route::post('/comments/facilities/{facility}', [ApiCommentController::class, 'storeFacilityComment']);
    Route::put('/comments/{comment}', [ApiCommentController::class, 'update']);
    Route::delete('/comments/{comment}', [ApiCommentController::class, 'destroy']);

    // User Notifications Routes
    Route::get('/notifications', [ApiNotificationController::class, 'index']);
    Route::post('/notifications/mark-read', [ApiNotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [ApiNotificationController::class, 'markAllAsRead']);
    Route::get('/notifications/settings', [ApiNotificationController::class, 'settings']);
    Route::post('/notifications/settings', [ApiNotificationController::class, 'updateSettings']);

    // User Activity Routes
    Route::get('/activity', [ApiUserController::class, 'activity']);
    Route::get('/activity/bookings', [ApiUserController::class, 'bookingActivity']);
    Route::get('/activity/views', [ApiUserController::class, 'viewActivity']);
    Route::get('/activity/searches', [ApiUserController::class, 'searchActivity']);

    // User Reports Routes
    Route::get('/reports', [ApiReportController::class, 'index']);
    Route::get('/reports/bookings', [ApiReportController::class, 'bookings']);
    Route::get('/reports/spending', [ApiReportController::class, 'spending']);
    Route::get('/reports/activity', [ApiReportController::class, 'activity']);

    // User Settings Routes
    Route::get('/settings', [ApiUserController::class, 'settings']);
    Route::post('/settings', [ApiUserController::class, 'updateSettings']);
    Route::get('/settings/privacy', [ApiUserController::class, 'privacySettings']);
    Route::post('/settings/privacy', [ApiUserController::class, 'updatePrivacySettings']);
    Route::get('/settings/security', [ApiUserController::class, 'securitySettings']);
    Route::post('/settings/security', [ApiUserController::class, 'updateSecuritySettings']);

    // User Help & Support Routes
    Route::get('/help/tickets', [ApiTicketController::class, 'index']);
    Route::post('/help/tickets', [ApiTicketController::class, 'store']);
    Route::get('/help/tickets/{ticket}', [ApiTicketController::class, 'show']);
    Route::post('/help/tickets/{ticket}/reply', [ApiTicketController::class, 'reply']);
    Route::delete('/help/tickets/{ticket}', [ApiTicketController::class, 'destroy']);

    // Rating Routes
    Route::post('/products/{product}/rate', [ApiProductController::class, 'rate']);
    Route::post('/facilities/{facility}/rate', [ApiFacilityController::class, 'rate']);
});

// Admin API Routes (require admin role)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('v1/admin')->name('api.admin.')->group(function () {

    // Admin Dashboard Routes
    Route::get('/dashboard', [ApiAdminController::class, 'dashboard']);
    Route::get('/statistics', [ApiAdminController::class, 'statistics']);
    Route::get('/reports', [ApiAdminController::class, 'reports']);

    // Admin Users Routes
    Route::apiResource('users', ApiAdminUserController::class);
    Route::post('users/{user}/toggle-status', [ApiAdminUserController::class, 'toggleStatus']);
    Route::post('users/{user}/toggle-verification', [ApiAdminUserController::class, 'toggleVerification']);
    Route::post('users/{user}/assign-role', [ApiAdminUserController::class, 'assignRole']);
    Route::post('users/{user}/assign-facility', [ApiAdminUserController::class, 'assignFacility']);

    // Admin Facilities Routes
    Route::apiResource('facilities', ApiAdminFacilityController::class);
    Route::post('facilities/{facility}/toggle-status', [ApiAdminFacilityController::class, 'toggleStatus']);
    Route::post('facilities/{facility}/toggle-verification', [ApiAdminFacilityController::class, 'toggleVerification']);
    Route::post('facilities/{facility}/toggle-featured', [ApiAdminFacilityController::class, 'toggleFeatured']);

    // Admin Products Routes
    Route::apiResource('products', ApiAdminProductController::class);
    Route::post('products/{product}/toggle-status', [ApiAdminProductController::class, 'toggleStatus']);
    Route::post('products/{product}/toggle-verification', [ApiAdminProductController::class, 'toggleVerification']);
    Route::post('products/{product}/toggle-featured', [ApiAdminProductController::class, 'toggleFeatured']);

    // Admin Bookings Routes
    Route::apiResource('bookings', ApiAdminBookingController::class);
    Route::post('bookings/{booking}/confirm', [ApiAdminBookingController::class, 'confirm']);
    Route::post('bookings/{booking}/unconfirm', [ApiAdminBookingController::class, 'unconfirm']);
    Route::post('bookings/{booking}/update-payment', [ApiAdminBookingController::class, 'updatePaymentStatus']);

    // Admin Contracts Routes
    Route::apiResource('contracts', ApiAdminContractController::class);
    Route::post('contracts/{contract}/toggle-status', [ApiAdminContractController::class, 'toggleStatus']);
    Route::post('contracts/{contract}/toggle-verification', [ApiAdminContractController::class, 'toggleVerification']);

    // Admin Categories Routes
    Route::apiResource('categories', ApiAdminCategoryController::class);
    Route::post('categories/{category}/toggle-status', [ApiAdminCategoryController::class, 'toggleStatus']);
    Route::post('categories/{category}/toggle-featured', [ApiAdminCategoryController::class, 'toggleFeatured']);

    // Admin Features Routes
    Route::apiResource('features', ApiAdminFeatureController::class);
    Route::post('features/{feature}/toggle-status', [ApiAdminFeatureController::class, 'toggleStatus']);
});

// Facility API Routes (require facility role)
Route::middleware(['auth:sanctum', 'role:facility'])->prefix('v1/facility')->name('api.facility.')->group(function () {

    // Facility Dashboard Routes
    Route::get('/dashboard', [ApiFacilityDashboardController::class, 'dashboard']);
    Route::get('/statistics', [ApiFacilityDashboardController::class, 'statistics']);

    // Facility Products Routes
    Route::apiResource('products', ApiFacilityProductController::class);
    Route::post('products/{product}/toggle-status', [ApiFacilityProductController::class, 'toggleStatus']);
    Route::post('products/{product}/toggle-verification', [ApiFacilityProductController::class, 'toggleVerification']);
    Route::post('products/{product}/toggle-featured', [ApiFacilityProductController::class, 'toggleFeatured']);

    // Facility Bookings Routes
    Route::apiResource('bookings', ApiFacilityBookingController::class);
    Route::post('bookings/{booking}/confirm', [ApiFacilityBookingController::class, 'confirm']);
    Route::post('bookings/{booking}/unconfirm', [ApiFacilityBookingController::class, 'unconfirm']);
    Route::post('bookings/{booking}/update-payment', [ApiFacilityBookingController::class, 'updatePaymentStatus']);

    // Facility Profile Routes
    Route::get('/profile', [ApiFacilityProfileController::class, 'show']);
    Route::post('/profile', [ApiFacilityProfileController::class, 'update']);
    Route::post('/profile/logo', [ApiFacilityProfileController::class, 'updateLogo']);
});
