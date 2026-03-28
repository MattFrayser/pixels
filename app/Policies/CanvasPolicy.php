<?php

namespace App\Policies;

use App\Models\Canvas;
use App\Models\Project;
use App\Models\User;

class CanvasPolicy
{
    public function view(User $user, Canvas $canvas): bool
    {
        return $user->id === $canvas->project->user_id || $canvas->project->public;
    }

    public function create(User $user, Project $project): bool
    {
        $isMember = $project->room?->members()->where('user_id', $user->id)->exists();

        return $user->id === $project->user_id || $isMember;
    }

    public function update(User $user, Canvas $canvas): bool
    {
        $isMember = $canvas->project->room?->members()->where('user_id', $user->id)->exists();

        return $user->id === $canvas->project->user_id || $isMember;
    }

    public function delete(User $user, Canvas $canvas): bool
    {
        return $user->id === $canvas->project->user_id;
    }
}
