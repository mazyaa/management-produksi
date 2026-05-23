<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'role' => Role::class,
        ];
    }

    /**
     * Relationship with Produksi (as operator)
     */
    public function produksis(): HasMany
    {
        return $this->hasMany(Produksi::class, 'operator_id');
    }

    /**
     * Relationship with VerifikasiProduksi (as verifier)
     */
    public function verifikasiProduksis(): HasMany
    {
        return $this->hasMany(VerifikasiProduksi::class, 'verified_by');
    }

    /**
     * Role checks helper methods
     */
    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->role === Role::OPERATOR;
    }

    public function isLeader(): bool
    {
        return $this->role === Role::LEADER;
    }

    public function isAssistantManager(): bool
    {
        return $this->role === Role::ASSISTANT_MANAGER;
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role->value, $roles);
    }

    /**
     * Scope active users
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
