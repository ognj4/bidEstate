<x-app-layout>
    @section('title', 'Obavestenja')

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Obavestenja</h1>

            @if($notifications->total() > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="text-sm text-indigo-600 hover:underline">
                        Oznaci sve kao procitano
                    </button>
                </form>
            @endif
        </div>

        @if($notifications->count())
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    <div class="bg-white rounded-xl shadow-sm border p-5 flex items-start justify-between gap-4
                        {{ $notification->isRead() ? 'border-gray-100' : 'border-indigo-200 bg-indigo-50' }}">

                        <div class="flex items-start gap-4">
                            <div class="mt-1">
                                @if($notification->type === 'auction_won')
                                    <span class="text-2xl">🏆</span>
                                @elseif($notification->type === 'auction_lost')
                                    <span class="text-2xl">📋</span>
                                @else
                                    <span class="text-2xl">🔔</span>
                                @endif
                            </div>

                            <div>
                                <p class="text-gray-900 font-medium">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>

                                @if($notification->data && isset($notification->data['auction_id']))
                                    <a href="{{ route('auctions.show', $notification->data['auction_id']) }}"
                                       class="text-xs text-indigo-600 hover:underline mt-2 inline-block">
                                        Pogledaj aukciju
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if(!$notification->isRead())
                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button class="text-xs text-gray-400 hover:text-gray-600 whitespace-nowrap">
                                    Oznaci procitano
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-16 text-center">
                <p class="text-gray-400 text-lg mb-2">Nemate obavestenja</p>
                <p class="text-gray-300 text-sm">Obavestenja ce se pojaviti kada neko licitira ili aukcija zavrsi</p>
            </div>
        @endif
    </div>
</x-app-layout>