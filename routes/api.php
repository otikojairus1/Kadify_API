<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// OTP EMAIL VERIFICATION ENDPOINTS
Route::post('/send/email/otp', [\App\Http\Controllers\UserController::class, "send_email_otp"]);
Route::post('/verify/email/otp', [\App\Http\Controllers\UserController::class, "verify_email_otp"]);
//USER AUTHENTICATION ENDPOINTS
Route::post('/register', [\App\Http\Controllers\UserController::class, "register"]);
Route::post('/login', [\App\Http\Controllers\UserController::class, "login"]);
Route::post('/update/verified/phone', [\App\Http\Controllers\UserController::class, "updatephone"]);
//EQUITY BANK OF KENYA OPEN BANKING V3 (THE JENGAV3 OPEN BANKING APIs)
Route::post('/equity/bank/openbankingv2/accessToken', [\App\Http\Controllers\EquityBankController::class, "generateAccessToken"]);
Route::get('/equity/bank/openbankingv2/billers', [\App\Http\Controllers\EquityBankController::class, "allEquityBankBillers"]);
Route::get('/equity/bank/openbankingv2/merchants', [\App\Http\Controllers\EquityBankController::class, "getAllMerchants"]);
Route::get('/equity/bank/openbankingv2/kyc', [\App\Http\Controllers\EquityBankController::class, "KYC"]);
Route::get('/equity/bank/openbankingv2/CRB', [\App\Http\Controllers\EquityBankController::class, "CRB"]);
Route::get('/equity/bank/openbankingv2/billpayment', [\App\Http\Controllers\EquityBankController::class, "billPayment"]);
Route::get('/equity/bank/openbankingv2/buy/airtime', [\App\Http\Controllers\EquityBankController::class, "byAirtime"]);

