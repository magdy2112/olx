<?php

namespace App\Jobs;

use App\Models\Modal;
use App\Models\Submodal;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class Homescreen implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    public $userid;
    public function __construct($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $modals = Modal::with([
            'subCategory.category.attributes' => function ($query) {
                $query->select('attributes.id', 'attributes.name');
            },
            'subCategory.category' => function ($query) {
                $query->select('id', 'name');
            },
            'subCategory' => function ($query) {
                $query->select('id', 'category_id');
            }
        ])->orderBy('created_at', 'desc')
            ->whereDoesntHave('submodals')
            ->skip(100)
            ->lazy();

        $modalformatted = $modals->map(function ($modal) {
            return [
                'id' => $modal->id,
                'name' => $modal->name,
                'category' => [
                    // 'id' => optional($modal->subCategory->category)->id,
                    'name' => optional($modal->subCategory->category)->name,
                    'attributes' => optional($modal->subCategory->category->attributes)->map(function ($attr) {
                        return [
                            // 'id' => $attr->id,
                            'name' => $attr->name,
                        ];
                    }),
                ]
            ];
        });

        $submodals = Submodal::with([
            'modal' => function ($query) {
                $query->select('id', 'name', 'sub_category_id');
            },
            'modal.subCategory' => function ($query) {
                $query->select('id', 'category_id');
            },
            'modal.subCategory.category' => function ($query) {
                $query->select('id', 'name');
            },
            'modal.subCategory.category.attributes' => function ($query) {
                $query->select('attributes.id', 'attributes.name');
            },
        ])->orderBy('created_at', 'desc')
            ->skip(100)->lazy();


        $formatsub =  $submodals->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'modal' => $item->modal->name,
                'category' => $item->modal->subCategory->category->name,
                'attribute' => $item->modal->subCategory->category->attributes->map(function ($att) {
                    return [
                        'name' => $att->name
                    ];
                })
            ];
        });



        $mergejob  = collect($formatsub)->merge($modalformatted);
        $keyjob = 'homescreen_user_' . $this->userid;

        Cache::put($keyjob,  $mergejob, now()->addMinutes(60));
    }
}
