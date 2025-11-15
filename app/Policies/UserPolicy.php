<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Check if the current user can update another user (adminUpdate).
     */
    public function adminUpdate(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        // Example logic: must have 'user.edit' permission and not be an 'author'
        return $user->can('user.edit') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can view another user (adminView).
     */
    public function adminView(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('user.view') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can delete another user (adminDelete).
     */
    public function adminDelete(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('user.delete') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can create a new user (adminCreate).
     */
    public function adminCreate(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('user.store') && !$user->hasRole('author');
    }

    /**
     * Optional: fallback default Laravel methods
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, User $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->adminCreate($user);
    }

    public function update(User $user, User $model): bool
    {
        return $this->adminUpdate($user, $model);
    }

    public function delete(User $user, User $model): bool
    {
        return $this->adminDelete($user);
    }

    public function restore(User $user, User $model): bool
    {
        return false;
    }

    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
