<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('info'))
            Swal.fire({
                position: 'top-end',
                icon: 'info',
                title: '{{ session('info') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('question'))
            Swal.fire({
                position: 'top-end',
                icon: 'question',
                title: '{{ session('question') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    });
</script>
