<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advertising\Newadvertisingrequest;
use App\Http\Services\Advertising_service;
use App\Http\Services\Image_service;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertising;
use App\Enum\Role;
use App\Http\Requests\Advertising\Updateadvertising;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\AdvertisingResource;

// use Illuminate\Support\Facades\Request;

class AdvertisingController extends Controller
{


    use Httpresponse;

   public function __construct(protected Advertising_service $advertisingService, protected Image_service $imageService){}

    public function store(Newadvertisingrequest $request)
    {

      return $this->advertisingService->createNewAdvertising($request, $this->imageService);

   
    }

public function updateadvertising( Updateadvertising $request,$id){


    return $this->advertisingService->updateadvertising($request,$id, $this->imageService);

      
}

public function deleteadvertising(Request $request,$id){

    return $this->advertisingService->deleteadvertising($request,$id);

}
}