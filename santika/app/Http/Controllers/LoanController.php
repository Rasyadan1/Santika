<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Peminjaman;
use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Anggota: Pinjam unit.
     */
    public function pinjam(Unit $unit)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if (!$profile) {
            return back()->with('error', 'Profil anda tidak ditemukan.');
        }

        try {
            $this->loanService->pinjam($profile, $unit);
            return redirect()->route('pinjaman.index')
                ->with('success', "Unit \"{$unit->nama_unit}\" berhasil dipinjam!");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Anggota: Daftar pinjaman aktif.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile;

        $pinjamanAktif = Peminjaman::where('profile_id', $profile->id)
            ->where('status', 'dipinjam')
            ->with('unit.categories')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        return view('loans.index', compact('pinjamanAktif'));
    }

    /**
     * Anggota: Riwayat peminjaman sendiri.
     */
    public function riwayat()
    {
        $user = Auth::user();
        $profile = $user->profile;

        $riwayat = Peminjaman::where('profile_id', $profile->id)
            ->with(['unit.categories', 'admin'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(15);

        return view('loans.riwayat', compact('riwayat'));
    }
}
