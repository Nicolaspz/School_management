<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Falta;
use Illuminate\Auth\Access\HandlesAuthorization;

class FaltaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_falta');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Falta $falta): bool
    {
        return $user->can('view_falta');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_falta');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Falta $falta): bool
    {
        return $user->can('update_falta');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Falta $falta): bool
    {
        return $user->can('delete_falta');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_falta');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Falta $falta): bool
    {
        return $user->can('force_delete_falta');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_falta');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Falta $falta): bool
    {
        return $user->can('restore_falta');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_falta');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Falta $falta): bool
    {
        return $user->can('replicate_falta');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_falta');
    }
}
