<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class Allsubmodal implements ShouldQueue
{
    use Queueable;
       
    /**
     * Create a new job instance.
     */
 
    protected int $userId;

    public function __construct( $userId)
    {
     
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
           $categories = Category::with([
            'subcategories.modals.submodals:id,name,modal_id', 
            'subcategories.modals:id,name,sub_category_id',
            'subcategories:id,name,category_id'
        ])->get(); 

   
        $formatted = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'subcategories' => $category->subcategories->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'modals' => $subcategory->modals->map(function ($modal) {
                            return [
                                'id' => $modal->id,
                                'name' => $modal->name,
                                'submodals' => $modal->submodals->map(function ($submodal) {
                                    return [
                                        'id' => $submodal->id,
                                        'name' => $submodal->name,
                                    ];
                                }),
                            ];
                        }),
                    ];
                }),
            ];
        });

       
        Cache::forever('allsubmodal_cache', $formatted);
        } catch (\Throwable $e) {
             Log::error('DeleteCategory job error: ' . $e->getMessage());
            throw $e; // لتمرير الخطأ ل failed()
        }finally{
            Cache::forget('allsubmodal_cache');
        }
         
    }

    public function failed(\Throwable $exception)
    {
        Cache::forget('allsubmodal_cache');

        $user = User::find($this->userId);
        if ($user) {
            Mail::raw("حدث خطا اثناء جلب البيانات " . $exception->getMessage(), function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('  خطا اثناء عرض submodals  ');
            });
        }
    }
    }

