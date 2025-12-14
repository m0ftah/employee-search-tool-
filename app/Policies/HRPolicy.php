<?php

namespace App\Policies;

use App\Models\User;
use App\Models\HR;
use Illuminate\Auth\Access\HandlesAuthorization;

class HRPolicy
{
    use HandlesAuthorization;

    /**
     * Check if user is super admin
     */
    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('view_any_h::r');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('view_h::r');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('create_h::r');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('update_h::r');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('delete_h::r');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('delete_any_h::r');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('force_delete_h::r');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('force_delete_any_h::r');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('restore_h::r');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('restore_any_h::r');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, HR $hR): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('replicate_h::r');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('reorder_h::r');
    }
}
