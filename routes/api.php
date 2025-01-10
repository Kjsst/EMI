<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CustomerController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\TollFreeNumberController;

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

Route::post('login',[AuthController::class,'MerchantLogin']);
Route::post('customer-login',[AuthController::class,'CustomerLogin']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::post('logout',[AuthController::class,'Logout']);
    Route::get('contact',[UserController::class,'contact']);
    Route::get('terms', [UserController::class, 'termsPage']);
    Route::get('/page/{name}', [UserController::class, 'page']);
    Route::get('setting',[UserController::class,'setting']);
    Route::post('qr-info',[UserController::class,'qrInfo']);
    Route::post('personal-info',[UserController::class,'personalInfo']);
    Route::post('bank-detail',[UserController::class,'bankDetail']);
    Route::post('change-password',[UserController::class,'changePassword']);

    // customers
    Route::post('customer-create',[UserController::class,'createCustomer']);
    Route::get('customers',[UserController::class,'customers']);
    Route::post('/customer/update', [UserController::class,'update_customer']);
    Route::get('customer/{id}',[UserController::class,'customer']);
    Route::post('verify-field',[AuthController::class,'verifyField']);
    Route::post('verify-otp',[AuthController::class,'verifyOTP']);
    Route::post('update-emi',[UserController::class,'updateEMI']);

    Route::post('check-imei',[UserController::class,'checkDevice']);
    Route::get('devices',[UserController::class,'devices']);
    Route::post('verify-transfer-email',[UserController::class,'transferEmailVerify']);
    Route::post('transfer-coin',[UserController::class,'transferCoin']);
    Route::get('pending-emi',[UserController::class,'pendingEmi']);
    Route::get('transition-emi',[UserController::class,'transitionEmi']);
    Route::get('emi-detail/{id}',[UserController::class,'emiDetail']);
    Route::get('emi-payment-detail/{id}',[UserController::class,'emiPaymentDetail']);

    //customer
    Route::get('device',[CustomerController::class,'device']);
    Route::get('dashboard',[CustomerController::class,'dashboard']);
    Route::get('dashboard/{name}',[CustomerController::class,'dashboardWithQuery']);
    Route::get('filter-device/{id}',[CustomerController::class,'filterDevice']);
    Route::get('wallet',[CustomerController::class,'wallet']);

    Route::post('/set/mpin', [AuthController::class,'setmpin']);
    Route::get('/get/mpin', [AuthController::class,'getmpin']);
    Route::post('/forgot/mpin', [AuthController::class,'forgotmpin']);

    // Banner
    Route::post('/banner', [BannerController::class,'createBanner']);
    Route::get('/banner', [BannerController::class,'banners']);

    // Toll Free Number
    Route::post('/toll-free-number', [TollFreeNumberController::class,'createTollFreeNumber']);
    Route::get('/toll-free-number', [TollFreeNumberController::class,'tollFreeNumbers']);

    // coin transition
    Route::get('/coin-transition', [UserController::class,'cointransition']);

    // cron-job api
    Route::get('/auto-lock', [UserController::class,'autoLock']);

});
Route::post('/callback', [CustomerController::class, 'handleCallback']);





