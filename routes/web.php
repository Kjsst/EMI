<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\TollFreeNumberController;
use App\Http\Controllers\CoinOfferController;
use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});



Route::middleware(['auth'])->group(function () {

    Route::get('/banner', [BannerController::class, 'index'])->name('banner');
    Route::post('/banner', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banner/delete/{id}', [BannerController::class, 'delete'])->name('banner.delete');
    Route::get('/banner/edit/{id}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banner/update/{id}', [BannerController::class, 'update'])->name('banner.update');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin|distributor')->group(function (){
        Route::get('/merchant', [MerchantController::class, 'index'])->name('merchant');
        Route::get('/merchant/add', [MerchantController::class, 'create'])->name('merchant.add');
        Route::get('/merchant/view/{id}', [MerchantController::class, 'view'])->name('merchant.view');
        Route::post('/merchant/store', [MerchantController::class, 'store'])->name('merchant.store');
        Route::get('/merchant/edit/{id}', [MerchantController::class, 'edit'])->name('merchant.edit');
        Route::get('/merchant/users/{id}', [MerchantController::class, 'users'])->name('merchant.users');
        Route::post('/merchant/update/{id}', [MerchantController::class, 'update'])->name('merchant.update');
        Route::get('/merchant/search', [MerchantController::class, 'searchMerchant'])->name('merchant.search');
    });

    Route::middleware('role:admin|master distributor')->group(function () {
        Route::get('distributor', [UserController::class, 'distributors'])->name('distributors');
        Route::get('distributor/ajaxList', [UserController::class, 'distributorsAjaxList'])->name('distributor.ajaxList');
        Route::get('distributor/view/{id}', [UserController::class, 'distributorDetail'])->name('distributor.view');
        Route::get('distributor/edit/{id}', [UserController::class, 'distributorEdit'])->name('distributor.edit');
        Route::get('/distributor/users/{id}', [UserController::class, 'DistributorUsers'])->name('distributor.users');
        Route::post('distributor/update', [UserController::class, 'distributorUpdate'])->name('distributor.update');
    });

    Route::get('customer-report',[HomeController::class,'customerReport'])->name('customer.report');
    Route::get('/customer-report/export', [HomeController::class, 'export'])->name('customer.report.export');

    Route::get('device',[HomeController::class,'devicesList'])->name('device.list');
    Route::get('devicesAjaxList',[HomeController::class,'devicesAjaxList'])->name('device.ajaxList');

    Route::middleware('role:admin')->group(function () {

        Route::get('user',[UserController::class,'create'])->name('user.create');
        Route::post('user',[UserController::class,'store'])->name('user.store');
        Route::get('master-distributor',[UserController::class,'masterDistributor'])->name('masterDistributors');
        Route::get('master-distributor/view/{id}', [UserController::class, 'masterDistributorDetail'])->name('masterDistributor.view');
        Route::get('master-distributor/users/{id}', [UserController::class, 'MasterDistributorUsers'])->name('masterDistributor.users');
        Route::get('employee',[UserController::class,'employee'])->name('employees');

        Route::get('super-admin',[UserController::class,'super_admin'])->name('super-admin');
        Route::get('super-admin/{id}',[UserController::class,'super_admin_edit'])->name('super-admin.edit');
        Route::post('super-admin/{id}',[UserController::class,'super_admin_update'])->name('super-admin.update');
        Route::get('super-admin-destroy/{id}',[UserController::class,'super_admin_destroy'])->name('super-admin.destroy');

        //pages
        Route::get('/page/contact-us', [HomeController::class, 'contactPage'])->name('contact');
        Route::post('/contact-us', [HomeController::class, 'contact'])->name('contact.update');
        Route::get('/page/terms', [HomeController::class, 'termsPage'])->name('terms');
        Route::get('/page/privacy-policy', [HomeController::class, 'privacyPage'])->name('privacy');
        Route::get('/page/about-us', [HomeController::class, 'aboutUsPage'])->name('aboutUs');
        Route::post('/terms', [HomeController::class, 'privacy'])->name('page.update');
        Route::get('/setting', [HomeController::class, 'setting'])->name('setting');
        Route::post('/setting', [HomeController::class, 'storeSetting'])->name('setting.update');
        Route::get('/setting-qrl', [HomeController::class, 'qrlSetting'])->name('qrlsetting');
        Route::post('/setting-qrl', [HomeController::class, 'qrlStoreSetting'])->name('qrlsetting.update');

        Route::get('/page/tollfreenumber', [TollFreeNumberController::class, 'index'])->name('tollfreenumber');
        Route::post('/page/tollfreenumber', [TollFreeNumberController::class, 'store'])->name('tollfreenumber.store');
        Route::get('/page/tollfreenumber/delete/{id}', [TollFreeNumberController::class, 'delete'])->name('tollfreenumber.delete');

        //customer and device
        Route::get('/customer', [CustomerController::class, 'index'])->name('customer');
        Route::get('/customer-data', [CustomerController::class, 'getCustomerData'])->name('customer.ajax-data');
        Route::get('/customer/add', [CustomerController::class, 'create'])->name('customer.add');
        Route::get('/customer/view/{id}', [CustomerController::class, 'view'])->name('customer.view');
        Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::get('/customer/device/{id}', [CustomerController::class, 'device'])->name('customer.device');
        Route::post('change-status', [CustomerController::class, 'deviceStatus'])->name('device.status');
        Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
    });
});

Route::middleware(['auth','roleNotequal:employee'])->group(function (){
//coins
    Route::get('coins',[HomeController::class,'coins'])->name('coins');
    Route::post('credit',[HomeController::class,'credit'])->name('credit');
    Route::post('debit',[HomeController::class,'debit'])->name('debit');

    Route::get('/coin-offer', [CoinOfferController::class, 'index'])->name('coinoffer');
    Route::post('/coin-offer', [CoinOfferController::class, 'store'])->name('coinoffer.store');
    Route::get('/coin-offer/delete/{id}', [CoinOfferController::class, 'delete'])->name('coinoffer.delete');
});

require __DIR__.'/auth.php';

