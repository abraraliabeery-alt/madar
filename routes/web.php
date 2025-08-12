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

// Laravel UI Auth Routes
Auth::routes();

// Public profile route - accessible by anyone
Route::get('/profile/{id}', [App\Http\Controllers\ProfileController::class, 'publicProfile'])->name('profile.public');

// Profile routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/change-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.change-password.update');
});

// Default Laravel home route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Include route files
require __DIR__.'/public.php';
require __DIR__.'/admin.php';
require __DIR__.'/facility.php';
require __DIR__.'/client.php';

// Default Laravel welcome route (can be removed)
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Fallback route for 404 errors
Route::fallback(function () {
    return view('errors.404');
});
