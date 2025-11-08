@extends('layouts.app')

@section('title', 'Evaluación de Código')
@section('page-title', 'Evaluación de Calidad de Código (ISO/IEC 25010 y ISO/IEC 25023)')

@section('content')
<div class="container mt-4">
    <div class="card" style="box-shadow: 0 8px 20px rgba(0, 0, 0, 0.78);">
        <div class="card-header bg-primary text-white">
            <h4>🔍 Evaluar fragmento de código</h4>
        </div>
        <div class="card-body">
            {{-- FORMULARIO DE ANÁLISIS --}}
            <form action="{{ route('code_analysis.evaluar') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="codigo">Pega tu código fuente aquí:</label>
                    <textarea name="codigo" id="codigo" rows="10" class="form-control" placeholder="Pega tu código aquí..." required></textarea>
                </div>
                <button type="submit" class="btn btn-success mt-3">
                    <i class="fas fa-check"></i> Evaluar código
                </button>
            </form>

            {{-- ✅ SI EXISTE UN ANÁLISIS RECIENTE, MOSTRAR BOTONES --}}
            @if (session('registro_id'))
                <hr>
                <div class="text-center mt-3">
                    <h5>✅ Último análisis generado</h5>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        {{-- Descargar PDF --}}
                        <a href="{{ route('evaluacion.reporte.pdf', session('registro_id')) }}" 
                           class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Descargar PDF
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- SweetAlert de éxito --}}
@if (session('swal'))
<script>
Swal.fire({
    icon: 'success',
    title: '{{ session('swal') }}',
    showConfirmButton: false,
    timer: 1800
});
</script>
@endif
@endsection
