<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Profile;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanService
{
    /**
     * Proses peminjaman unit oleh anggota.
     *
     * @throws \Exception
     */
    public function pinjam(Profile $profile, Unit $unit): Peminjaman
    {
        return DB::transaction(function () use ($profile, $unit) {
            // Serialize borrowing attempts by one member so the active-loan cap cannot race.
            $lockedProfile = Profile::whereKey($profile->id)->lockForUpdate()->first();
            if (!$lockedProfile) {
                throw new \Exception('Profil anggota tidak ditemukan.');
            }

            // Lock the unit to prevent two members renting the same physical item.
            $unit = Unit::whereKey($unit->id)->lockForUpdate()->first();

            if (!$unit || !$unit->isTersedia()) {
                throw new \Exception('Unit ini sedang tidak tersedia untuk dipinjam.');
            }

            // Cek batas pinjaman aktif
            $batasPinjam = config('santika.batas_pinjam', 2);
            $pinjamanAktif = $lockedProfile->jumlahPinjamanAktif();

            if ($pinjamanAktif >= $batasPinjam) {
                throw new \Exception("Anda sudah memiliki {$pinjamanAktif} pinjaman aktif. Maksimal {$batasPinjam} unit.");
            }

            $tanggalPinjam = Carbon::today();
            $durasiPinjam = config('santika.durasi_pinjam', 5);

            // Buat record peminjaman
            $peminjaman = Peminjaman::create([
                'profile_id' => $lockedProfile->id,
                'unit_id' => $unit->id,
                'tanggal_pinjam' => $tanggalPinjam,
                'tanggal_jatuh_tempo' => $tanggalPinjam->copy()->addDays($durasiPinjam),
                'status' => 'dipinjam',
            ]);

            // Update status unit
            $unit->update(['status' => 'dipinjam']);

            return $peminjaman;
        });
    }

    /**
     * Proses pengembalian unit oleh admin.
     *
     * @throws \Exception
     */
    public function kembalikan(Peminjaman $peminjaman, int $adminId): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $adminId) {
            // Lock baris peminjaman
            $peminjaman = Peminjaman::where('id', $peminjaman->id)->lockForUpdate()->first();

            if (!$peminjaman || $peminjaman->status !== 'dipinjam') {
                throw new \Exception('Peminjaman ini sudah dikembalikan atau tidak valid.');
            }

            $tanggalKembali = Carbon::today();
            $hariTerlambat = $peminjaman->hitungHariTerlambat($tanggalKembali);

            // Denda keterlambatan memakai tarif tetap yang terpisah dari harga sewa unit.
            $dendaPerHari = (int) config('santika.denda_per_hari', 50000);
            $denda = $hariTerlambat * $dendaPerHari;

            $unit = $peminjaman->unit;

            // Update peminjaman
            $peminjaman->update([
                'tanggal_kembali' => $tanggalKembali,
                'status' => 'dikembalikan',
                'hari_terlambat' => $hariTerlambat,
                'denda' => $denda,
                'diproses_oleh' => $adminId,
            ]);

            // Update status unit kembali tersedia
            $unit->update(['status' => 'tersedia']);

            return $peminjaman->fresh();
        });
    }
}
