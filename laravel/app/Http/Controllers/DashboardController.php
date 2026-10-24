<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'user' && $user->profile) {
            return redirect()->route('katalog');
        }

        abort(403, 'Akun ini belum memiliki profil anggota yang valid.');
    }

    public function adminIndex(Request $request)
    {
        $search = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
        ])['search'] ?? null;

        $units = Unit::with('categories')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_unit', 'like', "%{$search}%")
                        ->orWhere('kode_unit', 'like', "%{$search}%");
                });
            })
            ->orderBy('kode_unit')
            ->paginate(10)
            ->appends(['search' => $search]);

        $totalUnit = Unit::count();
        $sedangDipinjam = Unit::where('status', 'dipinjam')->count();
        $totalAnggota = User::where('role', 'user')->count();

        return view('admin', compact('units', 'search', 'totalUnit', 'sedangDipinjam', 'totalAnggota'));
    }
}
