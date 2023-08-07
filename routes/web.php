<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
// Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forgetpassword.get');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('resetpassword.get');

// Route::get('/index', function () {
//     return File::get(public_path() . '/index.html');
// })->name('index');