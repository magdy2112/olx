<?php

namespace App\Http\Controllers;

use App\Models\Advertising;
use App\Models\image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Traits\Httpresponse;
use Illuminate\Support\Facades\Storage;
class ImageController extends Controller
{
      use Httpresponse;
    // public function store(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         if (!$user) {
    //             return response()->json(['error' => 'Unauthorized'], 401);
    //         }

    //         $request->validate([
    //             'advertising_id' => 'required|exists:advertisings,id',
    //             'images' => 'required',
    //             'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120', // 5MB
    //         ]);

    //         $advertising = Advertising::findOrFail($request->advertising_id);

    //         if ($advertising->user_id !== $user->id) {
    //             return response()->json(['error' => 'Unauthorized'], 403);
    //         }

    //         $existingImagesCount = $advertising->images()->count();
    //         $files = is_array($request->file('images')) ? $request->file('images') : [$request->file('images')];
    //         $newImagesCount = count($files);

    //         $maxImagesAllowed = $user->role === 'prouser' ? 8 : 5;

    //         if (($existingImagesCount + $newImagesCount) > $maxImagesAllowed) {
    //             return response()->json([
    //                 'error' => "You can upload max {$maxImagesAllowed} images per advertising. You already have {$existingImagesCount} images."
    //             ], 422);
    //         }

    //         $manager = new ImageManager(new Driver());
    //         $uploadedImages = [];

    //         foreach ($files as $uploadedFile) {
    //             $image = $manager->read($uploadedFile);

    //             $image->resize(800, 600, function ($constraint) {
    //                 $constraint->aspectRatio();
    //                 $constraint->upsize();
    //             });

    //             $extension = $uploadedFile->getClientOriginalExtension();
    //             $filename = Str::uuid() . '.' . $extension;
    //             $relativePath = 'ad_images/' . $filename;
    //             $savePath = storage_path('app/public/' . $relativePath);

    //             if (!file_exists(dirname($savePath))) {
    //                 mkdir(dirname($savePath), 0777, true);
    //             }

    //             $image->toJpeg(80)->save($savePath);

    //             $img = $advertising->images()->create([
    //                 'name' => $uploadedFile->getClientOriginalName(),
    //                 'url' => asset('storage/' . $relativePath),
    //                 'path' => $relativePath,
    //                 'advertising_id' => $advertising->id,
    //             ]);

    //             $uploadedImages[] = $img;
    //         }

    //        return $this->response(true, 200, 'Images uploaded successfully', [
    //            'images' => $uploadedImages,
    //        ]);
    //    } catch (\Illuminate\Validation\ValidationException $e) {
    //        return $this->response(false, 422, $e->getMessage(), [
    //            'errors' => $e->errors()
    //        ]);
    //    } catch (\Exception $e) {
    //        return $this->response(false, 500, 'Image upload failed', [
    //            'message' => $e->getMessage()
    //        ]);
    //    }
    // }


       public function destroy($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $image = image::find($id);
            if (!$image) {
                return response()->json(['error' => 'Image not found'], 404);
            }

            // تحقق أن المستخدم صاحب الإعلان اللي الصورة تخصه
            if ($image->advertising->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // حذف الملف من التخزين
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            // حذف السجل من قاعدة البيانات
            $image->delete();
            return $this->response(true, 200, 'Image deleted successfully');
        } catch (\Exception $e) {
            return $this->response(false, 500, 'Image deletion failed', [
                'message' => $e->getMessage()
            ]);
        }
    }

        
    }
