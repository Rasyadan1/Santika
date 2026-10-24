<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $profile = $user->profile;
        abort_unless($profile, 403, 'Profil anggota tidak ditemukan.');

        $validated = $request->validated();

        DB::transaction(function () use ($user, $profile, $validated) {
            $user->update(['name' => $validated['name']]);

            if (!empty($validated['password'])) {
                $user->update(['password' => $validated['password']]);
            }

            $profile->update([
                'no_telepon' => $validated['no_telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}
