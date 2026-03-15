<?php

namespace Database\Factories;

use App\Models\Canvas;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Canvas>
 */
class CanvasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'sort_order' => 0,
            'snapshot' => null,
        ];
    }
}
