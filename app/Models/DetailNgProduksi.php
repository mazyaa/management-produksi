<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailNgProduksi extends Model
{
    use HasFactory;

    protected $table = 'detail_ng_produksis';

    protected $fillable = [
        'produksi_id',
        'kategori_ng_id',
        'qty',
        'catatan',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    /**
     * Relationship with Produksi
     */
    public function produksi(): BelongsTo
    {
        return $this->belongsTo(Produksi::class, 'produksi_id');
    }

    /**
     * Relationship with KategoriNg
     */
    public function kategoriNg(): BelongsTo
    {
        return $this->belongsTo(KategoriNg::class, 'kategori_ng_id');
    }
}
