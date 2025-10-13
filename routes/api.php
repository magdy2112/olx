<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

require __DIR__ . '/categoryroutes.php';
require __DIR__ . '/Auth.php';
require __DIR__ . '/Subcategoryroutes.php';
require __DIR__ . '/Modalroutes.php';
require __DIR__ . '/Submodalroutes.php';
require __DIR__ . '/Attributeroutes.php';
require __DIR__ . '/Image.php';
require __DIR__ . '/Advertising.php';
require __DIR__ . '/Fav.php';
require __DIR__ . '/fcm.php';









