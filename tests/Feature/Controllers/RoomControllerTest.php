<?php

use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Tests\TestCase;

/** @var TestCase $this */

// -- Creation --
test('can create room', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();

    $this->actingAs($user)
        ->post(route('rooms.store', $project), [
            'name' => 'Test Room',
            'public' => true,
        ])
        ->assertRedirect();

    expect($project->fresh()->room)->not->toBeNull();
    expect($project->fresh()->room->name)->toBe('Test Room');
});

test('project can only have one room', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();

    Room::factory()->for($project)->create();

    $this->actingAs($user)
        ->post(route('rooms.store', $project), [
            'name' => 'Second Room',
            'public' => true,
        ])
        ->assertForbidden();
});

// -- Authentication --
test('create room requires auth', function () {
    $project = Project::factory()->create();

    $this->post(route('rooms.store', $project), [
        'name' => 'Test Room',
        'public' => true,
    ])->assertRedirect(route('login'));
});

test('destroy room requires auth', function () {
    $room = Room::factory()->create();

    $this->delete(route('rooms.destroy', $room))
        ->assertRedirect(route('login'));
});

// -- Deletion --
test('owner can delete room', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $room = Room::factory()->for($project)->create();

    $this->actingAs($user)
        ->delete(route('rooms.destroy', $room))
        ->assertRedirect(route('rooms.index'));

    expect(Room::count())->toBe(0);
});

test('non-owner cannot delete room', function () {
    $room = Room::factory()->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('rooms.destroy', $room))
        ->assertForbidden();

    expect(Room::count())->toBe(1);
});

// -- Viewing --
test('owner can view their private room', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    Room::factory()->for($project)->create(['public' => false]);

    $this->actingAs($user)
        ->get(route('rooms.show', $project->room))
        ->assertSuccessful();
});

test('non-owner cannot view private room', function () {
    $room = Room::factory()->create(['public' => false]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('rooms.show', $room))
        ->assertForbidden();
});

test('non-owner can view public room', function () {
    $room = Room::factory()->create(['public' => true]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('rooms.show', $room))
        ->assertSuccessful();
});

test('private rooms do not show on index', function () {
    Room::factory()->count(2)->create(['public' => true]);
    Room::factory()->count(3)->create(['public' => false]);

    $this->get(route('rooms.index'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('Rooms/Index')
            ->has('rooms', 2)
        );
});
