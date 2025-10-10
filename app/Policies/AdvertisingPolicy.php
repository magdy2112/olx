<?php

namespace App\Policies;

use App\Models\Advertising;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdvertisingPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function createNewAdvertising(User $user): bool
    {
        return in_array($user->role, ['user','admin','prouser']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Advertising $advertising): bool
    {
        return $user->id === $advertising->user_id || in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Advertising $advertising): bool
    {
        return $user->id === $advertising->user_id || in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Advertising $advertising): bool
    {
        return $user->role == 'admin';
    }

 
}
