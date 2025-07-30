<?php

use App\Http\Controllers\SubCategoryController;

use Illuminate\Support\Facades\Route;

Route::controller( SubCategoryController::class)->prefix('subcategory')

->group(function(){
      Route::get('/',  'allsubcategory');
      Route::post('addsubcategory', 'addsubcategory');
      Route::patch('updatesubcategory/{id}', 'updatesubcategory');
      Route::get('subcategorybycategory/{categoryid}', 'allsubcategorybycategory');
      Route::delete('deletesubcategory/{id}', 'destroy');
});
