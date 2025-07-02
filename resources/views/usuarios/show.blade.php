@extends('layouts.app')

@section('title', 'Detalle de Usuario')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>👁️ Detalle de Usuario</h1>
    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">← Volver al listado</a>
</div>

<div class="card">
    <div class="card-body">
        <h2 class="card-title">{{ $usuario->nombre }}</h2>
        <p><strong>Email:</strong> {{ $usuario->email }}</p>
        <p><strong>Teléfono:</strong> {{ $usuario->telefono ?? '-' }}</p>
        <p><strong>Rol:</strong>
            @foreach($usuario->getRoleNames() as $role)
                <span class="badge bg-info">{{ $role }}</span>
            @endforeach
        </p>
    </div>
</div>
@endsection
