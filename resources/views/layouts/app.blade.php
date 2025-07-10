<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Biblioteca')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('libros.index') }}">📚 Biblioteca</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto">
        @auth
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('libros.*') ? 'active':'' }}"
               href="{{ route('libros.index') }}">Libros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('estadisticas.publicas') ? 'active':'' }}"
               href="{{ route('estadisticas.publicas') }}">Populares</a>
          </li>
          @role('admin')
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('estadisticas.index') ? 'active':'' }}"
                 href="{{ route('estadisticas.index') }}">Estadísticas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active':'' }}"
                 href="{{ route('usuarios.index') }}">Usuarios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('prestamos.admin') ? 'active':'' }}"
                 href="{{ route('prestamos.index') }}">Préstamos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('prestamos.vencidos') ? 'active':'' }}"
                 href="{{ route('prestamos.vencidos') }}">Préstamos vencidos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('libros.disponibilidad-detallada') ? 'active':'' }}"
                 href="{{ route('libros.disponibilidad-detallada') }}">Disponibilidad detallada</a>
            </li>
          @else
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('prestamos.index') ? 'active':'' }}"
                 href="{{ route('prestamos.index') }}">Mis Préstamos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('libros.disponibles') ? 'active':'' }}"
                 href="{{ route('libros.disponibles') }}">Libros disponibles</a>
            </li>
          @endrole
        @endauth
      </ul>
      <ul class="navbar-nav ms-auto">
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              👤 {{ Auth::user()->nombre }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li class="dropdown-item-text text-muted">{{ Auth::user()->email }}</li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item text-danger">Cerrar sesión</button>
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar Sesión</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registrarse</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
  @foreach (['success','error','status'] as $msg)
    @if(session($msg))
      <div class="alert alert-{{ $msg==='success'?'success':($msg==='error'?'danger':'info') }} alert-dismissible fade show">
        {{ session($msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  @endforeach
  @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
