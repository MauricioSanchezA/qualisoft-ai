<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Evaluation;
use App\Models\ProjectMetric;

class DashboardController extends Controller
{
    public function index()
{
    // Total de proyectos
    $totalProyectos = Project::count();

    // Total de evaluaciones (cantidad de registros en project_metrics)
    $totalEvaluaciones = ProjectMetric::count();

    // Si quieres el último ID evaluado (opcional)
    $ultimoIdEvaluacion = ProjectMetric::max('id');

    return view('dashboard', compact('totalProyectos', 'totalEvaluaciones', 'ultimoIdEvaluacion'));
}
}
