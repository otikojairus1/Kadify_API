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