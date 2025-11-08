<!-- // Plantilla para generar documentacion de proyecto -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->title ?? 'Documento del Proyecto' }}</title>
    <style>
        /* Márgenes de 1 pulgada (≈ 2.54 cm) según APA 7 */ 
        @page { margin: 2.54cm; }
        body {
            font-family: "Arial", serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
        }
        h1, h2, h3 {
            font-weight: bold;
        }
        h1 { text-align: center; margin-top: 0; }
        h2 { margin-top: 1.5em; }
        p { text-align: left; text-indent: 0.5in; }
        .page-break { page-break-after: always; }
        .section-label { font-weight: bold; margin-top: 1em; }
    </style>
</head>
<body>

    {{-- Portada --}}
    <h1>{{ $project->title }}</h1>
    <p>{{ $project->author_name }}</p>
    <p>{{ $project->institution }}</p>
    <p>{{ $project->date }}</p>
    <div class="page-break"></div>

    {{-- 1. Problema de investigación --}}
    <h2>1. Problema de investigación</h2>
    <p>{{ $document['problema']->content ?? '' }}</p>

    {{-- 2. Objetivos --}}
    <h2>2. Objetivos</h2>
    <p class="section-label">2.1 Objetivo general</p>
    <p>{{ $document['objetivos_general']->content ?? '' }}</p>
    <p class="section-label">2.2 Objetivos específicos</p>
    <p>{{ $document['objetivos_especificos']->content ?? '' }}</p>

    {{-- 3. Justificación --}}
    <h2>3. Justificación</h2>
    <p>{{ $document['justificacion']->content ?? '' }}</p>

    {{-- 4. Estado del arte --}}
    <h2>4. Estado del arte</h2>
    <p>{{ $document['estado_arte']->content ?? '' }}</p>

    {{-- 5. Marco de referencia --}}
    <h2>5. Marco de referencia</h2>
    <p class="section-label">5.1 Marco teórico</p>
    <p>{{ $document['marco_teorico']->content ?? '' }}</p>
    <p class="section-label">5.2 Marco geográfico</p>
    <p>{{ $document['marco_geografico']->content ?? '' }}</p>
    <p class="section-label">5.3 Marco normativo o legal</p>
    <p>{{ $document['marco_normativo']->content ?? '' }}</p>

    {{-- 6. Metodología --}}
    <h2>6. Metodología</h2>
    <p>{{ $document['metodologia']->content ?? '' }}</p>

    {{-- 7. Análisis de resultados y discusión --}}
    <h2>7. Análisis de resultados y discusión</h2>
    <p>{{ $document['analisis']->content ?? '' }}</p>

    {{-- 8. Conclusiones --}}
    <h2>8. Conclusiones</h2>
    <p>{{ $document['conclusiones']->content ?? '' }}</p>

    {{-- 9. Recomendaciones --}}
    <h2>9. Recomendaciones</h2>
    <p>{{ $document['recomendaciones']->content ?? '' }}</p>

    {{-- 10. Referencias --}}
    <div class="page-break"></div>
    <h2>Referencias</h2>
    <p>{{ $project->references ?? '' }}</p>

</body>
</html>
