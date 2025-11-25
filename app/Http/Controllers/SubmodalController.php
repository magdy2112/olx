<?php

namespace App\Http\Controllers;

use App\Http\Requests\Submodal\Addsubmodalrequest;
use App\Http\Requests\Submodal\Updatesubmodalrequest;

use App\Jobs\Allsubmodal;
use App\Jobs\Deletesubmodal;
use App\Models\Category;
use App\Models\Modal;
use App\Models\Submodal;
use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class SubmodalController extends Controller
{
      use HttpResponse;
    public function allsubmodal()
    {
        if(!Gate::allows('admin')){
            return $this->response(false, 401, 'Unauthorized');
        }
       if (!Cache::has('allsubmodal_cache')) {
           
            Allsubmodal::dispatch(Auth::id());

            return response()->json([
                'success' => false,
                'message' => 'Data is being prepared, please try again shortly.'
            ], 202);
        }

  
        $data = Cache::get('allsubmodal_cache');

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }


    public function submodalbymodal($modalid)
    {
        try {
              $modal = Modal::find($modalid);
        if (!$modal) {
            return $this->response(false, 404, 'Modal not found');
        }
        $submodal = Submodal::where('modal_id', $modalid)->get();
        if ($submodal->isEmpty()) {
            return $this->response(false, 404, 'Submodal not found');
        }
        return $this->response(true, 200, 'success', $submodal);
        } catch (\Throwable $th) {
           return $this->response(false, 500, $th->getMessage());
        }
      
    }

    public function updatesubmodal(Updatesubmodalrequest $request, int $id)
    {
        $data = $request->validated();
        try {
            $submodal = Submodal::find($id);
            if (!$submodal) {
                return $this->response(false, 404, 'submodal not found');
            }

             $exists = Submodal::where('name', $data['name'])
            ->where('modal_id', $data['modal_id'])
            ->where('id', '!=', $id)
            ->exists();
            if ($exists) {
                return $this->response(false, 422, 'This name already exists for this submodal.', null);
            }

            $submodal->update([
                'modal_id' => $data['modal_id'],
                'name' => $data['name'],
            ]);
           
             cache::forget('allsubmodal_cache');
            return $this->response(true, 200, 'success', $submodal);
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

            
            $submodal = Submodal::find($id);
            if (!$submodal) {
                return $this->response(false, 404, 'Submodal not found');
            }
            Cache::put('destroy_submodal', 'delete_submodal', now()->addHours(1));
            Deletesubmodal::dispatch($id, Auth::id());
             Cache::forget('allsubmodal_cache');
           return $this->response(true, 200, 'Delete job dispatched successfully. It will be processed in background.');
           
           
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

   public function addsubmodal(Addsubmodalrequest $request)
    {
       
  try {
         $data = $request->validated();
        $exists = Submodal::where('name', $data['name'])
            ->where('modal_id', $data['modal_id'])
            ->exists();
            if ($exists) {
                return $this->response(false, 422, 'This name already exists for this category.', null);
            }
         
      
            $submodal =  Submodal::create([
                'name' => $data['name'],
                'modal_id' => $data['modal_id']
            ]);
          
             cache::forget('allsubmodal_cache');

            return $this->response(true, 200, 'success', $submodal);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }

}




}