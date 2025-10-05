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

public function getUserAdvertisings(Request $request){

    return $this->advertisingService->getUserAdvertisings($request);
}

public function getcategoryAdvertisings(Request $request,$category_id){

    return $this->advertisingService->getcategoryAdvertisings($request,$category_id);
}


public function getsubcategoryAdvertisings(Request $request,$subcategory_id){

    return $this->advertisingService->getsubcategoryAdvertisings($request,$subcategory_id); 

}


public function getAllAdvertisings(Request $request){

    return $this->advertisingService->getAllAdvertisings($request);

}


public function getadvertisingDetails(Request $request,$id){

    return $this->advertisingService->getadvertisingDetails($request,$id);
}

public function getmodaladvertisings(Request $request,$modalId){

    return $this->advertisingService->getmodaladvertisings($request,$modalId);
}




public function getsubmodaladvertisings(Request $request,$submodalId){

    return $this->advertisingService->getsubmodaladvertisings($request,$submodalId);
}

public function searchAdvertisings(Request $request){

    return $this->advertisingService->searchAdvertisings($request, $request->input('query'));

}

}