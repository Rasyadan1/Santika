<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'user',
            ]);

            // Generate nomor anggota unik
            $nomorAnggota = 'ANG-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            Profile::create([
                'user_id' => $user->id,
                'nomor_anggota' => $nomorAnggota,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_daftar' => now()->toDateString(),
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Santika.');
    }
}
