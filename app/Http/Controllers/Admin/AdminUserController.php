<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:kupac,prodavac,admin'],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Uloga je promenjena.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Ne mozete obrisati sopstveni nalog.');
        }

        $user->delete();

        return back()->with('success', 'Korisnik je obrisan.');
    }
}
