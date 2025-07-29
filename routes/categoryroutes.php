<?php

use App\Http\Controllers\CategoryController;

use Illuminate\Support\Facades\Route;


Route::controller( CategoryController::class)->prefix('category')
->group(function(){
      Route::get('/', 'allcategory');
      Route::post('addcategory', 'addcategory')->middleware('auth:sanctum');
      Route::patch('updatecategory/{id}', 'updatecategory')->middleware('auth:sanctum');
      Route::delete('deletecategory/{id}', 'destroy')->middleware('auth:sanctum');
});
      
