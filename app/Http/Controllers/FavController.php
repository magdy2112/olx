<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavController extends Controller
{
    public function toggleFav(Request $request)
    {
        $request->validate([
            'advertising_id' => 'required|exists:advertisings,id',
        ]);

        $user = Auth::user();
        $advertisingId = $request->input('advertising_id');

        // Check if the favorite already exists
        $fav = $user->favs()->where('advertising_id', $advertisingId)->first();

        if ($fav) {
            // If it exists, remove it (unfavorite)
            $fav->delete();
            return response()->json(['message' => 'Advertising removed from favorites'], 200);
        } else {
            // If it doesn't exist, add it to favorites
            $user->favs()->create(['advertising_id' => $advertisingId]);
            return response()->json(['message' => 'Advertising added to favorites'], 201);
        }
    }


    public function listFavs($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $favs = $user->favs()->with('advertising.categoryattributes.images')->get();

        return response()->json(['favorites' => $favs], 200);
    }
}
