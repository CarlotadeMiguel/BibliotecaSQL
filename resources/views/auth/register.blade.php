@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <h2>Registro de usuario</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input id="nombre" type="text" class="form-control" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input id="telefono" type="text" class="form-control" name="telefono" value="{{ old('telefono') }}">
                @error('telefono') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required>
                @error('password') <div class="text-danger">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Repetir contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif
            <button type="submit" class="btn btn-success">Registrarse</button>
            <p class="mt-3">¿Ya tienes cuenta? <a href="{{ route('register') }}">Logueate aquí</a></p>
        </form>
    </div>
</div>
@endsection
