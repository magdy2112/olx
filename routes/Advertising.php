<?php

use App\Http\Controllers\AdvertisingController;
use Illuminate\Support\Facades\Route;


Route::post('/storeadvertising', [AdvertisingController::class, 'store']);
Route::get('/advertising/{id}', [AdvertisingController::class, 'show']);
Route::put('/advertising/{id}', [AdvertisingController::class, 'update']);
Route::delete('/advertising/{id}', [AdvertisingController::class, 'destroy']);
