<?php

/**
 * 
 */
// namespace App\Http\Services;

// use Illuminate\Support\Facades\Http;

// class PaymobService
// {
//     private $apiKey;
//     private $integrationCard;
//     private $iframeCard;

//     public function __construct()
//     {
//         $this->apiKey = config('paymob.PAYMOB_API_KEY');
//         $this->integrationCard = config('paymob.PAYMOB_INTEGRATION_CARD');
//         $this->iframeCard = config('paymob.PAYMOB_IFRAME_CARD');
//     }

//     // الخطوة 1: نجيب Auth Token من Paymob
//     public function authenticate()
//     {
//         $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
//             'api_key' => $this->apiKey,
//         ]);


//         return $response->json()['token'] ?? null;
//     }

//     public function createSubscriptionPlan($name, $amountCents, $frequency, $integrationId, $webhookUrl)
//     {
//         $authToken = $this->authenticate();

//         $response = Http::withHeaders([
//             'Content-Type' => 'application/json',
//         ])->post('https://accept.paymob.com/api/acceptance/subscription-plans', [
//             "auth_token" => $authToken,
//             "frequency" => $frequency,
//             "name" => $name,
//             "webhook_url" => $webhookUrl, //https://webhook.site/6191ef7a-cdd2-4119-a77c-df06595aa276
//             "plan_type" => "rent",
//             "amount_cents" => $amountCents,
//             "use_transaction_amount" => true,
//             "is_active" => true,
//             "integration" => $integrationId,
//         ]);

//         return $response->json();
//     }



//    public function createSubscription($planId, $customerName, $customerEmail, $customerPhone)
// {
//     $secretKey = config('paymob.PAYMOB_SECRET_KEY');

//     $payload = [
//         "amount" => 5000, // بالسنت
//         "currency" => "EGP",
//         "payment_methods" => [(int)config('paymob.PAYMOB_INTEGRATION_CARD')],
//         "subscription_plan_id" => (int)$planId,
//         "subscription_start_date" => now()->tomorrow()->format('Y-m-d'),
//         "items" => [
//             [
//                 "name" => "Monthly Rent Plan",
//                 "amount" => 5000,
//                 "description" => "Monthly subscription",
//                 "quantity" => 1,
//             ]
//         ],
//         "billing_data" => [
//             "apartment" => "1",
//             "first_name" => $customerName,
//             "last_name" => "User",
//             "street" => "Cairo Street",
//             "building" => "1",
//             "phone_number" => "+20" . ltrim($customerPhone, "0"),
//             "country" => "EGY",
//             "email" => $customerEmail,
//             "floor" => "1",
//             "state" => "Cairo",
//             "city" => "Cairo",
//             "postal_code" => "11511"
//         ],
//         "customer" => [
//             "first_name" => $customerName,
//             "last_name" => "User",
//             "email" => $customerEmail,
//         ]
//     ];

//     $response = Http::withHeaders([
//         'Authorization' => 'Token ' . $secretKey,
//         'Content-Type' => 'application/json',
//     ])->post('https://accept.paymob.com/v1/intention', $payload);

//     return $response;
// }

//     public function createPaymentIntention($amount, $name, $email, $phone)
// {
//     $secretKey = config('paymob.PAYMOB_SECRET_KEY');

//     $payload = [
//         "amount" => $amount,
//         "currency" => "EGP",
//         "payment_methods" => [(int)config('paymob.PAYMOB_INTEGRATION_CARD')],
//         "items" => [
//             [
//                 "name" => "Subscription Start",
//                 "amount" => $amount,
//                 "description" => "First subscription payment",
//                 "quantity" => 1,
//             ]
//         ],
//         "billing_data" => [
//             "apartment" => "6",
//             "first_name" => $name,
//             "last_name" => "User",
//             "street" => "Cairo St",
//             "building" => "1",
//             "phone_number" => "+20" . ltrim($phone, "0"),
//             "country" => "EGY",
//             "email" => $email,
//             "floor" => "1",
//             "state" => "Cairo",

//         ],
//         "customer" => [
//             "first_name" => $name,
//             "last_name" => "User",
//             "email" => $email,
//         ]
        
//     ];

//     $response = Http::withHeaders([
//         'Authorization' => 'Token ' . $secretKey,
//         'Content-Type' => 'application/json',
//     ])->post('https://accept.paymob.com/v1/intention', $payload);

//     return $response;
// }

// }
//https://webhook.site/#!/view/6191ef7a-cdd2-4119-a77c-df06595aa276 -->



namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    /**
     * Authenticate to Paymob and get the auth token
     */
    public function authenticate()
    {
        $apiKey = config('paymob.PAYMOB_API_KEY');

        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $apiKey,
        ]);

        return $response->json()['token'] ?? null;
    }

    /**
     * Create an order on Paymob
     */
    public function createOrder($authToken, $amountCents)
    {
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            "auth_token" => $authToken,
            "delivery_needed" => false,
            "amount_cents" => $amountCents,
            "currency" => "EGP",
            "items" => [],
        ]);

        return $response->json();
    }

    /**
     * Generate a payment key
     */
    public function generatePaymentKey($authToken, $orderId, $amountCents, $billingData)
    {
        $integrationId = config('paymob.PAYMOB_INTEGRATION_CARD');

        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            "auth_token" => $authToken,
            "amount_cents" => $amountCents,
            "expiration" => 3600,
            "order_id" => $orderId,
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => $integrationId,
        ]);

        return $response->json();
    }

    /**
     * Build redirect URL for iframe payment page
     */
    public function getRedirectUrl($paymentKey)
    {
        $iframeId = config('paymob.PAYMOB_IFRAME_CARD');
        return "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentKey}";
    }
}

