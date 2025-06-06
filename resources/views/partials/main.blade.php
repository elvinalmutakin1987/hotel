<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>

    {{-- <link href="{{ asset('dist/navbar-top-fixed.css') }}" rel="stylesheet"> --}}
    {{-- <link href="{{ asset('dist/offcanvas.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('dist/sticky-footer-navbar.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    @include('partials.header')

    <main class="flex-shrink-0 flex-grow-1 p-2">
        <div class="container">
            <div class="text-start fw-bold text-dark d-flex mb-2" id="div-waktu">
            </div>

            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container ">
            <span class="text-muted">Developed By <a href="https://karyaetam.com" target="_blank">Karya Etam Software
                    House</a></span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('dist/offcanvas.js') }}"></script> --}}

    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            document.getElementById('div-waktu').innerText = now.toLocaleDateString('en-ID', options);
        }

        setInterval(updateDateTime, 1000);

        document.addEventListener('DOMContentLoaded', updateDateTime);
    </script>


    @stack('javascript')
</body>


</html>
