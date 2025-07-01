<?php

namespace App\Policies;

use App\Models\Stabile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StabilePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Stabile $stabile): bool
    {
        // L'utente può vedere lo stabile solo se è l'amministratore associato a quello stabile.
        // L'operatore '?->' (nullsafe) previene errori se l'utente non ha un profilo amministratore.
        return $user->amministratore?->id_amministratore === $stabile->amministratore_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Qualsiasi utente con il permesso 'manage-stabili' può creare uno stabile.
        return $user->can('manage-stabili');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Stabile $stabile): bool
    {
        // L'utente può modificare lo stabile solo se ne è l'amministratore associato e ha il permesso.
        return $user->amministratore?->id_amministratore === $stabile->amministratore_id
            && $user->can('manage-stabili');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Stabile $stabile): bool
    {
        // L'utente può eliminare lo stabile solo se ne è l'amministratore associato e ha il permesso.
        return $user->amministratore?->id_amministratore === $stabile->amministratore_id
            && $user->can('manage-stabili');
    }
}
