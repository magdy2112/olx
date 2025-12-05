<?php

use App\Http\Controllers\CategoryController;

use Illuminate\Support\Facades\Route;



Route::prefix('category')->controller(CategoryController::class)->group(function () {

    Route::get('/', 'allcategory');

    // مع Auth + Admin
    Route::middleware(['auth:sanctum', 'can:admin'])->group(function () {
        Route::post('addcategory', 'addcategory');
        Route::patch('updatecategory/{id}', 'updatecategory');
        Route::delete('deletecategory/{id}', 'destroy');
    });

});

      
