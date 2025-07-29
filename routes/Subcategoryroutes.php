<?php

use App\Http\Controllers\SubCategoryController;

use Illuminate\Support\Facades\Route;

Route::controller( SubCategoryController::class)->prefix('subcategory')

->group(function(){
      Route::get('/',  'allsubcategory');
      Route::post('addsubcategory', 'addsubcategory');
      Route::patch('updatesubcategory/{id}', 'updatesubcategory');
      Route::delete('deletesubcategory/{id}', 'destroy');
});
