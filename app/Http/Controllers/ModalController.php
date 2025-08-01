<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modal\Addmodalrequest;
use App\Http\Requests\Modal\Updatemodalrequest;
use App\Jobs\Deletemodal;
use App\Models\Category;
use App\Models\Modal;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class ModalController extends Controller
{
     use Httpresponse;

     public function allmodal()
     {
          try {
               $category = Category::with('subcategories.modals')->get();
               if (!$category) {
                    return $this->response(false, 404, 'category not found', null);
               }

               $formattedData = $category->map(function ($category) {
                    return [
                         'id' => $category->id,
                         'name' => $category->name,
                         'subcategories' => $category->subcategories->map(function ($subcategory) {
                              return [
                                   'id' => $subcategory->id,
                                   'name' => $subcategory->name,
                                   'modals' => $subcategory->modals->map(function ($modal) {
                                        return [
                                             'id' => $modal->id,
                                             'name' => $modal->name,
                                             // Add other modal fields as needed
                                        ];
                                   }),
                              ];
                         }),
                    ];
               });
               return $this->response(true, 200, 'success', $formattedData);
          } catch (\Exception $e) {
               return $this->response(false, 500, $e->getMessage(), null);
          }
     }

     public function modalbysubcategory($subcategoryid)
     {
          try {
               $subcategory = SubCategory::where('id', $subcategoryid)->first();
               if (! $subcategory) {
                    return $this->response(false, 404, 'subcategory not found', null);
               }
               $modals = Modal::where('sub_category_id', $subcategoryid)->get();
               if ($modals->isEmpty()) {
                    return $this->response(false, 404, 'modal not found', null);
               }
               return $this->response(true, 200, 'success', [
                    'subcategory' => $subcategory,
                    'modal' => $modals,
               ]);
          } catch (\Exception $e) {
               return $this->response(false, 500, $e->getMessage(), null);
          }
     }

     public function addmodal(Addmodalrequest $request)
     {
          $data = $request->validated();
          $exists = Modal::where('name', $data['name'])
               ->where('sub_category_id', $data['sub_category_id'])
               ->exists();

          if ($exists) {
               return $this->response(false, 422, 'This name already exists for this subcategory.', null);
          }

          try {
               $modal = Modal::create([
                    'name' => $data['name'],
                    'sub_category_id' => $data['sub_category_id']
               ]);

               $modal->updateFinalStatus();
               return $this->response(true, 200, 'success', $modal);
          } catch (\Exception $e) {
               return $this->response(false, 500, $e->getMessage(), null);
          }
     }


     public function updatemodal(Updatemodalrequest $request, int $id)
     {
          $data = $request->validated();

          $modal = Modal::find($id);
          if (!$modal) {
               return $this->response(false, 404, 'Modal not found', null);
          }

          $exists = Modal::where('name', $data['name'])
               ->where('sub_category_id', $data['sub_category_id'])
               ->where('id', '!=', $id)
               ->exists();

          if ($exists) {
               return $this->response(false, 422, 'This name already exists for this subcategory.', null);
          }
          try {
               $modal->update([
                    'name' => $data['name'],
                    'sub_category_id' => $data['sub_category_id']
               ]);
               $modal->updateFinalStatus();
               return $this->response(true, 200, 'success', $modal);
          } catch (\Throwable $th) {
               return $this->response(false, 500, $th->getMessage());
          }
     }

     public function destroy($id)
     {
          try {
               if (!Gate::allows('admin')) {
                    return $this->response(false, 401, 'Unauthorized', null);
               }

               $modal = Modal::find($id);
               if (!$modal) {
                    return $this->response(false, 404, 'Modal not found', null);
               }

               if (Cache::has('destroy_subcategory') || Cache::has('destroy_category') || Cache::has('destroy_modal')) {
                    return $this->response(false, 429, 'Another delete operation is in progress.');
               }



               Cache::put('destroy_modal', 'delete_modal', now()->addHours(1));
               Deletemodal::dispatch($id, Auth::id());
               return $this->response(true, 200, 'Delete job dispatched successfully. It will be processed in background.', null);
          } catch (\Exception $e) {
               return $this->response(false, 500, $e->getMessage(), null);
          }
     }

     public function isfinal()
     {
          $modals = Modal::all();
          foreach ($modals as $modal) {
               $modal->updateFinalStatus();
          }
         

            $subcategorys = subCategory::all();
          foreach ($subcategorys as $subcategory) {
              $subcategory->updateFinalStatus();
          }






          return $this->response(true, 200, 'success');
     }
}
