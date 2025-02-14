<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    @vite(['resources/css/app.css']) <!-- Si usas Vite -->
    @livewireStyles
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">Bienvenido, {{ Auth::user()->usuario }} 🎉</h1>

        <p class="mt-2 text-gray-600">Has iniciado sesión correctamente.</p>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                Cerrar Sesión
            </button>
        </form>
    </div>
    @livewireScripts
</body>
</html>
