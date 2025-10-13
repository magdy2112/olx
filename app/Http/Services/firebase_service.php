<?php 

namespace App\Http\Services;

use App\Traits\Httpresponse;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Models\UserDevice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;

class Firebase_service
{
     use Httpresponse;
    protected $messaging;

    public function __construct()
    {
        $serviceAccountPath = storage_path(config('fcm.firebase_credentials'));
        $factory = (new Factory)
            ->withServiceAccount($serviceAccountPath);
           $this->messaging= $factory->createMessaging();

    }
    

   
      public function sendnotification($token, $title, $body, $data = [])
    {
        
     try {
         $message = CloudMessage::fromArray([
          'token' => $token,
          'notification' => [
              'title' => $title,
              'body'  => $body,
          ],
          'data' => $data,
         ]);

         $this->messaging->send($message);
         return $this->response(true, 200,'Notification sent successfully');
     } catch (Exception $e) {

         return $this->response(false, 500,$e->getMessage());
     }
    }

    public function saveDeviceToken(Request $request)
{
    $request->validate([
        'device_token' => 'required|string',
        'device_type' => 'nullable|string',
    ]);

    $user = Auth::user();

    UserDevice::updateOrCreate(
        [
            'user_id' => $user->id,
            'device_token' => $request->device_token,
        ],
        [
            'device_type' => $request->device_type,
        ]
    );

    return response()->json(['message' => 'Device token saved successfully']);
}

    }
   

    // Add more methods as needed to interact with Firebase services
