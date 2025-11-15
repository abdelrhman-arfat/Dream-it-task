<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AuthorPolicy
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
        return $user->can('author.edit') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can view another user (adminView).
     */
    public function adminView(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('author.view') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can delete another user (adminDelete).
     */
    public function adminDelete(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('author.delete') && !$user->hasRole('author');
    }

    /**
     * Check if the current user can create a new user (adminCreate).
     */
    public function adminCreate(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->can('author.store') && !$user->hasRole('author');
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->adminCreate($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $author): bool
    {
        return $this->adminUpdate($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $author): bool
    {
        return $this->adminDelete($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $author): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $author): bool
    {
        return false;
    }
}
