<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Traits\Httpresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use Httpresponse;


    /**
     *
     */
    public function allcategory()
    {
        try {
            $allcategory = Category::all();
            return $this->response(true, 200, 'success', $allcategory);
        } catch (\Throwable $e) {
            return $this->response(false, 500, $e->getMessage());
        }
    }

    public function addcategory(Request $request) {}
}
