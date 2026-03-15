<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoomMemberController;
use App\Http\Controllers\RoomController;

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


Route::middleware(['auth'])->group( function () {
    // Projects
    Route::post('/projects', [ProjectController::class, 'store'])
        ->name('projects.store');
    Route::patch('/projects/{project}', [ProjectController::class, 'update'])
        ->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->name('projects.destroy');

    // Rooms
    Route::post('/projects/{project}/room', [RoomController::class, 'store'])
        ->name('rooms.store');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])
        ->name('rooms.destroy');
    Route::post('/rooms/{room}/join', [RoomMemberController::class, 'store'])
        ->name('rooms.members.store');
    Route::delete('/rooms/{room}/leave', [RoomMemberController::class, 'destory'])
        ->name('rooms.members.destroy');
});

require __DIR__.'/settings.php';
