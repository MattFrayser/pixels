<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use App\Models\Canvas;
use App\Http\Requests\StorePixelRequest;

class PixelController extends Controller
{

    public function store(StorePixelRequest $request, Canvas $canvas)
    {
        $this->authorize('update', $canvas);

        $pixels = collect($request->validated('pixels'))->map(function ($pixel) use ($canvas){
            return array_merge($pixel, [
                'canvas_id' => $canvas->id,
                'user_id' => auth()->id(),
            ]);
        })

        $canvas->pixels()->upsert($pixels, ['canvas_id', 'x', 'y'], ['color', 'user_id']);
    }

}
