<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_part',
        'nama_part',
        'kategori',
    ];

    /**
     * Relationship with Produksi
     */
    public function produksis(): HasMany
    {
        return $this->hasMany(Produksi::class, 'part_id');
    }
}
