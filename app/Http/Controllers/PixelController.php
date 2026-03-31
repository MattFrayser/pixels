<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pixels\StorePixelRequest;
use App\Models\Canvas;

class PixelController extends Controller
{
    public function store(StorePixelRequest $request, Canvas $canvas)
    {
        $this->authorize('update', $canvas);

        $pixels = collect($request->validated('pixels'))->map(function ($pixel) use ($canvas) {
            return array_merge($pixel, [
                'canvas_id' => $canvas->id,
                'user_id' => auth()->id(),
            ]);
        });

        $canvas->pixels()->upsert($pixels->toArray(), ['canvas_id', 'x', 'y'], ['color', 'user_id']);
    }
}
