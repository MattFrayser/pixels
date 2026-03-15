<?php

namespace Database\Factories;

use App\Models\Canvas;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'public' => fake()->boolean(),
            'width' => fake()->randomElement([16, 32, 64, 128]),
            'height' => fake()->randomElement([16, 32, 64, 128]),
            'framerate' => 12,
        ];
    }

    public function withFrames(int $count): static
    {
        return $this->afterCreating(function (Project $project) use ($count) {
            Canvas::factory()
                ->count($count)
                ->sequence(fn ($sequence) => ['sort_order' => $sequence->index])
                ->create(['project_id' => $project->id]);

        });
    }
}
