<?php


use App\Http\Controllers\SubmodalController;

use Illuminate\Support\Facades\Route;

Route::controller(SubmodalController::class)->prefix('submodal')

      ->group(function () {
            Route::get('/',  'allsubmodal');
            Route::get('submodalbymodal/{modalid}', 'submodalbymodal');

            Route::post('addsubmodal', 'addsubmodal');
            Route::patch('updatesubmodal/{id}', 'updatesubmodal');

            Route::delete('deletesubmodal/{id}', 'destroy');
      })->middleware('auth:sanctum')      ;
