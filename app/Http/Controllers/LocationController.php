<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
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

        if( isset($data['country'] ,$data['city']) ){
            $location = Location::updateOrCreate(
                [
                    'locationable_id' => $user?->id,
                    'locationable_type' => get_class($user)
                ],

        [
                'city' => $data['city'], 
                'country' => $data['country'],
                'lat' => Governorate::where('country', $data['country'])->first()?->lat,
                'lng' => Governorate::where('city', $data['city'])->first()?->lng
               ],
            );

        }
        if( isset($data['lat'] ,$data['lng']) ){
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

     return $this->response( true,  __('governorates.message'), 200, [
            'city' => __('governorates.' . $location->city),
            'country' => __('governorates.' . $location->country),
            'lat' => $location->lat,
            'lng' => $location->lng
        ]);
    }

}

