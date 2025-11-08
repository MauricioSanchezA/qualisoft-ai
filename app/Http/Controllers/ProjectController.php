<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('user')->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['user_id'] = auth()->id();

        Project::create($data);

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto creado correctamente.');
    }

    public function show(Project $project)
{
    $sections = [
        'problema'         => '1. Problema de investigación',
        'objetivos'        => '2. Objetivos',
        'justificacion'    => '3. Justificación',
        'estado_arte'      => '4. Estado del arte',
        'marco_teorico'    => '5.1 Marco teórico',
        'marco_geografico' => '5.2 Marco geográfico',
        'marco_normativo'  => '5.3 Marco normativo / legal',
        'metodologia'      => '6. Metodología',
        'analisis'         => '7. Análisis de resultados y discusión',
        'conclusiones'     => '8. Conclusiones',
        'recomendaciones'  => '9. Recomendaciones',
    ];

    return view('projects.show', compact('project','sections'));
}

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($data);

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
                         ->with('success', 'Proyecto eliminado correctamente.');
    }
}
