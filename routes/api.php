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
use App\Http\Controllers\Api\ApiOfferController;
use App\Http\Controllers\Api\ApiFinancialReportController;
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
use App\Http\Controllers\Api\ApiClientContractController;
use App\Http\Controllers\Api\ApiFacilityOfferController;
use App\Http\Controllers\Api\ApiAdminFinancialController;
use App\Http\Controllers\Api\ApiFacilityDashboardController;
use App\Http\Controllers\Api\ApiFacilityProductController;
use App\Http\Controllers\Api\ApiFacilityBookingController;
use App\Http\Controllers\Api\ApiFacilityProfileController;
use App\Http\Controllers\Api\ApiTicketController;
use App\Http\Controllers\Api\ApiAttributeController;

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

    // Public Offer Routes
    Route::get('/offers', [ApiOfferController::class, 'index']);
    Route::get('/offers/statistics', [ApiOfferController::class, 'statistics']);
    Route::get('/offers/export', [ApiOfferController::class, 'export']);
    Route::get('/products/{product}/offers', [ApiOfferController::class, 'getProductOffers']);
    Route::get('/offers/{offer}', [ApiOfferController::class, 'show']);

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
    Route::get('/features/by-category', [ApiFeatureController::class, 'getByCategory']);
    Route::get('/features/{feature}', [ApiFeatureController::class, 'show']);

    // Public Attribute Routes
    Route::get('/attributes', [ApiAttributeController::class, 'index']);
    Route::get('/attributes/by-category', [ApiAttributeController::class, 'getByCategory']);

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

    // User Client Contract Routes
    Route::prefix('client')->group(function () {
        Route::get('/offers', [ApiClientContractController::class, 'getAvailableOffers']);
        Route::get('/offers/{id}', [ApiClientContractController::class, 'getOfferDetails']);
        Route::post('/contracts', [ApiClientContractController::class, 'requestContract']);
        Route::get('/contracts', [ApiClientContractController::class, 'getMyContracts']);
        Route::get('/contracts/{id}', [ApiClientContractController::class, 'getContractDetails']);
        Route::delete('/contracts/{id}', [ApiClientContractController::class, 'cancelContract']);
        Route::get('/invoices', [ApiClientContractController::class, 'getMyInvoices']);
        Route::post('/payments', [ApiClientContractController::class, 'makePayment']);
        Route::get('/payments', [ApiClientContractController::class, 'getMyPayments']);
        Route::get('/financial-summary', [ApiClientContractController::class, 'getFinancialSummary']);
    });

    // Legacy User Contracts Routes (for backward compatibility)
    Route::get('/contracts', [ApiContractController::class, 'index']);
    Route::get('/contracts/{contract}', [ApiContractController::class, 'show']);
    Route::get('/contracts/{contract}/download', [ApiContractController::class, 'download']);
    Route::get('/contracts/{contract}/invoices', [ApiContractController::class, 'getInvoices']);
    Route::get('/contracts/{contract}/payments', [ApiContractController::class, 'getPayments']);
    Route::get('/contracts/{contract}/financial-report', [ApiContractController::class, 'getFinancialReport']);

    // User Offers Routes
    Route::get('/offers', [ApiOfferController::class, 'index']);
    Route::post('/offers', [ApiOfferController::class, 'store']);
    Route::get('/offers/{offer}', [ApiOfferController::class, 'show']);
    Route::put('/offers/{offer}', [ApiOfferController::class, 'update']);
    Route::delete('/offers/{offer}', [ApiOfferController::class, 'destroy']);
    Route::post('/offers/{offer}/toggle-status', [ApiOfferController::class, 'toggleStatus']);
    Route::post('/offers/{offer}/copy', [ApiOfferController::class, 'copy']);
    Route::get('/offers/statistics', [ApiOfferController::class, 'statistics']);
    Route::get('/offers/export', [ApiOfferController::class, 'export']);

    // Financial Reports Routes
    Route::get('/financial/revenue', [ApiFinancialReportController::class, 'revenue']);
    Route::get('/financial/receivables', [ApiFinancialReportController::class, 'receivables']);
    Route::get('/financial/commissions', [ApiFinancialReportController::class, 'commissions']);
    Route::get('/financial/payments', [ApiFinancialReportController::class, 'payments']);
    Route::get('/financial/invoices', [ApiFinancialReportController::class, 'invoices']);
    Route::get('/financial/contracts', [ApiFinancialReportController::class, 'contracts']);
    Route::get('/financial/customer', [ApiFinancialReportController::class, 'customer']);
    Route::get('/financial/owner', [ApiFinancialReportController::class, 'owner']);
    Route::get('/financial/monthly', [ApiFinancialReportController::class, 'monthly']);
    Route::get('/financial/yearly', [ApiFinancialReportController::class, 'yearly']);

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
    Route::post('contracts/{contract}/record-payment', [ApiContractController::class, 'recordPayment']);

    // Admin Offers Routes
    Route::apiResource('offers', ApiOfferController::class);
    Route::post('offers/{offer}/toggle-status', [ApiOfferController::class, 'toggleStatus']);
    Route::post('offers/{offer}/copy', [ApiOfferController::class, 'copy']);

    // Admin Financial Management Routes (New Enhanced)
    Route::get('/financial/dashboard', [ApiAdminFinancialController::class, 'dashboard']);
    Route::get('/offers', [ApiAdminFinancialController::class, 'getAllOffers']);
    Route::get('/contracts', [ApiAdminFinancialController::class, 'getAllContracts']);
    Route::get('/payments', [ApiAdminFinancialController::class, 'getAllPayments']);
    Route::post('/payments/{id}/confirm', [ApiAdminFinancialController::class, 'confirmPayment']);
    Route::put('/contracts/{id}/status', [ApiAdminFinancialController::class, 'updateContractStatus']);
    Route::get('/financial/reports', [ApiAdminFinancialController::class, 'getComprehensiveReport']);
    Route::get('/reports/customers', [ApiAdminFinancialController::class, 'getCustomersReport']);
    Route::get('/reports/owners', [ApiAdminFinancialController::class, 'getOwnersReport']);
    Route::get('/reports/facilities', [ApiAdminFinancialController::class, 'getFacilitiesReport']);
    Route::get('/accounting-entries', [ApiAdminFinancialController::class, 'getAccountingEntries']);
    Route::post('/accounting-entries', [ApiAdminFinancialController::class, 'createAccountingEntry']);

    // Legacy Admin Financial Reports Routes (for backward compatibility)
    Route::get('financial/facility-summary', [ApiFinancialReportController::class, 'facilitySummary']);
    Route::get('financial/revenue', [ApiFinancialReportController::class, 'revenue']);
    Route::get('financial/receivables', [ApiFinancialReportController::class, 'receivables']);
    Route::get('financial/commissions', [ApiFinancialReportController::class, 'commissions']);
    Route::get('financial/payments', [ApiFinancialReportController::class, 'payments']);
    Route::get('financial/invoices', [ApiFinancialReportController::class, 'invoices']);
    Route::get('financial/contracts', [ApiFinancialReportController::class, 'contracts']);
    Route::get('financial/customer', [ApiFinancialReportController::class, 'customer']);
    Route::get('financial/owner', [ApiFinancialReportController::class, 'owner']);
    Route::get('financial/monthly', [ApiFinancialReportController::class, 'monthly']);
    Route::get('financial/yearly', [ApiFinancialReportController::class, 'yearly']);

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

    // Facility Offers Routes (New Enhanced)
    Route::get('/offers', [ApiFacilityOfferController::class, 'index']);
    Route::post('/offers', [ApiFacilityOfferController::class, 'store']);
    Route::get('/offers/{id}', [ApiFacilityOfferController::class, 'show']);
    Route::put('/offers/{id}', [ApiFacilityOfferController::class, 'update']);
    Route::delete('/offers/{id}', [ApiFacilityOfferController::class, 'destroy']);
    Route::post('/offers/{id}/toggle-status', [ApiFacilityOfferController::class, 'toggleStatus']);
    Route::post('/offers/{id}/copy', [ApiFacilityOfferController::class, 'copyOffer']);
    Route::get('/offers/statistics', [ApiFacilityOfferController::class, 'getOfferStatistics']);
    Route::post('/offers/bulk-update-prices', [ApiFacilityOfferController::class, 'bulkUpdatePrices']);

    // Facility Contracts Routes (New Enhanced)
    Route::get('/contracts', [ApiFacilityOfferController::class, 'getContracts']);
    Route::post('/contracts/{id}/approve', [ApiFacilityOfferController::class, 'approveContract']);
    Route::post('/contracts/{id}/reject', [ApiFacilityOfferController::class, 'rejectContract']);
    Route::post('/payments/{id}/confirm', [ApiFacilityOfferController::class, 'confirmPayment']);
    Route::post('/payments/{id}/reject', [ApiFacilityOfferController::class, 'rejectPayment']);
    Route::get('/financial-report', [ApiFacilityOfferController::class, 'getFinancialReport']);

    // Legacy Facility Routes (for backward compatibility)
    Route::apiResource('legacy-offers', ApiOfferController::class);
    Route::post('legacy-offers/{offer}/toggle-status', [ApiOfferController::class, 'toggleStatus']);
    Route::post('legacy-offers/{offer}/copy', [ApiOfferController::class, 'copy']);
    Route::get('legacy-offers/statistics', [ApiOfferController::class, 'statistics']);
    Route::get('legacy-offers/export', [ApiOfferController::class, 'export']);

    Route::apiResource('legacy-contracts', ApiContractController::class);
    Route::post('legacy-contracts/{contract}/update-status', [ApiContractController::class, 'updateStatus']);
    Route::post('legacy-contracts/{contract}/cancel', [ApiContractController::class, 'cancel']);
    Route::post('legacy-contracts/{contract}/record-payment', [ApiContractController::class, 'recordPayment']);
    Route::get('legacy-contracts/{contract}/invoices', [ApiContractController::class, 'getInvoices']);
    Route::get('legacy-contracts/{contract}/payments', [ApiContractController::class, 'getPayments']);
    Route::get('legacy-contracts/{contract}/financial-report', [ApiContractController::class, 'getFinancialReport']);

    // Facility Financial Reports Routes
    Route::get('financial/facility-summary', [ApiFinancialReportController::class, 'facilitySummary']);
    Route::get('financial/revenue', [ApiFinancialReportController::class, 'revenue']);
    Route::get('financial/receivables', [ApiFinancialReportController::class, 'receivables']);
    Route::get('financial/commissions', [ApiFinancialReportController::class, 'commissions']);
    Route::get('financial/payments', [ApiFinancialReportController::class, 'payments']);
    Route::get('financial/invoices', [ApiFinancialReportController::class, 'invoices']);
    Route::get('financial/contracts', [ApiFinancialReportController::class, 'contracts']);
    Route::get('financial/customer', [ApiFinancialReportController::class, 'customer']);
    Route::get('financial/owner', [ApiFinancialReportController::class, 'owner']);
    Route::get('financial/monthly', [ApiFinancialReportController::class, 'monthly']);
    Route::get('financial/yearly', [ApiFinancialReportController::class, 'yearly']);
});
