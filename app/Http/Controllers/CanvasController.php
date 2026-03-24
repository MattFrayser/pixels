<?php

namespace App\Http\Controllers;

use App\Models\Canvas;
use Illuminate\Http\Request;

class CanvasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('create', [Canvas::class, $project]);

        $canvas = Canvas::create([
            'project_id' => $project->id,
            'sort_order' => $project->canvases()->max('sort_order') + 1,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Canvas $canvas)
    {
        $this->authorize('update', $canvas);

        $max = $canvas->project->canvases()->max('sort_order');
        $validated = $request->validate([
           'sort_order' => "integer|min:0|max:{$max}"
        ]);

        $canvas = Canvas->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Canvas $canvas)
    {
        $this->authorize('delete', $canvas);
        $canvas->destroy();
    }
}
