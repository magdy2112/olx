<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/storeimages', [ImageController::class, 'store']);
    Route::delete('/deleteimages/{id}', [ImageController::class, 'destroy']);
});
