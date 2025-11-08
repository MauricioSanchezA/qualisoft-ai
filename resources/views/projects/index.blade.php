@extends('layouts.app')

@section('title', 'Proyectos | ' . config('app.name'))
@section('page-title', 'Listado de Proyectos')

@section('content')
<div class="container mt-4">
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">Nuevo Proyecto</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($projects->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Usuario</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->user->name }}</td>
                    <td>{{ $project->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar proyecto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}
    @else
        <p>No hay proyectos registrados.</p>
    @endif
</div>
@endsection
