<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS de Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJjR8a9jwPq4Q9rU9n01AiWV6iO78g7ZBz1SdbV-8kcBGzFl7tI5ltm1z5tX" crossorigin="anonymous">

    <title>@yield('title', 'Mi Aplicación')</title>
    @livewireStyles
</head>
<body>

    @if(Route::currentRouteName() === 'login' || Route::currentRouteName() === 'register')
        @livewire('usuarios')
    @endif

    @livewireScripts
</body>
</html>
