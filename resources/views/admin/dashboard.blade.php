<x-app-layout>
    @section('title', 'Admin Panel')

    <div class="mb-8 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.users') }}" class="text-sm text-indigo-600 hover:underline">Korisnici</a>
            <a href="{{ route('admin.properties') }}" class="text-sm text-indigo-600 hover:underline">Oglasi</a>
            <a href="{{ route('admin.auctions') }}" class="text-sm text-indigo-600 hover:underline">Aukcije</a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-3xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Ukupno korisnika</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Aktivnih aukcija</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['finished'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Zavrsenih aukcija</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
            <p class="text-3xl font-bold text-orange-600">{{ $stats['properties'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Ukupno oglasa</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Novi korisnici</h2>
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-600' : '' }}
                            {{ $user->role === 'prodavac' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $user->role === 'kupac' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Poslednje aukcije</h2>
            <div class="space-y-3">
                @foreach($recentAuctions as $auction)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $auction->property->title }}</p>
                            <p class="text-xs text-gray-400">€{{ number_format($auction->current_price, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $auction->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $auction->status === 'finished' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $auction->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>