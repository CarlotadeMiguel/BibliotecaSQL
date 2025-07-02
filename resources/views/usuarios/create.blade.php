@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>➕ Nuevo Usuario</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<form action="{{ route('usuarios.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" required>
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
        @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Repetir contraseña</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="rol" class="form-label">Rol</label>
        <select name="rol" id="rol" class="form-select @error('rol') is-invalid @enderror" required>
            <option value="">Selecciona un rol</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ old('rol') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
            @endforeach
        </select>
        @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
</form>
@endsection
