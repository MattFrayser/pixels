<?php

namespace App\Policies;

use App\Models\Canvas;
use App\Models\Project;
use App\Models\User;
use App\RoomRole;

class CanvasPolicy
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
    public function view(User $user, Canvas $canvas): bool
    {
        return $user->id === $canvas->project->user_id || $canvas->project->public;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        $member = $project->room?->members()->where('user_id', $user->id)->first();

        return $user->id === $project->user_id || $member?->pivot->role === RoomRole::Editor->value;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Canvas $canvas): bool
    {
        $member = $canvas->project->room?->members()->where('user_id', $user->id)->first();

        return $user->id === $canvas->project->user_id || $member?->pivot->role === RoomRole::Editor->value;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Canvas $canvas): bool
    {
        return $user->id === $canvas->project->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Canvas $canvas): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Canvas $canvas): bool
    {
        return false;
    }
}
