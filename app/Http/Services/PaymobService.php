<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    private $apiKey;
    private $integrationCard;
    private $iframeCard;

    public function __construct()
    {
        $this->apiKey = config('paymob.PAYMOB_API_KEY');
        $this->integrationCard = config('paymob.PAYMOB_INTEGRATION_CARD');
        $this->iframeCard = config('paymob.PAYMOB_IFRAME_CARD');


    }

    // الخطوة 1: نجيب Auth Token من Paymob
    public function authenticate()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);
       

        return $response->json()['token'] ?? null;
    }

public function createSubscriptionPlan($name, $amountCents, $frequency, $integrationId, $webhookUrl)
{
    $authToken = $this->authenticate();

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('https://accept.paymob.com/api/acceptance/subscription-plans', [
        "auth_token" => $authToken,
        "frequency" => $frequency,
        "name" => $name,
        "webhook_url" => $webhookUrl,//https://webhook.site/6191ef7a-cdd2-4119-a77c-df06595aa276
        "plan_type" => "rent",
        "amount_cents" => $amountCents,
        "use_transaction_amount" => true,
        "is_active" => true,
        "integration" => $integrationId,
    ]);

    return $response->json();
}



public function createSubscription($planId, $customerName, $customerEmail, $customerPhone)
{
    $authToken = $this->authenticate();
    $secretKey = config('paymob.PAYMOB_SECRET_KEY');

    if (!$authToken) {
        return [
            'error' => true,
            'message' => 'Failed to authenticate with Paymob'
        ];
    }

    $payload = [
        "amount" => 50000,
        "currency" => "EGP",
        "payment_methods" => [(int)config('paymob.PAYMOB_INTEGRATION_CARD')],
        "subscription_plan_id" => (int)$planId, // تأكد أنه integer
        "subscription_start_date" => now()->tomorrow()->format('Y-m-d')
,
        "items" => [
            [
                "name" => "Monthly Rent Plan",
                "amount" => 50000,
                "description" => "Monthly subscription",
                "quantity" => 1,
            ]
        ],
        "billing_data" => [
            "apartment" => "1",
            "first_name" => $customerName,
            "last_name" => "User",
            "street" => "Cairo Street",
            "building" => "1",
            "phone_number" => "+20" . ltrim($customerPhone, "0"),
            "country" => "EGY", // ⬅️ جرب "EGY" بدلاً من "Egypt"
            "email" => $customerEmail,
            "floor" => "1",
            "state" => "Cairo",
            "city" => "Cairo",
            "postal_code" => "11511" // ⬅️ أضف postal code
        ],
        "customer" => [
            "first_name" => $customerName,
            "last_name" => "User",
            "email" => $customerEmail,
        ]
    ];

    $response = Http::withHeaders([
    'Authorization' => 'Token ' . $secretKey,
    'Content-Type' => 'application/json',
])->timeout(30)->post('https://accept.paymob.com/v1/intention', $payload);
    if ($response->failed()) {
        return [
            'error' => true,
            'status' => $response->status(),
            'message' => $response->body(),
            'payload' => $payload // لأغراض debugging
        ];
    }

    return $response->json();
}

}
//https://webhook.site/#!/view/6191ef7a-cdd2-4119-a77c-df06595aa276

