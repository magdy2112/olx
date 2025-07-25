<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function userlocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
        ]);
        try {

            $ip = app()->environment('local') ? '102.40.201.15' : $request->ip();
            $lat = $request->input('lat');
            $lon = $request->input('lon');

            $userId = Auth::check() ? Auth::id() : null;


            $cacheKey = 'user_location_' . ($userId ?? $ip);
            if (Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }


            if ($lat && $lon) {


                $locationIQKey = env('LOCATIONIQ_API_KEY');
                $locationResponse = Http::get("https://us1.locationiq.com/v1/reverse", [
                    'key' => $locationIQKey,
                    'lat' => $lat,
                    'lon' => $lon,
                    'format' => 'json'
                ]);

                if (!$locationResponse->successful()) {
                    throw new \Exception('Failed to get location from API.');
                }




                if ($userId && $lat && $lon) {
                    Userlocation::updateOrCreate(
                        [
                            'user_id'     => $userId,
                        ],
                        [
                            'ip'          =>  $ip,
                            'source'      => 'gps',
                            'country'     => $locationData['country'] ?? null,
                            'state'       => $locationData['state'] ?? null,
                            'city'        => $locationData['city'] ?? null,
                            'suburb'      => $locationData['suburb'] ?? null,
                            'road'        => $locationData['road'] ?? null,
                            'user_id'  =>  $userId,
                        ]
                    );
                    $usergpslocation =  [
                        'country'      => $locationData['country'] ?? null,
                        'state'        => $locationData['state'] ?? null,
                        'city'         => $locationData['city'] ?? null,
                        'suburb'       => $locationData['suburb'] ?? null,
                        'road'         => $locationData['road'] ?? null,
                        'user_id'  =>  $userId ? $userId : 'guest',
                    ];
                    Cache::forever($cacheKey,  $usergpslocation);
                    return response()->json($usergpslocation);
                }
            }


            if (!$userId && $lat && $lon && isset($locationData)) {
                $guestgpslocation = [
                    'country'      => $locationData['country'] ?? null,
                    'state'        => $locationData['state'] ?? null,
                    'city'         => $locationData['city'] ?? null,
                    'suburb'       => $locationData['suburb'] ?? null,
                    'road'         => $locationData['road'] ?? null,
                ];

                return response()->json($guestgpslocation);
            }




            // IP Fallback
            $res = Http::get("https://ipwho.is/{$ip}");

            if ($res->failed()) {
                return response()->json(['error' => 'Failed to get IP location'], 500);
            }

            $data = $res->json();


            if ($userId) {

                Userlocation::updateOrCreate(
                    [
                        'user_id'     => $userId,
                    ],
                    [
                        'user_id'  =>  $userId,
                        'source'   => 'ip',
                        'country'  => $data['country'] ?? null,
                        'state'    => $data['region'] ?? null,
                        'city'     => $data['city'] ?? null,
                    ]
                );

                $useriplocation =  [
                    'country'     => $data['country'] ?? null,
                    'state'       => $data['region'] ?? null,
                    'city'        => $data['city'] ?? null,
                    'user_id'  =>  $userId
                ];
                Cache::forever($cacheKey, $useriplocation);
                return response()->json($useriplocation);
            } else {
                $guestiplocation = [
                    'country'     => $data['country'] ?? null,
                    'state'       => $data['region'] ?? null,
                    'city'        => $data['city'] ?? null,

                ];

                return response()->json($guestiplocation);
            }
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
