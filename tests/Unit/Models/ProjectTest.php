<?php

use App\Models\Canvas;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// -- Creation --
test('project mass assignable', function () {
    $project = Project::factory()->create([
        'name' => 'My Project',
        'width' => 32,
        'height' => 32,
        'framerate' => 12,
        'public' => true,
    ]);

    expect($project->name)->toBe('My Project')
        ->and($project->width)->toBe(32)
        ->and($project->height)->toBe(32)
        ->and($project->framerate)->toBe(12)
        ->and($project->public)->toBeTrue();
});

// -- Relationships --
test('project belongs to a user', function () {
    $project = Project::factory()->create();
    expect($project->user)->toBeInstanceOf(User::class);
});

test('project can have multiple canvases', function () {
    $project = Project::factory()->create();
    Canvas::factory()->count(3)->create(['project_id' => $project->id]);

    expect($project->canvases)->toHaveCount(3);
    expect($project->canvases->first())->toBeInstanceOf(Canvas::class);
});

// -- Ordering --
test('project canvases are sorted in order', function () {
    $project = Project::factory()->create();
    Canvas::factory()->create(['project_id' => $project, 'sort_order' => 2]);
    Canvas::factory()->create(['project_id' => $project, 'sort_order' => 0]);
    Canvas::factory()->create(['project_id' => $project, 'sort_order' => 1]);

    $orders = $project->canvases->pluck('sort_order')->toArray();
    expect($orders)->toBe([0, 1, 2]);
});

// -- Factory --
test('project factory', function () {
    $project = Project::factory()->create();

    expect($project->name)->toBeString()
        ->and($project->width)->toBeInt()
        ->and($project->height)->toBeInt()
        ->and($project->public)->toBeBool()
        ->and($project->user_id)->not->toBeNull();
});
