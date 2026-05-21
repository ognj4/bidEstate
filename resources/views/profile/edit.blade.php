<x-app-layout>
    @section('title', 'Moj profil')

    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Moj profil</h1>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Osnovni podaci</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ime i prezime</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email adresa</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 bg-gray-50 text-gray-400">
                    <p class="text-xs text-gray-400 mt-1">Email adresa se ne moze menjati</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Broj telefona</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500"
                           placeholder="+381 60 123 4567">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3">Uloga</h2>

                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                        {{ $user->role === 'kupac' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="role" value="kupac"
                               {{ $user->role === 'kupac' ? 'checked' : '' }}
                               class="text-indigo-600">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Kupac</p>
                            <p class="text-xs text-gray-500">Licitiram na aukcijama</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                        {{ $user->role === 'prodavac' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                        <input type="radio" name="role" value="prodavac"
                               {{ $user->role === 'prodavac' ? 'checked' : '' }}
                               class="text-indigo-600">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Prodavac</p>
                            <p class="text-xs text-gray-500">Postavljam oglase</p>
                        </div>
                    </label>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs text-yellow-700">
                        Ako promenite ulogu sa prodavac na kupac, vasi aktivni oglasi ce ostati ali necete moci da kreirate nove.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">Obavestenja</h2>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="email_notifications" value="1"
                           {{ $user->email_notifications ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Email obavestenja</p>
                        <p class="text-xs text-gray-500">Primaj obavestenja kada te neko nadlicitira ili aukcija zavrsi</p>
                    </div>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Sacuvaj izmene
                </button>
                <a href="{{ route('dashboard') }}"
                   class="border border-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-50 transition">
                    Otkazi
                </a>
            </div>
        </form>
    </div>
</x-app-layout>