<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Bank\BankLoanController;
use App\Http\Controllers\AI\LandStudyController;
use App\Http\Controllers\Public\SmartBrokerController;
use App\Http\Controllers\Auth\PhoneOtpAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/migration', function () {
    if (!app()->isLocal()) {
        abort(404);
    }
    try {
        Artisan::call('migrate', ['--force' => true]);
        return nl2br(Artisan::output()); // يعرض الإخراج بالتنسيق المناسب
    } catch (\Exception $e) {
        return response()->make(
            "<h3>Error:</h3><pre>" . $e->getMessage() . "</pre>",
            500
        );
    }
});

// Facility rentals management
Route::middleware(['auth'])->group(function () {
    Route::get('/facility/rentals', [App\Http\Controllers\Facility\FacilityRentalController::class, 'index'])
        ->name('facility.rentals.index');
});


Route::get('/migration-seeder', function () {
    if (!app()->isLocal()) {
        abort(404);
    }
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        $output = Artisan::output();

        // تشغيل الـ seeder
        Artisan::call('db:seed', ['--force' => true]);
        $output .= Artisan::output();
        return nl2br(Artisan::output()); // يعرض الإخراج بالتنسيق المناسب
    } catch (\Exception $e) {
        return response()->make(
            "<h3>Error:</h3><pre>" . $e->getMessage() . "</pre>",
            500
        );
    }
});

// Language switching routes
Route::get('/language/{language}', [App\Http\Controllers\LanguageController::class, 'switch'])->name('language.switch');
Route::get('/language-info', [App\Http\Controllers\LanguageController::class, 'info'])->name('language.info');

// Laravel UI Auth Routes
Auth::routes();

// Phone OTP login (like Aqar)
Route::middleware('guest')->group(function () {
    Route::get('/login/phone', [PhoneOtpAuthController::class, 'showPhoneForm'])
        ->name('phone.otp.login.form');
    Route::post('/login/phone', [PhoneOtpAuthController::class, 'sendOtp'])
        ->middleware('throttle:otp-request')
        ->name('phone.otp.login.send');
    Route::get('/login/phone/verify', [PhoneOtpAuthController::class, 'showVerifyForm'])
        ->name('phone.otp.verify.form');
    Route::post('/login/phone/verify', [PhoneOtpAuthController::class, 'verify'])
        ->middleware('throttle:otp-verify')
        ->name('phone.otp.verify');
});

// Facility onboarding (public-style, auth-only)
Route::middleware(['auth'])->group(function () {
    Route::get('/register/facility', [App\Http\Controllers\Facility\FacilityController::class, 'onboardingCreate'])
        ->name('facility.onboarding.create');
    Route::post('/register/facility', [App\Http\Controllers\Facility\FacilityController::class, 'onboardingStore'])
        ->name('facility.onboarding.store');
});

// Temporary public Smart Broker page (no-auth) for testing
Route::get('/smart-broker', [SmartBrokerController::class, 'index'])->name('public.smart-broker.index');
Route::get('/smart-broker/data', [SmartBrokerController::class, 'data'])->middleware('throttle:30,1')->name('public.smart-broker.data');
Route::post('/smart-broker/fetch', [SmartBrokerController::class, 'fetch'])->middleware('throttle:10,1')->name('public.smart-broker.fetch');
Route::post('/smart-broker/match', [SmartBrokerController::class, 'match'])->middleware('throttle:10,1')->name('public.smart-broker.match');

// Home route for Laravel Auth redirects
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Compatibility route for landing contact form (touralbina template)
Route::post('/contact-home', [App\Http\Controllers\Public\ContactController::class, 'sendMessage'])
    ->name('contact.home.store');

// General dashboard route (redirects to role-specific dashboard)
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

// Profile routes for authenticated users (must come before public profile route)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.change-password.update');
});

// User Profile Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/profile/{user}', [App\Http\Controllers\UserProfileController::class, 'show'])->name('user.profile.show');
    Route::get('/user/profile/{user}/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/user/profile/{user}', [App\Http\Controllers\UserProfileController::class, 'update'])->name('user.profile.update');
    Route::put('/user/profile/{user}/change-password', [App\Http\Controllers\UserProfileController::class, 'changePassword'])->name('user.profile.change-password');
    Route::put('/user/profile/{user}/settings', [App\Http\Controllers\UserProfileController::class, 'updateSettings'])->name('user.profile.settings');
    Route::put('/user/profile/{user}/location', [App\Http\Controllers\UserProfileController::class, 'updateLocation'])->name('user.profile.location');
    Route::put('/user/profile/{user}/social-links', [App\Http\Controllers\UserProfileController::class, 'updateSocialLinks'])->name('user.profile.social-links');
    Route::get('/user/profile/{user}/statistics', [App\Http\Controllers\UserProfileController::class, 'statistics'])->name('user.profile.statistics');
    Route::get('/user/profile/{user}/export', [App\Http\Controllers\UserProfileController::class, 'export'])->name('user.profile.export');
    Route::delete('/user/profile/{user}', [App\Http\Controllers\UserProfileController::class, 'destroy'])->name('user.profile.destroy');
});

