<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::get('/migration-seeder', function () {
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

// Profile routes for authenticated users (must come before public profile route)
Route::middleware(['auth'])->group(function () {
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

// Public profile route - accessible by anyone (must come after specific routes)
Route::get('/profile/{id}', [App\Http\Controllers\ProfileController::class, 'publicProfile'])->name('profile.public');

// Default Laravel home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Default Laravel welcome route (can be removed)
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

require_once 'admin.php';
require_once 'facility.php';
require_once 'client.php';
require_once 'public.php';

// Fallback route for 404 errors
Route::fallback(function () {
    return view('errors.404');
});
