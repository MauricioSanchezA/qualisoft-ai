@extends('layouts.app')

@section('title', 'Listado de Evaluaciones')
@section('page-title', 'Evaluaciones')

@section('content')
<div class="container-fluid">
    <!--<a href="{{ route('evaluations.create') }}" class="btn btn-primary mb-3">Nueva Evaluación</a>-->
<div class="card mb-4">

</div>
    <form action="{{ route('evaluacion.reporte') }}" method="GET" class="d-inline-flex align-items-center mb-3">
        <input type="number" name="id" class="form-control me-2" placeholder="Ingrese ID del reporte" required style="width: 160px;">
        <button type="submit" class="btn btn-primary">Ver Reporte</button>
    </form><br>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Proyecto</th>
                <th>Sección</th>
                <th>Puntuación</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($evaluations as $eval)
        <tr>
            <td>
    <a href="{{ route('evaluations.show', $eval) }}" class="btn btn-sm btn-info">Ver</a>
    <a href="{{ route('evaluations.edit', $eval) }}" class="btn btn-sm btn-warning">Editar</a>

    {{-- 🔍 Nuevo botón para ver el reporte en HTML --}}
    <a href="{{ route('evaluacion.reporte.html', $eval->id) }}" target="_blank" class="btn btn-sm btn-primary">
        🔍 Ver HTML
    </a>

    {{-- 📄 Botón existente para el PDF --}}
    <a href="{{ route('code_analysis.reporte', $eval->project_id) }}" target="_blank" class="btn btn-sm btn-success">
        📄 Reporte PDF
    </a>

    <form action="{{ route('evaluations.destroy', $eval) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta evaluación?')">
            Eliminar
        </button>
    </form>
</td>

        </tr>
        @endforeach
        </tbody>
    </table>

    {{ $evaluations->links() }}
</div>
@endsection
