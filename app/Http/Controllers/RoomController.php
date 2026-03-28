<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rooms\StoreRoomRequest;
use App\Models\Project;
use App\Models\Room;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::query()->where('public', true)->get();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
        ]);
    }

    public function store(StoreRoomRequest $request, Project $project)
    {
        $this->authorize('create', [Room::class, $project]);

        $room = $project->room()->create($request->validated());

        return redirect()->route('rooms.show', $room);
    }

    public function show(Room $room)
    {
        $this->authorize('view', $room);

        $room->load(['project.canvases', 'members']);

        return Inertia::render('Rooms/Show', [
            'room' => $room,
        ]);
    }

    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);

        $room->delete();

        return redirect()->route('rooms.index');
    }
}
