<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Auth\AuthController;


Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('/register', 'Register')->middleware(['guest']);

    Route::get('/verify-email', 'verifyEmail')
        ->name('api.verification.verify')
    ->middleware(['signed']);  
    
    Route::post('/email/resend-verification', 'resendVerificationEmail')
        ->middleware('throttle:auth') 
        ->name('verification.resend');

    Route::post('/login', 'Login')->middleware(['throttle:auth',]); 

    Route::middleware(['auth:sanctum','throttle:auth'])->post('/logout', 'Logout');

    Route::post('/forgetpassword', 'forgetpassword')->middleware(['throttle:auth',]);



    Route::post('/resetPassword', 'resetPassword')->middleware('throttle:auth');
  

});


