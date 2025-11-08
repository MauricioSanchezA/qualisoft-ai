<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title')</title>
    
    <!-- AdminLTE & Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    @stack('styles')
    
</head>
<body class="hold-transition sidebar-mini {{ Auth::check() ? '' : 'login-page' }}">
<div class="wrapper">

    {{-- Mostrar navbar y sidebar solo si el usuario está autenticado --}}
    @auth
        @include('partials.navbar')
        @include('partials.sidebar')
    @endauth

    <!-- Contenido principal -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>@yield('page-title')</h1>
            </div>
        </section>

        <section class="content">
            @yield('content')
        </section>
    </div>

    {{-- Mostrar footer solo si el usuario está autenticado --}}
    @auth
        @include('partials.footer')
    @endauth

</div>

<!-- Scripts -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
@if(session('swal'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ session('swal') }}',
        showConfirmButton: false,
        timer: 2500
    });
</script>
@endif
@stack('scripts')

</body>
</html>
