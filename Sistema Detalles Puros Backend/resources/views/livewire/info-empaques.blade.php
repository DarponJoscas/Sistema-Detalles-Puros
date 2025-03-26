<div>
    <button class="btn btn-primary" wire:click="importEmpaque">Cargar Empaque</button>

    <div class="mt-3">
        <h5>Resumen de importación:</h5>
        <p>Registros guardados: <strong>{{ $totalGuardados }}</strong></p>
        <p>Registros no guardados: <strong>{{ $totalNoGuardados }}</strong></p>
    </div>
</div>
