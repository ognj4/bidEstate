<x-app-layout>
    @section('title', $property->title)

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $property->title }}</h1>
            <p class="text-gray-500 mb-6">{{ $property->address }}, {{ $property->city }}</p>

            @if($property->images->count())
                <div class="grid grid-cols-3 gap-3 mb-6">
                    @foreach($property->images as $image)
                        <img src="{{ asset('storage/' . $image->path) }}"
                             class="w-full h-40 object-cover rounded-lg">
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-y border-gray-100 mb-4">
                <div class="text-center">
                    <p class="text-xs text-gray-400">Površina</p>
                    <p class="font-semibold">{{ $property->area_m2 }} m²</p>
                </div>
                @if($property->rooms)
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Sobe</p>
                        <p class="font-semibold">{{ $property->rooms }}</p>
                    </div>
                @endif
                @if($property->floor)
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Sprat</p>
                        <p class="font-semibold">{{ $property->floor }}/{{ $property->total_floors }}</p>
                    </div>
                @endif
                @if($property->year_built)
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Godina gradnje</p>
                        <p class="font-semibold">{{ $property->year_built }}</p>
                    </div>
                @endif
            </div>

            <p class="text-gray-700 leading-relaxed mb-6">{{ $property->description }}</p>

            <div class="flex gap-3">
                @if($property->auction)
                    <a href="{{ route('auctions.show', $property->auction) }}"
                       class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                        Pogledaj aukciju
                    </a>
                @else
                    @can('update', $property)
                        <a href="{{ route('auctions.create') }}?property={{ $property->id }}"
                           class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                            Kreiraj aukciju
                        </a>
                    @endcan
                @endif

                @can('update', $property)
                    <a href="{{ route('properties.edit', $property) }}"
                       class="border border-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-50 transition text-sm">
                        Izmijeni
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>