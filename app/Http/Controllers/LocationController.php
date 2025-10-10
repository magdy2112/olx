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
   
    public function __construct(protected \App\Http\Services\Locationservice $locationservice)
    {
             $this->locationservice = $locationservice;
    }

    public function advertisinglocation(LocationRequest $locationRequest)
    {
       
        $data = $locationRequest->validated();


          return $this->locationservice->advertisingLocation(
               Advertising::find($data['advertising_id']),
               $data['lat'],
               $data['lng'],
               $data['city'],
               $data['country']
          );
    }

        public function userlocation(LocationRequest $locationRequest)
    {
        $data = $locationRequest->validated();
            return $this->locationservice->userLocation(
                 Auth::user(),
                        $data['lat'],
                        $data['lng'],
                        $data['city'],
                        $data['country']
            );
    }
}
