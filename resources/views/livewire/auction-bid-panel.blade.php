<div class="space-y-4">

    {{-- Status aukcije --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="text-center mb-6">
            @if($auction->isActive())
                <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium mb-3">
                    Aukcija aktivna
                </span>
            @elseif($auction->isFinished())
                <span class="inline-block bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full font-medium mb-3">
                    Aukcija završena
                </span>
            @endif

            <p class="text-sm text-gray-400">Trenutna cijena</p>
            <p class="text-4xl font-bold text-indigo-600 my-2">
                €{{ number_format($auction->current_price, 0, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500">
                Minimalni korak: <span class="font-medium">€{{ number_format($auction->min_increment, 0, ',', '.') }}</span>
            </p>
        </div>

        {{-- Odbrojavanje --}}
        @if($auction->isActive())
            <div class="bg-orange-50 rounded-lg p-3 text-center mb-4">
                <p class="text-xs text-orange-600 mb-1">Ističe</p>
                <p class="font-semibold text-orange-700">{{ $auction->ends_at->diffForHumans() }}</p>
            </div>
        @endif

        {{-- Flash poruka --}}
        @if($message)
            <div class="rounded-lg px-4 py-3 mb-4 text-sm
                {{ $messageType === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                {{ $message }}
            </div>
        @endif

        {{-- Forma za licitiranje --}}
        @if($auction->isActive())
            @auth
                @if(auth()->id() !== $auction->property->user_id)
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm text-gray-600 mb-1 block">Vaša ponuda (€)</label>
                            <input wire:model="bidAmount"
                                   type="number"
                                   min="{{ $auction->minimumNextBid() }}"
                                   step="{{ $auction->min_increment }}"
                                   class="w-full border border-gray-200 rounded-lg px-4 py-3 text-lg font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            @error('bidAmount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button wire:click="placeBid" wire:loading.attr="disabled"
                            class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition disabled:opacity-50">
                            <span wire:loading.remove>Licitiraj</span>
                            <span wire:loading>Šalje se...</span>
                        </button>

                        {{-- Buy Now --}}
                        @if($auction->buy_now_price)
                            <form action="{{ route('bids.buynow', $auction) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition"
                                    onclick="return confirm('Da li ste sigurni da zelite kupiti po cijeni €{{ number_format($auction->buy_now_price, 0, \",\", \".\") }}?')">
                                    Kupi odmah — €{{ number_format($auction->buy_now_price, 0, ',', '.') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-4 text-center text-sm text-gray-500">
                        Ovo je vaša aukcija
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Prijavi se da licitiraš
                </a>
            @endauth
        @endif

        {{-- Pobjednik --}}
        @if($auction->isFinished() && $auction->winner)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                <p class="text-sm text-yellow-700 font-medium">Pobjednik aukcije</p>
                <p class="font-bold text-gray-900 mt-1">{{ $auction->winner->name }}</p>
                <p class="text-indigo-600 font-semibold">€{{ number_format($auction->current_price, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    {{-- Favorit dugme --}}
    @auth
        <form action="{{ route('favorites.toggle', $auction->property) }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full border border-gray-200 bg-white text-gray-700 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Sačuvaj u favorite
            </button>
        </form>
    @endauth

</div>