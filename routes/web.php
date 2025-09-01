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
