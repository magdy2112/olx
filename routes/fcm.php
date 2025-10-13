<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FCMController;



Route::middleware('auth:sanctum')->post('/save-device-token', [FCMController::class, 'saveDeviceToken']);

// إرسال إشعار
Route::middleware('auth:sanctum')->post('/send-notification', [FCMController::class, 'sendPushNotification']);
