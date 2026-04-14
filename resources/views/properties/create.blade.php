<x-app-layout>
    @section('title', 'Novi oglas')

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Kreiraj novi oglas</h1>

        <form action="{{ route('properties.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Osnovno --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Osnovne informacije</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Naslov oglasa</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="npr. Trosoban stan u centru Sarajeva">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opis</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                              placeholder="Opišite nekretninu detaljno...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tip nekretnine</label>
                        <select name="type"
                                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                            <option value="">Odaberi tip</option>
                            @foreach(['stan', 'kuca', 'zemljiste', 'poslovni'] as $type)
                                <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>
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
                        <input type="number" name="area_m2" value="{{ old('area_m2') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="85">
                        @error('area_m2')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Broj soba</label>
                        <input type="number" name="rooms" value="{{ old('rooms') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="3">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sprat</label>
                        <input type="number" name="floor" value="{{ old('floor') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ukupno spratova</label>
                        <input type="number" name="total_floors" value="{{ old('total_floors') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="5">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Godina izgradnje</label>
                    <input type="number" name="year_built" value="{{ old('year_built') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                           placeholder="2005">
                </div>
            </div>

            {{-- Lokacija --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Lokacija</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grad</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="Sarajevo">
                        @error('city')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresa</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                               placeholder="Titova 15">
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Slike --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                 x-data="{ previews: [] }">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">Slike</h2>

                <input type="file" name="images[]" multiple accept="image/*" id="images"
                       class="hidden"
                       @change="previews = []; Array.from($event.target.files).forEach(f => {
                           const r = new FileReader();
                           r.onload = e => previews.push(e.target.result);
                           r.readAsDataURL(f);
                       })">

                <label for="images"
                       class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer hover:border-indigo-400 transition">
                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm text-gray-400">Klikni za upload slika</span>
                    <span class="text-xs text-gray-300 mt-1">Prva slika biće naslovna • Max 5MB po slici</span>
                </label>

                <div class="flex gap-3 mt-4 flex-wrap">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="relative">
                            <img :src="src" class="w-24 h-20 object-cover rounded-lg">
                            <span x-show="i === 0"
                                  class="absolute top-1 left-1 bg-indigo-600 text-white text-xs px-1 rounded">
                                Naslovna
                            </span>
                        </div>
                    </template>
                </div>

                @error('images')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Kreiraj oglas
                </button>
                <a href="{{ route('dashboard') }}"
                   class="border border-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition">
                    Otkaži
                </a>
            </div>
        </form>
    </div>
</x-app-layout>