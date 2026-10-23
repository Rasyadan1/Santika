<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Profile;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /**
     * Admin: Daftar semua pinjaman aktif.
     */
    public function index()
    {
        $pinjamanAktif = Peminjaman::where('status', 'dipinjam')
            ->with(['profile.user', 'unit'])
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(15);

        return view('admin.returns.index', compact('pinjamanAktif'));
    }

    /**
     * Admin: Proses pengembalian.
     */
    public function kembalikan(Peminjaman $peminjaman)
    {
        try {
            $result = $this->loanService->kembalikan($peminjaman, Auth::id());

            $message = "Unit \"{$result->unit->nama_unit}\" berhasil dikembalikan.";
            if ($result->denda > 0) {
                $message .= " Denda: Rp" . number_format($result->denda, 0, ',', '.') .
                    " ({$result->hari_terlambat} hari terlambat).";
            }

            return redirect()->route('admin.returns.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Riwayat peminjaman seluruh anggota dengan filter.
     */
    public function riwayat(Request $request)
    {
        $query = Peminjaman::with(['profile.user', 'unit', 'admin']);

        // Filter berdasarkan anggota
        if ($request->filled('member_id')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('user_id', $request->member_id);
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $riwayat = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->appends($request->query());

        $members = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.returns.riwayat', compact('riwayat', 'members'));
    }

    /**
     * Admin: Cetak laporan riwayat per anggota.
     */
    public function cetakLaporan(User $member)
    {
        $member->load('profile');
        $profile = $member->profile;

        $riwayat = Peminjaman::where('profile_id', $profile->id)
            ->with(['unit', 'admin'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        $totalDenda = $riwayat->sum('denda');
        $totalPinjaman = $riwayat->count();
        $totalTerlambat = $riwayat->where('hari_terlambat', '>', 0)->count();

        return view('admin.returns.cetak', compact('member', 'profile', 'riwayat', 'totalDenda', 'totalPinjaman', 'totalTerlambat'));
    }
}
