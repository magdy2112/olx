<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubCategory\Addsubcategoryrequest;
use App\Http\Requests\SubCategory\Updatecategoryrequest;
use App\Jobs\Deletesubcategory;
use App\Models\SubCategory;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
    use Httpresponse;

    public function allsubcategory()
    {
        try {
            $data =  SubCategory::all();
            return $this->response(true, 200, 'success', $data);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function addsubcategory(Addsubcategoryrequest $request)

    {
        $data = $request->validated();
        try {
            $subcategory =  SubCategory::create([
                'name' => $data['name'],
                'category_id' => $data['category_id']
            ]);
            $subcategory->updateFinalStatus();

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

            $subcategory->update([
                'name' => $data['name'],
            ]);
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
            if (Cache::has('destroy_subcategory') || Cache::has('destroy_category')) {
                return $this->response(false, 429, 'Another delete operation is in progress.');
            }
            $subcategory = SubCategory::find($id);
            if (!$subcategory) {
                return $this->response(false, 404, 'SubCategory not found');
            }
            cache::put('destroy_subcategory', 'delete_subcategory', now()->addHours(1));
            Deletesubcategory::dispatch($id, Auth::id());
           return $this->response(true, 200, 'Delete job dispatched successfully. It will be processed in background.');
           
           
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }
}
