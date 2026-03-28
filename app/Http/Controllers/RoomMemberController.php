<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RoomMemberController extends Controller
{
    public function store(Room $room): RedirectResponse
    {
        $this->authorize('join', $room);

        $room->members()->attach(Auth::id());

        return redirect()->route('rooms.show', $room);
    }

    public function destroy(Room $room): RedirectResponse
    {
        $this->authorize('leave', $room);

        $room->members()->detach(Auth::id());

        return redirect()->route('rooms.index');
    }
}
