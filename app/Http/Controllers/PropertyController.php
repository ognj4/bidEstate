<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function show(Property $property)
    {
        $this->authorize('view', $property);

        $property->load(['images', 'user', 'auction.bids.user']);

        return view('properties.show', compact('property'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);

        return view('properties.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Property::class);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'type'         => ['required', 'in:stan,kuca,zemljiste,poslovni'],
            'area_m2'      => ['required', 'numeric', 'min:1'],
            'rooms'        => ['nullable', 'integer', 'min:1'],
            'floor'        => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer', 'min:1'],
            'city'         => ['required', 'string', 'max:100'],
            'address'      => ['required', 'string', 'max:255'],
            'year_built'   => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
            'images'       => ['required', 'array', 'min:1'],
            'images.*'     => ['image', 'max:5120'], // max 5MB po slici
        ]);

        $property = $request->user()->properties()->create($validated);

        // Upload slika
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('properties', 'public');

            $property->images()->create([
                'path'       => $path,
                'is_primary' => $index === 0,
                'order'      => $index,
            ]);
        }

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Oglas je uspješno kreiran!');
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);

        $property->load('images');

        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'type'         => ['required', 'in:stan,kuca,zemljiste,poslovni'],
            'area_m2'      => ['required', 'numeric', 'min:1'],
            'rooms'        => ['nullable', 'integer', 'min:1'],
            'floor'        => ['nullable', 'integer'],
            'total_floors' => ['nullable', 'integer', 'min:1'],
            'city'         => ['required', 'string', 'max:100'],
            'address'      => ['required', 'string', 'max:255'],
            'year_built'   => ['nullable', 'integer', 'min:1800', 'max:' . date('Y')],
        ]);

        $property->update($validated);

        return redirect()
            ->route('properties.show', $property)
            ->with('success', 'Oglas je uspješno ažuriran!');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        // Obrisi slike sa diska
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $property->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'Oglas je obrisan.');
    }
}
