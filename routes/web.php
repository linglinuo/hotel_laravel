<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\AccessController;
// use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ForgotPasswordController;

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

Route::get('/', function () {
    return File::get(public_path() . '/index.html');
});

Route::get('/account/verify/{token}', [AuthController::class, 'verifyAccount'])->name('user.verify');
Route::get('reset-password/{token}/{email}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('resetpassword.get');


// Route::middleware(['auth', 'user-access:user'])->group(function () {
  
//     Route::get('/home', [AccessController::class, 'index'])->name('home');
// });

// Route::middleware(['auth', 'user-access:admin'])->group(function () {
  
//     Route::get('/admin/home', [AccessController::class, 'adminHome'])->name('admin.home');
// });

// Route::middleware(['auth', 'user-access:manager'])->group(function () {
  
//     Route::get('/manager/home', [AccessController::class, 'managerHome'])->name('manager.home');
// });

// Route::get('/auth/google', [SocialiteController::class, 'googleLogin'])->name('/auth/google');
// Route::get('/auth/google/callback', [SocialiteController::class, 'googleLoginCallback'])->name('/auth/google/callback');