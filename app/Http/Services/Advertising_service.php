<?php

namespace App\Http\Services;

use App\Http\Requests\Advertising\Newadvertisingrequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertising;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Requests\Advertising\Updateadvertising;
use Illuminate\Http\Request;
use App\Http\Resources\AdvertisingResource;
use App\Helper\SystemHelper;

class Advertising_service
{
     use Httpresponse;
     public function createNewAdvertising(Newadvertisingrequest $request, Image_service $image_service)
     {

         
          $response = SystemHelper::systemUpdatingResponse();
          if ($response) {
               return $response;
          }

          try {
               $data = $request->validated();
               $user = Auth::user();

               if (!$user) {
                    throw new Exception('Unauthorized', 401);
               }

               $advertising = null;

               DB::beginTransaction();

               try {

                    $fieldsMap =  __('attributes');
                    $translated = [];
                    foreach ($data as $key => $value) {
                         $englishKey = $fieldsMap[$key] ?? $key;
                         $translated[$englishKey] = $value;
                    }

                    // 1. Create Advertising
                    $advertising = Advertising::create([
                         'user_id'           => $user->id,
                         'title'             => $data['title'] ?? $data['العنوان'],
                         'description'       => $data['description'] ?? null,
                         'price'             => $data['price'] ?? null,
                         'purpose'           => $data['purpose'],
                         'category_id'       => $data['category_id'],
                         'sub_category_id'   => $data['sub_category_id'],
                         'modal_id'          => $data['modal_id'] ?? null,
                         'submodal_id'       => $data['submodal_id'] ?? null,
                         'status'            => $data['status'] ?? 'active',
                         // $translated
                    ]);


                    $image_service->storeImages($advertising, $user, $data['images'], $request);

                    $bulkInsertData = [];
                    $now = now();
                    // Save the attributes
                    foreach ($data['categoryattributes'] ?? [] as $attribute) {
                         $bulkInsertData[] = [
                              'advertising_id' => $advertising->id,
                              'category_attribute_id' => $attribute['attribute_id'],
                              'value' => $attribute['value'],
                              'created_at' => $now,
                              'updated_at' => $now,
                         ];
                    }

                    DB::table('advertising_categoryattribute')->insert($bulkInsertData);


                    DB::commit();
                
                    return new AdvertisingResource($advertising->load('categoryattributes', 'images'));
               } catch (Exception $e) {
                    DB::rollBack();
                    if (!empty($savedFiles)) {
                         foreach ($savedFiles as $file) {
                              if (file_exists($file)) {
                                   unlink($file);
                              }
                         }
                    }
                    throw $e;
               }
          } catch (Exception $e) {
               return $this->response(false, 500, __('message.failure'), [
                    'error' => $e->getMessage()
               ]);
          }
     }

