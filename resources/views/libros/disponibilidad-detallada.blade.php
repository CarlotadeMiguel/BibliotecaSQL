@extends('layouts.app')

@section('title','Disponibilidad Detallada')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📊 Disponibilidad Detallada</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Todos los libros</a>
</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>Título</th>
            <th>Autor</th>
            <th>Stock Actual</th>
            <th>Préstamos Activos</th>
            <th>Total Inicial</th>
        </tr>
    </thead>
    <tbody>
        @forelse($libros as $l)
            <tr>
                <td>{{ $l->titulo }}</td>
                <td>{{ $l->autor ?? 'Sin autor' }}</td>
                <td>{{ $l->stock_actual }}</td>
                <td>{{ $l->prestamos_activos }}</td>
                <td>{{ $l->total_inicial }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No hay resultados</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

{{ $libros->links() }}
@endsection
