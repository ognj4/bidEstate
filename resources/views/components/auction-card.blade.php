@props(['auction'])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">

    {{-- Slika --}}
    <a href="{{ route('auctions.show', $auction) }}">
        @if($auction->property->primaryImage)
            <img src="{{ asset('storage/' . $auction->property->primaryImage->path) }}"
                 alt="{{ $auction->property->title }}"
                 class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
        @else
            <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                <span class="text-gray-400 text-sm">Nema slike</span>
            </div>
        @endif
    </a>

    <div class="p-4">
        {{-- Tip i grad --}}
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full font-medium">
                {{ ucfirst($auction->property->type) }}
            </span>
            <span class="text-xs text-gray-500">{{ $auction->property->city }}</span>
        </div>

        {{-- Naslov --}}
        <a href="{{ route('auctions.show', $auction) }}"
           class="font-semibold text-gray-900 hover:text-indigo-600 line-clamp-2 block mb-3">
            {{ $auction->property->title }}
        </a>

        {{-- Detalji --}}
        <div class="flex items-center gap-3 text-xs text-gray-500 mb-4">
            @if($auction->property->area_m2)
                <span>{{ $auction->property->area_m2 }} m²</span>
            @endif
            @if($auction->property->rooms)
                <span>{{ $auction->property->rooms }} sobe</span>
            @endif
        </div>

        {{-- Cijena i timer --}}
        <div class="border-t border-gray-50 pt-3">
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-xs text-gray-400">Trenutna cijena</p>
                    <p class="text-lg font-bold text-indigo-600">
                        €{{ number_format($auction->current_price, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">Ističe</p>
                    <p class="text-sm font-medium text-orange-500">
                        {{ $auction->ends_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            @if($auction->buy_now_price)
                <p class="text-xs text-gray-400 mt-1">
                    Kupi odmah: <span class="font-semibold text-green-600">€{{ number_format($auction->buy_now_price, 0, ',', '.') }}</span>
                </p>
            @endif
        </div>
    </div>
</div>