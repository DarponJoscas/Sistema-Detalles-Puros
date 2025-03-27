<script>
    document.addEventListener('livewire:initialized', function() {
        Livewire.on('swalSuccess', ({
            message = 'Se ha registrado correctamente'
        }) => {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        });

        Livewire.on('swalError', ({
            message = 'Error no se puede completar la acción'
        }) => {
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        });

        Livewire.on('swalInfo', ({
            message = 'Se ha editado correctamente'
        }) => {
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
        });

        Livewire.on('swalConfirmDelete', (eventData) => {
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de desactivar este registro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarRegistro', eventData.marcaId);
                }
            });
        });

        Livewire.on('swalConfirmEdit', () => {
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de editar este registro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('editarRegistro');
                }
            });
        });

        Livewire.on('swalConfirmReactivate', () => {
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de reactivar este registro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('reactivarRegistro');
                }
            });
        });
    });
</script>
