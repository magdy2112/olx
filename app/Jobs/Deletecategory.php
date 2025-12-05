<?php

namespace App\Jobs;

use App\Models\Advertising;
use App\Models\Category;
use App\Models\Modal;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Submodal;
use App\Models\CategoryAttribute;





class Deletecategory implements ShouldQueue
{

    use Queueable;

    public $tries = 2;

    /**
     * Create a new job instance.
     */

    protected int $categoryId;
    protected int $userId;

    public function __construct($categoryId, $userId)
    {
        $this->categoryId = $categoryId;
        $this->userId = $userId;
    }





    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pathsToDelete = [];
        $category = Category::find($this->categoryId);
        if (!$category) {
            Log::channel('category')->info("DeleteCategory Job: Category {$this->categoryId} not found");
            return;
        }


        try {
            DB::transaction(function () use (&$pathsToDelete, $category) {

                $subcategory_ids_belongsto_category = SubCategory::where('category_id', $this->categoryId)->pluck('id');
                //[3/4/5/6]
                $modal_ids_belongsto_subcategory = Modal::whereIn('sub_category_id',       $subcategory_ids_belongsto_category)->pluck('id');
                //[14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31]               
                $submodal_ids = Submodal::whereIn('modal_id',      $modal_ids_belongsto_subcategory)->pluck('id');

                 
                    
                   
                    if ($submodal_ids->count() > 0) {
                    Submodal::whereIn('id',  $submodal_ids)->delete();
                }


                if ($modal_ids_belongsto_subcategory->count() > 0) {

                    Modal::whereIn('id',     $modal_ids_belongsto_subcategory)->delete();
                }

                if ($subcategory_ids_belongsto_category->count() > 0) {
                    SubCategory::whereIn('id',    $subcategory_ids_belongsto_category)->delete();;   //Eloquent Query Builder
                }
         
                Advertising::where('category_id', $category->id)
                    ->chunkById(500, function ($ads) use (&$pathsToDelete) {
                        foreach ($ads as $ad) {
                            foreach ($ad->images as $image) {
                                $pathsToDelete[] = $image->path;
                            }
                        }


                        foreach ($ads as $ad) {
                            $ad->images()->delete();
                            $ad->delete();
                        }
                    });

               CategoryAttribute::whereIn('sub_category_id', $subcategory_ids_belongsto_category)->delete();

             
                  $category->delete();
              
                foreach ($pathsToDelete as $path) {
                    Storage::disk('public')->delete($path);
                }

                // إشعار نجاح بالإيميل
                if ($user = User::find($this->userId)) {
                    Mail::raw(
                        "تم حذف التصنيف {$this->categoryId} بنجاح مع جميع الإعلانات والصور.",
                        fn($message) => $message->to($user->email)->subject('تم حذف التصنيف بنجاح')
                    );
                }
            });
        } catch (\Exception $e) {
            log::channel('category')->error('Error in Deletecategory Job: ' . $e->getMessage());
            throw $e; // لإعادة محاولة المهمة
        } finally {
            Cache::forget('destroy_category');
        }
    }




    public function failed(\Throwable $exception)
    {
        Cache::forget('destroy_category');

        $user = User::find($this->userId);
        if ($user) {
            Mail::raw("حدث خطأ أثناء حذف التصنيف {$this->categoryId}. راجع عمليه الحذف : " . $exception->getMessage(), function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('فشل حذف التصنيف');
            });
        }
    }
}
