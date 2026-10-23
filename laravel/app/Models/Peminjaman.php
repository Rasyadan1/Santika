<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'profile_id',
        'unit_id',
        'diproses_oleh',
        'tanggal_pinjam',
        'tanggal_jatuh_tempo',
        'tanggal_kembali',
        'status',
        'hari_terlambat',
        'denda',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
        ];
    }

    /**
     * Relasi: Peminjaman dimiliki oleh satu Profile (anggota).
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Relasi: Peminjaman untuk satu Unit.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relasi: Admin yang memproses pengembalian.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    /**
     * Cek apakah peminjaman sudah terlambat.
     */
    public function isTerlambat(): bool
    {
        if ($this->status === 'dikembalikan') {
            return $this->hari_terlambat > 0;
        }

        return now()->startOfDay()->greaterThan($this->tanggal_jatuh_tempo);
    }

    /**
     * Hitung hari terlambat berdasarkan tanggal kembali.
     */
    public function hitungHariTerlambat(?\Carbon\Carbon $tanggalKembali = null): int
    {
        $tanggalKembali = $tanggalKembali ?? now();
        $selisih = $tanggalKembali->startOfDay()->diffInDays($this->tanggal_jatuh_tempo, false);

        return max(0, (int) -$selisih);
    }
}
