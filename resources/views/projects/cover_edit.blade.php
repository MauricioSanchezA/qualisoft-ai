@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Editar portada del proyecto: <strong>{{ $project->title }}</strong></h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('projects.cover.update', $project->id) }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Título del trabajo</label>
                <input type="text" name="titulo_trabajo" class="form-control" value="{{ old('titulo_trabajo', $cover->titulo_trabajo) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Autor</label>
                <input type="text" name="autor" class="form-control" value="{{ old('autor', $cover->autor) }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Institución</label>
                <input type="text" name="institucion" class="form-control" value="{{ old('institucion', $cover->institucion) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Facultad</label>
                <input type="text" name="facultad" class="form-control" value="{{ old('facultad', $cover->facultad) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Asignatura</label>
                <input type="text" name="asignatura" class="form-control" value="{{ old('asignatura', $cover->asignatura) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Docente</label>
                <input type="text" name="docente" class="form-control" value="{{ old('docente', $cover->docente) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $cover->fecha ? $cover->fecha->format('Y-m-d') : now()->format('Y-m-d')) }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Portada</button>
    </form>
</div>
@endsection
