<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Addcategoryrequest;
use App\Http\Requests\Category\Updatecategoryrequest;
use App\Http\Resources\CategoryResource;
use App\Jobs\Allsubmodal;
use App\Jobs\Deletecategory;

use App\Models\Category;
use App\Helper\Systemupdate;

use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use HttpResponse;


    /**
     *
     */


    public function allcategory()
    {
       
        try {
            $allcategory = Category::all()->toResourceCollection(CategoryResource::class);
            $data = Category::with('subcategories')->get();
            // use resource collection for subcategories and modals then use resource collection for modals inside category resource

            return $this->response(true, 200, 'success', ['allcategory'=>$allcategory, 'allcategorywithsubcategory' => $data]);
        } catch (\Exception $e) {
            Log::channel('category')->error('Error in allcategory: ' . $e->getMessage());
            return $this->response(false, 500, $e->getMessage());
        }
    }

    public function addcategory(Addcategoryrequest $request)
    {


        $data = $request->validated();
       

        try {

           
            $category = Category::create([
                'name' => $data['name']
            ]);
            if ($category) {
                // cache::forget('allsubmodal_cache');
            
                return $this->response(true, 200, 'success', $category);
            } else {
                return $this->response(false, 401, 'Unauthorized');
            }
        } catch (\Exception $e) {
            Log::channel('category')->error('Error in addcategory: ' . $e->getMessage());
            return $this->response(false, 500, $e->getMessage());
        }
    }

    public function updatecategory(Updatecategoryrequest $request, int $id)
    {


        $data = $request->validated();

        try {

           
            $category = Category::find($id);

            if (!$category) {
                return $this->response(false, 404, 'Category not found');
            }
            if ($category) {
                $category->update([
                    'name' => $data['name'],
                ]);
                //  cache::forget('allsubmodal_cache');
                return $this->response(true, 200, 'success', $category);
            } else {
                return $this->response(false, 401, 'Unauthorized');
            }
        } catch (\Exception $e) {
            Log::channel('category')->error('Error in updatecategory: ' . $e->getMessage());
            return $this->response(false, 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        
        $updatingResponse = Systemupdate::systemUpdatingResponse();
        if ($updatingResponse) {
            return $updatingResponse;
        }
           
       
        $category = Category::find($id);
        if (!$category) {
            return $this->response(false, 404, 'Category not found');
        }

    

        try {

            Cache::put('destroy_category', 'delete_category', now()->addMinutes(20));

            Deletecategory::dispatch($id, Auth::id());
            
            
           return $this->response(true, 200, 'Delete job dispatched successfully. It will be processed in background.');
        } catch (\Exception $e) {
            Log::channel('category')->error('Error in destroy: ' . $e->getMessage());
            return $this->response(false, 500, $e->getMessage());
        }
    }

     
}
