<x-app-layout>
    @section('title', 'Izmeeni oglas')

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Izmeni oglas</h1>

        <form action="{{ route('properties.update', $property) }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Osnovne informacije</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naslov oglasa</label>
                    <input type="text" name="title" value="{{ old('title', $property->title) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opis</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tip nekretnine</label>
                        <select name="type"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            @foreach(['stan', 'kuca', 'zemljiste', 'poslovni'] as $type)
                                <option value="{{ $type }}"
                                    {{ old('type', $property->type) === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Površina (m²)</label>
                        <input type="number" name="area_m2" value="{{ old('area_m2', $property->area_m2) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('area_m2')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Broj soba</label>
                        <input type="number" name="rooms" value="{{ old('rooms', $property->rooms) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sprat</label>
                        <input type="number" name="floor" value="{{ old('floor', $property->floor) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ukupno spratova</label>
                        <input type="number" name="total_floors" value="{{ old('total_floors', $property->total_floors) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Godina izgradnje</label>
                    <input type="number" name="year_built" value="{{ old('year_built', $property->year_built) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Lokacija</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grad</label>
                        <input type="text" name="city" value="{{ old('city', $property->city) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresa</label>
                        <input type="text" name="address" value="{{ old('address', $property->address) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if($property->images->count())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">Postojece slike</h2>
                    <div class="flex gap-3 flex-wrap">
                        @foreach($property->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     class="w-24 h-20 object-cover rounded-lg">
                                @if($image->is_primary)
                                    <span class="absolute top-1 left-1 bg-indigo-600 text-white text-xs px-1 rounded">
                                        Naslovna
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                 x-data="{ previews: [] }">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">
                    Dodaj nove slike
                    <span class="text-gray-400 font-normal text-sm">— opciono</span>
                </h2>

                <input type="file" name="images[]" multiple accept="image/*" id="images"
                       class="hidden"
                       @change="previews = []; Array.from($event.target.files).forEach(f => {
                           const r = new FileReader();
                           r.onload = e => previews.push(e.target.result);
                           r.readAsDataURL(f);
                       })">

                <label for="images"
                       class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-indigo-400 transition">
                    <span class="text-sm text-gray-400">Klikni za upload novih slika</span>
                </label>

                <div class="flex gap-3 mt-4 flex-wrap">
                    <template x-for="(src, i) in previews" :key="i">
                        <img :src="src" class="w-24 h-20 object-cover rounded-lg">
                    </template>
                </div>
            </div>

            {{-- submit --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Sačuvaj izmene
                </button>
                <a href="{{ route('properties.show', $property) }}"
                   class="border border-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition">
                    Otkaži
                </a>
            </div>

        </form>
    </div>
</x-app-layout>