<x-app-layout>
    @section('title', 'Korisnici')

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600">← Admin panel</a>
        <h1 class="text-2xl font-bold text-gray-900">Korisnici</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Korisnik</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Uloga</th>
                    <th class="text-left px-6 py-3 text-gray-500 font-medium">Registrovan</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-gray-400 text-xs">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.users.role', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()"
                                        class="text-xs border border-gray-200 rounded px-2 py-1">
                                    @foreach(['kupac', 'prodavac', 'admin'] as $role)
                                        <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                            {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->created_at->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                      onsubmit="return confirm('Obrisati korisnika?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-red-400 hover:text-red-600">Obrisi</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>