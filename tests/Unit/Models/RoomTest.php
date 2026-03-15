<?php

use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// -- Relationships --
test('room owner returns the project user', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create();
    $room = Room::factory()->for($project)->create();

    expect($room->owner()->is($user))->toBeTrue();
});

test('rooms can have multiple members', function () {
    $room = Room::factory()->create();
    $members = User::factory()->count(3)->create();

    $room->members()->attach($members->pluck('id'), ['role' => 'viewer']);

    expect($room->members)->toHaveCount(3);
});

// -- Pivot --
test('room members have role on pivot table', function () {
    $room = Room::factory()->create();
    $user = User::factory()->create();

    $room->members()->attach($user, ['role' => 'editor']);

    $member = $room->members()->first();
    expect($member->pivot->role)->toBe('editor');
});
