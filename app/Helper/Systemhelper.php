<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;

class SystemHelper
{
    public static function systemUpdatingResponse()
    {
        $cacheKeys = [
            'destroy_subcategory',
            'destroy_category',
            'destroy_modal',
            'destroy_submodal',
            'destroy_attribute',
        ];

        foreach ($cacheKeys as $key) {
            if (Cache::has($key)) {
                return response()->json([
                    'success' => false,
                    'status' => 429,
                  
                    'message' => __('message.system_updating'),
                    'data' => []
                ]);
            }
        }

        return null;
    }
}

