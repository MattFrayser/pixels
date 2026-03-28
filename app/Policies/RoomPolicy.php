<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function view(User $user, Room $room): bool
    {
        return $room->public || $user->id === $room->project->user_id;
    }

    public function create(User $user, Project $project): bool
    {
        return $user->id === $project->user_id && $project->room === null;
    }

    public function update(User $user, Room $room): bool
    {
        return $user->id === $room->project->user_id;
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->id === $room->project->user_id;
    }

    public function join(User $user, Room $room): bool
    {
        return $room->public
        && $user->id !== $room->project->user_id
        && ! $room->members()->where('user_id', $user->id)->exists();
    }

    public function leave(User $user, Room $room): bool
    {
        return $room->members()->where('user_id', $user->id)->exists();
    }
}
