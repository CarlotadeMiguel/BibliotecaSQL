@extends('layouts.app')

@section('title', 'Listado de Libros')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="mb-2 mb-md-0">📚 Libros</h1>

    <form action="{{ route('libros.buscar') }}" method="GET" class="d-flex">
    <input type="text" name="q" class="form-control form-control-sm me-2"
           placeholder="Buscar..." value="{{ request('q') }}">
    <button class="btn btn-outline-primary btn-sm">🔍</button>
  </form>

    <div class="btn-group" role="group" aria-label="Acciones de libros">
        <a href="{{ route('libros.disponibles') }}" class="btn btn-outline-success btn-sm me-2">
            ✅ Solo disponibles
        </a>
        @role('admin')
            <a href="{{ route('libros.disponibilidad-detallada') }}" class="btn btn-outline-info btn-sm me-2">
                📊 Detalle disponibilidad
            </a>
            <a href="{{ route('libros.create') }}" class="btn btn-primary btn-sm">
                ➕ Nuevo Libro
            </a>
        @endrole
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Autor</th>
                <th>ISBN</th>
                <th>Ejemplares</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($libros as $libro)
                <tr>
                    <td>{{ $libro->id }}</td>
                    <td>{{ $libro->titulo }}</td>
                    <td>{{ $libro->autor ?? 'Sin autor' }}</td>
                    <td>{{ $libro->isbn ?? 'Sin ISBN' }}</td>
                    <td>
                        <span class="badge {{ $libro->ejemplares > 0 ? 'bg-success' : 'bg-danger' }}">
                            {{ $libro->ejemplares }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('libros.show', $libro) }}" class="btn btn-sm btn-info">👁️ Ver</a>
                        @role('admin')
                            <a href="{{ route('libros.edit', $libro) }}" class="btn btn-sm btn-warning">✏️ Editar</a>
                            <form action="{{ route('libros.destroy', $libro) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('¿Estás seguro?')">🗑️ Eliminar</button>
                            </form>
                        @endrole
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay libros registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
