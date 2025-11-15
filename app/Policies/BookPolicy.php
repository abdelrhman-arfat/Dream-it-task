<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    // Author can manage only their own books
    public function adminUpdate(?User $user, ?Book $book = null): bool
    {
        if (!$user) return false;

        // author can update their own books
        if ($user->hasRole('author') && $book && $book->author_id === $user->id) {
            return true;
        }

        // admin/editor logic
        return $user->can('book.edit') && !$user->hasRole("author");
    }

    public function adminView(?User $user, ?Book $book = null): bool
    {
        return true;
    }

    public function adminDelete(?User $user, ?Book $book = null): bool
    {
        if (!$user) return false;

        // author can delete their own books
        if ($user->hasRole('author') && $book && $book->author_id === $user->id) {
            return true;
        }

        return $user->can('book.delete') && !$user->hasRole("author");
    }

    public function adminCreate(?User $user): bool
    {
        if (!$user) return false;

        // author can create their own books
        if ($user->hasRole('author')) {
            return true;
        }

        return $user->can('book.store') && !$user->hasRole("author");
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
    {
        return $this->adminView($user, $book);
    }

    public function create(User $user): bool
    {
        return $this->adminCreate($user);
    }

    public function update(User $user, Book $book): bool
    {
        return $this->adminUpdate($user, $book);
    }

    public function delete(User $user, Book $book): bool
    {
        return $this->adminDelete($user, $book);
    }

    public function restore(User $user, Book $book): bool
    {
        return false;
    }

    public function forceDelete(User $user, Book $book): bool
    {
        return false;
    }
}
