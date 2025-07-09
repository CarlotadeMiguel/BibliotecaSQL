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
            
            <div class="navbar-nav ms-auto">
                @auth
                    {{-- Enlaces según el rol --}}
                    @if(Auth::user()->hasRole('admin'))
                        <a class="nav-link" href="{{ route('libros.index') }}">Libros</a>
                        <a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a>
                        <a class="nav-link" href="{{ route('prestamos.index') }}">Préstamos</a>
                        <a class="nav-link {{ request()->routeIs('libros.disponibilidad-detallada') ? 'active' : '' }}"
                           href="{{ route('libros.disponibilidad-detallada') }}">
                            Disponibilidad detallada
                        </a>
                        @else
                        <a class="nav-link" href="{{ route('libros.index') }}">Libros</a>
                        <a class="nav-link" href="{{ route('prestamos.index') }}">Mis Préstamos</a>
                        <a class="nav-link {{ request()->routeIs('libros.disponibles') ? 'active' : '' }}"
                       href="{{ route('libros.disponibles') }}">
                        Libros disponibles
                    </a>
                        @endif
                    {{-- Información del usuario y logout --}}
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            👤 {{ Auth::user()->nombre }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <span class="dropdown-item-text">
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        🚪 Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a>
                    <a class="nav-link" href="{{ route('register') }}">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        {{-- Mensajes de éxito/error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
