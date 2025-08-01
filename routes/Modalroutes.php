<?php

use App\Http\Controllers\ModalController;
use Illuminate\Support\Facades\Route;

Route::controller(ModalController::class)
->prefix('modal')->group(function(){
      Route::get('/', 'allmodal');
      Route::get('modalbysubcategory/{subcategoryid}', 'modalbysubcategory');
      Route::post('addmodal', 'addmodal');
      Route::patch('updatemodal/{id}', 'updatemodal');
      Route::delete('deletemodal/{id}', 'destroy');
      Route::get('isfinal', 'isfinal');
});

