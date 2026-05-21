<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function edit(Request $request)
	{
		return view('profile.edit', ['user' => $request->user()]);
	}

	public function update(Request $request)
	{
		$validated = $request->validate([
			'name'               => ['required', 'string', 'max:255'],
			'phone'              => ['nullable', 'string', 'max:20'],
			'role'               => ['required', 'in:kupac,prodavac'],
			'email_notifications' => ['boolean'],
		]);

		$request->user()->update($validated);

		return back()->with('success', 'Profil je uspesno azuriran!');
	}
}
