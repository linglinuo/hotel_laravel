<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BasicElementsController;
use App\Http\Controllers\DeviceController;
// use App\Http\Controllers\MailController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserProfileController;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
        Route::get('/user/search/{email}', 'search')->name('userProfile.search');
        Route::get('/user/list', 'index')->name('userProfile.index');
        Route::get('/user', 'get')->name('userProfile.get');
        Route::put('/user', 'update')->name('userProfile.update');
        Route::put('/user/img', 'updateimg')->name('userProfile.updateimg');
        Route::delete('/user/{id}', 'destroy')->name('userProfile.delete');
    });

    Route::controller(RoomController::class)->group(function () {
        Route::get('/room/search', 'search')->name('room.search');
        Route::get('/room', 'index')->name('room.index');
        Route::get('/room/info/{id}', 'get')->name('room.get');
        Route::put('/room/info/{id}', 'update')->name('room.update');
        Route::put('/room/info/{id}/img', 'updateimg')->name('room.updateimg');
        Route::put('/room/info/{id}/member', 'updateroommember')->name('room.updateroommember');
        Route::delete('/room/info/{id}/member', 'removeroommember')->name('room.removeroommember');
        Route::delete('/room/delete', 'destroy')->name('room.delete');
    });
});

Route::post('/forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forgetpassword.post');
Route::post('/reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('resetpassword.post');
Route::controller(DeviceController::class)->group(function () {
    Route::get('/room/{roomId}/device/{uuid}/updateDeviceConnect', 'updateDeviceConnect')->name('device.updateDeviceConnect');
    Route::get('/device', 'index')->name('device.index');
    Route::get('/device/info', 'get')->name('device.get');
    Route::get('/device/dataElement', 'getDataElement')->name('device.getDataElement');
    Route::get('/device/deviceCtrlCmd/{id}', 'getDeviceCtrlCmd')->name('device.getDeviceCtrlCmd');
    Route::get('/device/deviceElement', 'getDeviceElement')->name('device.getDeviceElement');
    Route::post('/device/info/updateDeviceDatas', 'updateDeviceDatas')->name('device.updateDeviceDatas');
    Route::post('/device/info/controlDeviceOK', 'controlDeviceOK')->name('device.controlDeviceOK');
    Route::post('/device/info/triggerOrNot', 'triggerOrNot')->name('device.triggerOrNot');
});
Route::controller(BasicElementsController::class)->group(function () {
    Route::get('/basic-element/room/{id}', 'index')->name('basicElement.index');
    Route::get('/basic-element/{id}', 'get')->name('basicElement.get');
    Route::put('/basic-element', 'update')->name('basicElement.update');
});
Route::get('/test', function () {
    return 1;
});