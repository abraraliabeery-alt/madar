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
