<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, Project $project): bool
    {
        return $project->public || $user->id === $project->user_id;
    }

    public function update(User $user, Project $project): bool
    {
        $isMember = $project->room?->members()->where('user_id', $user->id)->exists();

        return $user->id === $project->user_id || $isMember;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
