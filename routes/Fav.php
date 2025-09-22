<?php 

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FavController;


Route::controller(FavController::class)->prefix('fav')

      ->group(function () {
            Route::post('/',  'toggle');
            Route::get('list/{id}', 'listFavs');
            
      })->middleware('auth:sanctum')      ;