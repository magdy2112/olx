<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Traits\Httpresponse;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    use Httpresponse;
    public function allgovernorates()
    {

     $governorates =   cache()->remember('all_governorates', now()->addYears(1), function () { 
            return Governorate::all();
        });
      
        return $this->response(true, 200,'all governorates', $governorates);
    }
    public function showgovernorate($id)
    {
        $governorate = Governorate::find($id);
        if (!$governorate) {
            return $this->response(false, 404, 'governorate not found', null);
        }
        return $this->response(true, 200,'governorate found', $governorate);
    }

    public function createGovernorate(Request $request)
    {
        $request->validate([
            'city' => 'required|string|unique:governorates,name',
            'country' => 'required|string'
        ]);

        $governorate = Governorate::create([
            'name' => $request->city,
            'country' => $request->country
        ]);

        return $this->response(true, 201, 'Governorate created successfully', $governorate);
    }

    public function deleteGovernorate($id)
    {
        $governorate = Governorate::find($id);
        if (!$governorate) {
            return $this->response(false, 404, 'Governorate not found', null);
        }

        $governorate->delete();
        return $this->response(true, 200, 'Governorate deleted successfully', null);
    }

    
}
