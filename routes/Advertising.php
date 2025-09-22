<?php

use App\Http\Controllers\AdvertisingController;
use Illuminate\Support\Facades\Route;


Route::controller(AdvertisingController::class)
    ->prefix('advertising')->group(function () {
        Route::post('store', 'store');
        Route::patch('update/{id}', 'updateadvertising');
        Route::delete('delete/{id}', 'deleteadvertising');
    })->middleware('auth:sanctum');