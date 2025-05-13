<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view teams list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        // Admin can view any team, organizers can only view assigned teams
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // Example logic for organizer: check if team is associated with organizer
        // This would need to be customized based on your actual data model
        return $user->hasRole('organizer') && $user->id == $team->owner_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create teams
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        // Admin can update any team, organizers can only update their own teams
        if ($user->hasRole('admin')) {
            return true;
        }
        
        return $user->hasRole('organizer') && $user->id == $team->owner_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        // Only admins can delete teams
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        // Only admins can restore teams
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        // Only admins can force delete teams
        return $user->hasRole('admin');
    }
}
