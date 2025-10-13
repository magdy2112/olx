<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class FCMController extends Controller
{

 protected $firebaseService;

    public function __construct(\App\Http\Services\Firebase_service $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

   public function sendPushNotification(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string',
        'body' => 'required|string',
        'data' => 'nullable|array',
    ]);

    $user = User::findOrFail($request->user_id);
    $tokens = $user->devices()->pluck('device_token')->toArray();

    if (empty($tokens)) {
        return response()->json(['message' => 'No device tokens found for user'], 404);
    }

    try {
        foreach ($tokens as $token) {
            $this->firebaseService->sendnotification($token, $request->title, $request->body, $request->data ?? []);
        }

        return response()->json(['message' => 'Notification sent successfully']);
    } catch (\Exception $e) {
        Log::error('Error sending notification: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to send notification'], 500);
    }

}

public function saveDeviceToken(Request $request)
{
    $request->validate([
        'device_token' => 'required|string',
    ]);

    $user = $request->user();

    // تحقق مما إذا كان الجهاز موجودًا بالفعل
    $existingDevice = $user->devices()->where('device_token', $request->device_token)->first();
    if ($existingDevice) {
        return response()->json(['message' => 'Device token already exists'], 200);
    }

    // حفظ رمز الجهاز الجديد
    $user->devices()->create([
        'device_token' => $request->device_token,
    ]);

    return response()->json(['message' => 'Device token saved successfully'], 201);

}    

}