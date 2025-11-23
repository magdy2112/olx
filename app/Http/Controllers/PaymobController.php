<?php

namespace App\Http\Controllers;
use App\Http\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymobController extends Controller
{
//     public function testToken(PaymobService $paymob)
//     {
//         $token = $paymob->authenticate();

//         if ($token) {
//             return response()->json([
//                 'success' => true,
//                 'token' => $token,
//             ]);
//         }

//         return response()->json([
//             'success' => false,
//             'message' => 'Failed to get token',
//         ], 400);
//     }

//     public function createSubscriptionPlan_card(PaymobService $paymob)
//     {
//         $name = "Monthly Rent Plan";
//         $amountCents = 5000; // 50.00 EGP
//         $frequency = 30;
//       $integrationId = config('paymob.PAYMOB_INTEGRATION_CARD');
//         $webhookUrl = "https://webhook.site/6191ef7a-cdd2-4119-a77c-df06595aa276";

//         $response = $paymob->createSubscriptionPlan($name, $amountCents, $frequency, $integrationId, $webhookUrl);
//         // dd($integrationId);
//         return response()->json($response);
//     }


//     public function startPayment(PaymobService $paymob)
// {
//     $amount = 50000;
//     $name = "Mohamed Magdy";
//     $email = "mohamedmagdy157777@gmail.com";
//     $phone = "01554447882";

//     $response = $paymob->createPaymentIntention($amount, $name, $email, $phone);
//     $data = $response->json();
// //   dd( [(int)config('paymob.PAYMOB_INTEGRATION_CARD')],);
//     return [
//         'checkout_url' => "https://accept.paymob.com/unifiedcheckout/?client_secret=" . $data['client_secret'],
//         'client_secret' => $data['client_secret'],
//         'raw' => $data
//     ];
// }


// public function createUserSubscription(PaymobService $paymob, Request $request)
// {
//     $planId = 5070; // 👈 استخدم الـ ID الجديد
//     $customerName = "Mohamed Magdy";
//     $customerEmail = "mohamedmagdy157777@gmail.com";
//     $customerPhone = "201554447882";

//     $response = $paymob->createSubscription($planId, $customerName, $customerEmail, $customerPhone);

//     $data = $response->json();

//     return [
//         'redirect_url' => $data['redirect_url'] ?? null,
//         'client_secret' => $data['client_secret'] ?? null,
//         'raw_response' => $data
//     ];
// }

  public function startSubscriptionPayment(PaymobService $paymob)
    {
        $authToken = $paymob->authenticate();
        $amountCents = 5000; // 50.00 EGP

        // 1️⃣ Create order
        $order = $paymob->createOrder($authToken, $amountCents);
        if (!isset($order['id'])) {
            return response()->json(['error' => 'Failed to create order', 'details' => $order], 500);
        }

        $orderId = $order['id'];

        // 2️⃣ Prepare billing data
        $billingData = [
            "apartment" => "1",
            "email" => "mohamedmagdy157777@gmail.com",
            "floor" => "1",
            "first_name" => "Mohamed",
            "last_name" => "Magdy",
            "street" => "Cairo Street",
            "building" => "1",
            "phone_number" => "+201554447882",
            "shipping_method" => "NA",
            "postal_code" => "11511",
            "city" => "Cairo",
            "country" => "EGY",
            "state" => "Cairo",
        ];

        // 3️⃣ Generate payment key
        $paymentKeyData = $paymob->generatePaymentKey($authToken, $orderId, $amountCents, $billingData);

        if (!isset($paymentKeyData['token'])) {
            return response()->json(['error' => 'Failed to generate payment key', 'details' => $paymentKeyData], 500);
        }

        $paymentKey = $paymentKeyData['token'];

        // 4️⃣ Redirect URL
        $redirectUrl = $paymob->getRedirectUrl($paymentKey);

        return response()->json([
            'redirect_url' => $redirectUrl,
            'payment_key' => $paymentKey,
            'order_id' => $orderId,
        ]);

}

}
