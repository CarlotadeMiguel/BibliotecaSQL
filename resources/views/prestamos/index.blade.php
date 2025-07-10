@extends('layouts.app')

@section('title', 'Préstamos')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Préstamos</h1>
    <a href="{{ route('prestamos.create') }}" class="btn btn-primary">➕ Nuevo Préstamo</a>
    @role('admin')
        <a href="{{ route('prestamos.vencidos') }}" class="btn btn-outline-danger btn-sm ms-2">
            ⚠️ Ver préstamos vencidos
        </a>
    @endrole
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Devolución</th>
                <th>Plazo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prestamos as $prestamo)
                <tr>
                    <td>{{ $prestamo->id }}</td>
                    <td>{{ $prestamo->usuario->nombre }}</td>
                    <td>{{ $prestamo->libro->titulo }}</td>
                    <td>{{ $prestamo->fecha_prestamo }}</td>
                    <td>{{ $prestamo->fecha_devolucion ?? '-' }}</td>
                    <td>{{ $prestamo->plazo }}</td>
                    <td>
                        @switch($prestamo->estado)
                            @case('prestado')
                                <span class="badge bg-warning">Prestado</span>
                                @break
                            @case('devuelto')
                                <span class="badge bg-success">Devuelto</span>
                                @break
                            @case('retrasado')
                                <span class="badge bg-danger">Retrasado</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ ucfirst($prestamo->estado) }}</span>
                        @endswitch
                    </td>
               
                    <td>
                        <a href="{{ route('prestamos.show', $prestamo) }}" class="btn btn-info btn-sm">Ver</a>
                        @role('admin')
                        <a href="{{ route('prestamos.edit', $prestamo) }}" class="btn btn-warning btn-sm">Editar</a>

                        {{-- Sólo mostrar “Eliminar” cuando esté devuelto --}}
                        @if($prestamo->estado === 'devuelto')
                            <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar préstamo?')">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    </td>
    @endrole
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay préstamos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $prestamos->links() }}
@endsection
