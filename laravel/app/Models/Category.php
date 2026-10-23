<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    /**
     * Relasi: Category memiliki banyak Unit (many-to-many).
     */
    public function units(): BelongsToMany
    {
        return $this->belongsToMany(Unit::class, 'category_unit');
    }
}
