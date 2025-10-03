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
