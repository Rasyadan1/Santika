<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $filters = $request->validate([
            'member_id' => ['nullable', 'integer', Rule::exists('users', 'id')->where('role', 'user')],
            'status' => ['nullable', Rule::in(['dipinjam', 'dikembalikan'])],
        ]);

        $query = Peminjaman::with(['profile.user', 'unit', 'admin']);

        if (!empty($filters['member_id'])) {
            $query->whereHas('profile', function ($q) use ($filters) {
                $q->where('user_id', $filters['member_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $riwayat = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->appends($request->query());

        $members = User::where('role', 'user')
            ->whereHas('profile')
            ->orderBy('name')
            ->get();

        return view('admin.returns.riwayat', compact('riwayat', 'members'));
    }

    /**
     * Admin: Cetak laporan riwayat per anggota.
     */
    public function cetakLaporan(User $member)
    {
        abort_unless($member->role === 'user', 404);

        $member->load('profile');
        $profile = $member->profile;

        abort_unless($profile, 404);

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
