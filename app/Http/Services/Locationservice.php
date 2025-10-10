<?php

namespace App\Http\Services;

use App\Models\Advertising;
use App\Models\Governorate;
use Illuminate\Support\Facades\Auth;

class Locationservice
{
     use \App\Traits\Httpresponse;
     public function advertisingLocation(Advertising $advertising, $lat, $lng, $city = null, $country = null)
     {
       
          $gov = Governorate::query()
               ->when($lat && $lng, function ($query) use ($lat, $lng) {
                    return $query->where('lat', $lat)
                         ->where('lng', $lng);
               })
               ->when($city && $country, function ($query) use ($city, $country) {
                    return $query->where('city', $city)
                         ->where('country', $country);
               })
               ->first();
          $location = $advertising->location()->updateOrCreate(
               [
                    'locationable_id' => $advertising->id,
                    'locationable_type' => get_class($advertising)
               ],
               [
                    'city' => $city ?? $gov?->city,
                    'country' => $country ?? $gov?->country,
                    'lat' => $lat ?? $gov?->lat,
                    'lng' => $lng ?? $gov?->lng
               ],

          );

          $data =  
          ['city' => __('governorates.' . $location->city) !== 'governorates.' . $location->city
                ? __('governorates.' . $location->city)
                : $location->city,
            'country' => __('governorates.' . $location->country) !== 'governorates.' . $location->country
                ? __('governorates.' . $location->country)
                : $location->country,
            'lat' => $location->lat,
            'lng' => $location->lng,
     ];
          return $this->response(true, 200, __('governorates.message'), $data);
    }

   public function userLocation($user, $lat = null, $lng = null, $city = null, $country = null)
   {
            
            $gov = Governorate::query()
                ->when($lat && $lng, function ($query) use ($lat, $lng) {
                    return $query->where('lat', $lat)
                           ->where('lng', $lng);
                })
                ->when($city && $country, function ($query) use ($city, $country) {
                    return $query->where('city', $city)
                           ->where('country', $country);
                })
                ->first();

        return $this->response(true, 200, __('governorates.message'), [
             'city' => __('governorates.' . $gov?->city) !== 'governorates.' . $gov?->city
                  ? __('governorates.' . $gov?->city)
                  : $gov?->city,
             'country' => __('governorates.' . $gov?->country) !== 'governorates.' . $gov?->country
                  ? __('governorates.' . $gov?->country)
                  : $gov?->country,
             'lat' => $gov?->lat,
             'lng' => $gov?->lng,
        ]);
   }
} 
