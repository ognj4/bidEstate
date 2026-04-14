<x-app-layout>
    @section('title', 'Aktivne aukcije')

    {{-- Hero --}}
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Aktivne aukcije nekretnina</h1>
        <p class="text-gray-500">Pronađite svoju idealnu nekretninu i licitirajte</p>
    </div>

    {{-- Grid aukcija --}}
    @if($auctions->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($auctions as $auction)
                <x-auction-card :auction="$auction" />
            @endforeach
        </div>

        {{-- Paginacija --}}
        <div class="mt-8">
            {{ $auctions->links() }}
        </div>
    @else
        <div class="text-center py-20 text-gray-400">
            <p class="text-xl mb-2">Trenutno nema aktivnih aukcija</p>
            <p class="text-sm">Provjerite ponovo uskoro</p>
        </div>
    @endif
</x-app-layout>