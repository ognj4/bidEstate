<x-app-layout>
    @section('title', 'Oglasi')

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600">← Admin panel</a>
        <h1 class="text-2xl font-bold text-gray-900">Svi oglasi</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Oglas</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Prodavac</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Aukcija</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($properties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $property->title }}</p>
                            <p class="text-xs text-gray-400">{{ $property->city }} — {{ $property->area_m2 }} m²</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $property->user->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $property->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $property->status === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $property->status === 'sold' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $property->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}">
                                {{ ucfirst($property->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            @if($property->auction)
                                <a href="{{ route('auctions.show', $property->auction) }}"
                                   class="text-indigo-600 hover:underline text-xs">
                                    Pogledaj aukciju
                                </a>
                            @else
                                <span class="text-gray-300 text-xs">Nema aukcije</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('admin.properties.destroy', $property) }}" method="POST"
                                  onsubmit="return confirm('Obrisati oglas?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600">Obrisi</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $properties->links() }}
        </div>
    </div>
</x-app-layout>