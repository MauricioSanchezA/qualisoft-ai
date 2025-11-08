<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Project;
use App\Http\Requests\EvaluationRequest;
use Illuminate\Http\Request;
use App\Models\ProjectMetric;
use GuzzleHttp\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluacionController extends Controller
{

    public function generarReporte($project_id)
{
    $metricas = ProjectMetric::where('project_id', $project_id)
        ->latest('id')
        ->first();

    if (!$metricas) {
        return back()->with('error', 'No se encontró un análisis para este proyecto.');
    }

    $pdf = PDF::loadView('evaluacion.reporte', [
        'codigo' => $metricas->codigo,
        'respuesta' => $metricas->analisis,
        'resumen' => $metricas->resumen,
        'metricas' => $metricas
    ]);

    return $pdf->stream('reporte_analisis_'.$project_id.'.pdf');
}

        public function mostrarReporte($id)
    {
        $registro = Evaluation::findOrFail($id);

        $codigo = $registro->codigo;
        $analisis = $registro->analisis;
        $resumen = $registro->resumen;
        $metricas = json_decode($registro->metricas, true) ?? [];

        return view('evaluacion.reporte', compact('registro', 'codigo', 'analisis', 'resumen', 'metricas'));
    }

}
