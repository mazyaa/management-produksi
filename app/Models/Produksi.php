<?php

namespace App\Models;

use App\Enums\StatusProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksis';

    protected $fillable = [
        'tanggal_produksi',
        'shift_id',
        'mesin_id',
        'part_id',
        'operator_id',
        'target_qty',
        'good_qty',
        'total_ng_qty',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'status' => StatusProduksi::class,
        'target_qty' => 'integer',
        'good_qty' => 'integer',
        'total_ng_qty' => 'integer',
    ];

    /**
     * Relationship with Shift
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    /**
     * Relationship with Mesin
     */
    public function mesin(): BelongsTo
    {
        return $this->belongsTo(Mesin::class, 'mesin_id');
    }

    /**
     * Relationship with Part
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    /**
     * Relationship with Operator (User)
     */
    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    /**
     * Relationship with Detail NG
     */
    public function detailNgProduksis(): HasMany
    {
        return $this->hasMany(DetailNgProduksi::class, 'produksi_id');
    }

    /**
     * Relationship with VerifikasiProduksis
     */
    public function verifikasiProduksis(): HasMany
    {
        return $this->hasMany(VerifikasiProduksi::class, 'produksi_id');
    }

    /**
     * Relationship with the latest VerifikasiProduksi
     */
    public function latestVerifikasi(): HasOne
    {
        return $this->hasOne(VerifikasiProduksi::class, 'produksi_id')->latestOfMany();
    }

    /**
     * Status checkers helper methods
     */
    public function isDraft(): bool
    {
        return $this->status === StatusProduksi::DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === StatusProduksi::SUBMITTED;
    }

    public function isVerified(): bool
    {
        return $this->status === StatusProduksi::VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->status === StatusProduksi::REJECTED;
    }

    public function isRevised(): bool
    {
        return $this->status === StatusProduksi::REVISED;
    }

    /**
     * Achievement rate computed attribute (Good Qty / Target Qty * 100)
     */
    public function getAchievementRateAttribute(): float
    {
        if ($this->target_qty <= 0) {
            return 0.0;
        }
        return round(($this->good_qty / $this->target_qty) * 100, 1);
    }

    /**
     * Scopes
     */
    public function scopeByStatus(Builder $query, string|StatusProduksi $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('tanggal_produksi', today());
    }

    public function scopeByOperator(Builder $query, int $operatorId): Builder
    {
        return $query->where('operator_id', $operatorId);
    }
}
