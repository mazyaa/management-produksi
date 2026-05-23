<?php

namespace App\Policies;

use App\Models\Produksi;
use App\Models\User;
use App\Enums\Role;
use App\Enums\StatusProduksi;
use Illuminate\Auth\Access\Response;

class ProduksiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list (scoped by role in controller)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Produksi $produksi): bool
    {
        if ($user->hasRole('admin', 'leader', 'assistant_manager')) {
            return true;
        }

        return $user->id === $produksi->operator_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin', 'operator');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Produksi $produksi): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            return $user->id === $produksi->operator_id && 
                   in_array($produksi->status, [StatusProduksi::DRAFT, StatusProduksi::REJECTED, StatusProduksi::REVISED]);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Produksi $produksi): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isOperator()) {
            return $user->id === $produksi->operator_id && $produksi->status === StatusProduksi::DRAFT;
        }

        return false;
    }

    /**
     * Determine whether the user can submit the model for verification.
     */
    public function submit(User $user, Produksi $produksi): bool
    {
        if ($user->isOperator()) {
            return $user->id === $produksi->operator_id && 
                   in_array($produksi->status, [StatusProduksi::DRAFT, StatusProduksi::REVISED]);
        }

        return $user->isAdmin();
    }

    /**
     * Determine whether the user can verify the model.
     */
    public function verify(User $user, Produksi $produksi): bool
    {
        return $user->hasRole('admin', 'leader') && $produksi->status === StatusProduksi::SUBMITTED;
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, Produksi $produksi): bool
    {
        return $user->hasRole('admin', 'leader') && $produksi->status === StatusProduksi::SUBMITTED;
    }
}
