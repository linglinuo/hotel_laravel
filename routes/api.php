<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['api', 'auth:sanctum', 'is_verify_email']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/tokens', 'tokens')->name('tokens');
    });

    Route::controller(UserProfileController::class)->group(function () {
        Route::get('/user', 'get')->name('userProfile.get');
        Route::put('/user', 'update')->name('userProfile.update');
        Route::put('/user/img', 'updateimg')->name('userProfile.updateimg');
    });
});

Route::post('/forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forgetpassword.post');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('resetpassword.post');


// Route::get('/sendmail', [MailController::class, 'index']);
