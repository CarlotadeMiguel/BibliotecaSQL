@extends('layouts.app')

@section('title', 'Préstamos Vencidos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>⚠️ Préstamos Vencidos</h1>
    <div>
        <form action="{{ route('prestamos.marcar-vencidos') }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-warning">Marcar como retrasado</button>
        </form>
        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">← Volver</a>
    </div>
</div>

@if($prestamosVencidos->count())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>F. Préstamo</th>
                <th>F. Devolución</th>
                <th>Días Vencido</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestamosVencidos as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->usuario->nombre }}</td>
                <td>{{ $p->libro->titulo }}</td>
                <td>{{ $p->fecha_prestamo }}</td>
                <td>{{ $p->fecha_devolucion }}</td>
                <td>{{ now()->diffInDays($p->fecha_devolucion) }}</td>
                <td><span class="badge bg-danger">{{ ucfirst($p->estado) }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $prestamosVencidos->links() }}
@else
    <div class="alert alert-success">No hay préstamos vencidos.</div>
@endif
@endsection
