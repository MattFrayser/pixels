<?php

use App\Http\Controllers\CanvasController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomMemberController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', function () {
    return inertia('Dashboard');
})->name('home');

Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/{project}', [ProjectController::class, 'show'])
    ->name('projects.show');

Route::get('/rooms', [RoomController::class, 'index'])
    ->name('rooms.index');

Route::middleware(['auth'])->group(function () {
    // Projects
    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    // Rooms
    Route::post('/rooms', [RoomController::class, 'index'])
        ->name('rooms.index');
    Route::post('/projects/{project}/room', [RoomController::class, 'store'])
        ->name('rooms.store');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy');
    Route::get('/rooms/{room}', [RoomController::class, 'show'])
        ->name('rooms.show');
    Route::post('/rooms/{room}/join', [RoomMemberController::class, 'store'])
        ->name('rooms.members.store');
    Route::delete('/rooms/{room}/leave', [RoomMemberController::class, 'destroy'])
        ->name('rooms.members.destroy');

    // Canvases
    Route::post('/projects/{project}/canvases', [CanvasController::class, 'store'])
        ->name('canvas.store');
    Route::patch('/canvases/{canvas}', [CanvasController::class, 'update'])
        ->name('canvas.update');
    Route::delete('/canvases/{canvas}', [CanvasController::class, 'destroy'])
        ->name('canvas.destroy');
});

require __DIR__.'/settings.php';