// User Settings Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/settings', [App\Http\Controllers\UserSettingsController::class, 'index'])->name('user.settings.index');
    Route::post('/user/settings/notifications', [App\Http\Controllers\UserSettingsController::class, 'updateNotifications'])->name('user.settings.notifications');
    Route::post('/user/settings/privacy', [App\Http\Controllers\UserSettingsController::class, 'updatePrivacy'])->name('user.settings.privacy');
    Route::post('/user/settings/preferences', [App\Http\Controllers\UserSettingsController::class, 'updatePreferences'])->name('user.settings.preferences');
    Route::post('/user/settings/security', [App\Http\Controllers\UserSettingsController::class, 'updateSecurity'])->name('user.settings.security');
    Route::post('/user/settings/change-password', [App\Http\Controllers\UserSettingsController::class, 'changePassword'])->name('user.settings.change-password');
    Route::post('/user/settings/profile-picture', [App\Http\Controllers\UserSettingsController::class, 'updateProfilePicture'])->name('user.settings.profile-picture');
    Route::delete('/user/settings/delete-profile-picture', [App\Http\Controllers\UserSettingsController::class, 'deleteProfilePicture'])->name('user.settings.delete-profile-picture');
    Route::get('/user/settings/export-data', [App\Http\Controllers\UserSettingsController::class, 'exportData'])->name('user.settings.export-data');
    Route::post('/user/settings/delete-account', [App\Http\Controllers\UserSettingsController::class, 'deleteAccount'])->name('user.settings.delete-account');
    Route::get('/user/settings/activity-logs', [App\Http\Controllers\UserSettingsController::class, 'getActivityLogs'])->name('user.settings.activity-logs');
    Route::post('/user/settings/clear-activity-logs', [App\Http\Controllers\UserSettingsController::class, 'clearActivityLogs'])->name('user.settings.clear-activity-logs');
});

// User Statistics Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/statistics', [App\Http\Controllers\UserStatisticsController::class, 'index'])->name('user.statistics.index');
    Route::get('/user/statistics/export', [App\Http\Controllers\UserStatisticsController::class, 'export'])->name('user.statistics.export');
    Route::post('/user/statistics/period', [App\Http\Controllers\UserStatisticsController::class, 'getPeriodStats'])->name('user.statistics.period');
});

// Bank employee simple loan panel
Route::middleware(['auth'])->prefix('bank')->name('bank.')->group(function () {
    Route::get('/loans/requests', [BankLoanController::class, 'index'])->name('loans.requests');
    Route::post('/loans/{loanRequest}/offers', [BankLoanController::class, 'storeOffer'])->name('loans.offers.store');
});

// User Export Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/user/export', [App\Http\Controllers\UserExportController::class, 'index'])->name('user.export.index');
    Route::post('/user/export/json', [App\Http\Controllers\UserExportController::class, 'exportJson'])->name('user.export.json');
    Route::post('/user/export/excel', [App\Http\Controllers\UserExportController::class, 'exportExcel'])->name('user.export.excel');
    Route::post('/user/export/csv', [App\Http\Controllers\UserExportController::class, 'exportCsv'])->name('user.export.csv');
    Route::post('/user/export/pdf', [App\Http\Controllers\UserExportController::class, 'exportPdf'])->name('user.export.pdf');
    Route::get('/user/export/stats', [App\Http\Controllers\UserExportController::class, 'getExportStats'])->name('user.export.stats');
});

// Public profile route - accessible by anyone (must come after specific routes)
Route::get('/profile/{id}', [App\Http\Controllers\ProfileController::class, 'publicProfile'])->name('profile.public');

// Default Laravel welcome route (can be removed)
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Public execution marketplace (requests & bids)
Route::get('/execution', [App\Http\Controllers\Public\ExecutionMarketplaceController::class, 'index'])
    ->name('public.execution.marketplace');
Route::get('/execution/requests/{executionRequest}', [App\Http\Controllers\Public\ExecutionMarketplaceController::class, 'show'])
    ->name('public.execution.show');
Route::post('/execution/requests/{executionRequest}/bids', [App\Http\Controllers\Public\ExecutionMarketplaceController::class, 'storeBid'])
    ->middleware('auth')
    ->name('public.execution.bids.store');

// Public investment studies (no auth required)
Route::get('/investment-studies', [LandStudyController::class, 'form'])
    ->name('web.investment-studies.form');
Route::post('/investment-studies', [LandStudyController::class, 'submit'])
    ->name('web.investment-studies.submit');

// Route files are now registered through RouteServiceProvider
// This provides better organization, middleware handling, and performance

// (Removed experimental facility dashboard route block to avoid duplication)

// AI investment chat endpoint (used by investment-studies chat UI)
Route::post('/ai/investment-chat', [App\Http\Controllers\AI\InvestmentChatController::class, 'handle'])
    ->name('ai.investment.chat');

Route::get('/site/{facility}', [App\Http\Controllers\FacilitySite\SiteController::class, 'home'])
    ->name('facility.site.home');

Route::get('/site/{facility}', [App\Http\Controllers\FacilitySite\SiteController::class, 'home'])
    ->name('public.facility.site.home');

// Fallback route for 404 errors with enhanced handling
Route::fallback(function (Illuminate\Http\Request $request) {
    return \App\Services\RouteFallbackService::handleMissingRoute($request);
});
