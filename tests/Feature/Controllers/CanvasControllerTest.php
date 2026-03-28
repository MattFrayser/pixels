<?php

use App\Models\Canvas;
use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Tests\TestCase;

/** @var TestCase $this */

// -- Creation --
test('owner can create canvas', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->post(route('canvas.store', $project))
        ->assertSuccessful();

    expect(Canvas::count())->toBe(1);
    expect(Canvas::first()->sort_order)->toBe(1);
});

test('member can create canvas', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $room = Room::factory()->for($project)->create();
    $room->members()->attach($member);

    $this->actingAs($member)
        ->post(route('canvas.store', $project))
        ->assertSuccessful();

    expect(Canvas::count())->toBe(1);
});

// -- Update --
test('owner can update canvas sort order', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    Canvas::factory()->for($project)->create(['sort_order' => 0]);
    $canvas = Canvas::factory()->for($project)->create(['sort_order' => 1]);

    $this->actingAs($owner)
        ->patch(route('canvas.update', $canvas), [
            'sort_order' => 0,
        ])
        ->assertSuccessful();

    expect($canvas->fresh()->sort_order)->toBe(0);
});

test('member can update canvas sort order', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $room = Room::factory()->for($project)->create();
    $room->members()->attach($member);
    $canvas = Canvas::factory()->for($project)->create(['sort_order' => 0]);

    $this->actingAs($member)
        ->patch(route('canvas.update', $canvas), [
            'sort_order' => 0,
        ])
        ->assertSuccessful();
});

// -- Deletion --
test('owner can delete canvas', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($owner)
        ->delete(route('canvas.destroy', $canvas))
        ->assertSuccessful();

    expect(Canvas::count())->toBe(0);
});

test('non-owner cannot delete canvas', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $room = Room::factory()->for($project)->create();
    $room->members()->attach($member);
    $canvas = Canvas::factory()->for($project)->create();

    $this->actingAs($member)
        ->delete(route('canvas.destroy', $canvas))
        ->assertForbidden();

    expect(Canvas::count())->toBe(1);
});

test('guest cannot create canvas', function () {
    $project = Project::factory()->create();

    $this->post(route('canvas.store', $project))
        ->assertRedirect(route('login'));
});
