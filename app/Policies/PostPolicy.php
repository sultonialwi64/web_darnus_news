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
        return true; 
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
        // Semua user yang punya role (admin/journalist) bisa buat berita
        return $user->isAdmin() || $user->role === 'journalist';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // 1. Admin Berkuasa Penuh
        if ($user->isAdmin()) {
            return true;
        }

        // 2. Cek apakah user ini adalah Jurnalis yang ditunjuk sebagai Author atau Editor di berita tersebut
        $journalistId = $user->journalist?->id;
        if ($journalistId && ($post->author_id === $journalistId || $post->editor_id === $journalistId)) {
            return true;
        }

        // 3. Cek apakah user ini adalah yang menginput/memasukkan data beritanya (Created By)
        if ($post->created_by && $post->created_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Logika hapus sama dengan update
        return $this->update($user, $post);
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
