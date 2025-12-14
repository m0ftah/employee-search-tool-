<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Application;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
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
        return $user->can('view_any_application');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('view_application');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('create_application');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('update_application');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('delete_application');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('delete_any_application');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('force_delete_application');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('force_delete_any_application');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('restore_application');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('restore_any_application');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Application $application): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('replicate_application');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        if ($this->isSuperAdmin($user)) {
            return true;
        }
        return $user->can('reorder_application');
    }
}
