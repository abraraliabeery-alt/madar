<?php

namespace App\Policies;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FacilityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Facility $facility): bool
    {
        // Allow admins, facility owners, or users associated with the facility
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('facility')) {
            // Check if user is associated with this facility
            return $user->facilities()->where('facilities.id', $facility->id)->exists() 
                || $user->facility_id == $facility->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('facility');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Facility $facility): bool
    {
        // Allow admins to update any facility
        if ($user->hasRole('admin')) {
            return true;
        }

        // Allow facility users to update facilities they're associated with
        if ($user->hasRole('facility')) {
            return $user->facilities()->where('facilities.id', $facility->id)->exists() 
                || $user->facility_id == $facility->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Facility $facility): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Facility $facility): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Facility $facility): bool
    {
        return false;
    }
}
