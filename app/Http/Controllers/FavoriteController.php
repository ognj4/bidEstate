<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Property $property)
    {
        $user = $request->user();

        $existing = $user->favorites()
            ->where('property_id', $property->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Uklonjeno iz favorita.';
        } else {
            $user->favorites()->create(['property_id' => $property->id]);
            $message = 'Dodano u favorite!';
        }

        return back()->with('success', $message);
    }
}
