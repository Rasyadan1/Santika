<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'deskripsi',
        'status',
        'tarif_per_hari',
    ];

    /**
     * Relasi: Unit memiliki banyak Category (many-to-many).
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_unit');
    }

    /**
     * Relasi: Unit memiliki banyak Peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Cek apakah unit tersedia untuk dipinjam.
     */
    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }

    /**
     * Cek apakah unit memiliki pinjaman aktif.
     */
    public function hasPinjamanAktif(): bool
    {
        return $this->peminjaman()->where('status', 'dipinjam')->exists();
    }
}
