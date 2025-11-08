<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCover;
use Illuminate\Http\Request;

class ProjectCoverController extends Controller
{
    public function edit(Project $project)
    {
        $cover = ProjectCover::firstOrCreate(
            ['project_id' => $project->id],
            ['titulo_trabajo' => '', 'autor' => '', 'institucion' => '', 'facultad' => '', 'asignatura' => '', 'docente' => '', 'fecha' => now()]
        );

        return view('projects.cover_edit', compact('project', 'cover'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'titulo_trabajo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'institucion' => 'nullable|string|max:255',
            'facultad' => 'nullable|string|max:255',
            'asignatura' => 'nullable|string|max:255',
            'docente' => 'nullable|string|max:255',
            'fecha' => 'nullable|date',
        ]);

        $cover = ProjectCover::updateOrCreate(
            ['project_id' => $project->id],
            $request->only(['titulo_trabajo', 'autor', 'institucion', 'facultad', 'asignatura', 'docente', 'fecha'])
        );

        return redirect()->back()->with('success', 'Portada actualizada correctamente.');
    }
}
