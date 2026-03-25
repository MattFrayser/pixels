<?php

namespace Database\Seeders;

use App\Models\Pixel;
use App\Models\Project;
use App\Models\Room;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Project, in room, with users
        $owner = User::factory()->create(['name' => 'test user']);
        $members = User::factory()->count(3)->create();

        $project = Project::factory()->withFrames(3)->create(['user_id' => $owner->id]);
        $room = Room::factory()->create(['project_id' => $project->id, 'public' => true]);

        foreach ($members as $member) {
            $room->members()->attach($member->id, ['role' => 'editor']);
        }

        $canvas = $project->canvases()->orderBy('sort_order')->first();
        Pixel::factory()->forCanvas($canvas)->count(10)->create([
            'user_id' => $room->members()->random()->id,
        ]);

        // Private Project
        Project::factory()->withFrames(5)->create();

        // Public Project
        Project::factory()->withFrames(3)->create(['public' => true]);

    }
}
