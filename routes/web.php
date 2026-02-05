<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Forms\OtherController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminController;
use \App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SiteInfoController;
use App\Http\Controllers\TaxReturnController;
use App\Http\Controllers\Forms\BasicInfoFormController;
use App\Http\Controllers\Forms\IncomeController;
use App\Http\Controllers\Forms\DeductionController;

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





Auth::routes();

Route::get('/fresh', function () {
    Artisan::call('migrate:fresh');
    dd('Done fresh database');
});


Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');


Route::get('/next', [HomeController::class, function () {
    print_r(curl_version());
    die;
}]);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/start-plan', [HomeController::class, 'startPlan'])->name('plans.start');
Route::get('/contact-us', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact-us', [ContactController::class, 'send'])->name('contact.send');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-service', [HomeController::class, 'termsService'])->name('terms-service');
Route::get('/choosing-business-type', [HomeController::class, 'choosingBusinessType'])->name('choosing-business-type');
Route::get('/success', [HomeController::class, 'success'])->name('success');

Route::resource('tax-returns', TaxReturnController::class);

// FORMS

Route::prefix('basic-info')->name('basic-info.')->group(function () {
    Route::post('/{taxId}', [BasicInfoFormController::class, 'store'])->name('store');
    Route::put('/{taxId}/{id}', [BasicInfoFormController::class, 'update'])->name('update');
});

Route::prefix('income')->name('income.')->group(function () {
    Route::post('/{taxId}', [IncomeController::class, 'store'])->name('store');
    Route::post('/{taxId}/{id}', [IncomeController::class, 'update'])->name('update');
});


Route::prefix('other')->name('other.')->group(function () {
    Route::post('/{taxId}', [OtherController::class, 'store'])->name('store');
    Route::put('/{taxId}/{id}', [OtherController::class, 'update'])->name('update');
});

Route::prefix('deduction')->name('deduction.')->group(function () {
    Route::post('/{taxId}', [DeductionController::class, 'store'])->name('store');
    Route::put('/{taxId}/{id}', [DeductionController::class, 'update'])->name('update');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions.index');
    Route::resource('plans', PlanController::class);
    Route::resource('site-info', SiteInfoController::class);
});


Route::middleware(['auth'])->prefix('payments')->group(function () {
    Route::get('/{id}', [StripePaymentController::class, 'payment'])->name('payment');
    Route::post('/make', [StripePaymentController::class, 'makePayment'])->name('payment.make');
});
