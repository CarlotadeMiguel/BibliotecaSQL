@extends('layouts.app')

@section('title', 'Estadísticas del Sistema')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Estadísticas del Sistema</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Volver</a>
</div>

<!-- Tarjetas de resumen -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">📚 Total Libros</h5>
                <h2>{{ $estadisticas['total_libros'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">👥 Total Usuarios</h5>
                <h2>{{ $estadisticas['total_usuarios'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">📖 Préstamos Activos</h5>
                <h2>{{ $estadisticas['prestamos_activos'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <h5 class="card-title">⚠️ Préstamos Vencidos</h5>
                <h2>{{ $estadisticas['prestamos_vencidos'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Libros más prestados -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>📈 Top 10 Libros Más Prestados</h5>
                <a href="{{ route('estadisticas.libros-mas-prestados') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>Total Préstamos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($librosMasPrestados as $index => $libro)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $libro->titulo }}</td>
                                    <td>{{ $libro->autor ?? 'Sin autor' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $libro->total_prestamos }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            Activos: {{ $libro->prestamos_activos }} | 
                                            Disponibles: {{ $libro->disponibles_actuales }}
                                        </small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay datos de préstamos</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Usuarios más activos -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>👑 Usuarios Más Activos</h5>
            </div>
            <div class="card-body">
                @forelse($usuariosActivos as $usuario)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $usuario->nombre }}</span>
                        <span class="badge bg-info">{{ $usuario->prestamos_count }}</span>
                    </div>
                @empty
                    <p class="text-muted">No hay datos de usuarios</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
