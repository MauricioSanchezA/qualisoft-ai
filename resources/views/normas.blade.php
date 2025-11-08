@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/normas.css') }}">
@endpush

@section('title', 'Normas')
@section('page-title', 'Normas ISO/IEC 25010 & 25023')

@section('content')
<div class="container-fluid">
  <h3 class="mb-4 text-center">Normas de Calidad del Software</h3>

  <!-- Grid de tarjetas -->
  <div class="content-grid content-center">
    <dl class="card modern">
      <dt>ISO/IEC 25010</dt>
      <dd>Modelo de calidad del producto de software: características como funcionalidad, fiabilidad, eficiencia, mantenibilidad, entre otras.</dd>
      <dd>
        <button class="btn btn-primary open-url" data-url="https://iso25000.com/index.php/normas-iso-25000/iso-25010">
          Ver detalle
        </button>
      </dd>
    </dl>

    <dl class="card modern">
      <dt>ISO/IEC 25023</dt>
      <dd>Métricas para la medición de la calidad del producto de software.</dd>
      <dd>
        <button class="btn btn-primary open-url" data-url="https://www.iso25000.com/index.php/normas-iso-25000">
          Ver detalle
        </button>
      </dd>
    </dl>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.open-url').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-url');
      window.open(url, '_blank'); // Abre en nueva pestaña
    });
  });
});
</script>
@endpush
