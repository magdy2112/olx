<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\Addcategoryrequest;
use App\Http\Requests\Category\Updatecategoryrequest;
use App\Models\Category;

use App\Traits\Httpresponse;

use Illuminate\Support\Facades\Gate;

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

            if (Gate::allows('admin')) {
                $category = Category::create([
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

    public function updatecategory(Updatecategoryrequest $request, int $id )
    {
          $data = $request->validated();
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
            
}

}
