<?php

namespace Database\Factories;

use App\Models\Canvas;
use App\Models\Pixel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pixel>
 */
class PixelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $canvas = Canvas::factory()->create();

        return [
            'canvas_id' => $canvas->id,
            'x' => fake()->numberBetween(0, 126),
            'y' => fake()->numberBetween(0, 126),
            'color' => fake()->hexColor(),
            'placed_by' => User::factory(),
        ];
    }

    public function forCanvas(Canvas $canvas): static
    {
        return $this->state(fn () => [
            'canvas_id' => $canvas->id,
            'x' => fake()->numberBetween(0, $canvas->project->width),
            'y' => fake()->numberBetween(0, $canvas->project->height),
        ]);
    }
}
