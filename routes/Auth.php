<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;


use App\Http\Controllers\AuthController;


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'Register')->middleware(['guest', 'throttle:auth']);

    Route::get('/verify-email', 'verifyEmail')
        ->name('api.verification.verify')
    ->middleware(['signed', 'throttle:auth']);  
    
    Route::post('/email/resend-verification', 'resendVerificationEmail')
        ->middleware('throttle:auth') 
        ->name('verification.resend');

    Route::post('/login', 'Login')->middleware(['throttle:auth',]); 

    Route::middleware(['auth:sanctum','throttle:auth'])->post('/logout', 'Logout');

    Route::post('/forgetpassword', 'forgetpassword')->middleware(['throttle:auth',]);



    Route::post('/resetPassword', 'resetPassword')->middleware('throttle:auth');
  

});





