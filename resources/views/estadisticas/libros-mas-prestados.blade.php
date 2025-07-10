@extends('layouts.app')

@section('title', 'Libros Más Prestados')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📈 Libros Más Prestados</h1>
    <a href="{{ route('estadisticas.index') }}" class="btn btn-secondary">← Estadísticas</a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Posición</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Total Préstamos</th>
                <th>Activos</th>
                <th>Devueltos</th>
                <th>Disponibles</th>
            </tr>
        </thead>
        <tbody>
            @forelse($libros as $index => $libro)
                <tr>
                    <td>
                        <strong>{{ ($libros->currentPage() - 1) * $libros->perPage() + $index + 1 }}</strong>
                    </td>
                    <td>{{ $libro->titulo }}</td>
                    <td>{{ $libro->autor ?? 'Sin autor' }}</td>
                    <td>{{ $libro->isbn ?? 'Sin ISBN' }}</td>
                    <td>
                        <span class="badge bg-primary fs-6">{{ $libro->total_prestamos }}</span>
                    </td>
                    <td>
                        <span class="badge bg-warning">{{ $libro->prestamos_activos }}</span>
                    </td>
                    <td>
                        <span class="badge bg-success">{{ $libro->prestamos_devueltos }}</span>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $libro->disponibles_actuales }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hay datos de préstamos</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $libros->links() }}
@endsection
