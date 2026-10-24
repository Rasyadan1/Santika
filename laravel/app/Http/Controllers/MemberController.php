<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');

        $members = User::where('role', 'user')
            ->with('profile')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('profile', function ($profileQuery) use ($search) {
                            $profileQuery->where('nomor_anggota', 'like', "%{$search}%")
                                ->orWhere('no_telepon', 'like', "%{$search}%")
                                ->orWhere('alamat', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.members.index', compact('members', 'search'));
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
        $this->ensureMember($member);
        $member->load('profile');
        return view('admin.members.edit', compact('member'));
    }

    public function update(MemberRequest $request, User $member)
    {
        $this->ensureMember($member);
        $validated = $request->validated();

        DB::transaction(function () use ($member, $validated) {
            $member->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            if (!empty($validated['password'])) {
                $member->update(['password' => $validated['password']]);
            }

            $profile = $member->profile;
            if (!$profile) {
                $profile = Profile::create([
                    'user_id' => $member->id,
                    'nomor_anggota' => 'ANG-' . str_pad((string) $member->id, 5, '0', STR_PAD_LEFT),
                    'tanggal_daftar' => now()->toDateString(),
                ]);
            }

            $profile->update([
                'no_telepon' => $validated['no_telepon'] ?? null,
                'alamat' => $validated['alamat'] ?? null,
            ]);
        });

        return redirect()->route('admin.members.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $member)
    {
        $this->ensureMember($member);

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

    private function ensureMember(User $member): void
    {
        abort_unless($member->role === 'user', 404);
    }
}
