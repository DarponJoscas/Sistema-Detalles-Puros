<div id="dashboard">
    <style>
        html,
        body {
            background-color: black;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100%;
        }

        #dashboard {
            background-color: black;
            padding: 20px;
            text-align: center;
            min-height: 100vh;
            width: 100%;
            box-sizing: border-box;
        }

        #dashboard h1 {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        #dashboard .flex-container {
            display: flex;
            justify-content: space-evenly;
            gap: 25px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        #dashboard .flex-center {
            display: flex;
            justify-content: center;
        }

        #dashboard .card {
            background-color: white;
            color: black;
            width: 247px;
            height: 247px;
            padding: 0.9rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        #dashboard .card:hover {
            cursor: pointer;
            transform: scale(1.10);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }
    </style>

    <h1>Gestión Operativa</h1>

    <div class="flex-container">
        <div class="card" wire:click="redirectToPage('administracion')">Administración</div>
        <div class="card" wire:click="redirectToPage('produccion')">Producción</div>
    </div>
    <div class="flex-center">
        <div class="card" wire:click="redirectToPage('empaque')">Empaque</div>
    </div>


    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                Livewire.emit('checkAuthStatus');
            });
        </script>
    @endpush
</div>
