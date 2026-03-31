<?php

use App\Models\Canvas;
use App\Models\Pixel;
use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Tests\TestCase;

/** @var TestCase $this */
function validPixels(int $count = 1): array
{
    return collect(range(1, $count))->map(fn ($i) => [
        'x' => $i,
        'y' => $i,
        'color' => '#ff0000',
    ])->all();
}

// -- Authorization --
test('guest cannot store pixels', function () {
    $canvas = Canvas::factory()->create();

    $this->put(route('canvas.pixels.store', $canvas), ['pixels' => validPixels()])
        ->assertRedirect(route('login'));
});

test('non-owner non-member cannot store pixels', function () {
    $canvas = Canvas::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('canvas.pixels.store', $canvas), ['pixels' => validPixels()])
        ->assertForbidden();
});

test('owner can store pixels', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), ['pixels' => validPixels()])
        ->assertSuccessful();

    expect(Pixel::count())->toBe(1);
});

test('room member can store pixels', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $room = Room::factory()->for($project)->create();
    $room->members()->attach($member);
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($member)
        ->put(route('canvas.pixels.store', $canvas), ['pixels' => validPixels()])
        ->assertSuccessful();

    expect(Pixel::count())->toBe(1);
});

// -- Store behavior --
test('stores pixel with correct attributes', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), [
            'pixels' => [['x' => 5, 'y' => 10, 'color' => '#abcdef']],
        ])
        ->assertSuccessful();

    $pixel = Pixel::first();
    expect($pixel->canvas_id)->toBe($canvas->id)
        ->and($pixel->user_id)->toBe($owner->id)
        ->and($pixel->x)->toBe(5)
        ->and($pixel->y)->toBe(10)
        ->and($pixel->color)->toBe('#abcdef');
});

test('stores multiple pixels in one request', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), ['pixels' => validPixels(3)])
        ->assertSuccessful();

    expect(Pixel::count())->toBe(3);
});

test('upserts pixel at same coordinates', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), [
            'pixels' => [['x' => 0, 'y' => 0, 'color' => '#000000']],
        ]);

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), [
            'pixels' => [['x' => 0, 'y' => 0, 'color' => '#ffffff']],
        ]);

    expect(Pixel::count())->toBe(1)
        ->and(Pixel::first()->color)->toBe('#ffffff');
});

// -- Validation --
test('rejects missing pixels array', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), [])
        ->assertSessionHasErrors('pixels');
});

test('rejects invalid pixel data', function (array $pixel, string $errorField) {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->put(route('canvas.pixels.store', $canvas), ['pixels' => [$pixel]])
        ->assertSessionHasErrors($errorField);
})->with([
    'missing x' => [['y' => 0, 'color' => '#ff0000'], 'pixels.0.x'],
    'missing y' => [['x' => 0, 'color' => '#ff0000'], 'pixels.0.y'],
    'missing color' => [['x' => 0, 'y' => 0], 'pixels.0.color'],
    'x too low' => [['x' => -1, 'y' => 0, 'color' => '#ff0000'], 'pixels.0.x'],
    'x too high' => [['x' => 129, 'y' => 0, 'color' => '#ff0000'], 'pixels.0.x'],
    'y too low' => [['x' => 0, 'y' => -1, 'color' => '#ff0000'], 'pixels.0.y'],
    'y too high' => [['x' => 0, 'y' => 129, 'color' => '#ff0000'], 'pixels.0.y'],
    'invalid color' => [['x' => 0, 'y' => 0, 'color' => 'not-a-color'], 'pixels.0.color'],
]);
