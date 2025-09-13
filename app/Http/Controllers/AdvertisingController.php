<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advertising\Newadvertisingrequest;
use App\Models\Advertising;
use Illuminate\Http\Request;
use App\Models\CustomAttribute;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Request;

class AdvertisingController extends Controller
{
    use Httpresponse;



    public function getAttributes(Request $request)
    {
        $request->validate([
         
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);

        $attributes = SubCategory::with('attributes')
            ->where('id', $request->sub_category_id)
            ->get();
           
        return $this->response(true, 200, 'Attributes fetched successfully', ['attributes' => $attributes]);
    }
    public function store(Newadvertisingrequest $request)
    {


        $data = $request->validated();
         if (
        Cache::has('destroy_subcategory') ||
        Cache::has('destroy_category') ||
        Cache::has('destroy_modal') ||
        Cache::has('destroy_submodal') ||
        Cache::has('destroy_attribute')
    ) {
        return $this->response(false, 429, 'we are updating our system, please try again later');
    }

        $advertising = Advertising::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'purpose' => $data['purpose'],
            'category_id' => $data['category_id'],
            'sub_category_id' => $data['sub_category_id'],
            'modal_id' => $data['modal_id'],
            'submodal_id' => $data['submodal_id'],
            'status' => $data['status'] ?? 'active',


        ]);
       
        if (!empty($data['attributes'])) {
        $attachData = [];
        foreach ($data['attributes'] as $attributeId => $value) {
            $attachData[$attributeId] = ['value' => $value];
        }

        // ربط attributes بالـ pivot
        $advertising->attributes()->sync($attachData);
     
         return $this->response(true, 201, 'Advertising created successfully', ['advertising' => $advertising]);
       

     
    }
    }
}
