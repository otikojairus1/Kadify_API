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
Route::post('/get/userdetails', [\App\Http\Controllers\UserController::class, "getuser"]);

Route::post('/update/verified/phone', [\App\Http\Controllers\UserController::class, "updatephone"]);
//EQUITY BANK OF KENYA OPEN BANKING V3 (THE JENGAV3 OPEN BANKING APIs)
Route::get('/equity/bank/openbankingv3/accessToken', [\App\Http\Controllers\EquityBankController::class, "generateAccessToken"]);
Route::get('/equity/bank/openbankingv3/billers', [\App\Http\Controllers\EquityBankController::class, "allEquityBankBillers"]);
Route::get('/equity/bank/openbankingv3/merchants', [\App\Http\Controllers\EquityBankController::class, "getAllMerchants"]);
Route::get('/equity/bank/openbankingv3/kyc', [\App\Http\Controllers\EquityBankController::class, "KYC"]);
Route::get('/equity/bank/openbankingv3/CRB', [\App\Http\Controllers\EquityBankController::class, "CRB"]);
Route::get('/equity/bank/openbankingv3/billpayment', [\App\Http\Controllers\EquityBankController::class, "billPayment"]);
Route::get('/equity/bank/openbankingv3/buy/airtime', [\App\Http\Controllers\EquityBankController::class, "byAirtime"]);
Route::get('/equity/bank/openbankingv3/sendmoney/ift', [\App\Http\Controllers\EquityBankController::class, "send_money_within_equity"]);
Route::get('/equity/bank/openbankingv3/sendmoney/toMobileWallets', [\App\Http\Controllers\EquityBankController::class, "transferToMobileWallets"]);
Route::get('/equity/bank/openbankingv3/sendmoney/swift', [\App\Http\Controllers\EquityBankController::class, "swift"]);
//MPESA DARAJA V2 APIs
Route::post('/mpesa/v2/stk', [\App\Http\Controllers\MpesaController::class, "stk"]);

#AIRTEL AFRICA STK PUSH
Route::get('/airtelafrica/v2/token', [\App\Http\Controllers\AirtelAfricaController::class, "AccessToken"]);
Route::get('/coopbank/v2/token', [\App\Http\Controllers\COOPBANKController::class, "access_token"]);

#DPO GROUP
Route::get('/dpo', [\App\Http\Controllers\DPOcontroller::class, "access_token"]);
Route::get('/verify/payment', [\App\Http\Controllers\DPOcontroller::class, "verify_token"]);

// card creation endpoints
Route::post('/add/card', [\App\Http\Controllers\CardController::class, "create_card"]);
Route::post('/get/card', [\App\Http\Controllers\CardController::class, "get_card"]);
Route::post('/send/payment/card', [\App\Http\Controllers\CardController::class, "send_card"]);
Route::post('/delete/card', [\App\Http\Controllers\CardController::class, "delete_card"]);
Route::post('/card/transactions', [\App\Http\Controllers\CardController::class, "get_card_transactions_outgoing"]);


