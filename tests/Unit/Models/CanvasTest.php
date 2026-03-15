<?php

use App\Models\Canvas;
use App\Models\Pixel;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// -- Relationships --
test('canvas belong to a project', function () {
    $canvas = Canvas::factory()->create();
    expect($canvas->project)->toBeInstanceOf(Project::class);
});

test('canvas has many pixels', function () {
    $canvas = Canvas::factory()->create();

    Pixel::factory()->forCanvas($canvas)->count(5)->create();

    expect($canvas->pixels)->toHaveCount(5);
});
