@extends('layouts.guest')

@section('title', 'Bienvenido | ' . config('app.name'))

@section('content')
<style>
    /* Fondo de video en carrusel */
    .video-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    .video-background video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        transform: translate(-50%, -50%);
        object-fit: cover;
        opacity: 0;
        transition: opacity 2s ease-in-out;
    }

    .video-background video.active {
        opacity: 1;
        z-index: -1;
    }

    /* Contenedor principal */
    .container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 1rem;
        color: white;
        text-align: center;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
    }

    /* Títulos */
    .title {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: bold;
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
    }

    .subtitle {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
    }

    /* Botón */
    .app-button {
        background-color: #ffffff;
        color: #1e3c72;
        padding: 1rem 2rem;
        border: none;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: transform 0.3s ease, background-color 0.3s;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }

    .app-button:hover {
        transform: scale(1.25);
        background-color: #1e3c72;
        color: #ffffff; 
    }
</style>

<div class="video-background">
    <video class="active" autoplay muted loop playsinline preload="auto" poster="{{ asset('images/preview.png') }}">
        <source src="{{ asset('videos/262909_small_web.mp4') }}" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
    <video autoplay muted loop playsinline preload="auto" poster="{{ asset('images/preview.png') }}">
        <source src="{{ asset('videos/219130_small_web.mp4') }}" type="video/mp4">
        Tu navegador no soporta videos HTML5.
    </video>
</div>

<div class="container">
    <div class="title" data-aos="fade-down" data-aos-duration="1000">
        Sistema de Gestión de Calidad
    </div>
    <div class="subtitle" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
        Basado en Normas ISO/IEC 25010 y ISO/IEC 25023
    </div>
    <button class="app-button" data-aos="zoom-in" data-aos-delay="600" onclick="window.location.href='{{ route('login') }}'">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
            <path d="M4.5 1a.5.5 0 0 1 .5.5V3h6V1.5a.5.5 0 0 1 1 0V3h.5A1.5 1.5 0 0 1 14 4.5v9A1.5 1.5 0 0 1 12.5 15h-9A1.5 1.5 0 0 1 2 13.5v-9A1.5 1.5 0 0 1 3.5 3H4V1.5a.5.5 0 0 1 .5-.5zM3.5 4a.5.5 0 0 0-.5.5V6h10V4.5a.5.5 0 0 0-.5-.5h-9zM13 7H3v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7z"/>
        </svg>
        Aplicación
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const videos = document.querySelectorAll('.video-background video');
    let current = 0;

    if (videos.length > 0) {
        videos[current].classList.add('active');
        videos[current].play();
    }

    setInterval(() => {
        if (videos.length <= 1) return;

        videos[current].classList.remove('active');
        videos[current].pause();
        videos[current].currentTime = 0;

        current = (current + 1) % videos.length;

        videos[current].classList.add('active');
        videos[current].play();
    }, 17000);
});
</script>
@endsection
