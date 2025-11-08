<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Evaluación - ISO/IEC 25010 y 25023</title>
    <style>
        @page {
            margin: 50px 40px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
            line-height: 1.5;
        }

        table.header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.header td { vertical-align: middle; }
        .logo { width: 90px; }

        .title {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
            color: #003366;
        }
        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #555;
        }

        .section-title {
            font-weight: bold;
            margin-top: 25px;
            color: #003366;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        .code-box {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
            white-space: pre-wrap;
            border: 1px solid #ddd;
            margin-top: 5px;
        }

        table.metricas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }
        table.metricas th, table.metricas td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: center;
        }
        table.metricas th {
            background: #003366;
            color: #fff;
        }
        table.metricas tr:nth-child(even) td {
            background: #f3f6f9;
        }

        .btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Ocultar botón en el PDF */
        .no-print {
            display: none !important;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #555;
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

    </style>
</head>
<body>

    {{-- ENCABEZADO --}}
    <table class="header">
        <tr>
            <td class="logo">
                <img src="{{ asset('images/logo_1.jpg') }}" width="90" alt="Mauricio Sánchez Abella">
            </td>
            <td style="text-align: center;">
                <div class="title">Reporte de Evaluación de Código</div>
                <div class="subtitle">Normas ISO/IEC 25010 y 25023 — Auditoría de Calidad de Software</div>
            </td>
        </tr>
    </table>

    {{-- CÓDIGO --}}
    <div class="section-title">📄 Código Analizado:</div>
    <div class="code-box"><code>{{ $codigo ?? 'No disponible' }}</code></div>

    {{-- MÉTRICAS --}}
    <div class="section-title">📊 Métricas Cuantitativas (ISO/IEC 25023):</div>
    <table class="metricas">
        <thead>
            <tr>
                <th>Aspecto</th>
                <th>Valor (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Funcionalidad</td><td>{{ $metricas->funcionalidad ?? 0 }}%</td></tr>
            <tr><td>Eficiencia</td><td>{{ $metricas->eficiencia ?? 0 }}%</td></tr>
            <tr><td>Compatibilidad</td><td>{{ $metricas->compatibilidad ?? 0 }}%</td></tr>
            <tr><td>Usabilidad</td><td>{{ $metricas->usabilidad ?? 0 }}%</td></tr>
            <tr><td>Fiabilidad</td><td>{{ $metricas->fiabilidad ?? 0 }}%</td></tr>
            <tr><td>Seguridad</td><td>{{ $metricas->seguridad ?? 0 }}%</td></tr>
            <tr><td>Mantenibilidad</td><td>{{ $metricas->mantenibilidad ?? 0 }}%</td></tr>
            <tr><td>Portabilidad</td><td>{{ $metricas->portabilidad ?? 0 }}%</td></tr>
            <tr><td>Líneas Analizadas</td><td>{{ $metricas->lineas_analizadas ?? 0 }}</td></tr>
        </tbody>
    </table>

    {{-- ANÁLISIS --}}
    <div class="section-title">📋 Análisis Cualitativo (ISO/IEC 25010):</div>
    <div class="code-box">{{ $analisis ?? 'No se encontró información cualitativa.' }}</div>

    {{-- RESUMEN --}}
    <div class="section-title">📝 Resumen:</div>
    <div class="code-box">{{ $resumen ?? 'Sin resumen disponible.' }}</div>

    {{-- BOTÓN DESCARGAR PDF (solo visible en vista web) --}}
    @if(isset($registro))
        <div style="text-align: center;" class="no-print">
            <a href="{{ route('evaluacion.reporte.pdf', $registro->id) }}" class="btn btn-primary" target="_blank">
                📄 Descargar PDF
            </a>
        </div>
    @endif

    {{-- PIE DE PÁGINA --}}
    <div class="footer">
        Generado automáticamente por el sistema de auditoría ISO/IEC 25010 / 25023 — {{ now()->format('d/m/Y H:i') }}<br>
        <span style="font-style: italic;">Desarrollado por Mauricio Sánchez Abella</span>
    </div>

</body>
</html>
