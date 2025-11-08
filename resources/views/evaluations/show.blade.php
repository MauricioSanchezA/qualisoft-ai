@extends('layouts.app')

@section('title', 'Reporte de Evaluación')
@section('page-title', 'Detalle de Evaluación')

@section('content')
<div class="container-fluid">
    <div class="text-center mb-4">
        <h3>Reporte de Evaluación de Código</h3>
        <p>Normas ISO/IEC 25010 y 25023 - Auditoría de Calidad de Software</p>
    </div>

    <div class="mb-4 text-center">
        <a href="{{ route('evaluacion.reporte.pdf', $registro->id) }}" class="btn btn-primary" target="_blank">
            📄 Descargar PDF
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">📄 Código Analizado</div>
        <div class="card-body">
            <pre style="white-space: pre-wrap; background:#f4f4f4; padding:10px; border-radius:6px;">{{ $registro->codigo }}</pre>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">📊 Métricas Cuantitativas (ISO/IEC 25023)</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Aspecto</th>
                        <th>Valor (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($metricas as $nombre => $valor)
                        <tr>
                            <td>{{ $nombre }}</td>
                            <td>{{ $valor }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">📋 Análisis Cualitativo (ISO/IEC 25010)</div>
        <div class="card-body">
            <p>{{ $registro->analisis }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-bold">📝 Resumen</div>
        <div class="card-body">
            <p>{{ $registro->resumen }}</p>
        </div>
    </div>
</div>
@endsection
