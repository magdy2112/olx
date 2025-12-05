<?php

namespace App\Helper;

use Illuminate\Support\Facades\Cache;

class Systemupdate
{
    use \App\Traits\HttpResponse;
    public static function ensureSystemIsFree()
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
                return abort(response()->json([
                    'success' => false,
                    'status' => 429,            
                    'message' => __('message.system_updating'),
                    'data' => []
                ], 429));
            }
        }

       
    }
}

