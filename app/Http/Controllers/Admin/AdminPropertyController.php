<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;

class AdminPropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with(['user', 'auction'])
            ->latest()
            ->paginate(20);

        return view('admin.properties', compact('properties'));
    }

    public function destroy(Property $property)
    {
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $property->delete();

        return back()->with('success', 'Oglas je obrisan.');
    }
}
