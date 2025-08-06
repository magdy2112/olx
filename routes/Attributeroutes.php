<?php

use App\Http\Controllers\AttributesController;
use Illuminate\Support\Facades\Route;

Route::controller( AttributesController::class)->group(function () {
    Route::get('/attributes/{attributeId}/subattributes', 'attributewithsubattributes');
    Route::post('/addattribute', 'addattribute');
    Route::patch('/updateattribute/{id}', action: 'updateattribute');
    Route::delete('/deleteattribute/{id}', action: 'deleteattribute'); 
    Route::post('/addsubattributes', 'addsubattribute');
    Route::patch('/updatesubattributes/{id}', 'updatesubattribute');
    Route::delete('/deletesubattributes/{id}', 'deletesubattribute');
});

