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
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use App\Http\Controllers\ProjectCoverController;
use App\Http\Controllers\MetricController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\EvaluacionController;

// Página pública de bienvenida
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Login y logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Ruta principal tras el login
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // También puedo redirigir directamente al dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // gráficos, normas, apoyo
    Route::get('/graficos', [GraficosController::class, 'index'])->name('graficos');
    Route::get('/normas', [NormasController::class, 'index'])->name('normas');
    Route::get('/apoyo', [ApoyoController::class, 'index'])->name('apoyo');
    // projectos y documentos
    Route::resource('projects', ProjectController::class);
    // documentos de proyectos
    Route::prefix('projects/{project}')->group(function () {
        Route::get('sections/{sectionKey}', [ProjectDocumentController::class, 'show'])->name('projects.documents.show');
        Route::post('sections/{sectionKey}', [ProjectDocumentController::class, 'update'])->name('projects.documents.update');
        Route::post('sections/{sectionKey}/analyze', [ProjectDocumentController::class, 'analyzeByIA'])->name('projects.documents.analyze');
    });
    // análisis de código y evaluaciones
    Route::get('/analisis', [CodeAnalysisController::class, 'index'])->name('code_analysis.index');
    Route::post('/evaluacion', [CodeAnalysisController::class, 'evaluar'])->name('code_analysis.evaluar');
    Route::get('/evaluacion/reporte', [CodeAnalysisController::class, 'reporte'])->name('code_analysis.reporte');

    Route::get('/evaluations/reports', [EvaluationController::class, 'reports'])
     ->name('evaluations.reports');

    // rutas de portada del proyecto
    Route::prefix('projects/{project}')->group(function () {
    Route::get('/cover/edit', [ProjectCoverController::class, 'edit'])->name('projects.cover.edit');
    Route::post('/cover/update', [ProjectCoverController::class, 'update'])->name('projects.cover.update');
    });

    Route::resource('evaluations', EvaluationController::class);
    // rutas de IA y chat
    Route::get('/ai/chat', [ChatController::class, 'index'])->name('ai.chat');
    Route::post('/ai/chat/send', [ChatController::class, 'send'])->name('ai.chat.send');
    Route::post('/ai/analyze', [AIController::class, 'analyze'])->name('ai.analyze');
    // descarga de documento completo del proyecto
    Route::get('projects/{project}/document/download', [\App\Http\Controllers\ProjectDocumentController::class, 'downloadDocument'])
     ->name('projects.documents.download');
    // análisis AJAX de secciones del documento
    Route::post('/projects/{project}/documents/{section}/analyze-ajax', [ProjectDocumentController::class, 'analyzeByIAAjax'])
    ->name('projects.documents.analyze.ajax');

    Route::post('/projects/{project}/analyze-metrics', [MetricController::class, 'analyze'])->name('metrics.analyze');
    Route::get('/projects/{project}/metrics', [MetricController::class, 'show'])->name('metrics.show');

    // rutas de gráficos
 
    Route::get('/graficos', [GraficosController::class, 'index'])->name('graficos.index');
    Route::post('/graficos/generar', [GraficosController::class, 'generarMetricas'])->name('graficos.generar');
    
    Route::post('/evaluacion/graficos', [CodeAnalysisController::class, 'generarMetricas'])->name('evaluacion.graficos');
    Route::post('/evaluacion/reporte', [CodeAnalysisController::class, 'generarReporte'])->name('evaluacion.reporte');

    Route::get('/evaluacion/resultado', function () {
    $codigo = session('codigo');
    $respuesta = session('respuesta');

    if (!$codigo || !$respuesta) {
        return redirect()->route('code_analysis.index')
            ->withErrors(['error' => 'No hay datos disponibles para mostrar.']);
    }

    return view('evaluacion.resultado', compact('codigo', 'respuesta'));
})->name('code_analysis.resultado');

Route::get('/reporte/{id?}', [CodeAnalysisController::class, 'generarReporte'])->name('code_analysis.reporte');

Route::get('/evaluacion/reporte/{id}', [CodeAnalysisController::class, 'generarReporte'])
    ->name('code_analysis.reporte'); // Ruta para ver el reporte PDF

Route::get('/evaluacion/reportes', [CodeAnalysisController::class, 'index'])
    ->name('evaluacion.index'); // Ruta para listar todos los reportes

Route::get('/evaluations/reporte/{project_id}', [EvaluacionController::class, 'generarReporte'])
    ->name('code_analysis.reporte');

// RUTA REAL PARA GENERAR REPORTE PDF
Route::get('/evaluacion/reporte/pdf/{id}', [EvaluationController::class, 'generarPDF'])
    ->name('evaluacion.reporte.pdf');

Route::get('/evaluacion/reporte/{id}', [EvaluationController::class, 'show'])
    ->name('evaluacion.reporte.html');

// Mostrar reporte por ID vía query (ej. /evaluacion/reporte?id=4)
Route::get('/evaluacion/reporte', [EvaluationController::class, 'viewById'])
    ->name('evaluacion.reporte');

// Mostrar reporte por URL con ID (ej. /evaluacion/reporte/4)
Route::get('/evaluacion/reporte/{id}', [EvaluationController::class, 'show'])
    ->name('evaluacion.reporte.html');

// Ruta para buscar el reporte desde el formulario (por ?id=)
Route::get('/evaluacion/reporte', [EvaluationController::class, 'viewById'])
    ->name('evaluacion.reporte');

// Ruta para ver el reporte directamente con ID (por /evaluacion/reporte/4)
Route::get('/evaluacion/reporte/{id}', [EvaluationController::class, 'show'])
    ->name('evaluacion.reporte.html');

// Reporte y PDF  probandose
Route::get('/evaluacion/reporte/{id}', [EvaluationController::class, 'show'])->name('evaluacion.reporte');
Route::get('/evaluacion/reporte/pdf/{id}', [EvaluationController::class, 'generarPDF'])->name('evaluacion.reporte.pdf');

// rutas de documentos de proyectos para vista e impresión
Route::get('/projects/documents/index', [ProjectDocumentController::class, 'index'])
    ->name('projects.documents.index');

Route::get('/projects/documents/pdf', [ProjectDocumentController::class, 'exportarPDF'])
    ->name('projects.documents.pdf');
});
