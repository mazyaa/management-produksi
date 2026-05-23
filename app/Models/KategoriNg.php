<?php

namespace App\Models;

use App\Enums\Severity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriNg extends Model
{
    use HasFactory;

    protected $table = 'kategori_ngs';

    protected $fillable = [
        'kode_ng',
        'nama_ng',
        'severity',
    ];

    protected $casts = [
        'severity' => Severity::class,
    ];

    /**
     * Relationship with DetailNgProduksi
     */
    public function detailNgProduksis(): HasMany
    {
        return $this->hasMany(DetailNgProduksi::class, 'kategori_ng_id');
    }
}
