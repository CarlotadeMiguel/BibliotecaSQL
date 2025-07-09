@extends('layouts.app')

@section('title','Resultados de Búsqueda')

@section('content')
<div class="mb-4">
  <form action="{{ route('libros.buscar') }}" method="GET" class="d-flex">
    <input type="text" name="q" value="{{ $q }}"
           class="form-control me-2"
           placeholder="Buscar por título, autor o ISBN">
    <button class="btn btn-primary">🔍 Buscar</button>
  </form>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>🔍 Resultados</h1>
  <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Todos los libros</a>
</div>

@if($libros->count())
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Título</th><th>Autor</th><th>ISBN</th><th>Ejemplares</th><th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($libros as $libro)
        <tr>
          <td>{{ $libro->titulo }}</td>
          <td>{{ $libro->autor }}</td>
          <td>{{ $libro->isbn }}</td>
          <td>{{ $libro->ejemplares }}</td>
          <td>
            <a href="{{ route('libros.show',$libro) }}" class="btn btn-info btn-sm">Ver</a>
            @role('admin')
              <a href="{{ route('libros.edit',$libro) }}" class="btn btn-warning btn-sm">Editar</a>
            @endrole
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $libros->links() }}
@else
  <div class="alert alert-info">No se encontraron libros para “<strong>{{ $q }}</strong>”.</div>
@endif
@endsection
