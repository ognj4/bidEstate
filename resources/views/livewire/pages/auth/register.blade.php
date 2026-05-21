<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'kupac';
    public bool $email_notifications = true;

    public function register(): void
    {
        $validated = $this->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:kupac,prodavac'],
            'email_notifications' => ['boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">

        <div>
            <x-input-label for="name" value="Ime i prezime" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full"
                type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="Email adresa" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full"
                type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Lozinka" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Potvrdi lozinku" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation"
                class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label value="Registrujem se kao" />
            <div class="mt-2 grid grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                    {{ $role === 'kupac' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input wire:model="role" type="radio" value="kupac" class="text-indigo-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Kupac</p>
                        <p class="text-xs text-gray-500">Licitiram na aukcijama</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition
                    {{ $role === 'prodavac' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                    <input wire:model="role" type="radio" value="prodavac" class="text-indigo-600">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Prodavac</p>
                        <p class="text-xs text-gray-500">Postavljam oglase</p>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="mt-4 flex items-center gap-2">
            <input wire:model="email_notifications" type="checkbox" id="notif"
                   class="rounded border-gray-300 text-indigo-600">
            <label for="notif" class="text-sm text-gray-600">
                Zelim da primam email obavestenja o aukcijama
            </label>
        </div>

        <div class="flex items-center justify-end mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900"
               href="{{ route('login') }}" wire:navigate>
                Vec imate nalog?
            </a>
            <x-primary-button class="ms-4">
                Registruj se
            </x-primary-button>
        </div>

    </form>
</div>