<x-app-layout>
    @section('title', 'Aukcije')

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600">← Admin panel</a>
        <h1 class="text-2xl font-bold text-gray-900">Sve aukcije</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Nekretnina</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Prodavac</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Trenutna cena</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Ponuda</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Istice</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($auctions as $auction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <a href="{{ route('auctions.show', $auction) }}"
                               class="font-medium text-gray-900 hover:text-indigo-600">
                                {{ $auction->property->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $auction->property->user->name }}</td>
                        <td class="px-6 py-4 font-semibold text-indigo-600">
                            €{{ number_format($auction->current_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $auction->bids->count() }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $auction->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $auction->status === 'finished' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $auction->status === 'cancelled' ? 'bg-red-100 text-red-600' : '' }}
                                {{ $auction->status === 'pending' ? 'bg-gray-100 text-gray-600' : '' }}">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $auction->ends_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($auction->status === 'active')
                                <form action="{{ route('admin.auctions.cancel', $auction) }}" method="POST"
                                      onsubmit="return confirm('Otkazati aukciju?')">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-xs text-red-400 hover:text-red-600">Otkaži</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $auctions->links() }}
        </div>
    </div>
</x-app-layout>