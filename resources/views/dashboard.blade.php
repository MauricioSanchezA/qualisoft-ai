@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Panel de Control')

@section('content')
<style>
    /* === Estilos personalizados para el dashboard === */
    .dashboard-container {
        padding: 2rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: start;
        gap: 1.5rem;
    }

    .small-box {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
        width: 380px;
        height: 280px;
        border-radius: 1rem;
        color: #fff;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .small-box .inner {
        padding: 1.5rem;
        z-index: 2;
        transition: transform 0.3s ease; /* Animación del contenido */
    }

    .small-box h3 {
        font-size: 2.7rem;
        margin: 0;
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .small-box p {
        margin: 0.5rem 0;
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .small-box small {
        font-size: 1.15rem;
        opacity: 0.9;
        transition: transform 0.3s ease;
    }

    .small-box .icon {
        position: absolute;
        top: 14px;
        right: 15px;
        font-size: 3.5rem;
        opacity: 0.15;
        transition: transform 0.3s ease;
    }

    .small-box:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    /* 🔹 Efecto scale al texto dentro de la card */
    .small-box:hover .inner,
    .small-box:hover h3,
    .small-box:hover p,
    .small-box:hover small,
    .small-box:hover .icon {
        transform: scale(1.1);
    }

    .small-box-footer {
        background: rgba(0, 0, 0, 0.1);
        color: #fff;
        text-decoration: none;
        display: block;
        padding: 0.5rem;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
        transition: background 0.3s ease;
    }

    .small-box-footer:hover {
        background: rgba(0, 0, 0, 0.2);
    }

    /* Colores personalizados */
    .bg-info {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
    }

    .bg-success {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .small-box {
            width: 100%;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Proyectos -->
    <div class="small-box bg-info">
        <div class="inner">
            <h3>{{ $totalProyectos }}</h3>
            <p>Proyectos</p>
        </div>
        <div class="icon">
            <i class="fas fa-folder"></i>
        </div>
        <a href="{{ route('projects.index') }}" class="small-box-footer">
            Ver todos <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>

    <!-- Evaluaciones -->
    <div class="small-box bg-success">
        <div class="inner">
            <h3>{{ $totalEvaluaciones }}</h3>
            <p>Evaluaciones</p>
            <small>Última ID: {{ $ultimoIdEvaluacion ?? 'N/A' }}</small>
        </div>
        <div class="icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <a href="{{ route('code_analysis.index') }}" class="small-box-footer">
            Ver detalles <i class="fas fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
@endsection
