<?php

use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Tests\TestCase;

/** @var TestCase $this */

// -- Joining --
test('member can join public room', function () {
    $project = Project::factory()->create(['public' => true]);
    $room = Room::factory()->for($project)->create(['public' => true]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('rooms.members.store', $room))
        ->assertRedirect();

    expect($room->members()->where('user_id', $user->id)->exists())->toBeTrue();
});

test('member cannot join private room', function () {
    $project = Project::factory()->create();
    $room = Room::factory()->for($project)->create(['public' => false]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('rooms.members.store', $room))
        ->assertForbidden();
});

test('owner cannot join their own room', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create(['public' => true]);
    $room = Room::factory()->for($project)->create(['public' => true]);

    $this->actingAs($user)
        ->post(route('rooms.members.store', $room))
        ->assertForbidden();
});

// -- Leaving --
test('member can leave room they are in', function () {
    $project = Project::factory()->create();
    $room = Room::factory()->for($project)->create(['public' => false]);
    $user = User::factory()->create();

    $room->members()->attach($user->id);

    $this->actingAs($user)
        ->delete(route('rooms.members.destroy', $room))
        ->assertRedirect();

    expect($room->members()->where('user_id', $user->id)->exists())->toBeFalse();
});

test('user cannot leave room they are not in', function () {
    $project = Project::factory()->create();
    $room = Room::factory()->for($project)->create(['public' => false]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('rooms.members.destroy', $room))
        ->assertForbidden();
});

// -- Authentication --
test('join room requires auth', function () {
    $room = Room::factory()->create(['public' => true]);

    $this->post(route('rooms.members.store', $room))
        ->assertRedirect(route('login'));
});

test('leave room requires auth', function () {
    $room = Room::factory()->create();

    $this->delete(route('rooms.members.destroy', $room))
        ->assertRedirect(route('login'));
});
