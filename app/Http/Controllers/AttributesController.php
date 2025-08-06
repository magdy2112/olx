<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attributes\Addattributerequest;
use App\Models\subattribute;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Attributes\Updateattributerequest;
use App\Http\Requests\Attributes\AddsubattributeRequest;
use App\Http\Requests\Attributes\Updatesubattributerequest;
use App\Models\CustomAttribute;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isEmpty;

class AttributesController extends Controller
{
    use Httpresponse;
    public function attributewithsubattributes($attributeId)
    {
        try {
            $att = CustomAttribute::find($attributeId);
            if (!$att) {
                return $this->response(false, 404, 'Attribute not found');
            }
            $attribute = CustomAttribute::with('subattributes')->find($attributeId);
            if ($attribute->subattributes->isEmpty()) {
                return $this->response(true, 200, 'Subattribute not found', $attribute);
            }

            return $this->response(true, 200, 'success', $attribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function addattribute(Addattributerequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (CustomAttribute::where('name', $validatedData['name'])->where('sub_category_id', $validatedData['sub_category_id'])->exists()) {
                return $this->response(false, 400, 'Attribute  already exists');
            }
            $attribute = CustomAttribute::create($validatedData);
            return $this->response(true, 201, 'Attribute created successfully', $attribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function updateattribute(Updateattributerequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $attribute = CustomAttribute::find($id);
            if (!$attribute) {
                return $this->response(false, 404, 'Attribute not found');
            }
            if (CustomAttribute::where('name', $validatedData['name'])->where('sub_category_id', $validatedData['sub_category_id'])->where('id', '!=', $id)->exists()) {
                return $this->response(false, 400, 'Attribute with this name already exists in this sub-category');
            }
            $attribute->update($validatedData);
            return $this->response(true, 200, 'Attribute updated successfully', $attribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function deleteattribute($id)
    {

        if (Cache::has('destroy_subcategory') || Cache::has('destroy_category' || Cache::has('destroy_modal' || Cache::has('destroy_submodal') || Cache::has('destroy_attribute')))) {
            return $this->response(false, 429, 'Another delete operation is in progress.');
        }

        Cache::put('destroy_attribute', 'delete_attribute', now()->addHours(1));
        try {
            if (!Gate::allows('admin')) {
                return $this->response(false, 401, 'Unauthorized', null);
            }
            return DB::transaction(function () use ($id) {
                $attribute = CustomAttribute::find($id);
                if (!$attribute) {
                    return $this->response(false, 404, 'Attribute not found');
                }

                $attribute->subattributes()->delete();
                DB::table('advertising_attribute')->where('attribute_id', $id)->delete();
                $attribute->delete();

                return $this->response(true, 200, 'Attribute deleted successfully');
            });
        } catch (\Throwable $th) {
            cache::forget('destroy_attribute');

            return $this->response(false, 500, $th->getMessage());
        }
    }


    public function addsubattribute(AddsubattributeRequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (Subattribute::where('name', $validatedData['name'])
                ->where('attribute_id', $validatedData['attribute_id'])
                ->exists()
            ) {
                return $this->response(false, 400, 'Subattribute already exists');
            }

            $subattribute = Subattribute::create($validatedData);
            return $this->response(true, 201, 'Subattribute created successfully', $subattribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function updatesubattribute(Updatesubattributerequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
           
           
            $subattribute = subattribute::find($id);
            if (!$subattribute) {
                return $this->response(false, 404, 'Subattribute not found');
            }
            if (subattribute::where('name', $validatedData['name'])->where('attribute_id', $validatedData['attribute_id'])->where('id', '!=', $id)->exists()) {
                return $this->response(false, 400, 'Subattribute with this name already exists for this attribute');
            }
            $subattribute->update($validatedData);
            return $this->response(true, 200, 'Subattribute updated successfully', $subattribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function deletesubattribute($id)
    {
        try {
            if (!Gate::allows('admin')) {
                return $this->response(false, 401, 'Unauthorized', null);
            }
            $subattribute = subattribute::find($id);
            if (!$subattribute) {
                return $this->response(false, 404, 'Subattribute not found');
            }
            $subattribute->delete();
            return $this->response(true, 200, 'Subattribute deleted successfully');
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }
}
