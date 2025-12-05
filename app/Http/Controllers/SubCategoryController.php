<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategory\Addsubcategoryrequest;
use App\Http\Requests\SubCategory\Updatecategoryrequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SubcategoryResource;
use App\Jobs\Deletesubcategory;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{
    use HttpResponse;

    public function allsubcategory()
    {
        try {

            $data = SubCategory::all()->toResourceCollection(SubcategoryResource::class);
  
            return $this->response(true, 200, 'success', $data);
        } catch (\Exception $th) {
            Log::channel('subcategory')->error($th->getMessage());
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function allsubcategorybycategory($categoryid)
    {

        try {
           
            $subcategory = SubCategory::where('category_id', $categoryid)
            ->get()->toResourceCollection(SubcategoryResource::class);
           
            if($subcategory->isEmpty()){
                return $this->response(false, 404, 'subcategory not found', null);
            }
            return $this->response(true, 200, 'success', [
                'subcategory' => $subcategory,
                
            ]);
        } catch (\Exception $th) {
            Log::channel('subcategory')->error($th->getMessage());
            return $this->response(false, 500, $th->getMessage());
        }
    }

    

    public function addsubcategory(Addsubcategoryrequest $request)

    {
        $data = $request->validated();

        $exists = SubCategory::where('name', $data['name'])
            ->where('category_id', $data['category_id'])
            ->exists();
            if ($exists) {
                return $this->response(false, 422, 'This name already exists for this category.', null);
            }
         
        try {
            $subcategory =  SubCategory::create([
                'name' => $data['name'],
                'category_id' => $data['category_id']
                

            ]);
           

            return $this->response(true, 200, 'success', $subcategory);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }


    public function updatesubcategory(Updatecategoryrequest $request, int $id)
    {
        $data = $request->validated();
        try {
            $subcategory = SubCategory::find($id);
            if (!$subcategory) {
                return $this->response(false, 404, 'Category not found');
            }

             $exists = SubCategory::where('name', $data['name'])
            ->where('category_id', $data['category_id'])
            ->where('id', '!=', $id)
            ->exists();
            if ($exists) {
                return $this->response(false, 422, 'This name already exists for this category.', null);
            }

            $subcategory->update([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
            ]);
            $subcategory->updateFinalStatus();
             cache::forget('allsubmodal_cache');
            return $this->response(true, 200, 'success', $subcategory);
        } catch (\Throwable $th) {
            return  $this->response(false, 500, $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (!Gate::allows('admin')) {
                return $this->response(false, 401, 'Unauthorized');
            }
            if (Cache::has('destroy_subcategory') || Cache::has('destroy_category' || Cache::has('destroy_modal' || Cache::has('destroy_submodal') || Cache::has('destroy_attribute')))) {
                    return $this->response(false, 429, 'Another delete operation is in progress.');
               }

            $subcategory = SubCategory::find($id);
            if (!$subcategory) {
                return $this->response(false, 404, 'SubCategory not found');
            }
            cache::put('destroy_subcategory', 'delete_subcategory', now()->addHours(1));
            Deletesubcategory::dispatch($id, Auth::id());
             cache::forget('allsubmodal_cache');
           return $this->response(true, 200, 'Delete job dispatched successfully. It will be processed in background.');
           
           
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }
           

}
