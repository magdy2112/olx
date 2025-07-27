<?php

namespace App\Jobs;

use App\Models\Advertising;
use App\Models\Category;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Exception;




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


         try {
            DB::transaction(function () {
                $category = Category::find($this->categoryId);

                if (!$category) {
                    Log::info("DeleteCategory Job: Category {$this->categoryId} not found");
                    return;
                }

                foreach ($category->modals as $modal) {
                    $modal->submodals()->delete();
                }

                Advertising::where('category_id', $category->id)
                    ->chunkById(500, function ($ads) {
                        foreach ($ads as $ad) {
                            $ad->images()->delete();
                            $ad->delete();
                        }
                    });

                $category->modals()->delete();
                $category->subCategories()->delete();
                $category->attributes()->detach();
                $category->delete();
            });
        } catch (\Throwable $e) {
            Log::error('DeleteCategory job error: ' . $e->getMessage());
            throw $e; // لتمرير الخطأ ل failed()
        } finally {
            Cache::forget('destroy_category');
        }
    }

 

public function failed(\Throwable $exception)
{
    Cache::forget('destroy_category');

    $user = User::find($this->userId);
    if ($user) {
        Mail::raw("حدث خطأ أثناء حذف التصنيف {$this->categoryId}. رسالة الخطأ: " . $exception->getMessage(), function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('فشل حذف التصنيف');
        });
    }
}
}
