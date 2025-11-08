@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('title', 'Dashboard')
@section('page-title', 'Inicio')

@section('content')
<div class="container-fluid">
  <h3 class="mb-4 text-center">Bienvenido, {{ auth()->user()->name }}</h3>
  <p class="text-center">Accede rápidamente a tus módulos principales: proyectos y reportes.</p>

  <!-- Tarjetas con estilo moderno -->
  <div class="content-grid content-center mt-4">
    <dl class="card modern">
      <dt>Proyectos</dt>
      <dd>Gestiona y visualiza todos los proyectos activos o finalizados.</dd>
      <dd><a href="{{ route('projects.index') }}" class="btn btn-primary">Ver proyectos</a></dd>
    </dl>

    
    <dl class="card modern">
      <dt>Evaluaciones</dt>
      <dd>Administra evaluaciones y métricas de calidad.</dd>
      <dd><a href="{{ route('evaluations.index') }}" class="btn btn-primary">Ver evaluaciones</a></dd>
    </dl>
  </div>
</div>
@endsection
