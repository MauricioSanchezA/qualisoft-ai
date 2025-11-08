@extends('layouts.app')

@section('title', 'Informe del Proyecto')
@section('page-title', 'Informe del Proyecto')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Documentos del Proyecto</h5>
            <a href="{{ route('projects.documents.pdf') }}" class="btn btn-danger mb-3">
                <i class="fas fa-file-pdf"></i> Descargar PDF (APA)
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Sección</th>
                        <th>Contenido</th>
                        <th>Estado</th>
                        <th>Actualizado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documentos as $doc)
                        <tr>
                            <td>{{ $doc->id }}</td>
                            <td><strong>{{ ucfirst(str_replace('_', ' ', $doc->section_key)) }}</strong></td>
                            <td>{{ Str::limit(strip_tags($doc->content), 100) }}</td>
                            <td>{{ ucfirst($doc->status) }}</td>
                            <td>{{ $doc->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No hay documentos disponibles</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
