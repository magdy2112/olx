<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymobController;

Route::post('token', [PaymobController::class, 'testToken']);
Route::post( 'create-subscription-plan', [PaymobController::class, 'createSubscriptionPlan_card']);
Route::post('create-subscription', [PaymobController::class, 'createUserSubscription']);

Route::post('start-payment', [PaymobController::class, 'startPayment']);
Route::get('/paymob/start', [PaymobController::class, 'startSubscriptionPayment']);