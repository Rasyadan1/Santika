<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profile.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $user->update([
            'name' => $request->name,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        $user->profile->update([
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
