@extends('layouts.app')

@section('title', 'Nuevo Proyecto | ' . config('app.name'))
@section('page-title', 'Crear Proyecto')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('projects.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="title">Título</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group mt-2">
                    <label for="description">Descripción</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-success mt-3">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection
