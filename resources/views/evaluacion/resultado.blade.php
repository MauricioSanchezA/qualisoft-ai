@extends('layouts.app')

@section('title', 'Resultados de Evaluación')
@section('page-title', 'Resultados de Evaluación del Código')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h4>📊 Resultados de la Evaluación</h4>
        </div>
        <div class="card-body">
            <h5 class="mb-3">Código Analizado:</h5>
            <pre class="bg-light p-3 rounded"><code>{{ $codigo }}</code></pre>

            <h5 class="mt-4 mb-3">Evaluación según ISO/IEC 25010 y ISO/IEC 25023:</h5>
            <div class="alert alert-info" style="white-space: pre-wrap;">{{ $respuesta }}</div>

            <a href="{{ route('code_analysis.index') }}" class="btn btn-secondary mt-3">
                <i class="fas fa-arrow-left"></i> Nueva Evaluación
            </a>
        </div>
    </div>
</div>
@if(session('swal'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: '{{ session('swal') }}',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
@endif
@endsection
