@extends('layouts.app')

@section('title', 'Editar Proyecto | ' . config('app.name'))
@section('page-title', 'Editar Proyecto')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" name="title" class="form-control" value="{{ $project->title }}" required>
                </div>

                <div class="form-group mt-2">
                    <label for="description">Descripción</label>
                    <textarea name="description" class="form-control" rows="4">{{ $project->description }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
