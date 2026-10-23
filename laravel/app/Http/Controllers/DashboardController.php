<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Unit;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->memberDashboard($user);
    }

    private function adminDashboard()
    {
        $totalUnit = Unit::count();
        $unitTersedia = Unit::where('status', 'tersedia')->count();
        $unitDipinjam = Unit::where('status', 'dipinjam')->count();
        $totalAnggota = User::where('role', 'user')->count();
        $totalKategori = Category::count();
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_jatuh_tempo', '<', now()->toDateString())
            ->count();

        $pinjamanTerbaru = Peminjaman::with(['profile.user', 'unit'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalUnit', 'unitTersedia', 'unitDipinjam',
            'totalAnggota', 'totalKategori', 'pinjamanAktif',
            'terlambat', 'pinjamanTerbaru'
        ));
    }

    private function memberDashboard($user)
    {
        $profile = $user->profile;
        $pinjamanAktif = $profile ? $profile->peminjaman()
            ->where('status', 'dipinjam')
            ->with('unit.categories')
            ->get() : collect();

        $batasPinjam = config('santika.batas_pinjam', 2);
        $sisaPinjam = $batasPinjam - $pinjamanAktif->count();

        $riwayatTerakhir = $profile ? $profile->peminjaman()
            ->where('status', 'dikembalikan')
            ->with('unit')
            ->orderBy('tanggal_kembali', 'desc')
            ->limit(5)
            ->get() : collect();

        return view('dashboard.member', compact(
            'user', 'profile', 'pinjamanAktif',
            'batasPinjam', 'sisaPinjam', 'riwayatTerakhir'
        ));
    }
}
