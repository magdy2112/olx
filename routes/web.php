<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MessageController;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

