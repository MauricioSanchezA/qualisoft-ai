<!-- // plantilla para listar secciones de un proyecto -->
@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/projects-sections.css') }}">
@endpush

@section('title', 'Proyecto: ' . $project->name)
@section('page-title', 'Proyecto (' . $project->name . ')')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Secciones del proyecto</h3>
    <div class="list-group section-list">
        @foreach($sections as $key => $label)
            <a href="{{ route('projects.documents.show', [$project, $key]) }}"
               class="list-group-item list-group-item-action section-list-item">
                <strong>{{ $label }}</strong>
            </a>
        @endforeach
    </div>
</div>
@endsection
