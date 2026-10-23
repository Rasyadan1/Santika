<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'nomor_anggota',
        'no_telepon',
        'alamat',
        'tanggal_daftar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'date',
        ];
    }

    /**
     * Relasi: Profile dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Profile memiliki banyak Peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Hitung jumlah pinjaman aktif.
     */
    public function jumlahPinjamanAktif(): int
    {
        return $this->peminjaman()->where('status', 'dipinjam')->count();
    }
}
