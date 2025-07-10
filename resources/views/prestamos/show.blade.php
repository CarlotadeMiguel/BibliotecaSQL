@extends('layouts.app')

@section('title', 'Detalle de Préstamo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👁️ Detalle de Préstamo</h1>
    <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<div class="card">
    <div class="card-body">
        <h3 class="card-title">Préstamo #{{ $prestamo->id }}</h3>
        
        <div class="row">
            <div class="col-md-6">
                <h5>Información del Usuario</h5>
                <p><strong>Nombre:</strong> {{ $prestamo->usuario->nombre }}</p>
                <p><strong>Email:</strong> {{ $prestamo->usuario->email }}</p>
                <p><strong>Teléfono:</strong> {{ $prestamo->usuario->telefono ?? '-' }}</p>
            </div>
            
            <div class="col-md-6">
                <h5>Información del Libro</h5>
                <p><strong>Título:</strong> {{ $prestamo->libro->titulo }}</p>
                <p><strong>Autor:</strong> {{ $prestamo->libro->autor ?? 'Sin autor' }}</p>
                <p><strong>ISBN:</strong> {{ $prestamo->libro->isbn ?? 'Sin ISBN' }}</p>
                <p><strong>Ejemplares disponibles:</strong> {{ $prestamo->libro->ejemplares }}</p>
            </div>
        </div>
        
        <hr>
        
        <div class="row">
            <div class="col-md-12">
                <h5>Información del Préstamo</h5>
                <p><strong>Fecha de Préstamo:</strong> {{ $prestamo->fecha_prestamo }}</p>
                <p><strong>Fecha de Devolución:</strong> {{ $prestamo->fecha_devolucion ?? 'No establecida' }}</p>
                <p><strong>Plazo</strong>{{ $prestamo->plazo }}</p>
                <p><strong>Estado:</strong> 
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
                </p>
                
                @if($prestamo->fecha_devolucion && $prestamo->fecha_devolucion < now() && $prestamo->estado != 'devuelto')
                    <div class="alert alert-warning">
                        <strong>⚠️ Préstamo Vencido:</strong> 
                        Este préstamo debía ser devuelto el {{ $prestamo->fecha_devolucion }}.
                    </div>
                @endif
            </div>
        </div>
        @role('admin')
        <div class="mt-3">
            <a href="{{ route('prestamos.edit', $prestamo) }}" class="btn btn-warning">Editar</a>
            {{-- Sólo mostrar “Eliminar” cuando esté devuelto --}}
            @if($prestamo->estado === 'devuelto')
            <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('¿Eliminar préstamo?')">
                    Eliminar
                </button>
            </form>
            @endif
        </div>
        @endrole
    </div>
</div>
@endsection
