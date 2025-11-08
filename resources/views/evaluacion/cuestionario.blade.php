@extends('layouts.app')

@section('title', 'Analizar Código con IA')
@section('page-title', 'Evaluación de Código')

@section('content')
<div class="container mt-5">
  <h3 class="text-center mb-4">Analizar fragmento de código con Qualisoft AI</h3>

  @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('analizar.codigo') }}" method="POST">
      @csrf
      <div class="mb-3">
          <label for="project_id" class="form-label">Proyecto</label>
          <select name="project_id" id="project_id" class="form-control" required>
              @foreach (\App\Models\Project::all() as $project)
                  <option value="{{ $project->id }}">{{ $project->title }}</option>
              @endforeach
          </select>
      </div>

      <div class="mb-3">
          <label for="codigo" class="form-label">Fragmento de Código</label>
          <textarea name="codigo" id="codigo" rows="8" class="form-control" placeholder="Pega aquí tu código..." required></textarea>
      </div>

      <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-cpu"></i> Analizar con IA
      </button>
  </form>
</div>
@endsection
