<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <!-- Aquí puedes incluir tus fuentes, estilos generales -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col min-h-screen">

    <main class="flex-grow flex items-center justify-center py-10 px-4">
        @yield('content')
    </main>

    <footer class="text-center py-4">
        <small class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</small>
    </footer>

</body>
</html>
