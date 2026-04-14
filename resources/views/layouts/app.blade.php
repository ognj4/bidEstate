<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'BidEstate') }} — @yield('title', 'Aukcije nekretnina')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    {{-- Navigacija --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">
                    BidEstate
                </a>

                {{-- Sredina --}}
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('auctions.index') }}"
                       class="text-sm text-gray-600 hover:text-indigo-600 transition">
                        Aukcije
                    </a>
                </div>

                {{-- Desna strana --}}
                <div class="flex items-center gap-4">
                    @auth
                        {{-- Notifikacije --}}
                        @php
                            $unread = auth()->user()->notifications()->whereNull('read_at')->count();
                        @endphp
                        <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @if($unread > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                    {{ $unread }}
                                </span>
                            @endif
                        </a>

                        {{-- Dropdown --}}
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center gap-2 text-sm text-gray-700 hover:text-indigo-600">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                                <a href="{{ route('dashboard') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    Dashboard
                                </a>
                                @if(auth()->user()->isSeller())
                                    <a href="{{ route('properties.create') }}"
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        Novi oglas
                                    </a>
                                @endif
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                        Odjavi se
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 hover:text-indigo-600">Prijavi se</a>
                        <a href="{{ route('register') }}"
                           class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Registruj se
                        </a>
                    @endauth
                </div>

            </div>
        </div>
    </nav>

    {{-- Flash poruke --}}
    <x-flash-message />

    {{-- Sadržaj --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
