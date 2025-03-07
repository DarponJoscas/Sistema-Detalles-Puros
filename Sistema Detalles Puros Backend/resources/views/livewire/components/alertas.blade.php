@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success'))
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('info'))
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: "{{ session('info') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('confirm_delete'))
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de eliminar este registro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('eliminarRegistro');
                }
            });
        @endif

        @if (session('confirm_edit'))
            Swal.fire({
                icon: 'question',
                title: '¿Estás seguro de editar este registro?',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('editarRegistro');
                }
            });
        @endif
    });
</script>
@endpush
