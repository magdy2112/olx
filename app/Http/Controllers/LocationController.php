<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Advertising;
use App\Models\Governorate;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    use \App\Traits\Httpresponse;
    public function userlocation(LocationRequest $request)
    {
        $data =  $request->validated();

        $user = Auth::user();


        if (isset($data['country'], $data['city'])) {
            $gov = Governorate::where('country', $data['country'])
                ->where('city', $data['city'])
                ->first();
            $location = Location::updateOrCreate(
                [
                    'locationable_id' => $user?->id,
                    'locationable_type' => get_class($user)
                ],

                [
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'lat' => $gov?->lat,
                    'lng' => $gov?->lng
                ],
            );
        } elseif (isset($data['lat'], $data['lng'])) {
            $location = Location::updateOrCreate(
                [
                    'locationable_id' => $user?->id,
                    'locationable_type' => get_class($user)
                ],
                [
                    'city' => Governorate::where('lat', $data['lat'])->first()?->city,
                    'country' => Governorate::where('lat', $data['lat'])->first()?->country,
                    'lat' => $data['lat'],
                    'lng' => $data['lng']
                ],

            );
        }

        return $this->response(true,  __('governorates.message'), 200, [
            'city' => __('governorates.' . $location->city) !== 'governorates.' . $location->city
                ? __('governorates.' . $location->city)
                : $location->city,
            'country' => __('governorates.' . $location->country) !== 'governorates.' . $location->country
                ? __('governorates.' . $location->country)
                : $location->country,
            'lat' => $location->lat,
            'lng' => $location->lng
        ]);
    }

    public function advertisinglocation(LocationRequest $request, Request $req)
    {

        $data =  $request->validated();
        $advertising = Advertising::find($req->advertising_id);

        if (!$advertising) {
            return $this->response(false, 404, __('advertising.not_found'));
        }



        if (isset($data['country'], $data['city'])) {
            $gov = Governorate::where('country', $data['country'])
                ->where('city', $data['city'])
                ->first();
            $location = Location::updateOrCreate(
                [
                    'locationable_id' => $advertising?->id,
                    'locationable_type' => get_class($advertising)
                ],

                [
                    'city' => $data['city'],
                    'country' => $data['country'],
                    'lat' => $gov?->lat,
                    'lng' => $gov?->lng
                ],
            );
        } elseif (isset($data['lat'], $data['lng'])) {
            $gov = Governorate::where('lat', $data['lat'])
                ->where('lng', $data['lng'])
                ->first();

            $location = Location::updateOrCreate(
                [
                    'locationable_id' => $advertising?->id,
                    'locationable_type' => get_class($advertising)
                ],
                [
                    'city' => $gov?->city,
                    'country' => $gov?->country,
                    'lat' => $data['lat'],
                    'lng' => $data['lng']
                ],

            );
        }

        return $this->response(true, __('governorates.message'), 200, [
            'city' => __('governorates.' . $location->city) !== 'governorates.' . $location->city
                ? __('governorates.' . $location->city)
                : $location->city,
            'country' => __('governorates.' . $location->country) !== 'governorates.' . $location->country
                ? __('governorates.' . $location->country)
                : $location->country,
            'lat' => $location->lat,
            'lng' => $location->lng,
        ]);
    }
}
