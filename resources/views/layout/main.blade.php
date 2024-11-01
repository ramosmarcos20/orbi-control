<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/orbiErp-1.jpg') }}">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- JS --}}
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
    <title>Orbi Control</title>
</head>
<body>

    <nav class="side-navbar">
        <ul id="side-main-menu" class="side-menu list-unstyled">
            <li>
                <a href="{{ url('/') }}"><i class="bi bi-house"></i> Dashboard</a>
            </li>
            <li>
                <a href="{{ route('count.index') }}"><i class="bi bi-receipt"></i> Conteo</a>
            </li>
            <li>
                <a href="{{ route('company.index') }}"><i class="bi bi-building"></i> Empresas</a>
            </li>
            @if (Auth::id() == 1)
            <li>
                <a href="{{ route('user.index') }}"><i class="bi bi-people"></i> Usuario</a>
            </li>
            @endif
            <li>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none">
                        <i class="bi bi-box-arrow-left"></i>
                        Cerrar sesión
                    </button>
                </form>
            </li>
        </ul>
        
    </nav>
    
    <main class="container mt-4">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
