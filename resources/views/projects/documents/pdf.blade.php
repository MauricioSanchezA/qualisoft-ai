<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $portada->titulo_trabajo ?? 'Informe del Proyecto' }}</title>
    <style>
        /* ===== Configuración general ===== */
        @page {
            margin: 2.54cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 2;
            text-align: left; /* APA 7.0: alineado a la izquierda */
        }

        h1, h2, h3 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 1em;
        }

        h1 { font-size: 16pt; text-transform: uppercase; }
        h2 { font-size: 14pt; margin-top: 2em; }

        p {
            margin-bottom: 1em;
            text-indent: 1.27cm; /* Sangría APA */
        }

        .page-break { page-break-before: always; }

        /* ===== Portada ===== */
        .cover {
            display: flex;
            flex-direction: column;
            justify-content: center; /* centrado vertical */
            align-items: center;     /* centrado horizontal */
            height: 100vh;
            margin: 0;
            text-align: center;
            line-height: 1.5;
        }

        .cover h1 {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 60px;
        }

        .cover p {
            font-size: 12pt;
            margin: 8px 0;
            text-indent: 0;
        }

        .cover .author {
            margin-top: 40px;
            font-weight: bold;
        }

        .cover .footer {
            margin-top: 100px;
        }

        /* ===== Encabezados de secciones ===== */
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            text-align: center;
            text-transform: capitalize;
            margin-bottom: 1em;
        }
    </style>
</head>
<body>

{{-- ================= 📘 PORTADA ================= --}}
@if($portada)
<div class="cover">
    <h1>{{ strtoupper($portada->titulo_trabajo) }}</h1>
    <p class="author">{{ $portada->autor }}</p>
    <p>{{ $portada->institucion }}</p>
    <p>{{ $portada->facultad }}</p>
    <p>{{ $portada->asignatura }}</p>
    <p>Docente: {{ $portada->docente }}</p>

    <div class="footer">
        <p>{{ \Carbon\Carbon::parse($portada->fecha)->translatedFormat('F Y') }}</p>
    </div>
</div>
<div class="page-break"></div>
@endif

{{-- ================= 📄 CUERPO DEL PROYECTO ================= --}}
@foreach($documentos as $doc)
    @if($doc->section_key !== 'portada')
        <div class="section">
            <h2 class="section-title">{{ ucfirst(str_replace('_', ' ', $doc->section_key)) }}</h2>
            {!! $doc->content !!}
        </div>
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
