<?php

namespace App\Http\Controllers;

use App\Models\Canvas;
use App\Models\Project;
use Illuminate\Http\Request;

class CanvasController extends Controller
{
    public function store(Request $request, Project $project): void
    {
        $this->authorize('create', [Canvas::class, $project]);

        Canvas::create([
            'project_id' => $project->id,
            'sort_order' => $project->canvases()->max('sort_order') + 1,
        ]);
    }

    public function update(Request $request, Canvas $canvas): void
    {
        $this->authorize('update', $canvas);

        $max = $canvas->project->canvases()->max('sort_order');
        $validated = $request->validate([
            'sort_order' => "integer|min:0|max:{$max}",
        ]);

        $canvas->update($validated);
    }

    public function destroy(Canvas $canvas): void
    {
        $this->authorize('delete', $canvas);
        $canvas->delete();
    }
}
