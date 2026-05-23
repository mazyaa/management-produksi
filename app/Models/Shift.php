<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_shift',
        'jam_masuk',
        'jam_selesai',
    ];

    /**
     * Relationship with Produksi
     */
    public function produksis(): HasMany
    {
        return $this->hasMany(Produksi::class, 'shift_id');
    }
}
