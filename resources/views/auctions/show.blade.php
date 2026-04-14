<x-app-layout>
    @section('title', $auction->property->title)

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Lijeva strana — detalji nekretnine --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Slike --}}
            <div class="bg-white rounded-xl overflow-hidden shadow-sm" x-data="{ active: 0 }">
                @if($auction->property->images->count())
                    <img :src="images[active]"
                         class="w-full h-96 object-cover"
                         x-init="images = {{ json_encode($auction->property->images->pluck('path')->map(fn($p) => asset('storage/'.$p))) }}">
                    @if($auction->property->images->count() > 1)
                        <div class="flex gap-2 p-3 overflow-x-auto">
                            @foreach($auction->property->images as $i => $image)
                                <img src="{{ asset('storage/' . $image->path) }}"
                                     @click="active = {{ $i }}"
                                     class="w-20 h-16 object-cover rounded cursor-pointer opacity-60 hover:opacity-100 transition"
                                     :class="{ 'opacity-100 ring-2 ring-indigo-500': active === {{ $i }} }">
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>

            {{-- Info o nekretnini --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-1 rounded-full">
                            {{ ucfirst($auction->property->type) }}
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900 mt-2">
                            {{ $auction->property->title }}
                        </h1>
                        <p class="text-gray-500 mt-1">
                            {{ $auction->property->address }}, {{ $auction->property->city }}
                        </p>
                    </div>
                    <span class="text-sm text-gray-400">
                        Prodavac: <span class="font-medium text-gray-700">{{ $auction->property->user->name }}</span>
                    </span>
                </div>

                {{-- Karakteristike --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-4 border-y border-gray-100 mb-4">
                    <div class="text-center">
                        <p class="text-xs text-gray-400">Površina</p>
                        <p class="font-semibold">{{ $auction->property->area_m2 }} m²</p>
                    </div>
                    @if($auction->property->rooms)
                        <div class="text-center">
                            <p class="text-xs text-gray-400">Sobe</p>
                            <p class="font-semibold">{{ $auction->property->rooms }}</p>
                        </div>
                    @endif
                    @if($auction->property->floor)
                        <div class="text-center">
                            <p class="text-xs text-gray-400">Sprat</p>
                            <p class="font-semibold">{{ $auction->property->floor }}/{{ $auction->property->total_floors }}</p>
                        </div>
                    @endif
                    @if($auction->property->year_built)
                        <div class="text-center">
                            <p class="text-xs text-gray-400">Godina gradnje</p>
                            <p class="font-semibold">{{ $auction->property->year_built }}</p>
                        </div>
                    @endif
                </div>

                <p class="text-gray-700 leading-relaxed">{{ $auction->property->description }}</p>
            </div>

            {{-- Lista ponuda --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="font-semibold text-gray-900 mb-4">
                    Ponude ({{ $auction->bids->count() }})
                </h2>
                @forelse($auction->bids->take(10) as $bid)
                    <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                        <span class="text-sm text-gray-700">{{ $bid->user->name }}</span>
                        <span class="font-semibold text-indigo-600">
                            €{{ number_format($bid->amount, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-gray-400">
                            {{ $bid->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Još nema ponuda. Budite prvi!</p>
                @endforelse
            </div>
        </div>

        {{-- Desna strana — licitiranje --}}
        <div class="space-y-4">
            @livewire('auction-bid-panel', ['auction' => $auction])
        </div>

    </div>
</x-app-layout>