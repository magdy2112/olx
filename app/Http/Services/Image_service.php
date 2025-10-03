<?php

namespace App\Http\Services;
use App\Http\Requests\Advertising\Newadvertisingrequest;
use App\Enum\Role;
use App\Models\Advertising;
use App\Models\User;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Auth;
use Exception;
class Image_service
{
    public function storeImages(Advertising $advertising, User $user, array $files, Newadvertisingrequest $request): array
    {

     $data = $request->validated('images')?? [];
     if(empty($data)) {
        return [];
     }

     // عدد الصور اللي عند الإعلان الحالي
     $existingImagesCount = $advertising->images()->count();
     $newImagesCount = count($files);

        // الحد الأقصى حسب نوع المستخدم
       $maxImagesAllowed = $user->role === Role::Prouser
    ? config('advertising.max_images_pro')
    : config('advertising.max_images_free');

        if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
            throw new Exception("You can upload max {$maxImagesAllowed} images for this advertising.");
        }

        $savedFiles = [];
        $manager = new ImageManager(new Driver());
        $imageDirectory = storage_path('app/public/ad_images/');

        if (!file_exists($imageDirectory)) {
            mkdir($imageDirectory, 0755, true);
        }

        foreach ($files as $uploadedFile) {
            // Resize
            $image = $manager->read($uploadedFile);
            $image->resize(800, 600, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Generate unique filename
            $extension = $uploadedFile->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $relativePath = 'ad_images/' . $filename;
            $savePath = storage_path('app/public/' . $relativePath);

            // Save to disk
            $image->toJpeg(80)->save($savePath);
            $savedFiles[] = $savePath;

            // Save in DB relation
            $advertising->images()->create([
                'name'           => $uploadedFile->getClientOriginalName(),
                'url'            => asset('storage/ad_images/' . $filename),
                'path'           => $relativePath,
                'advertising_id' => $advertising->id,
            ]);
        }

        return $savedFiles;
    }

    public function updateimage( Advertising $advertising, User $user, array $advertisingData): array{
                $files = $advertisingData['images'] ?? [];
                $newImagesCount = count($files);
           $existingImagesCount = $advertising->images()->count();

                $maxImagesAllowed = $user->role === Role::Prouser
                    ? config('advertising.max_images_pro')
                    : config('advertising.max_images_free');

                if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
                    throw new Exception("You can upload max {$maxImagesAllowed} images per advertising.");
                }
                $savedFiles = [];

                if ($newImagesCount === 0) {
            return $savedFiles; 
          }

               
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
               
                return $savedFiles;
    }
}