@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('title', 'Documento del Proyecto')
@section('page-title')
    Sección: {{ ucfirst($sectionKey) }}
@endsection

@section('content')
<div class="container-fluid">
    <div class="card modern mb‑4 p‑6">
        <div class="card-body">
            <h5 class="card‑title mb‑3">Sección: {{ ucfirst(str_replace('_',' ', $sectionKey)) }}</h5>

            <div class="mb‑4">
                <label class="form-label">
                    <strong>Estado:</strong>
                    @if($doc->status === 'usuario_completo')
                        Usuario completo
                    @elseif($doc->status === 'analizado_ia')
                        Analizado por IA
                    @else
                        Pendiente
                    @endif
                </label>
            </div>

            <form method="POST" action="{{ route('projects.documents.update', [$project, $sectionKey]) }}">
                @csrf

                <div class="form-group mb‑4">
                    <textarea name="content" class="form-control" rows="10" required>{{ old('content', $doc->content) }}</textarea>
                </div>

                <div class="d‑flex flex-wrap gap‑2 mt‑3">
                    <button type="submit" class="btn btn-success">
                        Guardar sección
                    </button>

                    <button type="button" class="btn btn-primary" id="btnAnalyzeByIA">
                        Analizar con IA
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnAnalyze = document.getElementById('btnAnalyzeByIA');
    const textarea = document.querySelector('textarea[name="content"]');

    if (!btnAnalyze || !textarea) return;

    btnAnalyze.addEventListener('click', async () => {
        const content = textarea.value.trim();

        if (!content) {
            alert('Por favor ingresa texto antes de analizar.');
            return;
        }

        if (!confirm('¿Deseas que la IA analice esta sección?')) return;

        btnAnalyze.disabled = true;
        btnAnalyze.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Analizando...';

        try {
            const response = await fetch("{{ route('projects.documents.analyze.ajax', [$project, $sectionKey]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content })
            });

            const data = await response.json();

            if (response.ok && data.result) {
                textarea.value = data.result;
                alert('✅ Análisis completado. Revisa el texto antes de guardar.');
            } else {
                alert('⚠️ Error: ' + (data.error || 'No se obtuvo respuesta válida.'));
            }
        } catch (error) {
            alert('🚨 Error al conectar con la IA: ' + error.message);
        } finally {
            btnAnalyze.disabled = false;
            btnAnalyze.innerHTML = 'Analizar con IA';
        }
    });
});
</script>
@endpush
@endsection
