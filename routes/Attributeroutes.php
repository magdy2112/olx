<?php

use App\Http\Controllers\AttributesController;
use Illuminate\Support\Facades\Route;

Route::controller( AttributesController::class)->group(function () {
    Route::post('/addattribute', 'addattribute');
    Route::patch('/updateattribute/{id}', action: 'updateattribute');
    Route::delete('/deleteattribute/{id}', action: 'deleteattribute'); 
    Route::post('/attributes', 'getAttributes');

})->middleware('auth:sanctum');

