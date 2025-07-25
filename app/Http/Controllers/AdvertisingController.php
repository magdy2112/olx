<?php

namespace App\Http\Controllers;

use App\Models\Advertising;
use App\Models\Category;
use App\Models\Chain;
use App\Models\Modal;
use App\Models\SubCategory;
use App\Models\Submodal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Homescreen;
use Illuminate\Validation\Rules\Exists;

class AdvertisingController extends Controller
{
    public function home()
    {

        $category = Category::select('id', 'name')->get();


        $alladd  = Advertising::with(['attributes' => function ($query) {
            $query->select('attributes.id', 'attributes.name');
        }])->orderBy('created_at', direction: 'desc')->Paginate(10);

        // return response()->json(
        //     [
        //         'category' => $category,
        //         'data' => $alladd,
        //     ]
        // );

        $formatted = $alladd->map(function ($ad) {
            return [
                'ad_id' => $ad->id,
                'ad_user' => $ad->user_id,
                'ad_category' => $ad->category->name,
                'ad_att' => $ad->attributes->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'name' => $att->name,
                        'value' => $att->pivot->value
                    ];
                })

            ];
        });
        return response()->json([
            'category' => $category,
            'data' => $formatted,
            'pagination' => [
                'current_page' => $alladd->currentPage(),
                'last_page' => $alladd->lastPage(),
                'next_page_url' => $alladd->nextPageUrl(),
                'prev_page_url' => $alladd->previousPageUrl(),
                'total' => $alladd->total(),
            ]

        ]);
    }





    public function selectcategory($categoryid)
    {

        $category = Category::where('id', $categoryid)->first();

        $subcategoryid = SubCategory::where('category_id', $categoryid)->pluck('id');
        $subcategory = SubCategory::where('category_id', $categoryid)->select('name', 'id')->get();


        $addsid  = advertising::where('category_id', $categoryid)->pluck('id');

        $alladd  = Advertising::with(['attributes' => function ($query) {
            $query->select('attributes.id', 'attributes.name');
        }])->where('category_id', $categoryid)->whereIn('id', $addsid)
            ->orderBy('created_at', direction: 'desc')->Paginate(10);


        $formatted = $alladd->map(function ($ad) {

            $subcategory = SubCategory::where('category_id', $ad->category_id)->select('name', 'id')->get();

            return [
                'ad_id' => $ad->id,
                'ad_user' => $ad->user_id,
                'ad_category' => $ad->category->name,
                // 'add_subcategory' =>  $subcategory,
                'ad_att' => $ad->attributes->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'name' => $att->name,
                        'value' => $att->pivot->value
                    ];
                })

            ];
        });



        return response()->json([

            // 'category' => $category,
            'subcategory' => $subcategory,
            'alladd' => $formatted,
            // 'add_subcategory'
            // 'alladd' => $alladd



        ]);;
    }



    public function selectsubcategory($subcategoryid)
    {

        $subcategory = SubCategory::where('id',   $subcategoryid)->first();
        $category  =  Category::where('id', $subcategory->category_id)->pluck('name');
        $modal = Modal::where('sub_category_id', $subcategoryid)->whereDoesntHave('submodals')->get();
        if ($modal->isEmpty()) {
            $modalid = Modal::where('sub_category_id', $subcategoryid)->pluck('id');
            $submodal = Submodal::whereIn('modal_id', $modalid)->whereHas('modal')->get();

            return response()->json([
                'category' => $category,
                'subcategory' => $subcategory,
                'submodal' => $submodal,

            ]);
        }
        return response()->json([

            'category' => $category,
            'subcategory' => $subcategory,
            'modals' =>  $modal,

        ]);
    }


    public function selectproduct() {}
















    public function list1(Request $request)
    {

        $data = [
            'user_id' => 1,
            'category_id' => 3,

        ];
        $category = Category::where('id', $data['category_id'])->pluck('name', 'id');
        $subcatogory = SubCategory::where('category_id', $data['category_id'])->pluck('name', 'id');
        return response()->json([
            'category' =>      $category,
            'subcatogory' => $subcatogory,
        ]);
    }

    public function list2()
    {
        // $x = cache::get('$subcatogory');
        $specialsubcategoryid = [

            'id' => 6
        ];
        $modal = Modal::where('sub_category_id', $specialsubcategoryid['id'])->get();
        return response()->json([
            'modal' =>    $modal
        ]);
    }


    public function list3()
    {

        $specialmodalid = [

            'id' => 9
        ];
        $submodal = Submodal::where('modal_id', $specialmodalid['id'])->get();
        return response()->json([
            'modal' =>  $submodal
        ]);
    }
}
