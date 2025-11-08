@extends('layouts.app')

@section('title', 'Crear Evaluación')
@section('page-title', 'Nueva Evaluación')

@section('content')
<div class="container-fluid">
    <form action="{{ route('evaluations.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="project_id">Proyecto</label>
            <select name="project_id" id="project_id" class="form-control">
                @foreach($projects as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="section_key">Sección</label>
            <input type="text" name="section_key" id="section_key" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="score">Puntuación</label>
            <input type="number" step="0.01" name="score" id="score" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label for="comments">Comentarios</label>
            <textarea name="comments" id="comments" class="form-control"></textarea>
        </div>

        <div class="form-group mb-3">
            <label for="status">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="pendiente">pendiente</option>
                <option value="completada">completada</option>
                <option value="archivada">archivada</option>
            </select>
        </div>

        <button class="btn btn-primary">Guardar Evaluación</button>
    </form>
</div>
@endsection
