@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Nuevo Préstamo</h1>
    <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<form action="{{ route('prestamos.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="usuario_id" class="form-label">Usuario</label>
        <select name="usuario_id" id="usuario_id" class="form-select @error('usuario_id') is-invalid @enderror" required>
            <option value="">Selecciona un usuario</option>
            @foreach($usuarios as $usuario)
                <option value="{{ $usuario->id }}" {{ old('usuario_id') == $usuario->id ? 'selected' : '' }}>
                    {{ $usuario->nombre }} ({{ $usuario->email }})
                </option>
            @endforeach
        </select>
        @error('usuario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    
    <div class="mb-3">
        <label for="libro_id" class="form-label">Libro</label>
        <select name="libro_id" id="libro_id" class="form-select @error('libro_id') is-invalid @enderror" required>
            <option value="">Selecciona un libro</option>
            @foreach($libros as $libro)
                <option value="{{ $libro->id }}" {{ old('libro_id') == $libro->id ? 'selected' : '' }}>
                    {{ $libro->titulo }} - {{ $libro->autor ?? 'Sin autor' }} 
                    ({{ $libro->ejemplares }} disponibles)
                </option>
            @endforeach
        </select>
        @error('libro_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    
    <div class="mb-3">
        <label for="fecha_prestamo" class="form-label">Fecha de Préstamo</label>
        <input type="date" name="fecha_prestamo" id="fecha_prestamo" 
               class="form-control @error('fecha_prestamo') is-invalid @enderror" 
               value="{{ old('fecha_prestamo', date('Y-m-d')) }}" required>
        @error('fecha_prestamo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    
    <div class="mb-3">
        <label for="fecha_devolucion" class="form-label">Fecha de Devolución (opcional)</label>
        <input type="date" name="fecha_devolucion" id="fecha_devolucion" 
               class="form-control @error('fecha_devolucion') is-invalid @enderror" 
               value="{{ old('fecha_devolucion') }}">
        @error('fecha_devolucion') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    
    <button type="submit" class="btn btn-success">Guardar Préstamo</button>
</form>
@endsection
