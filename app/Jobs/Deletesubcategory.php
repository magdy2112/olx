<?php

namespace App\Jobs;

use App\Models\Advertising;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DeleteSubcategory implements ShouldQueue
{
    use Queueable;

    public $tries = 2;
    protected int $subcategoryId;
    protected int $userId;

    public function __construct($subcategoryId, $userId)
    {
        $this->subcategoryId = $subcategoryId;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        // مصفوفة لتخزين كل مسارات الصور اللي هتحذف بعد نجاح الـ Transaction
        $pathsToDelete = [];

        try {
            DB::transaction(function () use (&$pathsToDelete) {
                $subcategory = SubCategory::find($this->subcategoryId);
                if (!$subcategory) {
                    Log::info("Delete SubCategory Job: SubCategory {$this->subcategoryId} not found");
                    return;
                }

                // حذف كل الإعلانات المرتبطة
                Advertising::where('sub_category_id', $subcategory->id)
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

                // حذف العلاقات الفرعية
                $subcategory->modals()->delete();
                $subcategory->submodals()->delete();
                $subcategory->delete();
            });

            // لو وصلنا هنا، الـ Transaction نجحت → احذف الصور فعليًا
            foreach ($pathsToDelete as $path) {
                Storage::disk('public')->delete($path);
            }

            // إشعار نجاح بالإيميل
            if ($user = User::find($this->userId)) {
                Mail::raw(
                    "تم حذف التصنيف {$this->subcategoryId} بنجاح مع جميع الإعلانات والصور.",
                    fn($message) => $message->to($user->email)->subject('تم حذف التصنيف بنجاح')
                );
            }

        } catch (\Throwable $e) {
            Log::error('Delete SubCategory job error: ' . $e->getMessage());
            throw $e; // هيوصل لـ failed()
        } finally {
            Cache::forget('destroy_subcategory'); // فك القفل في كل الحالات
        }
    }

    public function failed(\Throwable $exception)
    {
        Cache::forget('destroy_subcategory');

        if ($user = User::find($this->userId)) {
            Mail::raw(
                "حدث خطأ أثناء حذف التصنيف {$this->subcategoryId}: {$exception->getMessage()}",
                fn($message) => $message->to($user->email)->subject('فشل حذف التصنيف')
            );
        }
    }
}
