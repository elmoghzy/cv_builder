<?php

namespace App\Policies;

use App\Models\Cv;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CvPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own CVs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id && !$cv->is_paid;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cv $cv): bool
    {
        return $user->id === $cv->user_id && !$cv->is_paid;
    }

    /**
     * Determine whether the user can download the CV.
     */
    public function download(User $user, Cv $cv): bool
    {
        return ($user->id === $cv->user_id && $cv->is_paid) || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cv $cv): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cv $cv): bool
    {
        return $user->hasRole('admin');
    }
}
