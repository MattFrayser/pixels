<?php

use App\Models\Project;
use App\Models\User;
use Tests\TestCase;

/** @var TestCase $this */

// -- Creation --
test('can create a project', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('projects.store'), [
            'name' => 'My Project',
            'public' => true,
            'width' => 32,
            'height' => 32,
            'framerate' => 12,
        ])
        ->assertRedirect();

    expect(Project::count())->toBe(1);
});

test('requires authentication to create a project', function () {
    $this->post(route('projects.store'), [])
        ->assertRedirect(route('login'));
});

// -- Validation --
test('rejects invalid field values', function (array $data, string $errorField) {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('projects.store'), $data + [
            'public' => true,
            'width' => 32,
            'height' => 32,
            'framerate' => 12,
        ])
        ->assertSessionHasErrors($errorField);
})->with([
    'name too long' => [['name' => str_repeat('a', 33)], 'name'],
    'description too long' => [['description' => str_repeat('a', 256)], 'description'],
    'width too low' => [['width' => 0], 'width'],
    'width too high' => [['width' => 129], 'width'],
    'height too low' => [['height' => 0], 'height'],
    'height too high' => [['height' => 129], 'height'],
    'framerate too low' => [['framerate' => 0], 'framerate'],
    'framerate too high' => [['framerate' => 31], 'framerate'],
]);

test('requires public, width, height, and framerate', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('projects.store'), [])
        ->assertSessionHasErrors(['public', 'width', 'height', 'framerate']);
});

// -- Permissions --
test('cannot delete unowned project', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('projects.destroy', $project))
        ->assertForbidden();

    expect(Project::count())->toBe(1);
});

test('can delete owned project', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->delete(route('projects.destroy', $project))
        ->assertRedirect(route('projects.index'));

    expect(Project::count())->toBe(0);
});

test('can update own project', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();

    $this->actingAs($owner)
        ->patch(route('projects.update', $project), [
            'name' => 'Updated Name',
        ])
        ->assertRedirect(route('projects.show', $project));

    expect($project->fresh()->name)->toBe('Updated Name');
});

test('cannot update unowned project', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner)->create();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->patch(route('projects.update', $project), [
            'name' => 'Hacked',
        ])
        ->assertForbidden();
});

// -- Project Privacy --
test('can view public project', function () {
    $project = Project::factory()->create(['public' => true]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('projects.show', $project))
        ->assertSuccessful();
});

test('cannot view private unowned project', function () {
    $project = Project::factory()->create(['public' => false]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('projects.show', $project))
        ->assertForbidden();
});

test('can view own private project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->for($user)->create(['public' => false]);

    $this->actingAs($user)
        ->get(route('projects.show', $project))
        ->assertSuccessful();
});

test('can list public projects', function () {
    Project::factory()->count(3)->create(['public' => true]);
    Project::factory()->count(2)->create(['public' => false]);

    $this->get(route('projects.index'))
        ->assertSuccessful();
});
