@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#049ED2'
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#0480AA'
        });
    </script>
@endif

@if (session('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Info',
            text: @json(session('info')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#049ED2'
        });
    </script>
@endif

@if (session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: @json(session('warning')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#0480AA'
        });
    </script>
@endif

{{-- Validation errors --}}
@if ($errors->any())
    <script>
        $(document).ready(function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                customClass: {
                    popup: 'my-toast-margin' // Dodeljujemo klasu
                },
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                background: '#ee4d4d',
                color: '#fff',
                iconColor: '#fff',
            });

            @foreach ($errors->all() as $error)
            Toast.fire({
                icon: 'error',
                title: "{{ $error }}"
            });
            @endforeach
        });
    </script>
@endif
