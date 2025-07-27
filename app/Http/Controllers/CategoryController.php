<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Addcategoryrequest;
use App\Http\Requests\Category\Updatecategoryrequest;
use App\Jobs\Deletecategory;
use App\Models\advertising;
use App\Models\Category;

use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use Httpresponse;


    /**
     *
     */
    public function allcategory()
    {
        try {
            $allcategory = Category::all();
            return $this->response(true, 200, 'success', $allcategory);
        } catch (\Throwable $e) {
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

                return $this->response(true, 200, 'success', $category);
            } else {
                return $this->response(false, 401, 'Unauthorized');
            }
        } catch (\Throwable $e) {
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
                return $this->response(true, 200, 'success', $category);
            } else {
                return $this->response(false, 401, 'Unauthorized');
            }
        } catch (\Throwable $e) {
            return $this->response(false, 500, $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (!Gate::allows('admin')) {
            return $this->response(false, 401, 'Unauthorized');
        }


           $lock = Cache::get('destroy_category');
            if ($lock) {
            return $this->response(false, 429, 'Another delete operation is in progress.');
            }
             $category = Category::find($id);
            if (!$category) {
            return $this->response(false, 404, 'Category not found');
            }

               if (!Gate::allows('admin')) {
            return $this->response(false, 401, 'Unauthorized');
            }

        try {
         
            Cache::put('destroy_category', 'delete_category', now()->addHours(1));
           
            Deletecategory::dispatch($id, Auth::id());
            return $this->response(true, 200, 'Category deleted successfully');
        } catch (\Throwable $e) {
            return $this->response(false, 500, $e->getMessage());
        }
    }
}
