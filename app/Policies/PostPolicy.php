<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang masuk panel bisa lihat daftar berita
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return true; 
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Semua user (Wartawan/Admin) bisa nambah berita
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // 1. Admin bisa edit semua berita
        if ($user->isAdmin()) return true;

        // 2. Jika jurnalis yang login adalah Author berita ini
        if ($post->author_id && $user->journalist && $post->author_id === $user->journalist->id) {
            return true;
        }

        // 3. Jika jurnalis yang login adalah pembuat data beritanya
        return $post->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // 1. Admin bisa hapus semua berita
        if ($user->isAdmin()) return true;

        // 2. Jika jurnalis yang login adalah Author berita ini
        if ($post->author_id && $user->journalist && $post->author_id === $user->journalist->id) {
            return true;
        }

        // 3. Jika jurnalis yang login adalah pembuat data beritanya
        return $post->created_by === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->isAdmin();
    }
}
