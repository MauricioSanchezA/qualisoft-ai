<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\NormasController;
use App\Http\Controllers\ApoyoController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDocumentController;
use App\Http\Controllers\CodeAnalysisController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProjectCoverController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\EvaluacionController;

// Página pública
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Inicio y Dashboard
    Route::get('/home', fn() => view('home'))->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Módulos generales
    Route::get('/graficos', [GraficosController::class, 'index'])->name('graficos.index');
    Route::post('/graficos/generar', [GraficosController::class, 'generarMetricas'])->name('graficos.generar');
    Route::get('/normas', [NormasController::class, 'index'])->name('normas');
    Route::get('/apoyo', [ApoyoController::class, 'index'])->name('apoyo');

    // Proyectos
    Route::resource('projects', ProjectController::class);

    // Portada de proyecto
    Route::prefix('projects/{project}')->group(function () {
        Route::get('/cover/edit', [ProjectCoverController::class, 'edit'])->name('projects.cover.edit');
        Route::post('/cover/update', [ProjectCoverController::class, 'update'])->name('projects.cover.update');
    });

    // Documentos de proyectos
    Route::prefix('projects/{project}')->group(function () {
        Route::get('sections/{sectionKey}', [ProjectDocumentController::class, 'show'])->name('projects.documents.show');
        Route::post('sections/{sectionKey}', [ProjectDocumentController::class, 'update'])->name('projects.documents.update');
        Route::post('sections/{sectionKey}/analyze', [ProjectDocumentController::class, 'analyzeByIA'])->name('projects.documents.analyze');
    });

    Route::get('/projects/documents/index', [ProjectDocumentController::class, 'index'])->name('projects.documents.index');
    Route::get('/projects/documents/pdf', [ProjectDocumentController::class, 'exportarPDF'])->name('projects.documents.pdf');
    Route::get('projects/{project}/document/download', [ProjectDocumentController::class, 'downloadDocument'])->name('projects.documents.download');
    Route::post('/projects/{project}/documents/{section}/analyze-ajax', [ProjectDocumentController::class, 'analyzeByIAAjax'])->name('projects.documents.analyze.ajax');

    // Métricas
    Route::post('/projects/{project}/analyze-metrics', [MetricController::class, 'analyze'])->name('metrics.analyze');
    Route::get('/projects/{project}/metrics', [MetricController::class, 'show'])->name('metrics.show');

    // Análisis de código
    Route::get('/analisis', [CodeAnalysisController::class, 'index'])->name('code_analysis.index');
    Route::post('/evaluacion', [CodeAnalysisController::class, 'evaluar'])->name('code_analysis.evaluar');
    Route::get('/evaluacion/reporte', [CodeAnalysisController::class, 'reporte'])->name('code_analysis.reporte');

    // Resultados del análisis
    Route::get('/evaluacion/resultado', function () {
        $codigo = session('codigo');
        $respuesta = session('respuesta');

        if (!$codigo || !$respuesta) {
            return redirect()->route('code_analysis.index')
                ->withErrors(['error' => 'No hay datos disponibles para mostrar.']);
        }

        return view('evaluacion.resultado', compact('codigo', 'respuesta'));
    })->name('code_analysis.resultado');

    // Reportes de análisis
    Route::post('/evaluacion/graficos', [CodeAnalysisController::class, 'generarMetricas'])->name('evaluacion.graficos');
    Route::post('/evaluacion/reporte', [CodeAnalysisController::class, 'generarReporte'])->name('evaluacion.reporte.pdf');

    Route::get('/reporte/{id?}', [CodeAnalysisController::class, 'generarReporte'])->name('code_analysis.reporte.show');
    Route::get('/evaluacion/reportes', [CodeAnalysisController::class, 'index'])->name('evaluacion.index');

    // Evaluaciones
    Route::resource('evaluations', EvaluationController::class);
    Route::get('/evaluations/reports', [EvaluationController::class, 'reports'])->name('evaluations.reports');

    // Reportes individuales de evaluación
    Route::get('/evaluacion/reporte/pdf/{id}', [EvaluationController::class, 'generarPDF'])->name('evaluacion.reporte.pdf.show');
    Route::get('/evaluacion/reporte/{id}', [EvaluationController::class, 'show'])->name('evaluacion.reporte.html');
    Route::get('/evaluacion/reporte', [EvaluationController::class, 'viewById'])->name('evaluacion.reporte.view');

    // IA y chat
    Route::get('/ai/chat', [ChatController::class, 'index'])->name('ai.chat');
    Route::post('/ai/chat/send', [ChatController::class, 'send'])->name('ai.chat.send');
    Route::post('/ai/analyze', [AIController::class, 'analyze'])->name('ai.analyze');
});
