<?php

namespace App\Http\Controllers;

use App\Http\Requests\Advertising\Newadvertisingrequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertising;
use App\Enum\Role;
use App\Http\Requests\Advertising\Updateadvertising;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Request;

class AdvertisingController extends Controller
{
    use Httpresponse;
 


    public function store(Newadvertisingrequest $request)
    {


        if (
            Cache::has('destroy_subcategory') ||
            Cache::has('destroy_category') ||
            Cache::has('destroy_modal') ||
            Cache::has('destroy_submodal') ||
            Cache::has('destroy_attribute')
        ) {
            return $this->response(false, 429, 'We are updating our system, please try again later');
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
                // 1. Create Advertising
                $advertising = Advertising::create([
                    'user_id'           => $user->id,
                    'title'             => $data['title'],
                    'description'       => $data['description'] ?? null,
                    'price'             => $data['price'] ?? null,
                    'purpose'           => $data['purpose'],
                    'category_id'       => $data['category_id'],
                    'sub_category_id'   => $data['sub_category_id'],
                    'modal_id'          => $data['modal_id'] ?? null,
                    'submodal_id'       => $data['submodal_id'] ?? null,
                    'status'            => $data['status'] ?? 'active',
                ]);

        
                // 3. Handle Images
                $files = $data['images'] ?? [];
                $newImagesCount = count($files);
                $existingImagesCount = 0; // Since it's new advertising, this should be 0

                $maxImagesAllowed = $user->role === Role::Prouser
                    ? config('advertising.max_images_pro')
                    : config('advertising.max_images_free');

                if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
                    throw new Exception("You can upload max {$maxImagesAllowed} images per advertising.");
                }
                $savedFiles = [];

                if ($newImagesCount > 0) {
                    $manager = new ImageManager(new Driver());
                    $imageDirectory = storage_path('app/public/ad_images/');

                    if (!file_exists($imageDirectory)) {
                        mkdir($imageDirectory, 0755, true);
                    }

                    foreach ($files as $uploadedFile) {
                        $image = $manager->read($uploadedFile);
                        $image->resize(800, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $extension = $uploadedFile->getClientOriginalExtension();
                        $filename = Str::uuid() . '.' . $extension;
                        $relativePath = 'ad_images/' . $filename;
                        $savePath = storage_path('app/public/' . $relativePath);

                        $image->toJpeg(80)->save($savePath);
                        $savedFiles[] = $savePath;
                        $advertising->images()->create([
                            'name'           => $uploadedFile->getClientOriginalName(),
                            'url'            => asset('storage/ad_images/' . $filename),
                            'path'           => $relativePath,
                            'advertising_id' => $advertising->id,
                        ]);
                    }
                }

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
                return $this->response(true, 201, 'Advertising created successfully', [
                    'advertising' => $advertising->load('categoryattributes', 'images')
                   
                ]);
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
            return $this->response(false, 500, 'Error creating advertising: ' . $e->getMessage());
        }
    }

public function updateadvertising( Updateadvertising $request,$id){

    $advertisingData = $request->validated();

      if (
            Cache::has('destroy_subcategory') ||
            Cache::has('destroy_category') ||
            Cache::has('destroy_modal') ||
            Cache::has('destroy_submodal') ||
            Cache::has('destroy_attribute')
        ) {
            return $this->response(false, 429, 'We are updating our system, please try again later');
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

                $advertising->update(    $advertisingData);

                

                // 3. Handle Images
                $files = $advertisingData['images'] ?? [];
                $newImagesCount = count($files);
                $existingImagesCount = 0; // Since it's new advertising, this should be 0

                $maxImagesAllowed = $user->role === Role::Prouser
                    ? config('advertising.max_images_pro')
                    : config('advertising.max_images_free');

                if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
                    throw new Exception("You can upload max {$maxImagesAllowed} images per advertising.");
                }
                $savedFiles = [];

                if ($newImagesCount > 0) {
                    $manager = new ImageManager(new Driver());
                    $imageDirectory = storage_path('app/public/ad_images/');

                    if (!file_exists($imageDirectory)) {
                        mkdir($imageDirectory, 0755, true);
                    }

                    foreach ($files as $uploadedFile) {
                        $image = $manager->read($uploadedFile);
                        $image->resize(800, 600, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        $extension = $uploadedFile->getClientOriginalExtension();
                        $filename = Str::uuid() . '.' . $extension;
                        $relativePath = 'ad_images/' . $filename;
                        $savePath = storage_path('app/public/' . $relativePath);

                        $image->toJpeg(80)->save($savePath);
                        $savedFiles[] = $savePath;
                        $advertising->images()->create([
                            'name'           => $uploadedFile->getClientOriginalName(),
                            'url'            => asset('storage/ad_images/' . $filename),
                            'path'           => $relativePath,
                            'advertising_id' => $advertising->id,
                        ]);
                    }
                }

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

    // DB::table('advertising_categoryattribute')->update($bulkInsertData);
    DB::table('advertising_categoryattribute')
    ->upsert($bulkInsertData, ['advertising_id', 'category_attribute_id'], ['value', 'updated_at']);

   

                DB::commit();
                return $this->response(true, 201, 'Advertising update successfully', [
                    'advertising' => $advertising->load('categoryattributes', 'images')
                   
                ]);
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
            return $this->response(false, 500, 'Error creating advertising: ' . $e->getMessage());
        }
      
}

public function deleteadvertising(Request $request,$id){

    $user = Auth::user();
   

    if (!$user) {
        return $this->response(false, 401, 'Unauthorized');
    }

    $advertising = Advertising::find($id);

    if (!$advertising || $advertising->user_id !== $user->id) {
        return $this->response(false, 403, 'Forbidden');
    }

    try {
        // Delete the advertising record from the database
       
        // Delete associated images from storage
      
         foreach ($advertising->images as $image) {
            $imagePath = storage_path('app/public/' . $image->path);
            if (file_exists($imagePath)) {   
                unlink($imagePath);
            }
        }

         $advertising->delete();
        $advertising->images()->delete();

        DB::table('advertising_categoryattribute')->where('advertising_id', $id)->delete();

        return $this->response(true, 200, 'Advertising deleted successfully');
    } catch (Exception $e) {
        return $this->response(false, 500, 'Error deleting advertising: ' . $e->getMessage());
    }
}
}