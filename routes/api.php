<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\DeductionController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\OtherController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\TaxReturnController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh/token', [AuthController::class, 'refreshToken']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode']);
Route::post('/verify-reset-code', [ForgotPasswordController::class, 'verifyResetCode']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);


    Route::group(['prefix' => 'tax-returns'], function () {
        Route::get('/', [TaxReturnController::class, 'index']);
        Route::get('/show', [TaxReturnController::class, 'show']);
    });

    Route::group(['prefix' => 'forms'], function () {
        Route::post('/basic-info', [FormController::class, 'basicInfo']);
//        Route::post('/income', [FormController::class, 'income']);
//        Route::post('/deduction', [FormController::class, 'deduction']);
//        Route::post('/other', [FormController::class, 'other']);

        Route::group(['prefix' => 'income'], function () {
            Route::post('/', [IncomeController::class, 'income']);
            Route::post('/capital-gains', [IncomeController::class, 'saveCapitalGains']);
            Route::post('/managed-funds', [IncomeController::class, 'saveManagedFunds']);
            Route::post('/termination-payments', [IncomeController::class, 'saveTerminationPayments']);
            Route::post('/rent', [IncomeController::class, 'saveRent']);
        });


        Route::group(['prefix' => 'deduction'], function () {
            Route::post('/', [DeductionController::class, 'deduction']);
            Route::post('/travel-expenses', [DeductionController::class, 'saveTravelExpenses']);
            Route::post('/computer', [DeductionController::class, 'saveComputer']);
            Route::post('/home-office', [DeductionController::class, 'saveHomeOffice']);
            Route::post('/books', [DeductionController::class, 'saveBooks']);
            Route::post('/uniforms', [DeductionController::class, 'saveUniforms']);
            Route::post('/education', [DeductionController::class, 'saveEducation']);
            Route::post('/union-fees', [DeductionController::class, 'saveUnionFees']);
            Route::post('/sun-protection', [DeductionController::class, 'saveSunProtection']);
            Route::post('/low-value-pool', [DeductionController::class, 'saveLowValuePool']);
        });

        Route::group(['prefix' => 'other'], function () {
            Route::post('/', [OtherController::class, 'other']);
            Route::post('/private-health-insurance', [OtherController::class, 'savePrivateHealthInsurance']);
            Route::post('/medicare-reduction-exemption', [OtherController::class, 'saveMedicareReductionExemption']);
            Route::post('/medical-expenses-offset', [OtherController::class, 'saveMedicalExpensesOffset']);
            Route::post('/documents-to-attach', [OtherController::class, 'saveDocumentsToAttach']);
        });

    });

    Route::group(['prefix' => 'payments'], function () {
        Route::get('/{id}', [PaymentController::class, 'payment']);
        Route::post('/make', [PaymentController::class, 'makePayment']);
    });

});
