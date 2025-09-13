<?php

namespace App\Http\Controllers;
use App\Http\Requests\Advertising\Newadvertisingrequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Advertising;
use App\Models\CategoryAttribute;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\DB;
use Exception;

// use Illuminate\Support\Facades\Request;

class AdvertisingController extends Controller
{
    use Httpresponse;
        const MAX_IMAGES_FREE = 5;
    const MAX_IMAGES_PRO = 8;


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

         if (isset($data['attributes']) && is_array($data['attributes'])) {
    $attachData = [];
foreach ($data['attributes'] as $key => $value) {
    $categoryAttribute = CategoryAttribute::where('sub_category_id', $data['sub_category_id'])
                                          ->where('name', $key)
                                          ->first();
    if ($categoryAttribute && !empty($value)) {
        $attachData[$categoryAttribute->id] = ['value' => $value];
    }
}
$advertising->categoryattributes()->sync($attachData);
}

                // 3. Handle Images
                $files = $data['images'] ?? [];
                $newImagesCount = count($files);
                $existingImagesCount = 0; // Since it's new advertising, this should be 0

                $maxImagesAllowed = $user->role === 'proser' 
                    ? self::MAX_IMAGES_PRO 
                    : self::MAX_IMAGES_FREE;

                if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
                    throw new Exception("You can upload max {$maxImagesAllowed} images per advertising.");
                }
                $savedFiles = [];

                if ($newImagesCount > 0) {
                    $manager = new ImageManager(new Driver());
                    $imageDirectory = storage_path('app/public/ad_images/');
                    
                    if (!file_exists($imageDirectory)) {
                        mkdir($imageDirectory, 0777, true);
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

            // return $this->response(true, 201, 'Advertising created successfully', [
            //     'advertising' => $advertising->load('attributes', 'images')
            // ]);
        } catch (Exception $e) {
            return $this->response(false, 500, 'Error creating advertising: ' . $e->getMessage());
        }
    }
}



