<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'user')
            ->with('profile')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(MemberRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'user',
            ]);

            $nomorAnggota = 'ANG-' . str_pad($user->id, 5, '0', STR_PAD_LEFT);

            Profile::create([
                'user_id' => $user->id,
                'nomor_anggota' => $nomorAnggota,
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
                'tanggal_daftar' => now()->toDateString(),
            ]);
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $member)
    {
        $member->load('profile');
        return view('admin.members.edit', compact('member'));
    }

    public function update(MemberRequest $request, User $member)
    {
        $member->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $member->update(['password' => $request->password]);
        }

        if ($member->profile) {
            $member->profile->update([
                'no_telepon' => $request->no_telepon,
                'alamat' => $request->alamat,
            ]);
        }

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $member)
    {
        // Cek apakah anggota masih punya pinjaman aktif
        if ($member->profile && $member->profile->jumlahPinjamanAktif() > 0) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena masih memiliki pinjaman aktif.');
        }

        try {
            DB::transaction(function () use ($member) {
                if ($member->profile) {
                    $member->profile->delete();
                }
                $member->delete();
            });

            return redirect()->route('admin.members.index')
                ->with('success', 'Anggota berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Anggota tidak dapat dihapus karena masih memiliki riwayat peminjaman.');
        }
    }
}
