<?php

namespace App\Http\Controllers;
use App\Http\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymobController extends Controller
{
    public function testToken(PaymobService $paymob)
    {
        $token = $paymob->authenticate();

        if ($token) {
            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to get token',
        ], 400);
    }

    public function createSubscriptionPlan_card(PaymobService $paymob)
    {
        $name = "Monthly Rent Plan";
        $amountCents = 5000; // 50.00 EGP
        $frequency = 30;
        $integrationId = env('PAYMOB_INTEGRATION_CARD');
        $webhookUrl = "https://webhook.site/6191ef7a-cdd2-4119-a77c-df06595aa276";

        $response = $paymob->createSubscriptionPlan($name, $amountCents, $frequency, $integrationId, $webhookUrl);
 
        return response()->json($response);
    }
//     public function createUserSubscription(PaymobService $paymob, Request $request)
// {
//     // $user = Auth::user(); // افترضنا إنك بتستخدم نظام التوثيق الافتراضي
//     // $planId = $request->input('plan_id'); // رقم الخطة اللي رجع من الاستجابة السابقة
//     // $customerName = $user->name;
//     // $customerEmail = $user->email;
//     // $customerPhone = $user->phone;
//     $planId = 4963; // رقم الخطة اللي رجع من الاستجابة السابقة
//     $customerName = "Mohamed Magdy";
//     $customerEmail = "mohamedmagdy157777@gmail.com";
//     $customerPhone = "201554447882";

//     $response = $paymob->createSubscription($planId, $customerName, $customerEmail, $customerPhone);

// return response()->json([
//     'status' => $response->status(),
//     'body' => $response->body(),
// ]);
// }

public function createUserSubscription(PaymobService $paymob, Request $request)
{
    $planId = 4963;
    $customerName = "Mohamed Magdy";
    $customerEmail = "mohamedmagdy157777@gmail.com";
    $customerPhone = "201554447882";

    $response = $paymob->createSubscription($planId, $customerName, $customerEmail, $customerPhone);

    return response()->json($response); // ⬅️ أرجع الـ response مباشرة
}
}
