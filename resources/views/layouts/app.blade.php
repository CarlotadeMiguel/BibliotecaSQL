<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Biblioteca')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('libros.index') }}">📚 Biblioteca</a>
            <div class="navbar-nav">
                @auth
                    @if(Auth::user()->hasRole('admin'))
                        <a class="nav-link" href="{{ route('libros.index') }}">Libros</a>
                        <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                        <a class="nav-link" href="{{ route('prestamos.index') }}">Préstamos</a>
                    @else
                        <a class="nav-link" href="{{ route('prestamos.index') }}">Mis Préstamos</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