     public function updateadvertising(Updateadvertising $request, $id, Image_service $image_service)
     {
          $advertisingData = $request->validated();

          $response = SystemHelper::systemUpdatingResponse();
          if ($response) {
               return $response;
          }

          try {
               $user = Auth::user();

               if (!$user) {
                    throw new Exception('Unauthorized', 401);
               }

               $advertising = null;

               DB::beginTransaction();

               try {
                    $advertising = Advertising::find($id);
                    if (!$advertising) {
                         throw new Exception('Advertising not found', 404);
                    }

                    $advertising->update([
                         'title'           => $advertisingData['title'] ?? $advertising->title,
                         'description'     => $advertisingData['description'] ?? $advertising->description,
                         'price'           => $advertisingData['price'] ?? $advertising->price,
                         'purpose'         => $advertisingData['purpose'] ?? $advertising->purpose,
                         'category_id'     => $advertisingData['category_id'] ?? $advertising->category_id,
                         'sub_category_id' => $advertisingData['sub_category_id'] ?? $advertising->sub_category_id,
                         'modal_id'        => $advertisingData['modal_id'] ?? $advertising->modal_id,
                         'submodal_id'     => $advertisingData['submodal_id'] ?? $advertising->submodal_id,
                         'status'          => $advertisingData['status'] ?? $advertising->status,
                    ]);



                    $savedFiles = $image_service->updateimage($advertising, $user, $advertisingData['images'] ?? []);

                    $bulkInsertData = [];
                    $now = now();
                    // Save the attributes
                    foreach ($advertisingData['categoryattributes'] ?? [] as $attribute) {
                         $bulkInsertData[] = [
                              'advertising_id' => $advertising->id,
                              'category_attribute_id' => $attribute['attribute_id'],
                              'value' => $attribute['value'],
                              'created_at' => $now,
                              'updated_at' => $now,
                         ];
                    }

                

                    DB::table('advertising_categoryattribute')->where('advertising_id', $advertising->id)->delete();
                    // ثم إضافة الجديدة
                    DB::table('advertising_categoryattribute')->insert($bulkInsertData);
                    DB::commit();
             
                    return new AdvertisingResource($advertising->load('categoryattributes', 'images'));
               } catch (Exception $e) {
                    DB::rollBack();
                    if (!empty($savedFiles)) {
                         foreach ($savedFiles as $file) {
                              if (file_exists($file)) {
                                   unlink($file);
                              }
                         }
                    }
                    throw $e;
               }
          } catch (Exception $e) {
               return $this->response(false, 500, __('message.error_occurred') . $e->getMessage());
          }
     }

     public function deleteadvertising(Request $request, $id)
     {
          $user = Auth::user();


          if (!$user) {
               return $this->response(false, 401, __('message.unauthorized'));
          }

          $advertising = Advertising::find($id);

          if (!$advertising || $advertising->user_id !== $user->id) {
               return $this->response(false, 403, __('message.forbidden'));
          }

          try {
          

               foreach ($advertising->images as $image) {
                    $imagePath = storage_path('app/public/' . $image->path);
                    if (file_exists($imagePath)) {
                         unlink($imagePath);
                    }
               }

               $advertising->images()->delete();
               DB::table('advertising_categoryattribute')->where('advertising_id', $id)->delete();
               $advertising->delete();
               return $this->response(true, 200, __('message.deleted_success'));
          } catch (Exception $e) {
               return $this->response(false, 500, __('message.error_occurred') . $e->getMessage());
          }
     }

     public function getUserAdvertisings(Request $request)
     {
          $user = Auth::user();

          if (!$user) {
               return $this->response(false, 401, __('message.unauthorized'));
          }

          $advertisings = Advertising::with('images', 'categoryattributes')
               ->where('user_id', $user->id)
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }

     public function getcategoryAdvertisings(Request $request, $categoryId)
     {
          $advertisings = Advertising::with('images', 'category')
               ->where('category_id', $categoryId)
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }

     public function getsubcategoryAdvertisings(Request $request, $subcategoryId)
     {
          $advertisings = Advertising::with('images', 'subCategory')
               ->where('sub_category_id', $subcategoryId)
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }

     public function getAllAdvertisings(Request $request)
     {
          $advertisings = Advertising::with('images', 'category', 'subCategory', 'modal', 'submodal')
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }

     public function getadvertisingDetails(Request $request, $id)
     {
          $advertising = Advertising::with('images', 'categoryattributes', 'category', 'subCategory', 'modal', 'submodal', 'location', 'user')
               ->where('id', $id)
               ->first();

          if (!$advertising) {
               return $this->response(false, 404, __('message.not_found'));
          }

          return $this->response(true, 200, __('message.success'), [
               'advertising' => $advertising,
          ]);
     }

     public function getmodaladvertisings(Request $request, $modalId)
     {
          $advertisings = Advertising::with('images', 'modal')
               ->where('modal_id', $modalId)
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }

     public function getsubmodaladvertisings(Request $request, $submodalId)
     {
          $advertisings = Advertising::with('images', 'submodal')
               ->where('submodal_id', $submodalId)
               ->orderBy('created_at', 'desc')
               ->paginate(10);

          return $this->response(true, 200, __('message.success'), [
               'advertisings' => $advertisings,
          ]);
     }
}
