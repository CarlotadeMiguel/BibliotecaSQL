@extends('layouts.app')

@section('title', 'Crear Libro')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Nuevo Libro</h1>
    <a href="{{ route('libros.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<form action="{{ route('libros.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" name="titulo" id="titulo"
               class="form-control @error('titulo') is-invalid @enderror"
               value="{{ old('titulo') }}" required>
        @error('titulo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" name="autor" id="autor"
               class="form-control @error('autor') is-invalid @enderror"
               value="{{ old('autor') }}">
        @error('autor')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" name="isbn" id="isbn"
               class="form-control @error('isbn') is-invalid @enderror"
               value="{{ old('isbn') }}">
        @error('isbn')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="ejemplares" class="form-label">Ejemplares</label>
        <input type="number" name="ejemplares" id="ejemplares" min="1"
               class="form-control @error('ejemplares') is-invalid @enderror"
               value="{{ old('ejemplares', 1) }}" required>
        @error('ejemplares')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
</form>
@endsection
