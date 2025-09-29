<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Tentukan apakah pengguna dapat melihat daftar pengguna.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin dan petugas yang bisa melihat daftar pengguna
        return $user->role === 'admin' || $user->role === 'petugas';
    }

    /**
     * Tentukan apakah pengguna dapat melihat detail pengguna.
     */
    public function view(User $user, User $model): bool
    {
        // Admin bisa melihat semua, petugas bisa melihat, dan pengguna bisa melihat diri sendiri
        return $user->role === 'admin' || $user->role === 'petugas' || $user->id === $model->id;
    }

    /**
     * Tentukan apakah pengguna dapat membuat pengguna baru.
     */
    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat pengguna baru
        return $user->role === 'admin';
    }

    /**
     * Tentukan apakah pengguna dapat memperbarui pengguna.
     */
    public function update(User $user, User $model): bool
    {
        // Admin bisa memperbarui semua, pengguna bisa memperbarui diri sendiri
        return $user->role === 'admin' || $user->id === $model->id;
    }

    /**
     * Tentukan apakah pengguna dapat menghapus pengguna.
     */
    public function delete(User $user, User $model): bool
    {
        // Hanya admin yang bisa menghapus pengguna lain, dan tidak bisa menghapus diri sendiri
        return $user->role === 'admin' && $user->id !== $model->id;
    }
}