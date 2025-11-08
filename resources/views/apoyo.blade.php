@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/apoyos.css') }}">
@endpush

@section('title', 'Apoyo')
@section('page-title', 'Herramientas de Apoyo')

@section('content')
<div class="container-fluid">
  <h3 class="mb-4 text-center">Herramientas Externas y Recursos</h3>

  <!-- Grid de tarjetas -->
  <div class="row mt-4 justify-content-center">
    <div class="content-grid content-center">

      <dl class="card modern">
        <dt>Tester Online</dt>
        <dd>Herramienta para pruebas automáticas de calidad de código.</dd>
        <dd>
          <button class="btn btn-primary open-url" data-url="https://edutin.com/curso-de-tester">
            Ver detalle
          </button>
        </dd>
      </dl>

      <dl class="card modern">
        <dt>Cobertura de Código</dt>
        <dd>Visualizador de cobertura de pruebas unitarias y abiertas.</dd>
        <dd>
          <button class="btn btn-primary open-url" data-url="https://www.glukhov.org/es/post/2025/10/unit-testing-in-python">
            Ver detalle
          </button>
        </dd>
      </dl>

      <dl class="card modern">
        <dt>Editor de Código</dt>
        <dd>Plataforma que permite organizar de manera adecuada el código fuente de un proyecto.</dd>
        <dd>
          <button class="btn btn-primary open-url" data-url="https://www.online-python.com">
            Ver detalle
          </button>
        </dd>
      </dl>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.open-url').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-url');
      window.open(url, '_blank'); // abre la URL en una nueva pestaña
    });
  });
});
</script>
@endpush
