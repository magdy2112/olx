<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attributes\Addattributerequest;
use App\Models\subattribute;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Attributes\Updateattributerequest;
use App\Http\Requests\Attributes\AddsubattributeRequest;
use App\Http\Requests\Attributes\Updatesubattributerequest;
use App\Models\CategoryAttribute;
use App\Models\CustomAttribute;
use App\Models\SubCategory;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


class AttributesController extends Controller
{
    use Httpresponse;

    public function addattribute(Addattributerequest $request)
    {
        try {
            $validatedData = $request->validated();
            if (CategoryAttribute::where('name', $validatedData['name'])->where('sub_category_id', $validatedData['sub_category_id'])->exists()) {
                return $this->response(false, 400, 'Attribute  already exists');
            }
            $attribute = CategoryAttribute::create($validatedData);
            return $this->response(true, 201, 'Attribute created successfully', $attribute);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

    public function updateattribute(Updateattributerequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $attribute = CategoryAttribute::find($id);
            if (!$attribute) {
                return $this->response(false, 404, 'Attribute not found');
            }
            if (CategoryAttribute::where('name', $validatedData['name'])->where('sub_category_id', $validatedData['sub_category_id'])->where('id', '!=', $id)->exists()) {
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
                $attribute = CategoryAttribute::find($id);
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



    public function getAttributes(Request $request)
    {
        try {
            $request->validate([
                'sub_category_id' => 'required|exists:sub_categories,id',
            ]);

            $subCategory = SubCategory::with('attributes')
                ->where('id', $request->sub_category_id)
                ->first();

            if (!$subCategory) {
                return $this->response(false, 404, 'SubCategory not found');
            }

            $attributes = $subCategory->attributes->map(function ($attribute) {
                return [
                    'id'   => $attribute->id,
                    'name' => $attribute->name,
                ];
            });

            return $this->response(true, 200, 'Attributes fetched successfully', [
                'sub_category' => $subCategory->name,
                'attributes' => $attributes
            ]);
        } catch (\Throwable $th) {
            return $this->response(false, 500, $th->getMessage());
        }
    }

}
