<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación</title>
    @livewireStyles <!-- Asegúrate de incluir los estilos de Livewire -->
</head>
<body>

    <div class="app-container">
        @yield('content') <!-- Aquí se insertará el contenido del componente -->
    </div>

    @livewireScripts <!-- Asegúrate de incluir los scripts de Livewire -->
</body>
</html>
