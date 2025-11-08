@extends('layouts.app')

@section('title', 'Editar Evaluación')
@section('page-title', 'Editar Evaluación')

@section('content')
<div class="container-fluid">
    <form action="{{ route('evaluations.update', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label for="project_id">Proyecto</label>
            <select name="project_id" id="project_id" class="form-control">
                @foreach($projects as $id => $name)
                    <option value="{{ $id }}" {{ ($evaluation->project_id == $id) ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="section_key">Sección</label>
            <input type="text" name="section_key" id="section_key" class="form-control"
                   value="{{ old('section_key', $evaluation->section_key) }}" required>
        </div>

        <div class="form-group mb-3">
            <label for="score">Puntuación</label>
            <input type="number" step="0.01" name="score" id="score" class="form-control"
                   value="{{ old('score', $evaluation->score) }}">
        </div>

        <div class="form-group mb-3">
            <label for="comments">Comentarios</label>
            <textarea name="comments" id="comments" class="form-control" rows="4">{{ old('comments', $evaluation->comments) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="status">Estado</label>
            <select name="status" id="status" class="form-control">
                <option value="pendiente"   {{ $evaluation->status === 'pendiente'   ? 'selected' : '' }}>pendiente</option>
                <option value="completada"  {{ $evaluation->status === 'completada'  ? 'selected' : '' }}>completada</option>
                <option value="archivada"   {{ $evaluation->status === 'archivada'   ? 'selected' : '' }}>archivada</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Evaluación</button>
        <a href="{{ route('evaluations.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
