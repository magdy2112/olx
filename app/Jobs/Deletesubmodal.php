<?php

namespace App\Jobs;


use Illuminate\Foundation\Queue\Queueable;
use App\Models\Advertising;
use App\Models\SubCategory;
use App\Models\Submodal;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class Deletesubmodal implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $tries = 2;
    protected int $submodalId;
    protected int $userId;

    public function __construct($submodalId, $userId)
    {
        $this->submodalId = $submodalId;
        $this->userId = $userId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
         // مصفوفة لتخزين كل مسارات الصور اللي هتحذف بعد نجاح الـ Transaction
        $pathsToDelete = [];

        try {
            DB::transaction(function () use (&$pathsToDelete) {
                $submodal = Submodal::find($this->submodalId);
                if (!$submodal) {
                    Log::info("Delete SubCategory Job: Submodal {$this->submodalId} not found");
                    return;
                }

                // حذف كل الإعلانات المرتبطة
                Advertising::where('submodal_id', $submodal->id)
                    ->chunkById(500, function ($ads) use (&$pathsToDelete) {
                        foreach ($ads as $ad) {
                            // سجل مسارات الصور فقط
                            foreach ($ad->images as $image) {
                                $pathsToDelete[] = $image->path;
                            }

                            // احذف من قاعدة البيانات
                            $ad->images()->delete();
                            $ad->delete();
                        }
                    });

             $submodal->delete();
            });

            // لو وصلنا هنا، الـ Transaction نجحت → احذف الصور فعليًا
            foreach ($pathsToDelete as $path) {
                Storage::disk('public')->delete($path);
            }

            // إشعار نجاح بالإيميل
            if ($user = User::find($this->userId)) {
                Mail::raw(
                    "تم حذف التصنيف {$this->submodalId} بنجاح مع جميع الإعلانات والصور.",
                    fn($message) => $message->to($user->email)->subject('تم حذف التصنيف بنجاح')
                );
            }

        } catch (\Throwable $e) {
            Log::error('Delete Submodal job error: ' . $e->getMessage());
            throw $e; // هيوصل لـ failed()
        } finally {
            Cache::forget('destroy_submodal'); // فك القفل في كل الحالات
        }
    }

     public function failed(\Throwable $exception)
    {
        Cache::forget('destroy_subcategory');

        if ($user = User::find($this->userId)) {
            Mail::raw(
                "حدث خطأ أثناء حذف التصنيف {$this->submodalId}: {$exception->getMessage()}",
                fn($message) => $message->to($user->email)->subject('فشل حذف التصنيف')
            );
        }
    }
}
