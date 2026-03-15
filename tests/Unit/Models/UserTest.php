<?php

use App\Models\Project;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// --Relationships--
test('user has many projects', function () {
    $user = User::factory()->create();
    Project::factory()->count(2)->for($user)->create();

    expect($user->projects)->toHaveCount(2);
});

test('user can belong to many rooms', function () {
    $user = User::factory()->create();
    $rooms = Room::factory()->count(2)->create();
    $user->rooms()->attach($rooms->pluck('id'), ['role' => 'viewer']);

    expect($user->rooms)->toHaveCount(2);
});

// -- Hidden Fields --
test('password is hidden from serialization', function () {
    $user = User::factory()->create();
    expect(array_keys($user->toArray()))->not->toContain('password');
});
