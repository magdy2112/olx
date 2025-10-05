<?php

use App\Http\Controllers\AdvertisingController;
use Illuminate\Support\Facades\Route;


Route::controller(AdvertisingController::class)
    ->prefix('advertising')->group(function () {
        Route::post('store', 'store');
        Route::patch('update/{id}', 'updateadvertising');
        Route::delete('delete/{id}', 'deleteadvertising');
        Route::get('user/advertisings', 'getUserAdvertisings');
        Route::get('all/advertisings', 'getAllAdvertisings');
        Route::get('category/{category_id}/advertisings', 'getcategoryAdvertisings');
        Route::get('subcategory/{subcategory_id}/advertisings', 'getsubcategoryAdvertisings');
        Route::get('details/{id}', 'getadvertisingDetails');
        Route::get('modal/{modalId}/advertisings', 'getmodaladvertisings');
        Route::get('submodal/{submodalId}/advertisings', 'getsubmodaladvertisings');
        Route::post('search', 'searchAdvertisings');



    })->middleware('auth:sanctum');










