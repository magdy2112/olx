<?php

namespace App\Jobs;

use App\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class Allsubmodal implements ShouldQueue
{
    use Queueable;
       
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
          $categories = Category::with([
            'subcategories.modals.submodals:id,name,modal_id', 
            'subcategories.modals:id,name,sub_category_id',
            'subcategories:id,name,category_id'
        ])->cursor(); 

   
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
    }
    }

