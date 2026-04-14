<x-app-layout>
    @section('title', 'Dashboard')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Dobrodošli, {{ auth()->user()->name }}!
        </h1>
        <p class="text-gray-500 mt-1">
            {{ auth()->user()->isSeller() ? 'Prodavac' : 'Kupac' }} —
            {{ auth()->user()->email }}
        </p>
    </div>

    {{-- Nepročitane notifikacije --}}
    @if($notifications->count())
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-8">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold text-indigo-900">Obavještenja</h2>
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="text-xs text-indigo-600 hover:underline">
                        Označi sve kao pročitano
                    </button>
                </form>
            </div>
            <div class="space-y-2">
                @foreach($notifications as $notification)
                    <div class="flex items-start justify-between bg-white rounded-lg px-4 py-3">
                        <p class="text-sm text-gray-700">{{ $notification->message }}</p>
                        <form action="{{ route('notifications.read', $notification) }}" method="POST" class="ml-4 shrink-0">
                            @csrf
                            @method('PATCH')
                            <button class="text-xs text-gray-400 hover:text-gray-600">✓</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- PRODAVAC --}}
    @if(auth()->user()->isSeller())

        {{-- Aktivne aukcije --}}
        @if($myAuctions->count())
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Moje aktivne aukcije</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($myAuctions as $auction)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <a href="{{ route('auctions.show', $auction) }}"
                               class="font-semibold text-gray-900 hover:text-indigo-600 block mb-2">
                                {{ $auction->property->title }}
                            </a>
                            <div class="flex justify-between items-center text-sm mb-3">
                                <span class="text-gray-500">Trenutna cijena</span>
                                <span class="font-bold text-indigo-600">
                                    €{{ number_format($auction->current_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-sm mb-3">
                                <span class="text-gray-500">Ponuda</span>
                                <span class="font-medium">{{ $auction->bids->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Ističe</span>
                                <span class="text-orange-500 font-medium">
                                    {{ $auction->ends_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Svi oglasi --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Moji oglasi</h2>
                <a href="{{ route('properties.create') }}"
                   class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    + Novi oglas
                </a>
            </div>

            @if($myProperties->count())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Nekretnina</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Grad</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Aukcija</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($myProperties as $property)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($property->primaryImage)
                                                <img src="{{ asset('storage/' . $property->primaryImage->path) }}"
                                                     class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-gray-100"></div>
                                            @endif
                                            <span class="font-medium text-gray-900">{{ $property->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $property->city }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColors = [
                                                'draft'     => 'bg-gray-100 text-gray-600',
                                                'active'    => 'bg-green-100 text-green-700',
                                                'sold'      => 'bg-blue-100 text-blue-700',
                                                'cancelled' => 'bg-red-100 text-red-600',
                                            ];
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$property->status] ?? '' }}">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        @if($property->auction)
                                            <a href="{{ route('auctions.show', $property->auction) }}"
                                               class="text-indigo-600 hover:underline">
                                                Pogledaj aukciju
                                            </a>
                                        @else
                                            <a href="{{ route('auctions.create') }}?property={{ $property->id }}"
                                               class="text-green-600 hover:underline">
                                                Kreiraj aukciju
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('properties.edit', $property) }}"
                                               class="text-xs text-gray-500 hover:text-indigo-600">
                                                Izmijeni
                                            </a>
                                            @if(!$property->hasActiveAuction())
                                                <form action="{{ route('properties.destroy', $property) }}" method="POST"
                                                      onsubmit="return confirm('Obrisati oglas?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-xs text-red-400 hover:text-red-600">Obriši</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-400 mb-4">Još nemate oglasa</p>
                    <a href="{{ route('properties.create') }}"
                       class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                        Kreirajte prvi oglas
                    </a>
                </div>
            @endif
        </div>

    {{-- KUPAC --}}
    @else
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Moje ponude</h2>

            @if($myBids->count())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Nekretnina</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Moja ponuda</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Trenutna cijena</th>
                                <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($myBids as $bid)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $bid->auction->property->title }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-indigo-600">
                                        €{{ number_format($bid->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700">
                                        €{{ number_format($bid->auction->current_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($bid->auction->isActive())
                                            @if($bid->amount >= $bid->auction->current_price)
                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                                    Vodeći
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded-full text-xs font-medium">
                                                    Nadlicitiran
                                                </span>
                                            @endif
                                        @elseif($bid->auction->winner_id === auth()->id())
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">
                                                Pobijedio
                                            </span>
                                        @else
                                            <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">
                                                Završeno
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('auctions.show', $bid->auction) }}"
                                           class="text-xs text-indigo-600 hover:underline">
                                            Pogledaj
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-400 mb-4">Još niste licitirali ni na jednoj aukciji</p>
                    <a href="{{ route('auctions.index') }}"
                       class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                        Pogledajte aktivne aukcije
                    </a>
                </div>
            @endif
        </div>
    @endif

</x-app-layout>

