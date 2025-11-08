@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/graficos.css') }}">
  {{-- SweetAlert2 CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('title', 'Gráficos')
@section('page-title', 'Gráficos de Calidad')

@section('content')
<div class="container-fluid">
  <h3 class="mb-4 text-center">Métricas de Calidad del Software</h3>

  <div class="text-center mb-4">
    <button id="btnGenerar" class="btn btn-primary">
      <i class="fas fa-sync-alt"></i> Generar Métricas
    </button>
  </div>

  <div id="metricasContainer" class="content-grid content-center">

    <dl class="card modern" data-field="funcionalidad">
      <dt>Funcionalidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="mantenibilidad">
      <dt>Mantenibilidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="eficiencia">
      <dt>Eficiencia</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="usabilidad">
      <dt>Usabilidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="compatibilidad">
      <dt>Compatibilidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="portabilidad">
      <dt>Portabilidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="fiabilidad">
      <dt>Fiabilidad</dt>
      <dd><strong>0%</strong></dd>
    </dl>

    <dl class="card modern" data-field="seguridad">
      <dt>Seguridad</dt>
      <dd><strong>0%</strong></dd>
    </dl>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('btnGenerar');

  btn.addEventListener('click', async () => {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';

    try {
      const res = await fetch("{{ route('evaluacion.graficos') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({})
      });

      const data = await res.json();
      console.log("📊 Datos recibidos:", data); // 👈 mira aquí en consola (F12)

      if (!data.success) throw new Error(data.error || "Error inesperado.");

      // ✅ Acceso robusto a los datos (por si vienen dentro de 'metricas' o al nivel raíz)
      const metricas = data.metricas ?? data;

      // 🔹 Actualizar las tarjetas
      document.querySelectorAll(".card.modern").forEach(card => {
        const field = card.dataset.field;
        if (metricas[field] !== undefined) {
          const value = metricas[field];
          const dd = card.querySelector("dd strong");
          dd.innerText = (field === "lineas_analizadas")
            ? `${value} líneas`
            : `${value}%`;
        }
      });

      // 🔹 Calcular el promedio general (sin contar líneas)
      const keys = Object.keys(metricas).filter(k => k !== "lineas_analizadas");
      const promedio = keys.reduce((a, k) => a + parseFloat(metricas[k] || 0), 0) / keys.length;

      // 🔹 Mostrar SweetAlert
      Swal.fire({
        icon: "success",
        title: "✅ Métricas actualizadas",
        html: `
          <p><strong>Promedio general:</strong> ${promedio.toFixed(2)}%</p>
        `,
        confirmButtonText: "Aceptar",
        confirmButtonColor: "#3085d6",
      });

    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error al generar métricas",
        text: err.message,
        confirmButtonText: "Cerrar",
        confirmButtonColor: "#d33",
      });
    } finally {
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-sync-alt"></i> Generar Métricas';
    }
  });
});
</script>

@endsection
