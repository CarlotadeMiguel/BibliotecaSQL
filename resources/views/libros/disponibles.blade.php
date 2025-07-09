@extends('layouts.app')

@section('title','Libros Disponibles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>📚 Libros Disponibles</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Todos los libros</a>
</div>

<div class="table-responsive">
<table class="table table-striped">
    <thead>
        <tr>
            <th>Título</th><th>Autor</th><th>ISBN</th><th>Ejemplares</th><th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($librosDisponibles as $libro)
            <tr>
                <td>{{ $libro->titulo }}</td>
                <td>{{ $libro->autor ?? 'Sin autor' }}</td>
                <td>{{ $libro->isbn ?? 'Sin ISBN' }}</td>
                <td>{{ $libro->ejemplares }}</td>
                <td>
                    <a href="{{ route('libros.show',$libro) }}" class="btn btn-info btn-sm">Ver</a>
                    <a href="{{ route('prestamos.create',['libro_id'=>$libro->id]) }}" class="btn btn-primary btn-sm">Pedir</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center">No hay libros disponibles</td></tr>
        @endforelse
    </tbody>
</table>
</div>
{{ $librosDisponibles->links() }}
@endsection
