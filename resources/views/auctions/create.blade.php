<x-app-layout>
    @section('title', 'Nova aukcija')

    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Kreiraj aukciju</h1>

        <form action="{{ route('auctions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Odaberi nekretninu</h2>

                @if($properties->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-gray-400 mb-3">Nemate nekretnina dostupnih za aukciju</p>
                        <a href="{{ route('properties.create') }}"
                           class="text-indigo-600 hover:underline text-sm">
                            Kreirajte oglas prvo
                        </a>
                    </div>
                @else
                    <div class="grid gap-3">
                        @foreach($properties as $property)
                            <label class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-indigo-400 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="property_id" value="{{ $property->id }}"
                                       {{ request('property') == $property->id ? 'checked' : '' }}>
                                @if($property->primaryImage)
                                    <img src="{{ asset('storage/' . $property->primaryImage->path) }}"
                                         class="w-14 h-12 object-cover rounded">
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $property->city }} — {{ $property->area_m2 }} m²</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('property_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            @if($properties->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                    <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Podešavanja aukcije</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Početna cijena (€)</label>
                            <input type="number" name="start_price" value="{{ old('start_price') }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                                   placeholder="50000">
                            @error('start_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimalni korak (€)</label>
                            <input type="number" name="min_increment" value="{{ old('min_increment', 500) }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                                   placeholder="500">
                            @error('min_increment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Buy Now cijena (€)
                            <span class="text-gray-400 font-normal">— opciono</span>
                        </label>
                        <input type="number" name="buy_now_price" value="{{ old('buy_now_price') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="Ostavite prazno ako ne želite ovu opciju">
                        @error('buy_now_price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Datum i vrijeme završetka</label>
                        <input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}"
                               min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('ends_at')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Objavi aukciju
                    </button>
                    <a href="{{ route('dashboard') }}"
                       class="border border-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition">
                        Otkaži
                    </a>
                </div>
            @endif

        </form>
    </div>
</x-app-layout>

